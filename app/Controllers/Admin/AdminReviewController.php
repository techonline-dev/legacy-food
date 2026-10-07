<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Review;

class AdminReviewController extends Controller {
    public function index(): void {
        $reviews = Database::fetchAll("
            SELECT r.*, p.name as product_name 
            FROM `reviews` r
            JOIN `products` p ON r.product_id = p.id
            ORDER BY r.id DESC
        ");

        $this->view('admin.reviews.index', [
            'meta_title' => 'Customer Reviews Moderation | Legacy Food Admin',
            'reviews' => $reviews
        ], 'admin');
    }

    public function updateStatus(int $id, string $status): void {
        if (in_array($status, ['approved', 'pending', 'rejected'])) {
            Database::update('reviews', ['status' => $status], "`id` = :id", ['id' => $id]);
            $rev = Review::find($id);
            if ($rev) {
                Review::updateProductRatingCache($rev['product_id']);
            }
            flash('success', "Review status updated to {$status}.");
        }
        $this->redirect('admin/reviews');
    }

    public function delete(int $id): void {
        $rev = Review::find($id);
        Database::query("DELETE FROM `reviews` WHERE `id` = :id", ['id' => $id]);
        if ($rev) {
            Review::updateProductRatingCache($rev['product_id']);
        }
        flash('success', 'Review deleted.');
        $this->redirect('admin/reviews');
    }
}
