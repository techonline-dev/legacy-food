<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
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
            $this->redirect(safe_redirect_target((string)($_SERVER['HTTP_REFERER'] ?? 'shop'), 'shop'));
            return;
        }

        if (!\App\Core\RateLimiter::attempt('review:' . client_ip(), 5, 3600)) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => 'Too many review submissions. Please try again later.'], 429);
                return;
            }
            flash('error', 'Too many review submissions. Please try again later.');
            $this->redirect('shop');
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
            $this->redirect(safe_redirect_target((string)($_SERVER['HTTP_REFERER'] ?? 'shop'), 'shop'));
            return;
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => 'Please enter a valid email address.'], 422);
                return;
            }
            flash('error', 'Please enter a valid email address.');
            $this->redirect(safe_redirect_target((string)($_SERVER['HTTP_REFERER'] ?? 'shop'), 'shop'));
            return;
        }

        $verifiedPurchase = 0;
        if (Auth::check()) {
            $verifiedPurchase = Database::fetch(
                "SELECT 1 FROM `orders` o
                 INNER JOIN `order_items` oi ON oi.order_id = o.id
                 WHERE o.user_id = :uid AND oi.product_id = :pid
                   AND o.status NOT IN ('cancelled', 'returned')
                   AND (o.payment_status = 'paid' OR o.status = 'delivered')
                 LIMIT 1",
                ['uid' => Auth::id(), 'pid' => $productId]
            ) ? 1 : 0;
        }

        Review::addReview([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'customer_name' => $name,
            'customer_email' => $email,
            'rating' => $rating,
            'title' => $title,
            'review_text' => $reviewText,
            'is_verified_purchase' => $verifiedPurchase
        ]);

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Thank you! Your review has been submitted and is awaiting moderation.']);
            return;
        }

        flash('success', 'Thank you! Your review has been submitted and is awaiting moderation.');
        $this->redirect(safe_redirect_target((string)($_SERVER['HTTP_REFERER'] ?? 'shop'), 'shop'));
    }
}
