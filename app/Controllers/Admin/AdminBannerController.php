<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Banner;

class AdminBannerController extends Controller {
    public function index(): void {
        $banners = Banner::all('sort_order ASC');
        $editId = (int)($this->request->query('edit') ?? 0);
        $editBanner = $editId > 0 ? Banner::find($editId) : null;

        $this->view('admin.banners.index', [
            'meta_title' => 'Banners & Hero Sliders | Legacy Food Admin',
            'banners' => $banners,
            'editBanner' => $editBanner
        ], 'admin');
    }

    public function edit(int $id): void {
        $banners = Banner::all('sort_order ASC');
        $editBanner = Banner::find($id);

        if (!$editBanner) {
            flash('error', 'Banner not found.');
            $this->redirect('admin/banners');
            return;
        }

        $this->view('admin.banners.index', [
            'meta_title' => 'Edit Banner | Legacy Food Admin',
            'banners' => $banners,
            'editBanner' => $editBanner
        ], 'admin');
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/banners');
            return;
        }

        $title = sanitize($this->request->input('title'));
        $subtitle = sanitize($this->request->input('subtitle'));
        $image = sanitize($this->request->input('image'));
        $ctaText = sanitize($this->request->input('cta_text'));
        $ctaUrl = sanitize($this->request->input('cta_url'));
        $type = sanitize($this->request->input('type', 'hero'));
        $order = (int)$this->request->input('sort_order', 0);
        $status = sanitize($this->request->input('status', 'active'));

        Database::insert('banners', [
            'title' => $title,
            'subtitle' => $subtitle,
            'image' => $image,
            'cta_text' => $ctaText,
            'cta_url' => $ctaUrl,
            'type' => $type,
            'sort_order' => $order,
            'status' => $status
        ]);

        flash('success', "Banner slide published successfully.");
        $this->redirect('admin/banners');
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/banners');
            return;
        }

        $banner = Banner::find($id);
        if (!$banner) {
            flash('error', 'Banner not found.');
            $this->redirect('admin/banners');
            return;
        }

        $title = sanitize($this->request->input('title'));
        $subtitle = sanitize($this->request->input('subtitle'));
        $image = sanitize($this->request->input('image'));
        $ctaText = sanitize($this->request->input('cta_text'));
        $ctaUrl = sanitize($this->request->input('cta_url'));
        $type = sanitize($this->request->input('type', 'hero'));
        $order = (int)$this->request->input('sort_order', 0);
        $status = sanitize($this->request->input('status', 'active'));

        Database::update('banners', [
            'title' => $title,
            'subtitle' => $subtitle,
            'image' => $image,
            'cta_text' => $ctaText,
            'cta_url' => $ctaUrl,
            'type' => $type,
            'sort_order' => $order,
            'status' => $status
        ], '`id` = :id', ['id' => $id]);

        flash('success', "Banner #{$id} updated successfully.");
        $this->redirect('admin/banners');
    }

    public function delete(int $id): void {
        Database::query("DELETE FROM `banners` WHERE `id` = :id", ['id' => $id]);
        flash('success', 'Banner deleted successfully.');
        $this->redirect('admin/banners');
    }
}
