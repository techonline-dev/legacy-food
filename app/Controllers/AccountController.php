<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Order;

class AccountController extends Controller {
    public function dashboard(): void {
        $user = Auth::user();
        $orders = Order::getUserOrders($user['id']);

        $totalOrders = count($orders);
        $pendingOrders = count(array_filter($orders, fn($o) => in_array($o['status'], ['pending', 'payment_confirmed', 'processing', 'packed'])));
        $deliveredOrders = count(array_filter($orders, fn($o) => $o['status'] === 'delivered'));

        $recentOrders = array_slice($orders, 0, 5);

        $this->view('account.dashboard', [
            'meta_title' => 'My Account Dashboard | Legacy Food',
            'user' => $user,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'deliveredOrders' => $deliveredOrders,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function orders(): void {
        $user = Auth::user();
        $orders = Order::getUserOrders($user['id']);

        $this->view('account.orders', [
            'meta_title' => 'My Orders | Legacy Food',
            'orders' => $orders,
        ]);
    }

    public function orderDetail(int $id): void {
        $user = Auth::user();
        $order = Order::getWithDetails($id);

        if (!$order || $order['user_id'] != $user['id']) {
            $this->response->status(404);
            $this->view('errors.404', ['title' => 'Order Not Found']);
            return;
        }

        $this->view('account.order_detail', [
            'meta_title' => "Order #{$order['order_number']} | Legacy Food",
            'order' => $order,
        ]);
    }

    public function cancelOrder(int $id): void {
        $user = Auth::user();
        $order = Order::getWithDetails($id);

        if (!$order || $order['user_id'] != $user['id']) {
            flash('error', 'Unauthorized access.');
            $this->redirect('account/orders');
            return;
        }

        if (!in_array($order['status'], ['pending', 'payment_confirmed'])) {
            flash('error', 'This order is already being processed and cannot be cancelled automatically. Please contact our support team.');
            $this->redirect('account/order/' . $id);
            return;
        }

        Order::updateStatus($id, 'cancelled');
        flash('success', "Order #{$order['order_number']} has been cancelled.");
        $this->redirect('account/order/' . $id);
    }

    public function profile(): void {
        $user = Auth::user();

        $this->view('account.profile', [
            'meta_title' => 'Profile Settings | Legacy Food',
            'user' => $user,
        ]);
    }

    public function updateProfile(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('account/profile');
            return;
        }

        $user = Auth::user();
        $name = sanitize($this->request->input('name'));
        $phone = sanitize($this->request->input('phone'));
        $password = (string)$this->request->input('password');

        $updateData = [
            'name' => $name,
            'phone' => $phone,
        ];

        if (!empty($password)) {
            if (strlen($password) < 6) {
                flash('error', 'New password must be at least 6 characters.');
                $this->redirect('account/profile');
                return;
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        Database::update('users', $updateData, "`id` = :id", ['id' => $user['id']]);
        flash('success', 'Profile updated successfully.');
        $this->redirect('account/profile');
    }

    public function addresses(): void {
        $user = Auth::user();
        $addresses = Database::fetchAll("SELECT * FROM `user_addresses` WHERE `user_id` = :uid ORDER BY `is_default` DESC, `id` DESC", ['uid' => $user['id']]);

        $this->view('account.addresses', [
            'meta_title' => 'Delivery Addresses | Legacy Food',
            'addresses' => $addresses,
        ]);
    }
}
