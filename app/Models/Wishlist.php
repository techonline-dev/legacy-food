<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Core\Auth;

class Wishlist extends Model {
    protected static string $table = 'wishlists';

    public static function getUserItems(int $userId): array {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, wi.created_at as added_at 
                FROM `wishlists` w
                JOIN `wishlist_items` wi ON w.id = wi.wishlist_id
                JOIN `products` p ON wi.product_id = p.id
                LEFT JOIN `product_categories` c ON p.category_id = c.id
                WHERE w.user_id = :uid AND p.status = 'published'
                ORDER BY wi.created_at DESC";
        return Database::fetchAll($sql, ['uid' => $userId]);
    }

    public static function toggle(int $productId): array {
        if (!Auth::check()) {
            return ['success' => false, 'require_login' => true, 'message' => 'Please login to manage your wishlist.'];
        }

        $userId = Auth::id();
        $wishlist = Database::fetch("SELECT id FROM `wishlists` WHERE `user_id` = :uid LIMIT 1", ['uid' => $userId]);
        if (!$wishlist) {
            $wishlistId = Database::insert('wishlists', ['user_id' => $userId]);
        } else {
            $wishlistId = (int)$wishlist['id'];
        }

        $exists = Database::fetch("SELECT id FROM `wishlist_items` WHERE `wishlist_id` = :wid AND `product_id` = :pid", [
            'wid' => $wishlistId,
            'pid' => $productId
        ]);

        if ($exists) {
            Database::query("DELETE FROM `wishlist_items` WHERE `id` = :id", ['id' => $exists['id']]);
            $added = false;
            $msg = 'Removed from wishlist.';
        } else {
            Database::insert('wishlist_items', [
                'wishlist_id' => $wishlistId,
                'product_id' => $productId
            ]);
            $added = true;
            $msg = 'Added to wishlist!';
        }

        $count = Database::fetch("SELECT COUNT(*) as cnt FROM `wishlist_items` WHERE `wishlist_id` = :wid", ['wid' => $wishlistId]);

        return [
            'success' => true,
            'added' => $added,
            'message' => $msg,
            'wishlist_count' => (int)($count['cnt'] ?? 0)
        ];
    }

    public static function getCount(int $userId): int {
        $res = Database::fetch("SELECT COUNT(wi.id) as cnt FROM `wishlists` w JOIN `wishlist_items` wi ON w.id = wi.wishlist_id WHERE w.user_id = :uid", ['uid' => $userId]);
        return (int)($res['cnt'] ?? 0);
    }
}
