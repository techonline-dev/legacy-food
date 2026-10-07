<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Category;

class AdminCategoryController extends Controller {
    public function index(): void {
        $categories = Category::all('id ASC');
        $editId = (int)($this->request->query('edit') ?? 0);
        $editCategory = $editId > 0 ? Category::find($editId) : null;

        $this->view('admin.categories.index', [
            'meta_title' => 'Categories | Legacy Food Admin',
            'categories' => $categories,
            'editCategory' => $editCategory
        ], 'admin');
    }

    public function edit(int $id): void {
        $categories = Category::all('id ASC');
        $editCategory = Category::find($id);

        if (!$editCategory) {
            flash('error', 'Category not found.');
            $this->redirect('admin/categories');
            return;
        }

        $this->view('admin.categories.index', [
            'meta_title' => 'Edit Category | Legacy Food Admin',
            'categories' => $categories,
            'editCategory' => $editCategory
        ], 'admin');
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/categories');
            return;
        }

        $name = sanitize($this->request->input('name'));
        $rawSlug = $this->request->input('slug');
        $slug = slugify($rawSlug ?: $name);
        $baseSlug = $slug;
        $counter = 1;
        while (Database::fetchOne("SELECT id FROM product_categories WHERE slug = :s LIMIT 1", ['s' => $slug])) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $desc = sanitize($this->request->input('description'));
        $image = sanitize($this->request->input('image'));
        $metaTitle = sanitize($this->request->input('meta_title'));
        $metaDescription = sanitize($this->request->input('meta_description'));

        Database::insert('product_categories', [
            'name' => $name,
            'slug' => $slug,
            'description' => $desc,
            'image' => $image,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'is_active' => 1
        ]);

        flash('success', "Category '{$name}' created.");
        $this->redirect('admin/categories');
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/categories');
            return;
        }

        $cat = Category::find($id);
        if (!$cat) {
            flash('error', 'Category not found.');
            $this->redirect('admin/categories');
            return;
        }

        $name = sanitize($this->request->input('name'));
        $rawSlug = $this->request->input('slug');
        $slug = slugify($rawSlug ?: $name);
        $baseSlug = $slug;
        $counter = 1;
        while (Database::fetchOne("SELECT id FROM product_categories WHERE slug = :s AND id != :id LIMIT 1", ['s' => $slug, 'id' => $id])) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $desc = sanitize($this->request->input('description'));
        $image = sanitize($this->request->input('image'));
        $isActive = (int)$this->request->input('is_active', 1);
        $metaTitle = sanitize($this->request->input('meta_title'));
        $metaDescription = sanitize($this->request->input('meta_description'));

        Database::update('product_categories', [
            'name' => $name,
            'slug' => $slug,
            'description' => $desc,
            'image' => $image,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'is_active' => $isActive
        ], '`id` = :id', ['id' => $id]);

        flash('success', "Category '{$name}' updated.");
        $this->redirect('admin/categories');
    }

    public function delete(int $id): void {
        Database::query("DELETE FROM `product_categories` WHERE `id` = :id", ['id' => $id]);
        flash('success', 'Category deleted.');
        $this->redirect('admin/categories');
    }
}
