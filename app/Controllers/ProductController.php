<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Review;

class ProductController extends Controller {
    public function show(string $slug): void {
        $product = Product::getBySlug($slug);
        if (!$product) {
            $this->response->status(404);
            $this->view('errors.404', ['title' => 'Product Not Found | Legacy Food']);
            return;
        }

        // Decode specifications JSON if stored as string
        if (is_string($product['specifications'])) {
            $product['specifications'] = json_decode($product['specifications'], true) ?: [];
        }

        // Related products from same category
        $related = Product::filter(['category' => $product['category_slug'] ?? null]);
        $related = array_filter($related, fn($p) => $p['id'] !== $product['id']);
        $related = array_slice($related, 0, 3);
        foreach ($related as &$rel) {
            $rel['variants'] = Product::getVariants($rel['id']);
        }

        $metaTitle = $product['meta_title'] ?: ($product['name'] . ' — Hand Churned Purity | Legacy Food');
        $metaDescription = $product['meta_description'] ?: ($product['short_description']);

        $this->view('product.detail', [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'og_image' => $product['featured_image'],
            'product' => $product,
            'relatedProducts' => $related,
        ]);
    }
}
