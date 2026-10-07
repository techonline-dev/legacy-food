<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class AdminDashboardController extends Controller {
    public function index(): void {
        // Metric aggregations
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');

        $totalSalesRow = Database::fetch("SELECT SUM(total_amount) as total FROM `orders` WHERE `payment_status` = 'paid' OR `status` = 'delivered'");
        $totalSales = (float)($totalSalesRow['total'] ?? 0);

        $todaySalesRow = Database::fetch("SELECT SUM(total_amount) as total FROM `orders` WHERE (`payment_status` = 'paid' OR `status` = 'delivered') AND DATE(created_at) = :today", ['today' => $today]);
        $todaySales = (float)($todaySalesRow['total'] ?? 0);

        $monthlySalesRow = Database::fetch("SELECT SUM(total_amount) as total FROM `orders` WHERE (`payment_status` = 'paid' OR `status` = 'delivered') AND created_at >= :mstart", ['mstart' => $monthStart]);
        $monthlySales = (float)($monthlySalesRow['total'] ?? 0);

        $totalOrdersRow = Database::fetch("SELECT COUNT(*) as total FROM `orders`");
        $totalOrders = (int)($totalOrdersRow['total'] ?? 0);

        $pendingOrdersRow = Database::fetch("SELECT COUNT(*) as total FROM `orders` WHERE `status` IN ('pending', 'payment_confirmed', 'processing')");
        $pendingOrders = (int)($pendingOrdersRow['total'] ?? 0);

        $deliveredOrdersRow = Database::fetch("SELECT COUNT(*) as total FROM `orders` WHERE `status` = 'delivered'");
        $deliveredOrders = (int)($deliveredOrdersRow['total'] ?? 0);

        $totalCustomersRow = Database::fetch("SELECT COUNT(*) as total FROM `users`");
        $totalCustomers = (int)($totalCustomersRow['total'] ?? 0);

        $totalProductsRow = Database::fetch("SELECT COUNT(*) as total FROM `products` WHERE `status` != 'archived'");
        $totalProducts = (int)($totalProductsRow['total'] ?? 0);

        // Low stock products
        $lowStockProducts = Database::fetchAll("SELECT p.*, c.name as category_name FROM `products` p LEFT JOIN `product_categories` c ON p.category_id = c.id WHERE p.stock_quantity <= p.low_stock_threshold ORDER BY p.stock_quantity ASC LIMIT 5");

        // Recent orders
        $recentOrders = Database::fetchAll("SELECT * FROM `orders` ORDER BY id DESC LIMIT 8");

        // Last 7 days sales data for chart
        $chartDates = [];
        $chartSales = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-{$i} days"));
            $chartDates[] = date('d M', strtotime($day));
            $daySale = Database::fetch("SELECT SUM(total_amount) as total FROM `orders` WHERE DATE(created_at) = :d", ['d' => $day]);
            $chartSales[] = (float)($daySale['total'] ?? 0);
        }

        $this->view('admin.dashboard', [
            'meta_title' => 'Admin Dashboard | Legacy Food Control Center',
            'totalSales' => $totalSales,
            'todaySales' => $todaySales,
            'monthlySales' => $monthlySales,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'deliveredOrders' => $deliveredOrders,
            'totalCustomers' => $totalCustomers,
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'recentOrders' => $recentOrders,
            'chartDates' => $chartDates,
            'chartSales' => $chartSales,
        ], 'admin');
    }
}
