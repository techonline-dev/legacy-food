<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Order;
use App\Models\ActivityLog;

class AdminOrderController extends Controller {
    public function index(): void {
        $status = $this->request->input('status');
        $where = "1=1";
        $params = [];

        if (!empty($status)) {
            $where = "o.`status` = :status";
            $params['status'] = $status;
        }

        $orders = Database::fetchAll("
            SELECT o.*, 
                   COALESCE(oa.full_name, u.name, o.guest_email, 'Customer') as customer_name,
                   COALESCE(o.guest_phone, oa.phone, u.phone, 'N/A') as customer_phone,
                   COALESCE(o.guest_email, oa.email, u.email, 'N/A') as customer_email
            FROM `orders` o
            LEFT JOIN `order_addresses` oa ON o.id = oa.order_id AND oa.type = 'shipping'
            LEFT JOIN `users` u ON o.user_id = u.id
            WHERE {$where}
            ORDER BY o.id DESC
        ", $params);

        $counts = [
            'all' => Database::fetch("SELECT COUNT(*) as c FROM `orders`")['c'] ?? 0,
            'pending' => Database::fetch("SELECT COUNT(*) as c FROM `orders` WHERE `status` = 'pending'")['c'] ?? 0,
            'processing' => Database::fetch("SELECT COUNT(*) as c FROM `orders` WHERE `status` = 'processing'")['c'] ?? 0,
            'shipped' => Database::fetch("SELECT COUNT(*) as c FROM `orders` WHERE `status` = 'shipped'")['c'] ?? 0,
            'delivered' => Database::fetch("SELECT COUNT(*) as c FROM `orders` WHERE `status` = 'delivered'")['c'] ?? 0,
            'cancelled' => Database::fetch("SELECT COUNT(*) as c FROM `orders` WHERE `status` = 'cancelled'")['c'] ?? 0,
        ];

        $this->view('admin.orders.index', [
            'meta_title' => 'Customer Orders | Legacy Food Admin',
            'orders' => $orders,
            'counts' => $counts,
            'currentStatus' => $status
        ], 'admin');
    }

    public function detail(int $id): void {
        $order = Order::getWithDetails($id);
        if (!$order) {
            flash('error', 'Order not found.');
            $this->redirect('admin/orders');
            return;
        }

        $this->view('admin.orders.detail', [
            'meta_title' => "Order #{$order['order_number']} | Legacy Food Admin",
            'order' => $order,
        ], 'admin');
    }

    public function updateStatus(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/orders/' . $id);
            return;
        }

        $status = sanitize($this->request->input('status'));
        $courier = sanitize($this->request->input('shipping_courier'));
        $tracking = sanitize($this->request->input('tracking_number'));
        $adminNotes = sanitize($this->request->input('admin_notes'));

        Order::updateStatus($id, $status, $tracking, $courier);

        if (!empty($adminNotes)) {
            Database::update('orders', ['admin_notes' => $adminNotes], "`id` = :id", ['id' => $id]);
        }

        flash('success', "Order status successfully updated to {$status}.");
        $this->redirect('admin/orders/' . $id);
    }
}
