<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Database;

class Cart {
    private static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // 1. If session cart is empty, try restoring from persistent guest cookie
        if (empty($_SESSION['cart']) && !empty($_COOKIE['legacy_guest_cart'])) {
            $cookieData = json_decode((string)$_COOKIE['legacy_guest_cart'], true);
            if (is_array($cookieData) && !empty($cookieData)) {
                $_SESSION['cart'] = $cookieData;
            }
        }

        // 2. If logged in and session cart is empty, restore saved items from database
        if (empty($_SESSION['cart']) && Auth::check()) {
            $userId = Auth::id();
            try {
                $dbItems = Database::fetchAll("SELECT * FROM `cart_items` WHERE `user_id` = :uid", ['uid' => $userId]);
                if (!empty($dbItems)) {
                    foreach ($dbItems as $dbi) {
                        $key = $dbi['product_id'] . '_' . ($dbi['variant_id'] ?: '0');
                        $_SESSION['cart'][$key] = [
                            'product_id' => (int)$dbi['product_id'],
                            'variant_id' => $dbi['variant_id'] ? (int)$dbi['variant_id'] : null,
                            'quantity' => (int)$dbi['quantity']
                        ];
                    }
                }
            } catch (\Throwable $t) {
                // Ignore if DB not ready
            }
        }
    }

    private static function persistCartCookie(): void {
        if (!headers_sent()) {
            if (!empty($_SESSION['cart'])) {
                setcookie('legacy_guest_cart', json_encode($_SESSION['cart']), time() + (86400 * 30), '/');
            } else {
                setcookie('legacy_guest_cart', '', time() - 3600, '/');
            }
        }
    }

    public static function getSessionId(): string {
        self::initSession();
        return session_id();
    }

    public static function getStockFor(int $productId, ?int $variantId = null): int {
        if ($variantId) {
            $variant = Database::fetch("SELECT stock_quantity FROM `product_variants` WHERE `id` = :vid", ['vid' => $variantId]);
            if ($variant) return (int)$variant['stock_quantity'];
        }
        $product = Database::fetch("SELECT stock_quantity FROM `products` WHERE `id` = :pid", ['pid' => $productId]);
        return $product ? (int)$product['stock_quantity'] : 0;
    }

    public static function add(int $productId, ?int $variantId = null, int $quantity = 1): array {
        self::initSession();
        $product = Database::fetch("SELECT * FROM `products` WHERE `id` = :id AND `status` = 'published' LIMIT 1", ['id' => $productId]);
        if (!$product) {
            return ['success' => false, 'error' => 'Product not found'];
        }

        $variant = null;
        if ($variantId) {
            $variant = Database::fetch("SELECT * FROM `product_variants` WHERE `id` = :vid AND `product_id` = :pid LIMIT 1", [
                'vid' => $variantId,
                'pid' => $productId
            ]);
        } else {
            // Pick default variant if any
            $variant = Database::fetch("SELECT * FROM `product_variants` WHERE `product_id` = :pid ORDER BY `is_default` DESC, `price` ASC LIMIT 1", [
                'pid' => $productId
            ]);
            if ($variant) {
                $variantId = (int)$variant['id'];
            }
        }

        $itemKey = $productId . '_' . ($variantId ?: '0');

        // Check stock
        $availableStock = $variant ? (int)$variant['stock_quantity'] : (int)$product['stock_quantity'];
        $currentInCart = $_SESSION['cart'][$itemKey]['quantity'] ?? 0;
        if (($currentInCart + $quantity) > $availableStock) {
            return ['success' => false, 'error' => "Only {$availableStock} items in stock."];
        }

        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$itemKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ];
        }

        // Sync to DB if logged in
        if (Auth::check()) {
            $userId = Auth::id();
            try {
                $vSql = $variantId ? "= :vid" : "IS NULL";
                $p = ['uid' => $userId, 'pid' => $productId];
                if ($variantId) $p['vid'] = $variantId;
                $existing = Database::fetch("SELECT id, quantity FROM `cart_items` WHERE `user_id` = :uid AND `product_id` = :pid AND `variant_id` {$vSql}", $p);

                if ($existing) {
                    Database::update('cart_items', ['quantity' => $existing['quantity'] + $quantity], "`id` = :id", ['id' => $existing['id']]);
                } else {
                    Database::insert('cart_items', [
                        'user_id' => $userId,
                        'session_id' => session_id(),
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'quantity' => $quantity
                    ]);
                }
            } catch (\Throwable $e) {
                error_log("Failed to sync cart item to DB: " . $e->getMessage());
            }
        }

        self::persistCartCookie();

        return ['success' => true, 'message' => "Added {$product['name']} to cart!", 'cart_summary' => self::getSummary()];
    }

    public static function update(string $itemKey, int $quantity): array {
        self::initSession();
        if ($quantity <= 0) {
            return self::remove($itemKey);
        }

        if (isset($_SESSION['cart'][$itemKey])) {
            $productId = $_SESSION['cart'][$itemKey]['product_id'];
            $variantId = $_SESSION['cart'][$itemKey]['variant_id'];

            // Stock check
            $stock = self::getStockFor($productId, $variantId);
            if ($quantity > $stock) {
                return ['success' => false, 'error' => "Only {$stock} units available in stock."];
            }

            $_SESSION['cart'][$itemKey]['quantity'] = $quantity;

            if (Auth::check()) {
                $userId = Auth::id();
                try {
                    $vSql = $variantId ? "= :vid" : "IS NULL";
                    $p = ['qty' => $quantity, 'uid' => $userId, 'pid' => $productId];
                    if ($variantId) $p['vid'] = $variantId;
                    Database::query("UPDATE `cart_items` SET `quantity` = :qty WHERE `user_id` = :uid AND `product_id` = :pid AND `variant_id` {$vSql}", $p);
                } catch (\Throwable $e) {
                    error_log("Failed to update cart item in DB: " . $e->getMessage());
                }
            }

            self::persistCartCookie();
        }

        return ['success' => true, 'cart_summary' => self::getSummary()];
    }

    public static function remove(string $itemKey): array {
        self::initSession();
        if (isset($_SESSION['cart'][$itemKey])) {
            $item = $_SESSION['cart'][$itemKey];
            unset($_SESSION['cart'][$itemKey]);

            if (Auth::check()) {
                $userId = Auth::id();
                try {
                    $vSql = $item['variant_id'] ? "= :vid" : "IS NULL";
                    $params = ['uid' => $userId, 'pid' => $item['product_id']];
                    if ($item['variant_id']) $params['vid'] = $item['variant_id'];
                    Database::query("DELETE FROM `cart_items` WHERE `user_id` = :uid AND `product_id` = :pid AND `variant_id` {$vSql}", $params);
                } catch (\Throwable $e) {
                    error_log("Failed to delete cart item in DB: " . $e->getMessage());
                }
            }

            self::persistCartCookie();
        }

        return ['success' => true, 'cart_summary' => self::getSummary()];
    }

    public static function clear(): void {
        self::initSession();
        $_SESSION['cart'] = [];
        unset($_SESSION['coupon']);

        if (Auth::check()) {
            try {
                Database::query("DELETE FROM `cart_items` WHERE `user_id` = :uid", ['uid' => Auth::id()]);
            } catch (\Throwable $e) {
                error_log("Failed to clear DB cart: " . $e->getMessage());
            }
        }

        self::persistCartCookie();
    }

    public static function syncSessionCartToUser(int $userId): void {
        self::initSession();

        try {
            // 1. Load any existing cart items already saved in DB for this user
            $dbItems = Database::fetchAll("SELECT * FROM `cart_items` WHERE `user_id` = :uid", ['uid' => $userId]);

            // 2. Intelligently merge database cart items with the active guest cart
            foreach ($dbItems as $dbi) {
                $pid = (int)$dbi['product_id'];
                $vid = $dbi['variant_id'] ? (int)$dbi['variant_id'] : null;
                $dbQty = (int)$dbi['quantity'];
                $key = $pid . '_' . ($vid ?: '0');

                if (isset($_SESSION['cart'][$key])) {
                    // Item was added in guest session AND also existed in user's saved DB cart -> merge
                    $_SESSION['cart'][$key]['quantity'] += $dbQty;
                } else {
                    // Item only existed in user's saved DB cart -> restore into session
                    $_SESSION['cart'][$key] = [
                        'product_id' => $pid,
                        'variant_id' => $vid,
                        'quantity' => $dbQty
                    ];
                }
            }

            // 3. Stock & validity check for ALL items in merged cart
            foreach ($_SESSION['cart'] as $k => &$cItem) {
                $pid = (int)($cItem['product_id'] ?? 0);
                $vid = !empty($cItem['variant_id']) ? (int)$cItem['variant_id'] : null;
                $stock = self::getStockFor($pid, $vid);
                if ($stock <= 0) {
                    unset($_SESSION['cart'][$k]);
                } elseif ((int)$cItem['quantity'] > $stock) {
                    $cItem['quantity'] = $stock;
                }
            }
            unset($cItem);

            // 4. Save the unified merged cart back to the database for this user
            Database::query("DELETE FROM `cart_items` WHERE `user_id` = :uid", ['uid' => $userId]);
            foreach ($_SESSION['cart'] as $item) {
                Database::insert('cart_items', [
                    'user_id' => $userId,
                    'session_id' => session_id(),
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?: null,
                    'quantity' => $item['quantity']
                ]);
            }
        } catch (\Throwable $e) {
            error_log("Cart sync error: " . $e->getMessage());
        }

        self::persistCartCookie();
    }

    public static function getSummary(): array {
        self::initSession();
        $items = [];
        $subtotal = 0.00;
        $totalItemsCount = 0;

        foreach ($_SESSION['cart'] as $key => $cartItem) {
            $pid = (int)$cartItem['product_id'];
            $vid = !empty($cartItem['variant_id']) ? (int)$cartItem['variant_id'] : null;
            $qty = (int)$cartItem['quantity'];

            $product = Database::fetch("SELECT id, name, slug, featured_image, base_price, sale_price, stock_quantity, sku, gst_rate, hsn_code FROM `products` WHERE `id` = :pid LIMIT 1", ['pid' => $pid]);
            if (!$product) continue;

            $variant = null;
            $unitPrice = (float)($product['sale_price'] ?: $product['base_price']);
            $variantName = '';
            $sku = $product['sku'];
            $image = $product['featured_image'];

            if ($vid) {
                $variant = Database::fetch("SELECT id, name, price, sale_price, stock_quantity, sku, image FROM `product_variants` WHERE `id` = :vid LIMIT 1", ['vid' => $vid]);
                if ($variant) {
                    $unitPrice = (float)($variant['sale_price'] ?: $variant['price']);
                    $variantName = $variant['name'];
                    $sku = $variant['sku'];
                    if (!empty($variant['image'])) {
                        $image = $variant['image'];
                    }
                }
            }

            $lineTotal = $unitPrice * $qty;
            $subtotal += $lineTotal;
            $totalItemsCount += $qty;

            $items[] = [
                'key' => $key,
                'product_id' => $pid,
                'variant_id' => $vid,
                'name' => $product['name'],
                'slug' => $product['slug'],
                'variant_name' => $variantName,
                'sku' => $sku,
                'image' => $image,
                'unit_price' => $unitPrice,
                'unit_price_formatted' => currency_format($unitPrice),
                'quantity' => $qty,
                'line_total' => $lineTotal,
                'line_total_formatted' => currency_format($lineTotal),
                'total_price' => $lineTotal,
                'total_price_formatted' => currency_format($lineTotal),
                'gst_rate' => ($product['gst_rate'] !== null && $product['gst_rate'] !== '') ? (float)$product['gst_rate'] : null,
                'hsn_code' => $product['hsn_code'] ?: setting('default_hsn_code', '04059020'),
            ];
        }

        // Coupon calculation with full conditional rules
        $discount = 0.00;
        $appliedCoupon = $_SESSION['coupon'] ?? null;
        if ($appliedCoupon && $subtotal > 0) {
            $user = \App\Core\Auth::user();
            $userId = $user['id'] ?? null;
            $userEmail = $user['email'] ?? null;

            $couponCheck = Coupon::validateCoupon($appliedCoupon['code'], $subtotal, $userId, $items, $userEmail);
            if ($couponCheck['valid']) {
                $discount = (float)$couponCheck['discount'];
                $appliedCoupon = array_merge($appliedCoupon, $couponCheck['coupon'], [
                    'discount' => $discount,
                    'discount_formatted' => $couponCheck['discount_formatted']
                ]);
            } else {
                unset($_SESSION['coupon']);
                $appliedCoupon = null;
            }
        }

        // Shipping calculation: Free over threshold
        $freeShippingThreshold = (float)setting('free_shipping_min', 999.00);
        $standardShipping = (float)setting('standard_shipping_charge', 60.00);

        if ($subtotal <= 0) {
            $shipping = 0.00;
        } elseif ($subtotal >= $freeShippingThreshold) {
            $shipping = 0.00;
        } else {
            $shipping = $standardShipping;
        }

        // Dynamic Tax calculation based on configured GST settings
        $globalGstRate = (float)setting('gst_percentage', 5.00);
        $taxInclusive = (setting('tax_inclusive', '1') == '1');

        $calculatedTax = 0.00;

        foreach ($items as $item) {
            $effectiveRate = ($item['gst_rate'] !== null) ? (float)$item['gst_rate'] : $globalGstRate;
            $lineShare = $subtotal > 0 ? ($item['line_total'] / $subtotal) : 0;
            $itemDiscountedTotal = max(0.00, $item['line_total'] - ($discount * $lineShare));

            if ($taxInclusive) {
                // Price includes GST: Tax = Amount - (Amount / (1 + Rate / 100))
                if ($effectiveRate > 0) {
                    $calculatedTax += $itemDiscountedTotal - ($itemDiscountedTotal / (1 + ($effectiveRate / 100)));
                }
            } else {
                // Price excludes GST: Tax = Amount * (Rate / 100)
                $calculatedTax += $itemDiscountedTotal * ($effectiveRate / 100);
            }
        }

        $tax = round($calculatedTax, 2);

        // Grand total
        if ($taxInclusive) {
            $grandTotal = max(0.00, ($subtotal - $discount + $shipping));
        } else {
            $grandTotal = max(0.00, ($subtotal - $discount + $shipping + $tax));
        }

        $halfTax = round($tax / 2, 2);
        $otherHalfTax = round($tax - $halfTax, 2);

        return [
            'items' => $items,
            'items_count' => $totalItemsCount,
            'subtotal' => $subtotal,
            'subtotal_formatted' => currency_format($subtotal),
            'discount' => $discount,
            'discount_formatted' => currency_format($discount),
            'coupon' => $appliedCoupon,
            'coupon_code' => $appliedCoupon['code'] ?? null,
            'shipping' => $shipping,
            'shipping_formatted' => $shipping == 0 ? 'FREE' : currency_format($shipping),
            'shipping_fee' => $shipping,
            'shipping_fee_formatted' => $shipping == 0 ? 'FREE' : currency_format($shipping),
            'free_shipping_threshold' => $freeShippingThreshold,
            'amount_needed_for_free_shipping' => max(0, $freeShippingThreshold - $subtotal),
            'tax' => $tax,
            'tax_formatted' => currency_format($tax),
            'tax_rate' => $globalGstRate,
            'tax_inclusive' => $taxInclusive,
            'tax_label' => 'GST ' . $globalGstRate . '%' . ($taxInclusive ? ' (Included)' : ' (Added)'),
            'cgst' => $halfTax,
            'sgst' => $otherHalfTax,
            'cgst_formatted' => currency_format($halfTax),
            'sgst_formatted' => currency_format($otherHalfTax),
            'total' => $grandTotal,
            'total_formatted' => currency_format($grandTotal),
            'available_coupons' => Coupon::getActiveCoupons(),
        ];
    }
}
