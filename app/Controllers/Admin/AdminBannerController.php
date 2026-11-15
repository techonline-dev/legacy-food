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
        $mediaType = sanitize($this->request->input('media_type', 'image'));
        $image = sanitize($this->request->input('image', ''));
        $videoUrl = sanitize($this->request->input('video_url', ''));
        $ctaText = sanitize($this->request->input('cta_text'));
        $ctaUrl = sanitize($this->request->input('cta_url'));
        $type = sanitize($this->request->input('type', 'hero'));
        $order = (int)$this->request->input('sort_order', 0);
        $status = sanitize($this->request->input('status', 'active'));

        // Handle manual image file upload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $upImg = $this->uploadMediaFile($_FILES['image_file'], 'banners', 'banner_img');
            if ($upImg) {
                $image = $upImg['url'];
            }
        }

        // Handle manual video file upload
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $upVid = $this->uploadMediaFile($_FILES['video_file'], 'banners', 'banner_vid');
            if ($upVid) {
                $videoUrl = $upVid['url'];
                $mediaType = 'video';
            }
        }

        if (!empty($videoUrl) && $mediaType !== 'image') {
            $mediaType = 'video';
        } elseif (empty($videoUrl)) {
            $mediaType = 'image';
        }

        if (empty($image) && empty($videoUrl)) {
            flash('error', 'Please provide an image or video for the banner.');
            $this->redirect('admin/banners');
            return;
        }

        Database::insert('banners', [
            'title' => $title,
            'subtitle' => $subtitle,
            'image' => $image ?: null,
            'video_url' => $videoUrl ?: null,
            'media_type' => $mediaType,
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
        $mediaType = sanitize($this->request->input('media_type', $banner['media_type'] ?? 'image'));
        $image = sanitize($this->request->input('image', $banner['image'] ?? ''));
        $videoUrl = sanitize($this->request->input('video_url', $banner['video_url'] ?? ''));
        $ctaText = sanitize($this->request->input('cta_text'));
        $ctaUrl = sanitize($this->request->input('cta_url'));
        $type = sanitize($this->request->input('type', 'hero'));
        $order = (int)$this->request->input('sort_order', 0);
        $status = sanitize($this->request->input('status', 'active'));

        // Handle manual image file upload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $upImg = $this->uploadMediaFile($_FILES['image_file'], 'banners', 'banner_img');
            if ($upImg) {
                $image = $upImg['url'];
            }
        }

        // Handle manual video file upload
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $upVid = $this->uploadMediaFile($_FILES['video_file'], 'banners', 'banner_vid');
            if ($upVid) {
                $videoUrl = $upVid['url'];
                $mediaType = 'video';
            }
        }

        if (!empty($videoUrl) && $this->request->input('media_type') === 'video') {
            $mediaType = 'video';
        } elseif (empty($videoUrl)) {
            $mediaType = 'image';
        }

        Database::update('banners', [
            'title' => $title,
            'subtitle' => $subtitle,
            'image' => $image ?: null,
            'video_url' => $videoUrl ?: null,
            'media_type' => $mediaType,
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
