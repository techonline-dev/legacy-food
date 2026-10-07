<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Product;
use App\Models\Category;
use App\Models\ActivityLog;

class AdminProductController extends Controller {
    public function index(): void {
        $products = Database::fetchAll("
            SELECT p.*, c.name as category_name,
                   (SELECT COUNT(*) FROM product_variants pv WHERE pv.product_id = p.id) as variants_count
            FROM `products` p
            LEFT JOIN `product_categories` c ON p.category_id = c.id
            ORDER BY p.id DESC
        ");

        $this->view('admin.products.index', [
            'meta_title' => 'Product Management | Legacy Food Admin',
            'products' => $products,
        ], 'admin');
    }

    public function create(): void {
        $categories = Category::all('name ASC');

        $this->view('admin.products.create', [
            'meta_title' => 'Add New Heritage Product | Legacy Food Admin',
            'categories' => $categories,
        ], 'admin');
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/products/create');
            return;
        }

        $errors = $this->validate([
            'name' => 'required',
            'category_id' => 'required|numeric',
            'base_price' => 'required|numeric',
            'stock_quantity' => 'required|numeric',
        ]);

        if (!empty($errors)) {
            flash('error', reset($errors)[0]);
            $this->redirect('admin/products/create');
            return;
        }

        $name = sanitize($this->request->input('name'));
        $rawSlug = $this->request->input('slug');
        $slug = slugify($rawSlug ?: $name);
        $baseSlug = $slug;
        $counter = 1;
        while (Database::fetchOne("SELECT id FROM products WHERE slug = :s LIMIT 1", ['s' => $slug])) {
            $slug = $baseSlug . '-' . $counter++;
        }
        $sku = strtoupper(sanitize($this->request->input('sku') ?: ('LF-' . strtoupper(substr(md5(uniqid()), 0, 8)))));

        // Handle uploaded file or remote image url
        $image = sanitize($this->request->input('featured_image', ''));
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleUpload($_FILES['image_file']);
            if ($uploaded) $image = $uploaded;
        }

        $gstRateInput = $this->request->input('gst_rate');
        $gstRate = ($gstRateInput !== null && $gstRateInput !== '') ? (float)$gstRateInput : null;
        $hsnCode = trim((string)$this->request->input('hsn_code', ''));
        $hsnCode = $hsnCode !== '' ? strtoupper(sanitize($hsnCode)) : null;

        $productId = Database::insert('products', [
            'category_id' => (int)$this->request->input('category_id'),
            'name' => $name,
            'slug' => $slug,
            'sku' => $sku,
            'hsn_code' => $hsnCode,
            'short_description' => sanitize($this->request->input('short_description')),
            'description' => $this->request->input('description'),
            'benefits' => $this->request->input('benefits'),
            'ingredients' => $this->request->input('ingredients'),
            'featured_image' => $image ?: 'https://cdn.sanity.io/images/dwps51kj/production/adbe8001d264661c9bbd49ef61f0f8d00894dba9-1080x1080.png?w=640',
            'base_price' => (float)$this->request->input('base_price'),
            'sale_price' => $this->request->input('sale_price') ? (float)$this->request->input('sale_price') : null,
            'gst_rate' => $gstRate,
            'stock_quantity' => (int)$this->request->input('stock_quantity'),
            'low_stock_threshold' => (int)$this->request->input('low_stock_threshold', 5),
            'is_featured' => $this->request->input('is_featured') ? 1 : 0,
            'is_bestseller' => $this->request->input('is_bestseller') ? 1 : 0,
            'status' => $this->request->input('status', 'published'),
            'meta_title' => sanitize($this->request->input('meta_title')),
            'meta_description' => sanitize($this->request->input('meta_description')),
        ]);

        // Process default variant
        Database::insert('product_variants', [
            'product_id' => $productId,
            'name' => 'Standard',
            'sku' => $sku . '-STD',
            'price' => (float)$this->request->input('base_price'),
            'sale_price' => $this->request->input('sale_price') ? (float)$this->request->input('sale_price') : null,
            'stock_quantity' => (int)$this->request->input('stock_quantity'),
            'is_default' => 1,
            'image' => $image
        ]);

