<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Coupon;
use App\Models\Category;

class AdminCouponController extends Controller {
    public function index(): void {
        $coupons = Coupon::all('id DESC');
        $categories = Category::all('name ASC');
        $editId = (int)($this->request->query('edit') ?? 0);
        $editCoupon = $editId > 0 ? Coupon::find($editId) : null;

        $this->view('admin.coupons.index', [
            'meta_title' => 'Coupon Management | Legacy Food Admin',
            'coupons' => $coupons,
            'categories' => $categories,
            'editCoupon' => $editCoupon
        ], 'admin');
    }

    public function edit(int $id): void {
        $coupons = Coupon::all('id DESC');
        $categories = Category::all('name ASC');
        $editCoupon = Coupon::find($id);

        if (!$editCoupon) {
            flash('error', 'Coupon not found.');
            $this->redirect('admin/coupons');
            return;
        }

        $this->view('admin.coupons.index', [
            'meta_title' => 'Edit Coupon | Legacy Food Admin',
            'coupons' => $coupons,
            'categories' => $categories,
            'editCoupon' => $editCoupon
        ], 'admin');
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/coupons');
            return;
        }

        $code = strtoupper(trim(sanitize($this->request->input('code'))));
        if (empty($code)) {
            flash('error', 'Coupon code is required.');
            $this->redirect('admin/coupons');
            return;
        }

        // Check if code already exists
        $existing = Database::fetch("SELECT id FROM `coupons` WHERE UPPER(`code`) = :code LIMIT 1", ['code' => $code]);
        if ($existing) {
            flash('error', "Coupon code '{$code}' already exists. Please choose a unique code.");
            $this->redirect('admin/coupons');
            return;
        }

        $type = sanitize($this->request->input('type', 'percentage'));
        $value = (float)$this->request->input('value', 0);
        $desc = sanitize($this->request->input('description', ''));
        $min = (float)$this->request->input('min_order_amount', 0);
        $max = $this->request->input('max_discount_amount') ? (float)$this->request->input('max_discount_amount') : null;
        $limitTotal = $this->request->input('usage_limit_total') ? (int)$this->request->input('usage_limit_total') : null;
        $limitPerUser = $this->request->input('usage_limit_per_user') ? max(1, (int)$this->request->input('usage_limit_per_user')) : 1;
        $minQty = $this->request->input('min_quantity') ? max(0, (int)$this->request->input('min_quantity')) : 0;
        $firstOrderOnly = (int)$this->request->input('first_order_only', 0) ? 1 : 0;
        $categoryId = $this->request->input('category_id') ? (int)$this->request->input('category_id') : null;
        $isActive = (int)$this->request->input('is_active', 1);

        $startDateInput = $this->request->input('start_date');
        $startDate = !empty($startDateInput) ? date('Y-m-d H:i:s', strtotime($startDateInput)) : null;

        $endDateInput = $this->request->input('end_date');
        $endDate = !empty($endDateInput) ? date('Y-m-d H:i:s', strtotime($endDateInput)) : null;

        Database::insert('coupons', [
            'code' => $code,
            'type' => $type,
            'value' => $value,
            'description' => $desc ?: null,
            'min_order_amount' => $min,
            'max_discount_amount' => $max,
            'usage_limit_total' => $limitTotal,
            'usage_limit_per_user' => $limitPerUser,
            'min_quantity' => $minQty,
            'first_order_only' => $firstOrderOnly,
            'category_id' => $categoryId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => $isActive,
        ]);

        flash('success', "Coupon '{$code}' created successfully.");
        $this->redirect('admin/coupons');
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/coupons');
            return;
        }

        $coupon = Coupon::find($id);
        if (!$coupon) {
            flash('error', 'Coupon not found.');
            $this->redirect('admin/coupons');
            return;
        }

        $code = strtoupper(trim(sanitize($this->request->input('code'))));
        if (empty($code)) {
            flash('error', 'Coupon code is required.');
            $this->redirect('admin/coupons?edit=' . $id);
            return;
        }

        // Check if code exists on another record
        $existing = Database::fetch("SELECT id FROM `coupons` WHERE UPPER(`code`) = :code AND `id` != :id LIMIT 1", [
            'code' => $code,
            'id' => $id
        ]);
        if ($existing) {
            flash('error', "Coupon code '{$code}' already exists on another coupon.");
            $this->redirect('admin/coupons?edit=' . $id);
            return;
        }

        $type = sanitize($this->request->input('type', 'percentage'));
        $value = (float)$this->request->input('value', 0);
        $desc = sanitize($this->request->input('description', ''));
        $min = (float)$this->request->input('min_order_amount', 0);
        $max = $this->request->input('max_discount_amount') ? (float)$this->request->input('max_discount_amount') : null;
        $limitTotal = $this->request->input('usage_limit_total') ? (int)$this->request->input('usage_limit_total') : null;
        $limitPerUser = $this->request->input('usage_limit_per_user') ? max(1, (int)$this->request->input('usage_limit_per_user')) : 1;
        $minQty = $this->request->input('min_quantity') ? max(0, (int)$this->request->input('min_quantity')) : 0;
        $firstOrderOnly = (int)$this->request->input('first_order_only', 0) ? 1 : 0;
        $categoryId = $this->request->input('category_id') ? (int)$this->request->input('category_id') : null;
        $isActive = (int)$this->request->input('is_active', 1);

        $startDateInput = $this->request->input('start_date');
        $startDate = !empty($startDateInput) ? date('Y-m-d H:i:s', strtotime($startDateInput)) : null;

        $endDateInput = $this->request->input('end_date');
        $endDate = !empty($endDateInput) ? date('Y-m-d H:i:s', strtotime($endDateInput)) : null;

        Database::update('coupons', [
            'code' => $code,
            'type' => $type,
            'value' => $value,
            'description' => $desc ?: null,
            'min_order_amount' => $min,
            'max_discount_amount' => $max,
            'usage_limit_total' => $limitTotal,
            'usage_limit_per_user' => $limitPerUser,
            'min_quantity' => $minQty,
            'first_order_only' => $firstOrderOnly,
            'category_id' => $categoryId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => $isActive,
        ], '`id` = :id', ['id' => $id]);

        flash('success', "Coupon '{$code}' updated successfully.");
        $this->redirect('admin/coupons');
    }

    public function delete(int $id): void {
        Database::query("DELETE FROM `coupons` WHERE `id` = :id", ['id' => $id]);
        flash('success', 'Coupon deleted.');
        $this->redirect('admin/coupons');
    }
}
