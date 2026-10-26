<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Setting;
use App\Core\Database;
use App\Core\Razorpay;

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
            'razorpayKey' => razorpay_key_id(),
            'paymentDemo' => payment_demo_enabled(),
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

        if (!\App\Core\RateLimiter::attempt('checkout:' . client_ip(), 10, 3600)) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => 'Too many checkout attempts. Please wait and try again.'], 429);
                return;
            }
            flash('error', 'Too many checkout attempts. Please wait and try again.');
            $this->redirect('cart');
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
            $passwordError = password_policy_error($accountPassword);
            if ($passwordError !== null) {
                if ($this->request->isAjax()) {
                    $this->json(['success' => false, 'error' => $passwordError], 422);
                    return;
                }
                flash('error', $passwordError);
                $this->redirect('checkout');
                return;
            }

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
        Order::rememberAccess((int)$res['order_id'], (string)$orderNumber);

        // If online payment (Razorpay / UPI / Card)
        if ($paymentMethod !== 'cod') {
            $orderRecord = Order::getWithDetails((int)$res['order_id']);
            if (!$orderRecord) {
                $this->json(['success' => false, 'error' => 'Order could not be loaded.'], 500);
                return;
            }

            $demo = payment_demo_enabled();
            $gatewayOrderId = null;

            if (!$demo) {
                try {
                    if (!Razorpay::configured()) {
                        throw new \RuntimeException('Online payment is not configured yet. Please choose Cash on Delivery.');
                    }
                    $gatewayOrder = Razorpay::createOrder(
                        (int)round(((float)$orderRecord['total_amount']) * 100),
                        (string)$orderNumber,
                        ['local_order_id' => (string)$res['order_id']]
                    );
                    $gatewayOrderId = (string)$gatewayOrder['id'];
                    Database::insert('payments', [
                        'order_id' => (int)$res['order_id'],
                        'payment_method' => $paymentMethod,
                        'gateway_order_id' => $gatewayOrderId,
                        'amount' => $orderRecord['total_amount'],
                        'currency' => 'INR',
                        'status' => 'initiated',
                    ]);
                } catch (\Throwable $gatewayEx) {
                    Order::updateStatus((int)$res['order_id'], 'cancelled');
                    if ($this->request->isAjax()) {
                        $this->json(['success' => false, 'error' => $gatewayEx->getMessage()], 502);
                        return;
                    }
                    flash('error', $gatewayEx->getMessage());
                    $this->redirect('checkout');
                    return;
                }
            }

            if ($this->request->isAjax()) {
                $this->json([
                    'success' => true,
                    'order_id' => (int)$res['order_id'],
                    'order_number' => $orderNumber,
                    'total_amount' => $orderRecord['total_amount'],
                    'currency' => 'INR',
                    'key_id' => razorpay_key_id(),
                    'razorpay_order_id' => $gatewayOrderId,
                    'demo' => $demo,
                    'payment_method' => $paymentMethod,
                    'redirect' => url('checkout/success/' . $orderNumber)
                ]);
                return;
            }

            if (!$demo) {
                Order::updateStatus((int)$res['order_id'], 'cancelled');
                flash('error', 'Please enable JavaScript to complete online payment, or choose Cash on Delivery.');
                $this->redirect('checkout');
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
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'error' => 'Security token expired. Please refresh the page.'], 419);
            return;
        }

        if (!\App\Core\RateLimiter::attempt('payment_verify:' . client_ip(), 20, 900)) {
            $this->json(['success' => false, 'error' => 'Too many verification attempts. Please wait and try again.'], 429);
            return;
        }

        $orderId = (int)$this->request->input('order_id');
        $order = Order::getWithDetails($orderId);
        if (!$order) {
            $this->json(['success' => false, 'error' => 'Order not found.'], 404);
            return;
        }

        if (!Order::canAccess($order)) {
            $this->json(['success' => false, 'error' => 'You are not authorized to verify this payment.'], 403);
            return;
        }

        if ($order['payment_method'] === 'cod') {
            $this->json(['success' => false, 'error' => 'This order does not require online payment.'], 400);
            return;
        }

        if (($order['payment_status'] ?? '') === 'paid') {
            $this->json([
                'success' => true,
                'message' => 'Payment was already verified.',
                'redirect' => url('checkout/success/' . $order['order_number'])
            ]);
            return;
        }

        if (($order['status'] ?? '') === 'cancelled' || ($order['status'] ?? '') === 'refunded') {
            $this->json(['success' => false, 'error' => 'This order can no longer be paid.'], 400);
            return;
        }

        $demo = payment_demo_enabled();
        $gatewayOrderId = '';
        $paymentId = '';
        $signature = '';

        if ($demo) {
            if (!$this->request->input('demo')) {
                $this->json(['success' => false, 'error' => 'Invalid demo payment request.'], 400);
                return;
            }
            $gatewayOrderId = 'demo_' . $orderId;
            $paymentId = 'DEMO_' . strtoupper(bin2hex(random_bytes(8)));
            $signature = 'demo';
        } else {
            $gatewayOrderId = trim((string)$this->request->input('razorpay_order_id', ''));
            $paymentId = trim((string)$this->request->input('razorpay_payment_id', ''));
            $signature = trim((string)$this->request->input('razorpay_signature', ''));

            if ($gatewayOrderId === '' || $paymentId === '' || $signature === '') {
                $this->json(['success' => false, 'error' => 'Incomplete payment verification data.'], 422);
                return;
            }

            $paymentRecord = Database::fetch(
                "SELECT * FROM `payments` WHERE `order_id` = :oid AND `gateway_order_id` IS NOT NULL ORDER BY `id` DESC LIMIT 1",
                ['oid' => $orderId]
            );
            if (!$paymentRecord || !hash_equals((string)$paymentRecord['gateway_order_id'], $gatewayOrderId)) {
                $this->json(['success' => false, 'error' => 'Payment reference does not match this order.'], 400);
                return;
            }

            if (!Razorpay::verifySignature($gatewayOrderId, $paymentId, $signature)) {
                $this->json(['success' => false, 'error' => 'Payment signature verification failed.'], 400);
                return;
            }
        }

        try {
            Database::beginTransaction();

            Database::update('orders', [
                'status' => 'payment_confirmed',
                'payment_status' => 'paid',
                'transaction_id' => $paymentId
            ], "`id` = :id AND `payment_status` = 'pending'", ['id' => $orderId]);

            $existingPayment = Database::fetch("SELECT id FROM `payments` WHERE `order_id` = :oid ORDER BY `id` DESC LIMIT 1", ['oid' => $orderId]);
            if ($existingPayment) {
                Database::update('payments', [
                    'gateway_payment_id' => $paymentId,
                    'gateway_signature' => $signature,
                    'status' => 'captured',
                ], "`id` = :id", ['id' => $existingPayment['id']]);
                if ($demo) {
                    Database::update('payments', ['gateway_order_id' => $gatewayOrderId], "`id` = :id", ['id' => $existingPayment['id']]);
                }
            } else {
                Database::insert('payments', [
                    'order_id' => $orderId,
                    'payment_method' => $order['payment_method'],
                    'gateway_order_id' => $gatewayOrderId,
                    'gateway_payment_id' => $paymentId,
                    'gateway_signature' => $signature,
                    'amount' => $order['total_amount'],
                    'currency' => 'INR',
                    'status' => 'captured'
                ]);
            }

            Database::commit();
        } catch (\Throwable $e) {
            Database::rollBack();
            error_log('Payment verification failed for order ' . $orderId . ': ' . $e->getMessage());
            $this->json(['success' => false, 'error' => 'Payment could not be recorded. Please contact support.'], 500);
            return;
        }

        $this->json([
            'success' => true,
            'message' => 'Payment verified successfully.',
            'redirect' => url('checkout/success/' . $order['order_number'])
        ]);
    }

    public function success(string $orderNumber): void {
        $order = Order::getByNumber($orderNumber);
        if (!$order || !Order::canAccess($order)) {
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
