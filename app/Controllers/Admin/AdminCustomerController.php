<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\ActivityLog;

class AdminCustomerController extends Controller {
    public function index(): void {
        $q = trim((string)$this->request->input('q', ''));
        $where = "1=1";
        $params = [];

        if ($q !== '') {
            $where = "(u.name LIKE :q OR u.email LIKE :q OR u.phone LIKE :q)";
            $params['q'] = '%' . $q . '%';
        }

        $customers = Database::fetchAll("
            SELECT u.*, 
                   COUNT(o.id) as orders_count,
                   COALESCE(SUM(o.total_amount), 0) as total_spent,
                   MAX(o.created_at) as last_order_date
            FROM `users` u
            LEFT JOIN `orders` o ON u.id = o.user_id
            WHERE {$where}
            GROUP BY u.id
            ORDER BY u.id DESC
        ", $params);

        $this->view('admin.customers.index', [
            'meta_title' => 'Registered Customers | Legacy Food Admin',
            'customers' => $customers,
            'searchQuery' => $q,
        ], 'admin');
    }

    public function detail(int $id): void {
        $customer = Database::fetch("SELECT * FROM `users` WHERE `id` = :id LIMIT 1", ['id' => $id]);
        if (!$customer) {
            flash('error', 'Customer not found.');
            $this->redirect('admin/customers');
            return;
        }

        // Customer Order History
        $orders = Database::fetchAll("
            SELECT o.*,
                   (SELECT COUNT(*) FROM `order_items` oi WHERE oi.order_id = o.id) as items_count
            FROM `orders` o
            WHERE o.user_id = :uid
            ORDER BY o.id DESC
        ", ['uid' => $id]);

        // Customer Metrics
        $totalSpent = (float)(Database::fetch("SELECT SUM(total_amount) as s FROM `orders` WHERE user_id = :uid", ['uid' => $id])['s'] ?? 0);
        $ordersCount = count($orders);
        $deliveredCount = (int)(Database::fetch("SELECT COUNT(*) as c FROM `orders` WHERE user_id = :uid AND status = 'delivered'", ['uid' => $id])['c'] ?? 0);
        $pendingCount = (int)(Database::fetch("SELECT COUNT(*) as c FROM `orders` WHERE user_id = :uid AND status = 'pending'", ['uid' => $id])['c'] ?? 0);
        $avgOrderValue = $ordersCount > 0 ? ($totalSpent / $ordersCount) : 0;

        // Shipping Addresses used by customer in past orders
        $rawAddresses = Database::fetchAll("
            SELECT id, full_name, phone, email, address_line1, address_line2, city, state, postal_code, country
            FROM `order_addresses`
            WHERE `order_id` IN (SELECT id FROM `orders` WHERE user_id = :uid)
            ORDER BY id DESC
        ", ['uid' => $id]);

        $addresses = [];
        $seen = [];
        foreach ($rawAddresses as $addr) {
            $key = strtolower(trim($addr['address_line1'] . '|' . $addr['postal_code']));
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $addresses[] = $addr;
                if (count($addresses) >= 6) break;
            }
        }

        // Product Reviews by this user
        $reviews = Database::fetchAll("
            SELECT r.*, p.name as product_name, p.slug as product_slug, p.featured_image
            FROM `reviews` r
            LEFT JOIN `products` p ON r.product_id = p.id
            WHERE r.user_id = :uid
            ORDER BY r.id DESC
        ", ['uid' => $id]);

        // Wishlist items
        $wishlist = Database::fetchAll("
            SELECT wi.*, p.name, p.slug, p.name as product_name, p.slug as product_slug, p.featured_image, p.base_price
            FROM `wishlists` w
            JOIN `wishlist_items` wi ON w.id = wi.wishlist_id
            JOIN `products` p ON wi.product_id = p.id
            WHERE w.user_id = :uid
            ORDER BY wi.id DESC
        ", ['uid' => $id]);

        $this->view('admin.customers.detail', [
            'meta_title' => "Customer Profile: {$customer['name']} | Legacy Food Admin",
            'customer' => $customer,
            'orders' => $orders,
            'addresses' => $addresses,
            'reviews' => $reviews,
            'wishlist' => $wishlist,
            'stats' => [
                'totalSpent' => $totalSpent,
                'ordersCount' => $ordersCount,
                'deliveredCount' => $deliveredCount,
                'pendingCount' => $pendingCount,
                'avgOrderValue' => $avgOrderValue,
            ]
        ], 'admin');
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/customers/' . $id);
            return;
        }

        $customer = Database::fetch("SELECT * FROM `users` WHERE `id` = :id LIMIT 1", ['id' => $id]);
        if (!$customer) {
            flash('error', 'Customer not found.');
            $this->redirect('admin/customers');
            return;
        }

        $name = sanitize($this->request->input('name'));
        $email = sanitize($this->request->input('email'));
        $phone = sanitize($this->request->input('phone'));
        $status = $this->request->input('status', 'active');

        if (empty($name) || empty($email)) {
            flash('error', 'Name and email are required.');
            $this->redirect('admin/customers/' . $id);
            return;
        }

        // Email uniqueness check
        $exists = Database::fetch("SELECT id FROM `users` WHERE `email` = :em AND `id` != :id LIMIT 1", ['em' => $email, 'id' => $id]);
        if ($exists) {
            flash('error', "Email '{$email}' is already associated with another customer.");
            $this->redirect('admin/customers/' . $id);
            return;
        }

        Database::update('users', [
            'name' => $name,
            'email' => $email,
            'phone' => $phone ?: null,
            'status' => in_array($status, ['active', 'blocked']) ? $status : 'active',
        ], "`id` = :id", ['id' => $id]);

        ActivityLog::log('customer_updated', "Updated customer profile for {$name} (#{$id})");
        flash('success', "Customer details updated successfully.");
        $this->redirect('admin/customers/' . $id);
    }

    public function updateStatus(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/customers/' . $id);
            return;
        }

        $customer = Database::fetch("SELECT * FROM `users` WHERE `id` = :id LIMIT 1", ['id' => $id]);
        if (!$customer) {
            flash('error', 'Customer not found.');
            $this->redirect('admin/customers');
            return;
        }

        $newStatus = $customer['status'] === 'active' ? 'blocked' : 'active';
        Database::update('users', ['status' => $newStatus], "`id` = :id", ['id' => $id]);

        ActivityLog::log('customer_status_changed', "Changed customer #{$id} status to {$newStatus}");
        flash('success', "Customer account status set to " . ucfirst($newStatus) . ".");
        $this->redirect('admin/customers/' . $id);
    }
}
