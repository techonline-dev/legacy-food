<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Mailer;

class Order extends Model {
    protected static string $table = 'orders';

    public static function generateOrderNumber(): string {
        $year = date('Y');
        $lastOrder = Database::fetch("SELECT id FROM `orders` ORDER BY id DESC LIMIT 1");
        $nextSeq = ($lastOrder ? (int)$lastOrder['id'] : 0) + 1;
        return 'LF-' . $year . '-' . str_pad((string)$nextSeq, 6, '0', STR_PAD_LEFT);
    }

    public static function createFromCart(array $customerData, array $shippingAddress, array $billingAddress, string $paymentMethod): array {
        Database::beginTransaction();

        try {
            // Re-calculate cart totals server-side
            $cartSummary = Cart::getSummary();
            if (empty($cartSummary['items'])) {
                Database::rollBack();
                return ['success' => false, 'error' => 'Your cart is empty.'];
            }

            $userId = Auth::id();
            $orderNumber = self::generateOrderNumber();

            // 1. Validate coupon if present before order generation
            if (!empty($cartSummary['coupon'])) {
                $cCheck = Coupon::validateCoupon(
                    $cartSummary['coupon']['code'],
                    $cartSummary['subtotal'],
                    $userId,
                    $cartSummary['items'],
                    $customerData['email']
                );
                if (!$cCheck['valid']) {
                    Database::rollBack();
                    return ['success' => false, 'error' => 'Coupon validation failed: ' . $cCheck['error']];
                }
            }

            // 1. Insert Order
            $orderId = Database::insert('orders', [
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'guest_email' => $customerData['email'],
                'guest_phone' => $customerData['phone'],
                'status' => 'pending',
                'payment_status' => ($paymentMethod === 'cod') ? 'pending' : 'pending',
                'payment_method' => $paymentMethod,
                'subtotal' => $cartSummary['subtotal'],
                'discount_amount' => $cartSummary['discount'],
                'coupon_code' => $cartSummary['coupon']['code'] ?? null,
                'shipping_amount' => $cartSummary['shipping'],
                'tax_amount' => $cartSummary['tax'],
                'total_amount' => $cartSummary['total'],
                'customer_notes' => $customerData['notes'] ?? null,
                'placed_at' => date('Y-m-d H:i:s')
            ]);

            // 2. Insert Order Items & reduce stock
            foreach ($cartSummary['items'] as $item) {
                Database::insert('order_items', [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'product_name' => $item['name'],
                    'variant_name' => $item['variant_name'] ?: 'Standard',
                    'sku' => $item['sku'],
                    'price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['line_total'],
                ]);

                // Deduct inventory
                if ($item['variant_id']) {
                    Database::query("UPDATE `product_variants` SET `stock_quantity` = GREATEST(0, `stock_quantity` - :qty) WHERE `id` = :vid", [
                        'qty' => $item['quantity'],
                        'vid' => $item['variant_id']
                    ]);
                }
                Database::query("UPDATE `products` SET `stock_quantity` = GREATEST(0, `stock_quantity` - :qty) WHERE `id` = :pid", [
                    'qty' => $item['quantity'],
                    'pid' => $item['product_id']
                ]);

                // Inventory transaction record
                Database::insert('inventory_transactions', [
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'order_id' => $orderId,
                    'type' => 'sale',
                    'quantity_change' => -$item['quantity'],
                    'quantity_before' => 0,
                    'quantity_after' => 0,
                    'reason' => "Order #{$orderNumber}"
                ]);
            }

            // 3. Insert Shipping Address
            Database::insert('order_addresses', [
                'order_id' => $orderId,
                'type' => 'shipping',
                'full_name' => $shippingAddress['full_name'],
                'phone' => $shippingAddress['phone'],
                'email' => $customerData['email'],
                'address_line1' => $shippingAddress['address_line1'],
                'address_line2' => $shippingAddress['address_line2'] ?? null,
                'city' => $shippingAddress['city'],
                'state' => $shippingAddress['state'],
                'postal_code' => $shippingAddress['postal_code'],
                'country' => 'India'
            ]);

            // 4. Insert Billing Address
            Database::insert('order_addresses', [
                'order_id' => $orderId,
                'type' => 'billing',
                'full_name' => $billingAddress['full_name'],
                'phone' => $billingAddress['phone'],
                'email' => $customerData['email'],
                'address_line1' => $billingAddress['address_line1'],
                'address_line2' => $billingAddress['address_line2'] ?? null,
                'city' => $billingAddress['city'],
                'state' => $billingAddress['state'],
                'postal_code' => $billingAddress['postal_code'],
                'country' => 'India'
            ]);

            // 5. Generate Invoice
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad((string)$orderId, 6, '0', STR_PAD_LEFT);
            Database::insert('invoices', [
                'order_id' => $orderId,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => date('Y-m-d'),
                'subtotal' => $cartSummary['subtotal'],
                'discount' => $cartSummary['discount'],
                'shipping' => $cartSummary['shipping'],
                'tax' => $cartSummary['tax'],
                'total' => $cartSummary['total'],
            ]);

            // 6. Record coupon usage if applied
            if (!empty($cartSummary['coupon'])) {
                Database::insert('coupon_usages', [
                    'coupon_id' => $cartSummary['coupon']['id'],
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'discount_amount' => $cartSummary['discount']
                ]);
                Database::query("UPDATE `coupons` SET `usage_count` = `usage_count` + 1 WHERE `id` = :cid", [
                    'cid' => $cartSummary['coupon']['id']
                ]);
            }

            Database::commit();

            // Clear Cart after successful order creation
            Cart::clear();

            // Trigger Email Notification in background
            try {
                $orderRecord = self::getWithDetails($orderId);
                Mailer::sendOrderConfirmation($orderRecord, $orderRecord['items']);
            } catch (\Throwable $mEx) {
                error_log("Failed to send order email: " . $mEx->getMessage());
            }

            return ['success' => true, 'order_id' => $orderId, 'order_number' => $orderNumber];
        } catch (\Throwable $e) {
            Database::rollBack();
            error_log("Order creation failed: " . $e->getMessage());
            return ['success' => false, 'error' => 'Order processing failed: ' . $e->getMessage()];
        }
    }

