<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Page;

class AdminPageController extends Controller {
    public function index(): void {
        $pages = Page::all('id ASC');
        $this->view('admin.pages.index', [
            'meta_title' => 'Content Management (CMS) | Legacy Food Admin',
            'pages' => $pages
        ], 'admin');
    }

    public function create(): void {
        $this->view('admin.pages.create', [
            'meta_title' => 'Create New Page | Legacy Food Admin'
        ], 'admin');
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/pages/create');
            return;
        }

        $title = sanitize($this->request->input('title'));
        $slugInput = trim((string)$this->request->input('slug'));
        $slug = slugify($slugInput ?: $title);
        $content = $this->request->input('content');
        $metaTitle = sanitize($this->request->input('meta_title'));
        $metaDesc = sanitize($this->request->input('meta_description'));
        $isActive = (int)$this->request->input('is_active', 1);

        if (empty($title)) {
            flash('error', 'Page title is required.');
            $this->redirect('admin/pages/create');
            return;
        }

        // Validate slug uniqueness
        $existing = Database::fetch("SELECT id FROM `pages` WHERE `slug` = :slug LIMIT 1", ['slug' => $slug]);
        if ($existing) {
            flash('error', "URL slug '{$slug}' is already taken by another page. Please choose a unique slug.");
            $this->redirect('admin/pages/create');
            return;
        }

        $pageId = Database::insert('pages', [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'meta_title' => $metaTitle ?: null,
            'meta_description' => $metaDesc ?: null,
            'is_active' => $isActive,
        ]);

        flash('success', "Page '{$title}' with slug '/{$slug}' created successfully.");
        $this->redirect('admin/pages');
    }

    public function edit(int $id): void {
        $page = Page::find($id);
        if (!$page) {
            flash('error', 'Page not found.');
            $this->redirect('admin/pages');
            return;
        }

        $this->view('admin.pages.edit', [
            'meta_title' => "Edit Page: {$page['title']} | Legacy Food Admin",
            'page' => $page
        ], 'admin');
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/pages/edit/' . $id);
            return;
        }

        $page = Page::find($id);
        if (!$page) {
            flash('error', 'Page not found.');
            $this->redirect('admin/pages');
            return;
        }

        $title = sanitize($this->request->input('title'));
        $slugInput = trim((string)$this->request->input('slug'));
        $slug = slugify($slugInput ?: $title);
        $content = $this->request->input('content');
        $metaTitle = sanitize($this->request->input('meta_title'));
        $metaDesc = sanitize($this->request->input('meta_description'));
        $isActive = (int)$this->request->input('is_active', 1);

        if (empty($title)) {
            flash('error', 'Page title is required.');
            $this->redirect('admin/pages/edit/' . $id);
            return;
        }

        // Validate slug uniqueness excluding current page
        $existing = Database::fetch("SELECT id FROM `pages` WHERE `slug` = :slug AND `id` != :id LIMIT 1", [
            'slug' => $slug,
            'id' => $id
        ]);
        if ($existing) {
            flash('error', "URL slug '{$slug}' is already taken by another page. Please choose a unique slug.");
            $this->redirect('admin/pages/edit/' . $id);
            return;
        }

        Database::update('pages', [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'meta_title' => $metaTitle ?: null,
            'meta_description' => $metaDesc ?: null,
            'is_active' => $isActive,
        ], "`id` = :id", ['id' => $id]);

        flash('success', "Page '{$title}' updated successfully. URL slug set to '/{$slug}'.");
        $this->redirect('admin/pages');
    }

    public function delete(int $id): void {
        $page = Page::find($id);
        if (!$page) {
            flash('error', 'Page not found.');
            $this->redirect('admin/pages');
            return;
        }

        Database::query("DELETE FROM `pages` WHERE `id` = :id", ['id' => $id]);
        flash('success', "Page '{$page['title']}' deleted.");
        $this->redirect('admin/pages');
    }
}
