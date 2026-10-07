<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;
use App\Models\Coupon;

class CartController extends Controller {
    public function index(): void {
        $cartSummary = Cart::getSummary();

        $this->view('cart.index', [
            'meta_title' => 'Your Shopping Cart | Legacy Food',
            'meta_description' => 'Review your pure heritage ghee and cold pressed oil items before proceeding to secure checkout.',
            'cart' => $cartSummary,
        ]);
    }

    public function summary(): void {
        $summary = Cart::getSummary();
        $this->json(['success' => true, 'cart' => $summary]);
    }

    public function add(): void {
        $productId = (int)$this->request->input('product_id');
        $variantId = $this->request->input('variant_id') ? (int)$this->request->input('variant_id') : null;
        $quantity = max(1, (int)$this->request->input('quantity', 1));

        if (!$productId) {
            $this->json(['success' => false, 'error' => 'Invalid product selection.'], 400);
            return;
        }

        $res = Cart::add($productId, $variantId, $quantity);

        if ($this->request->isAjax()) {
            $this->json($res, $res['success'] ? 200 : 400);
            return;
        }

        if ($res['success']) {
            flash('success', $res['message']);
            $this->redirect('cart');
        } else {
            flash('error', $res['error']);
            $this->redirect($_SERVER['HTTP_REFERER'] ?? 'cart');
        }
    }

    public function update(): void {
        $itemKey = (string)$this->request->input('item_key');
        $quantity = (int)$this->request->input('quantity', 1);

        $res = Cart::update($itemKey, $quantity);
        $this->json($res, $res['success'] ? 200 : 400);
    }

    public function remove(): void {
        $itemKey = (string)$this->request->input('item_key');
        $res = Cart::remove($itemKey);

        if ($this->request->isAjax()) {
            $this->json($res);
            return;
        }

        flash('success', 'Item removed from your cart.');
        $this->redirect('cart');
    }

    public function applyCoupon(): void {
        $code = trim((string)($this->request->input('coupon_code') ?: $this->request->input('code', '')));
        if (empty($code)) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => 'Please enter a valid coupon code.'], 400);
            } else {
                flash('error', 'Please enter a coupon code.');
                $this->redirect($_SERVER['HTTP_REFERER'] ?? 'cart');
            }
            return;
        }

        $summary = Cart::getSummary();
        $subtotal = (float)$summary['subtotal'];

        $user = \App\Core\Auth::user();
        $userId = $user['id'] ?? null;
        $customerEmail = $user['email'] ?? sanitize($this->request->input('email', ''));

        $check = Coupon::validateCoupon($code, $subtotal, $userId, $summary['items'], $customerEmail ?: null);
        if (!$check['valid']) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => $check['error']], 400);
            } else {
                flash('error', $check['error']);
                $this->redirect($_SERVER['HTTP_REFERER'] ?? 'cart');
            }
            return;
        }

        $_SESSION['coupon'] = array_merge($check['coupon'], [
            'discount' => $check['discount'],
            'discount_formatted' => $check['discount_formatted']
        ]);

        $updatedSummary = Cart::getSummary();

        if ($this->request->isAjax()) {
            $this->json([
                'success' => true,
                'message' => $check['message'],
                'discount' => $check['discount'],
                'discount_formatted' => $check['discount_formatted'],
                'cart' => $updatedSummary
            ]);
        } else {
            flash('success', $check['message']);
            $this->redirect($_SERVER['HTTP_REFERER'] ?? 'cart');
        }
    }

    public function removeCoupon(): void {
        unset($_SESSION['coupon']);
        $updatedSummary = Cart::getSummary();

        if ($this->request->isAjax()) {
            $this->json([
                'success' => true,
                'message' => 'Coupon removed.',
                'cart' => $updatedSummary
            ]);
        } else {
            flash('info', 'Coupon removed.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? 'cart');
        }
    }
}
