<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller {
    public function store(): void {
        if (!$this->validateCsrf()) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => 'Security token expired.'], 419);
                return;
            }
            flash('error', 'Token expired.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? 'shop');
            return;
        }

        $productId = (int)$this->request->input('product_id');
        $rating = (int)$this->request->input('rating', 5);
        $name = sanitize($this->request->input('customer_name'));
        $email = sanitize($this->request->input('customer_email'));
        $title = sanitize($this->request->input('title'));
        $reviewText = sanitize($this->request->input('review_text'));

        if (!$productId || empty($name) || empty($reviewText)) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => 'Please fill in your name, rating and review.'], 422);
                return;
            }
            flash('error', 'Please fill in all required review fields.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? 'shop');
            return;
        }

        Review::addReview([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'customer_name' => $name,
            'customer_email' => $email,
            'rating' => $rating,
            'title' => $title,
            'review_text' => $reviewText,
            'is_verified_purchase' => Auth::check() ? 1 : 0
        ]);

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Thank you! Your review has been submitted successfully.']);
            return;
        }

        flash('success', 'Thank you! Your review has been submitted.');
        $this->redirect($_SERVER['HTTP_REFERER'] ?? 'shop');
    }
}