    public static function getWithDetails(int $orderId): ?array {
        $order = Database::fetch("SELECT * FROM `orders` WHERE `id` = :id LIMIT 1", ['id' => $orderId]);
        if (!$order) return null;

        $order['items'] = Database::fetchAll("SELECT * FROM `order_items` WHERE `order_id` = :oid", ['oid' => $orderId]);
        $order['shipping_address'] = Database::fetch("SELECT * FROM `order_addresses` WHERE `order_id` = :oid AND `type` = 'shipping' LIMIT 1", ['oid' => $orderId]);
        $order['billing_address'] = Database::fetch("SELECT * FROM `order_addresses` WHERE `order_id` = :oid AND `type` = 'billing' LIMIT 1", ['oid' => $orderId]);
        $order['invoice'] = Database::fetch("SELECT * FROM `invoices` WHERE `order_id` = :oid LIMIT 1", ['oid' => $orderId]);

        // Auto-generate invoice if not present
        if (!$order['invoice']) {
            $invoiceNumber = 'INV-' . date('Y', strtotime($order['created_at'])) . '-' . str_pad((string)$orderId, 6, '0', STR_PAD_LEFT);
            try {
                Database::insert('invoices', [
                    'order_id' => $orderId,
                    'invoice_number' => $invoiceNumber,
                    'invoice_date' => date('Y-m-d', strtotime($order['created_at'])),
                    'total_amount' => $order['total_amount'],
                    'tax_amount' => $order['tax_amount'],
                    'file_path' => null
                ]);
                $order['invoice'] = Database::fetch("SELECT * FROM `invoices` WHERE `order_id` = :oid LIMIT 1", ['oid' => $orderId]);
            } catch (\Throwable $invEx) {
                $order['invoice'] = [
                    'invoice_number' => $invoiceNumber,
                    'invoice_date' => date('Y-m-d', strtotime($order['created_at'])),
                    'total_amount' => $order['total_amount'],
                    'tax_amount' => $order['tax_amount'],
                ];
            }
        }

        // Customer details normalization
        $order['customer_name'] = $order['shipping_address']['full_name'] ?? ($order['guest_email'] ?: 'Customer');
        $order['customer_phone'] = $order['guest_phone'] ?? ($order['shipping_address']['phone'] ?? '');
        $order['customer_email'] = $order['guest_email'] ?? ($order['shipping_address']['email'] ?? '');
        $order['shipping_fee'] = $order['shipping_amount'];

        if ((empty($order['customer_name']) || $order['customer_name'] === 'Customer') && !empty($order['user_id'])) {
            $u = Database::fetch("SELECT full_name, email, phone FROM `users` WHERE `id` = :uid LIMIT 1", ['uid' => $order['user_id']]);
            if ($u) {
                if (!empty($u['full_name'])) $order['customer_name'] = $u['full_name'];
                if (empty($order['customer_email']) && !empty($u['email'])) $order['customer_email'] = $u['email'];
                if (empty($order['customer_phone']) && !empty($u['phone'])) $order['customer_phone'] = $u['phone'];
            }
        }

        // Item properties normalization
        foreach ($order['items'] as &$item) {
            $item['unit_price'] = $item['price'];
            $item['total_price'] = $item['total'];
            if (empty($item['image'])) {
                $p = Database::fetch("SELECT featured_image FROM `products` WHERE `id` = :pid LIMIT 1", ['pid' => $item['product_id']]);
                $item['image'] = $p['featured_image'] ?? '';
            }
        }
        unset($item);

        return $order;
    }

