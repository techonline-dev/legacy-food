<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Coupon extends Model {
    protected static string $table = 'coupons';

    /**
     * Validate a coupon against subtotal, user ID, cart items, and customer email.
     */
    public static function validateCoupon(
        string $code,
        float $subtotal,
        ?int $userId = null,
        ?array $items = null,
        ?string $customerEmail = null
    ): array {
        $code = strtoupper(trim($code));
        if (empty($code)) {
            return ['valid' => false, 'error' => 'Please enter a coupon code.'];
        }

        $coupon = Database::fetch("SELECT * FROM `coupons` WHERE UPPER(`code`) = :code LIMIT 1", ['code' => $code]);

        if (!$coupon) {
            return ['valid' => false, 'error' => "Coupon code '{$code}' does not exist."];
        }

        if (empty($coupon['is_active'])) {
            return ['valid' => false, 'error' => "Coupon '{$code}' is currently inactive."];
        }

        // 1. Date validity checks
        $now = date('Y-m-d H:i:s');
        if (!empty($coupon['start_date']) && $now < $coupon['start_date']) {
            $startDate = date('d M Y', strtotime($coupon['start_date']));
            return ['valid' => false, 'error' => "This coupon will be active starting {$startDate}."];
        }
        if (!empty($coupon['end_date']) && $now > $coupon['end_date']) {
            return ['valid' => false, 'error' => 'This coupon has expired.'];
        }

        // 2. Minimum order amount check
        $minOrder = (float)($coupon['min_order_amount'] ?? 0);
        if ($subtotal < $minOrder) {
            return [
                'valid' => false,
                'error' => "Minimum order value of ₹" . number_format($minOrder, 2) . " required for coupon '{$code}'."
            ];
        }

        // 3. Total store-wide usage limit check
        if (!empty($coupon['usage_limit_total']) && (int)$coupon['usage_limit_total'] > 0) {
            if ((int)$coupon['usage_count'] >= (int)$coupon['usage_limit_total']) {
                return ['valid' => false, 'error' => 'This coupon has reached its total redemption limit.'];
            }
        }

        // 4. Minimum items quantity check
        $minQty = (int)($coupon['min_quantity'] ?? 0);
        if ($minQty > 0) {
            $totalQty = 0;
            if (!empty($items)) {
                foreach ($items as $it) {
                    $totalQty += (int)($it['quantity'] ?? 1);
                }
            }
            if ($totalQty < $minQty) {
                return [
                    'valid' => false,
                    'error' => "Coupon '{$code}' requires at least {$minQty} item(s) in your cart."
                ];
            }
        }

        // 5. Category-specific restriction check
        $eligibleSubtotal = $subtotal;
        if (!empty($coupon['category_id']) && (int)$coupon['category_id'] > 0) {
            $catId = (int)$coupon['category_id'];
            $category = Database::fetch("SELECT name FROM `product_categories` WHERE `id` = :cid LIMIT 1", ['cid' => $catId]);
            $catName = $category['name'] ?? 'selected category';

            $categorySubtotal = 0.0;
            $hasCategoryItem = false;

            if (!empty($items)) {
                foreach ($items as $it) {
                    $prod = Database::fetch("SELECT category_id FROM `products` WHERE `id` = :pid LIMIT 1", ['pid' => $it['product_id']]);
                    if ($prod && (int)$prod['category_id'] === $catId) {
                        $hasCategoryItem = true;
                        $categorySubtotal += (float)$it['line_total'];
                    }
                }
            }

            if (!$hasCategoryItem) {
                return [
                    'valid' => false,
                    'error' => "Coupon '{$code}' is only applicable for products in the '{$catName}' collection."
                ];
            }

            // Cap percentage discounts to the eligible category subtotal
            $eligibleSubtotal = $categorySubtotal;
        }

        // 6. First-order only restriction check
        if (!empty($coupon['first_order_only'])) {
            $hasPriorOrder = false;
            if ($userId) {
                $pastCount = Database::fetch("SELECT COUNT(*) as cnt FROM `orders` WHERE `user_id` = :uid AND `status` != 'cancelled'", ['uid' => $userId])['cnt'] ?? 0;
                if ($pastCount > 0) $hasPriorOrder = true;
            }
            if ($customerEmail) {
                $pastEmailCount = Database::fetch("SELECT COUNT(*) as cnt FROM `orders` WHERE `guest_email` = :em AND `status` != 'cancelled'", ['em' => $customerEmail])['cnt'] ?? 0;
                if ($pastEmailCount > 0) $hasPriorOrder = true;
            }
            if ($hasPriorOrder) {
                return [
                    'valid' => false,
                    'error' => "Coupon '{$code}' is valid only for first-time customers."
                ];
            }
        }

        // 7. Per-user usage limit check
        $perUserLimit = (int)($coupon['usage_limit_per_user'] ?? 1);
        if ($perUserLimit > 0) {
            $userUsages = 0;
            if ($userId) {
                $userUsageRec = Database::fetch("SELECT COUNT(*) as cnt FROM `coupon_usages` WHERE `coupon_id` = :cid AND `user_id` = :uid", [
                    'cid' => $coupon['id'],
                    'uid' => $userId
                ]);
                $userUsages = max($userUsages, (int)($userUsageRec['cnt'] ?? 0));
            }
            if ($customerEmail) {
                $emailUsageRec = Database::fetch("SELECT COUNT(*) as cnt FROM `coupon_usages` cu JOIN `orders` o ON cu.order_id = o.id WHERE cu.coupon_id = :cid AND o.guest_email = :em", [
                    'cid' => $coupon['id'],
                    'em' => $customerEmail
                ]);
                $userUsages = max($userUsages, (int)($emailUsageRec['cnt'] ?? 0));
            }

            if ($userUsages >= $perUserLimit) {
                return [
                    'valid' => false,
                    'error' => "You have already reached the maximum usage limit ({$perUserLimit}) for coupon '{$code}'."
                ];
            }
        }

        // 8. Calculate discount amount
        $discount = 0.00;
        if ($coupon['type'] === 'percentage') {
            $discount = round(($eligibleSubtotal * (float)$coupon['value']) / 100, 2);
            if (!empty($coupon['max_discount_amount']) && (float)$coupon['max_discount_amount'] > 0) {
                $discount = min($discount, (float)$coupon['max_discount_amount']);
            }
        } else {
            $discount = min($eligibleSubtotal, (float)$coupon['value']);
        }
        $discount = max(0.00, round($discount, 2));

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'discount_formatted' => currency_format($discount),
            'message' => "Coupon '{$coupon['code']}' applied! Saved " . currency_format($discount)
        ];
    }

    /**
     * Get active, available coupons for promotional display on cart & checkout.
     */
    public static function getActiveCoupons(): array {
        $now = date('Y-m-d H:i:s');
        $coupons = Database::fetchAll("
            SELECT * FROM `coupons`
            WHERE `is_active` = 1
              AND (`start_date` IS NULL OR `start_date` <= :now1)
              AND (`end_date` IS NULL OR `end_date` >= :now2)
              AND (`usage_limit_total` IS NULL OR `usage_count` < `usage_limit_total`)
            ORDER BY `value` DESC
            LIMIT 5
        ", ['now1' => $now, 'now2' => $now]);

        foreach ($coupons as &$c) {
            $c['discount_type'] = $c['type'] ?? 'fixed';
            $c['discount_value'] = (float)($c['value'] ?? 0);
        }
        unset($c);

        return $coupons;
    }
}
