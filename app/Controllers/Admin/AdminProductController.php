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

        // Process variants (multiple)
        $variantsInput = $this->request->input('variants');
        $defaultVariantIndex = (int)$this->request->input('default_variant_index', 0);
        $insertedVariants = 0;
        
        if (!empty($variantsInput) && is_array($variantsInput)) {
            foreach ($variantsInput as $idx => $v) {
                $vName = trim($v['name'] ?? '');
                if ($vName === '') continue;
                $vSku = trim($v['sku'] ?? '');
                if ($vSku === '') {
                    $vSku = $sku . '-' . strtoupper(substr(slugify($vName), 0, 8)) . '-' . ($idx + 1);
                }
                $vPrice = isset($v['price']) && $v['price'] !== '' ? (float)$v['price'] : (float)$this->request->input('base_price');
                $vSalePrice = isset($v['sale_price']) && $v['sale_price'] !== '' ? (float)$v['sale_price'] : null;
                $vStock = isset($v['stock_quantity']) && $v['stock_quantity'] !== '' ? (int)$v['stock_quantity'] : (int)$this->request->input('stock_quantity');
                $vWeight = isset($v['weight_grams']) && $v['weight_grams'] !== '' ? (int)$v['weight_grams'] : null;
                $isDef = ($idx === $defaultVariantIndex || (!empty($v['is_default']) && $insertedVariants === 0)) ? 1 : 0;

                Database::insert('product_variants', [
                    'product_id' => $productId,
                    'name' => $vName,
                    'sku' => $vSku,
                    'price' => $vPrice,
                    'sale_price' => $vSalePrice,
                    'stock_quantity' => $vStock,
                    'weight_grams' => $vWeight,
                    'is_default' => $isDef,
                    'image' => !empty($v['image']) ? $v['image'] : $image
                ]);
                $insertedVariants++;
            }
        }
        
        if ($insertedVariants === 0) {
            // Process fallback default variant
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
        }

        // Process Gallery Images (multiple uploads + URLs)
        $gallerySort = 0;
        if (isset($_FILES['gallery_files'])) {
            $uploadedGallery = $this->handleMultipleUploads($_FILES['gallery_files']);
            foreach ($uploadedGallery as $gUrl) {
                Database::insert('product_images', [
                    'product_id' => $productId,
                    'image_url' => $gUrl,
                    'alt_text' => $name,
                    'sort_order' => $gallerySort++
                ]);
            }
        }
        $galleryUrls = $this->request->input('gallery_urls');
        if (!empty($galleryUrls)) {
            $urlsList = is_array($galleryUrls) ? $galleryUrls : preg_split('/[\r\n]+/', (string)$galleryUrls);
            foreach ($urlsList as $urlItem) {
                $urlItem = trim($urlItem);
                if (filter_var($urlItem, FILTER_VALIDATE_URL)) {
                    Database::insert('product_images', [
                        'product_id' => $productId,
                        'image_url' => $urlItem,
                        'alt_text' => $name,
                        'sort_order' => $gallerySort++
                    ]);
                }
            }
        }

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
        $images = Database::fetchAll("SELECT * FROM `product_images` WHERE `product_id` = :pid ORDER BY `sort_order` ASC, `id` ASC", ['pid' => $id]);

        $this->view('admin.products.edit', [
            'meta_title' => "Edit Product: {$product['name']} | Legacy Food Admin",
            'product' => $product,
            'categories' => $categories,
            'variants' => $variants,
            'images' => $images,
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

        // Variants processing in update
        $variantsInput = $this->request->input('variants');
        $defaultVariantId = (int)$this->request->input('default_variant_id', 0);
        $deletedVariantIds = $this->request->input('deleted_variant_ids');
        if (!empty($deletedVariantIds)) {
            $delIds = is_array($deletedVariantIds) ? $deletedVariantIds : explode(',', (string)$deletedVariantIds);
            foreach ($delIds as $delId) {
                $delId = (int)$delId;
                if ($delId > 0) {
                    Database::query("DELETE FROM `product_variants` WHERE `id` = :id AND `product_id` = :pid", [
                        'id' => $delId,
                        'pid' => $id
                    ]);
                }
            }
        }

        if (!empty($variantsInput) && is_array($variantsInput)) {
            foreach ($variantsInput as $idx => $v) {
                $vName = trim($v['name'] ?? '');
                if ($vName === '') continue;
                $vId = isset($v['id']) && is_numeric($v['id']) && (int)$v['id'] > 0 ? (int)$v['id'] : null;
                $vSku = trim($v['sku'] ?? '');
                if ($vSku === '') {
                    $vSku = sanitize($this->request->input('sku')) . '-' . strtoupper(substr(slugify($vName), 0, 8)) . '-' . ($idx + 1);
                }
                $vPrice = isset($v['price']) && $v['price'] !== '' ? (float)$v['price'] : (float)$this->request->input('base_price');
                $vSalePrice = (isset($v['sale_price']) && $v['sale_price'] !== '') ? (float)$v['sale_price'] : null;
                $vStock = isset($v['stock_quantity']) && $v['stock_quantity'] !== '' ? (int)$v['stock_quantity'] : (int)$this->request->input('stock_quantity');
                $vWeight = (isset($v['weight_grams']) && $v['weight_grams'] !== '') ? (int)$v['weight_grams'] : null;
                $isDef = (($vId && $vId === $defaultVariantId) || (!empty($v['is_default']))) ? 1 : 0;

                if ($vId) {
                    Database::update('product_variants', [
                        'name' => $vName,
                        'sku' => $vSku,
                        'price' => $vPrice,
                        'sale_price' => $vSalePrice,
                        'stock_quantity' => $vStock,
                        'weight_grams' => $vWeight,
                        'is_default' => $isDef,
                    ], "`id` = :id AND `product_id` = :pid", ['id' => $vId, 'pid' => $id]);
                } else {
                    Database::insert('product_variants', [
                        'product_id' => $id,
                        'name' => $vName,
                        'sku' => $vSku,
                        'price' => $vPrice,
                        'sale_price' => $vSalePrice,
                        'stock_quantity' => $vStock,
                        'weight_grams' => $vWeight,
                        'is_default' => $isDef,
                        'image' => !empty($v['image']) ? $v['image'] : $image
                    ]);
                }
            }
        }

        // Ensure at least one variant is marked as default
        $hasDefault = Database::fetch("SELECT id FROM `product_variants` WHERE `product_id` = :pid AND `is_default` = 1 LIMIT 1", ['pid' => $id]);
        if (!$hasDefault) {
            $firstVariant = Database::fetch("SELECT id FROM `product_variants` WHERE `product_id` = :pid ORDER BY id ASC LIMIT 1", ['pid' => $id]);
            if ($firstVariant) {
                Database::update('product_variants', ['is_default' => 1], "`id` = :id", ['id' => $firstVariant['id']]);
            }
        }

        // Handle gallery images uploads in update
        $existingCount = (int)(Database::fetch("SELECT COUNT(*) as c FROM `product_images` WHERE `product_id` = :pid", ['pid' => $id])['c'] ?? 0);
        $gallerySort = $existingCount;
        if (isset($_FILES['gallery_files'])) {
            $uploadedGallery = $this->handleMultipleUploads($_FILES['gallery_files']);
            foreach ($uploadedGallery as $gUrl) {
                Database::insert('product_images', [
                    'product_id' => $id,
                    'image_url' => $gUrl,
                    'alt_text' => $name,
                    'sort_order' => $gallerySort++
                ]);
            }
        }
        $galleryUrls = $this->request->input('gallery_urls');
        if (!empty($galleryUrls)) {
            $urlsList = is_array($galleryUrls) ? $galleryUrls : preg_split('/[\r\n]+/', (string)$galleryUrls);
            foreach ($urlsList as $urlItem) {
                $urlItem = trim($urlItem);
                if (filter_var($urlItem, FILTER_VALIDATE_URL)) {
                    Database::insert('product_images', [
                        'product_id' => $id,
                        'image_url' => $urlItem,
                        'alt_text' => $name,
                        'sort_order' => $gallerySort++
                    ]);
                }
            }
        }
        $deletedImageIds = $this->request->input('deleted_image_ids');
        if (!empty($deletedImageIds)) {
            $delImgIds = is_array($deletedImageIds) ? $deletedImageIds : explode(',', (string)$deletedImageIds);
            foreach ($delImgIds as $imgId) {
                $imgId = (int)$imgId;
                if ($imgId > 0) {
                    Database::query("DELETE FROM `product_images` WHERE `id` = :id AND `product_id` = :pid", [
                        'id' => $imgId,
                        'pid' => $id
                    ]);
                }
            }
        }

        ActivityLog::log('product_updated', "Updated product {$name} (ID #{$id})");
        flash('success', "Product '{$name}' updated successfully.");
        $this->redirect('admin/products/edit/' . $id);
    }

    public function deleteImage(int $id): void {
        $img = Database::fetch("SELECT * FROM `product_images` WHERE `id` = :id", ['id' => $id]);
        if ($img) {
            Database::query("DELETE FROM `product_images` WHERE `id` = :id", ['id' => $id]);
            flash('success', 'Gallery image removed.');
            $this->redirect('admin/products/edit/' . $img['product_id']);
            return;
        }
        $this->redirect('admin/products');
    }

    public function deleteVariant(int $id): void {
        $variant = Database::fetch("SELECT * FROM `product_variants` WHERE `id` = :id", ['id' => $id]);
        if ($variant) {
            $count = Database::fetch("SELECT COUNT(*) as c FROM `product_variants` WHERE `product_id` = :pid", ['pid' => $variant['product_id']])['c'];
            if ((int)$count <= 1) {
                flash('error', 'A product must have at least one variant.');
                $this->redirect('admin/products/edit/' . $variant['product_id']);
                return;
            }
            Database::query("DELETE FROM `product_variants` WHERE `id` = :id", ['id' => $id]);
            flash('success', 'Variant removed.');
            $this->redirect('admin/products/edit/' . $variant['product_id']);
            return;
        }
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

    private function handleMultipleUploads(array $files): array {
        $uploadedUrls = [];
        if (!isset($files['name']) || !is_array($files['name'])) {
            return $uploadedUrls;
        }

        $targetDir = __DIR__ . '/../../public/assets/images/products/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK && in_array($files['type'][$i], $allowed)) {
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $filename = 'gallery_' . uniqid() . '.' . $ext;
                $dest = $targetDir . $filename;
                if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                    $uploadedUrls[] = url('assets/images/products/' . $filename);
                }
            }
        }

        return $uploadedUrls;
    }
}
