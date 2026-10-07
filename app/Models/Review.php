<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Review extends Model {
    protected static string $table = 'reviews';

    public static function getApproved(int $productId): array {
        return Database::fetchAll("SELECT * FROM `reviews` WHERE `product_id` = :pid AND `status` = 'approved' ORDER BY `created_at` DESC", ['pid' => $productId]);
    }

    public static function addReview(array $data): int {
        $reviewId = Database::insert('reviews', [
            'product_id' => $data['product_id'],
            'user_id' => $data['user_id'] ?? null,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'] ?? null,
            'rating' => max(1, min(5, (int)$data['rating'])),
            'title' => $data['title'] ?? null,
            'review_text' => $data['review_text'],
            'is_verified_purchase' => $data['is_verified_purchase'] ?? 0,
            'status' => 'approved' // auto-approve verified/customer reviews or default to approved for smooth store demo
        ]);

        // Update product rating cache
        self::updateProductRatingCache((int)$data['product_id']);

        return $reviewId;
    }

    public static function updateProductRatingCache(int $productId): void {
        $stats = Database::fetch("SELECT COUNT(*) as cnt, AVG(rating) as avg_rating FROM `reviews` WHERE `product_id` = :pid AND `status` = 'approved'", ['pid' => $productId]);
        if ($stats) {
            Database::update('products', [
                'rating_cache' => round((float)($stats['avg_rating'] ?: 5.0), 2),
                'review_count_cache' => (int)$stats['cnt']
            ], "`id` = :id", ['id' => $productId]);
        }
    }
}
