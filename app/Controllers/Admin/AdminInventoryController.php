<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\ActivityLog;

class AdminInventoryController extends Controller {
    public function index(): void {
        $inventory = Database::fetchAll("
            SELECT p.id as product_id, p.name as product_name, p.sku as product_sku, p.featured_image,
                   pv.id as variant_id, pv.name as variant_name, pv.sku as variant_sku,
                   COALESCE(pv.stock_quantity, p.stock_quantity) as stock,
                   p.low_stock_threshold
            FROM `products` p
            LEFT JOIN `product_variants` pv ON p.id = pv.product_id
            ORDER BY stock ASC, p.name ASC
        ");

        $transactions = Database::fetchAll("
            SELECT it.*, p.name as product_name 
            FROM `inventory_transactions` it
            LEFT JOIN `products` p ON it.product_id = p.id
            ORDER BY it.id DESC LIMIT 30
        ");

        $this->view('admin.inventory.index', [
            'meta_title' => 'Inventory Management | Legacy Food Admin',
            'inventory' => $inventory,
            'transactions' => $transactions,
        ], 'admin');
    }

    public function adjust(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/inventory');
            return;
        }

        $productId = (int)$this->request->input('product_id');
        $variantId = $this->request->input('variant_id') ? (int)$this->request->input('variant_id') : null;
        $adjustment = (int)$this->request->input('adjustment_quantity');
        $reason = sanitize($this->request->input('reason', 'Manual Stock Adjustment'));

        if ($variantId) {
            $curr = Database::fetch("SELECT stock_quantity FROM `product_variants` WHERE `id` = :vid", ['vid' => $variantId]);
            $before = (int)($curr['stock_quantity'] ?? 0);
            $after = max(0, $before + $adjustment);

            Database::update('product_variants', ['stock_quantity' => $after], "`id` = :vid", ['vid' => $variantId]);
        } else {
            $curr = Database::fetch("SELECT stock_quantity FROM `products` WHERE `id` = :pid", ['pid' => $productId]);
            $before = (int)($curr['stock_quantity'] ?? 0);
            $after = max(0, $before + $adjustment);

            Database::update('products', ['stock_quantity' => $after], "`id` = :pid", ['pid' => $productId]);
        }

        Database::insert('inventory_transactions', [
            'product_id' => $productId,
            'variant_id' => $variantId,
            'type' => 'adjustment',
            'quantity_change' => $adjustment,
            'quantity_before' => $before,
            'quantity_after' => $after,
            'reason' => $reason
        ]);

        ActivityLog::log('inventory_adjusted', "Adjusted stock by {$adjustment} for Product #{$productId}: {$reason}");

        flash('success', "Stock adjusted successfully.");
        $this->redirect('admin/inventory');
    }
}