    public static function getByNumber(string $orderNumber): ?array {
        $order = Database::fetch("SELECT id FROM `orders` WHERE `order_number` = :num LIMIT 1", ['num' => $orderNumber]);
        return $order ? self::getWithDetails($order['id']) : null;
    }

    public static function getUserOrders(int $userId): array {
        $user = Database::fetch("SELECT email FROM `users` WHERE `id` = :uid LIMIT 1", ['uid' => $userId]);
        if ($user && !empty($user['email'])) {
            try {
                Database::query("UPDATE `orders` SET `user_id` = :uid WHERE LOWER(TRIM(`guest_email`)) = :email AND (`user_id` IS NULL OR `user_id` = 0)", [
                    'uid' => $userId,
                    'email' => strtolower(trim($user['email']))
                ]);
            } catch (\Throwable $t) {}
        }
        return Database::fetchAll("SELECT * FROM `orders` WHERE `user_id` = :uid ORDER BY `id` DESC", ['uid' => $userId]);
    }

    public static function updateStatus(int $orderId, string $status, ?string $trackingNumber = null, ?string $courier = null): bool {
        $validStatuses = ['pending', 'payment_confirmed', 'processing', 'packed', 'shipped', 'delivered', 'cancelled', 'refunded'];
        if (!in_array($status, $validStatuses)) return false;

        $order = Database::fetch("SELECT * FROM `orders` WHERE `id` = :id", ['id' => $orderId]);
        if (!$order) return false;

        $updateData = ['status' => $status];
        if ($trackingNumber !== null) $updateData['tracking_number'] = $trackingNumber;
        if ($courier !== null) $updateData['shipping_courier'] = $courier;

        // Restore stock if transitioning to cancelled or refunded
        if (in_array($status, ['cancelled', 'refunded']) && !in_array($order['status'], ['cancelled', 'refunded'])) {
            $items = Database::fetchAll("SELECT * FROM `order_items` WHERE `order_id` = :oid", ['oid' => $orderId]);
            foreach ($items as $item) {
                if ($item['variant_id']) {
                    Database::query("UPDATE `product_variants` SET `stock_quantity` = `stock_quantity` + :qty WHERE `id` = :vid", [
                        'qty' => $item['quantity'],
                        'vid' => $item['variant_id']
                    ]);
                }
                Database::query("UPDATE `products` SET `stock_quantity` = `stock_quantity` + :qty WHERE `id` = :pid", [
                    'qty' => $item['quantity'],
                    'pid' => $item['product_id']
                ]);
            }
        }

        Database::update('orders', $updateData, "`id` = :id", ['id' => $orderId]);

        ActivityLog::log('order_status_update', "Order #{$order['order_number']} changed to {$status}");

        return true;
    }
}