        ActivityLog::log('product_created', "Created product {$name} (ID #{$productId})");
        flash('success', "Product '{$name}' created successfully.");
        $this->redirect('admin/products');
    }

    public function edit(int $id): void {
        $product = Product::find($id);
        if (!$product) {
            flash('error', 'Product not found.');
            $this->redirect('admin/products');
            return;
        }

        $categories = Category::all('name ASC');
        $variants = Product::getVariants($id);

        $this->view('admin.products.edit', [
            'meta_title' => "Edit Product: {$product['name']} | Legacy Food Admin",
            'product' => $product,
            'categories' => $categories,
            'variants' => $variants,
        ], 'admin');
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/products/edit/' . $id);
            return;
        }

        $product = Product::find($id);
        if (!$product) {
            $this->redirect('admin/products');
            return;
        }

        $name = sanitize($this->request->input('name'));
        $rawSlug = $this->request->input('slug');
        $slug = slugify($rawSlug ?: $name);
        $baseSlug = $slug;
        $counter = 1;
        while (Database::fetchOne("SELECT id FROM products WHERE slug = :s AND id != :id LIMIT 1", ['s' => $slug, 'id' => $id])) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $image = sanitize($this->request->input('featured_image', $product['featured_image']));
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleUpload($_FILES['image_file']);
            if ($uploaded) $image = $uploaded;
        }

        $gstRateInput = $this->request->input('gst_rate');
        $gstRate = ($gstRateInput !== null && $gstRateInput !== '') ? (float)$gstRateInput : null;
        $hsnCode = trim((string)$this->request->input('hsn_code', ''));
        $hsnCode = $hsnCode !== '' ? strtoupper(sanitize($hsnCode)) : null;

        Database::update('products', [
            'category_id' => (int)$this->request->input('category_id'),
            'name' => $name,
            'slug' => $slug,
            'sku' => sanitize($this->request->input('sku')),
            'hsn_code' => $hsnCode,
            'short_description' => sanitize($this->request->input('short_description')),
            'description' => $this->request->input('description'),
            'benefits' => $this->request->input('benefits'),
            'ingredients' => $this->request->input('ingredients'),
            'featured_image' => $image,
            'base_price' => (float)$this->request->input('base_price'),
            'sale_price' => $this->request->input('sale_price') ? (float)$this->request->input('sale_price') : null,
            'gst_rate' => $gstRate,
            'stock_quantity' => (int)$this->request->input('stock_quantity'),
            'low_stock_threshold' => (int)$this->request->input('low_stock_threshold', 5),
            'is_featured' => $this->request->input('is_featured') ? 1 : 0,
            'is_bestseller' => $this->request->input('is_bestseller') ? 1 : 0,
            'status' => $this->request->input('status', 'published'),
            'meta_title' => sanitize($this->request->input('meta_title')),
            'meta_description' => sanitize($this->request->input('meta_description')),
        ], "`id` = :id", ['id' => $id]);

        ActivityLog::log('product_updated', "Updated product {$name} (ID #{$id})");
        flash('success', "Product '{$name}' updated successfully.");
        $this->redirect('admin/products');
    }

    public function delete(int $id): void {
        $product = Product::find($id);
        if ($product) {
            Database::query("DELETE FROM `products` WHERE `id` = :id", ['id' => $id]);
            ActivityLog::log('product_deleted', "Deleted product {$product['name']} (ID #{$id})");
            flash('success', "Product deleted.");
        }
        $this->redirect('admin/products');
    }

    private function handleUpload(array $file): ?string {
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
        if (!in_array($file['type'], $allowed)) return null;

        $targetDir = __DIR__ . '/../../public/assets/images/products/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'prod_' . uniqid() . '.' . $ext;
        $dest = $targetDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return url('assets/images/products/' . $filename);
        }
        return null;
    }
}
