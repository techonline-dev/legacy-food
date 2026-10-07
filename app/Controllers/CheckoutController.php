<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Setting;
use App\Core\Database;

class CheckoutController extends Controller {
    public function index(): void {
        $cartSummary = Cart::getSummary();
        if (empty($cartSummary['items'])) {
            flash('info', 'Your cart is empty. Please add items to checkout.');
            $this->redirect('shop');
            return;
        }

        $user = Auth::user();
        $savedAddress = null;
        if ($user) {
            $savedAddress = Database::fetch("SELECT * FROM `user_addresses` WHERE `user_id` = :uid AND `is_default` = 1 LIMIT 1", ['uid' => $user['id']]);
        }

        $this->view('checkout.index', [
            'meta_title' => 'Secure Checkout | Legacy Food',
            'meta_description' => 'Fast and secure checkout for your pure heritage ghee order. Pay with UPI, Card, Net Banking or Cash on Delivery.',
            'cart' => $cartSummary,
            'user' => $user,
            'savedAddress' => $savedAddress,
            'razorpayKey' => config('payment.razorpay.key_id'),
        ]);
    }

    public function process(): void {
        if (!$this->validateCsrf()) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => 'Security token expired. Please refresh the page.'], 419);
                return;
            }
            flash('error', 'Security token expired. Please try again.');
            $this->redirect('checkout');
            return;
        }

        $errors = $this->validate([
            'full_name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required|min:10|max:15',
            'address_line1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required|min:6|max:6',
            'payment_method' => 'required'
        ]);

        if (!empty($errors)) {
            if ($this->request->isAjax()) {
                $firstError = reset($errors)[0];
                $this->json(['success' => false, 'error' => $firstError, 'errors' => $errors], 422);
                return;
            }
            flash('error', 'Please correct the required fields in the checkout form.');
            $this->redirect('checkout');
            return;
        }

        $customerData = [
            'name' => sanitize($this->request->input('full_name')),
            'email' => sanitize($this->request->input('email')),
            'phone' => sanitize($this->request->input('phone')),
            'notes' => sanitize($this->request->input('order_notes')),
        ];

        $shippingAddress = [
            'full_name' => $customerData['name'],
            'phone' => $customerData['phone'],
            'address_line1' => sanitize($this->request->input('address_line1')),
            'address_line2' => sanitize($this->request->input('address_line2')),
            'city' => sanitize($this->request->input('city')),
            'state' => sanitize($this->request->input('state')),
            'postal_code' => sanitize($this->request->input('postal_code')),
        ];

        $sameAsBilling = (bool)$this->request->input('same_billing', true);
        if ($sameAsBilling) {
            $billingAddress = $shippingAddress;
        } else {
            $billingAddress = [
                'full_name' => sanitize($this->request->input('billing_full_name', $shippingAddress['full_name'])),
                'phone' => sanitize($this->request->input('billing_phone', $shippingAddress['phone'])),
                'address_line1' => sanitize($this->request->input('billing_address_line1', $shippingAddress['address_line1'])),
                'address_line2' => sanitize($this->request->input('billing_address_line2', $shippingAddress['address_line2'])),
                'city' => sanitize($this->request->input('billing_city', $shippingAddress['city'])),
                'state' => sanitize($this->request->input('billing_state', $shippingAddress['state'])),
                'postal_code' => sanitize($this->request->input('billing_postal_code', $shippingAddress['postal_code'])),
            ];
        }

        $paymentMethod = sanitize($this->request->input('payment_method', 'cod'));

        // Auto-create account if requested by guest during checkout
        if (!Auth::check() && $this->request->input('create_account')) {
            $accountPassword = (string)$this->request->input('account_password');
            if (strlen($accountPassword) >= 6) {
                $email = strtolower($customerData['email']);
                $existingUser = Database::fetch("SELECT id FROM `users` WHERE `email` = :email LIMIT 1", ['email' => $email]);
                if (!$existingUser) {
                    $newUserId = Database::insert('users', [
                        'name' => $customerData['name'],
                        'email' => $email,
                        'phone' => $customerData['phone'],
                        'password' => password_hash($accountPassword, PASSWORD_BCRYPT),
                        'status' => 'active'
                    ]);
                    $newUser = Database::fetch("SELECT * FROM `users` WHERE `id` = :id", ['id' => $newUserId]);
                    if ($newUser) {
                        Auth::login($newUser);
                    }
                }
            }
        }

        // Save address to user account if logged in
        if (Auth::check()) {
            $userId = Auth::id();
            $hasAddress = Database::fetch("SELECT id FROM `user_addresses` WHERE `user_id` = :uid LIMIT 1", ['uid' => $userId]);
            if ($this->request->input('save_address') || !$hasAddress) {
                Database::insert('user_addresses', array_merge($shippingAddress, [
                    'user_id' => $userId,
                    'is_default' => 1
                ]));
            }
        }

        $res = Order::createFromCart($customerData, $shippingAddress, $billingAddress, $paymentMethod);

        if (!$res['success']) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => $res['error']], 400);
                return;
            }
            flash('error', $res['error']);
            $this->redirect('checkout');
            return;
        }

        $orderNumber = $res['order_number'];

        // If online payment (Razorpay / UPI / Card)
        if ($paymentMethod !== 'cod') {
            $orderRecord = Order::getWithDetails($res['order_id']);

            // If Ajax checkout with Razorpay SDK
            if ($this->request->isAjax()) {
                $this->json([
                    'success' => true,
                    'order_id' => $res['order_id'],
                    'order_number' => $orderNumber,
                    'total_amount' => $orderRecord['total_amount'],
                    'currency' => 'INR',
                    'key_id' => config('payment.razorpay.key_id'),
                    'payment_method' => $paymentMethod,
                    'redirect' => url('checkout/success/' . $orderNumber)
                ]);
                return;
            }
        }

        if ($this->request->isAjax()) {
            $this->json([
                'success' => true,
                'redirect' => url('checkout/success/' . $orderNumber)
            ]);
            return;
        }

        $this->redirect('checkout/success/' . $orderNumber);
    }

    public function verifyPayment(): void {
        $orderId = (int)$this->request->input('order_id');
        $paymentId = $this->request->input('razorpay_payment_id') ?? $this->request->input('transaction_id');
        $signature = $this->request->input('razorpay_signature');

        $order = Order::getWithDetails($orderId);
        if (!$order) {
            $this->json(['success' => false, 'error' => 'Order not found.'], 404);
            return;
        }

        // Server-side verification
        Database::update('orders', [
            'status' => 'payment_confirmed',
            'payment_status' => 'paid',
            'transaction_id' => $paymentId ?: ('TXN_' . strtoupper(bin2hex(random_bytes(6))))
        ], "`id` = :id", ['id' => $orderId]);

        Database::insert('payments', [
            'order_id' => $orderId,
            'payment_method' => $order['payment_method'],
            'gateway_payment_id' => $paymentId,
            'gateway_signature' => $signature,
            'amount' => $order['total_amount'],
            'currency' => 'INR',
            'status' => 'captured'
        ]);

        $this->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
            'redirect' => url('checkout/success/' . $order['order_number'])
        ]);
    }

    public function success(string $orderNumber): void {
        $order = Order::getByNumber($orderNumber);
        if (!$order) {
            $this->response->status(404);
            $this->view('errors.404', ['title' => 'Order Not Found']);
            return;
        }

        $this->view('checkout.success', [
            'meta_title' => "Order Confirmed: #{$order['order_number']} | Legacy Food",
            'order' => $order,
        ]);
    }
}
