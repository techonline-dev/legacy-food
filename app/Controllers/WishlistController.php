<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Wishlist;
use App\Models\Cart;

class WishlistController extends Controller {
    public function index(): void {
        if (!Auth::check()) {
            flash('info', 'Please log in to view your saved wishlist items.');
            $this->redirect('login?redirect=wishlist');
            return;
        }

        $userId = Auth::id();
        $items = Wishlist::getUserItems($userId);

        $this->view('wishlist.index', [
            'meta_title' => 'My Wishlist | Legacy Food',
            'items' => $items,
        ]);
    }

    public function toggle(): void {
        $productId = (int)$this->request->input('product_id');
        if (!$productId) {
            $this->json(['success' => false, 'error' => 'Invalid product.'], 400);
            return;
        }

        $res = Wishlist::toggle($productId);
        $this->json($res);
    }

    public function moveToCart(): void {
        $productId = (int)$this->request->input('product_id');
        if (!$productId) {
            $this->redirect('wishlist');
            return;
        }

        Cart::add($productId, null, 1);
        if (Auth::check()) {
            Wishlist::toggle($productId); // removes from wishlist
        }

        flash('success', 'Product moved to cart!');
        $this->redirect('cart');
    }
}
