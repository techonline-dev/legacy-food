<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class AdminCustomerController extends Controller {
    public function index(): void {
        $customers = Database::fetchAll("
            SELECT u.*, 
                   COUNT(o.id) as orders_count,
                   COALESCE(SUM(o.total_amount), 0) as total_spent
            FROM `users` u
            LEFT JOIN `orders` o ON u.id = o.user_id
            GROUP BY u.id
            ORDER BY u.id DESC
        ");

        $this->view('admin.customers.index', [
            'meta_title' => 'Registered Customers | Legacy Food Admin',
            'customers' => $customers
        ], 'admin');
    }
}
