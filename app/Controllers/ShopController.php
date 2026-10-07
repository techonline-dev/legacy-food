<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller {
    public function index(): void {
        $categorySlug = $this->request->input('category');
        $search = $this->request->input('q') ?? $this->request->input('search');
        $sort = $this->request->input('sort', 'featured');
        $minPrice = $this->request->input('min_price');
        $maxPrice = $this->request->input('max_price');

        $filters = [
            'category' => $categorySlug,
            'search' => $search,
            'sort' => $sort,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
        ];

        $products = Product::filter($filters);
        foreach ($products as &$prod) {
            $prod['variants'] = Product::getVariants($prod['id']);
        }

        $categories = Category::getActive();
        $currentCategory = $categorySlug ? Category::getBySlug($categorySlug) : null;

        // If AJAX request, return JSON or partial product grid
        if ($this->request->isAjax()) {
            ob_start();
            $data = ['products' => $products];
            extract($data);
            include __DIR__ . '/../../views/shop/partials/product_grid.php';
            $html = ob_get_clean();

            $this->json([
                'success' => true,
                'count' => count($products),
                'html' => $html
            ]);
            return;
        }

        $title = $currentCategory 
            ? (!empty($currentCategory['meta_title']) ? $currentCategory['meta_title'] : ($currentCategory['name'] . ' | Legacy Food'))
            : 'Shop Pure Heritage Ghee & Cold Pressed Oils | Legacy Food';
        $description = ($currentCategory && !empty($currentCategory['meta_description']))
            ? $currentCategory['meta_description']
            : 'Explore our complete range of hand-churned South Indian butter ghee and cold-pressed oils. 100% pure, unadulterated, NABL certified.';

        $this->view('shop.index', [
            'meta_title' => $title,
            'meta_description' => $description,
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'filters' => $filters,
        ]);
    }

    public function category(string $slug): void {
        $category = Category::getBySlug($slug);
        if (!$category) {
            $this->response->status(404);
            $this->view('errors.404', ['title' => 'Category Not Found']);
            return;
        }

        $products = Product::filter(['category' => $slug]);
        foreach ($products as &$prod) {
            $prod['variants'] = Product::getVariants($prod['id']);
        }
        $categories = Category::getActive();

        $this->view('shop.category', [
            'meta_title' => !empty($category['meta_title']) ? $category['meta_title'] : ($category['name'] . ' — Pure Heritage | Legacy Food'),
            'meta_description' => !empty($category['meta_description']) ? $category['meta_description'] : ($category['description'] ?? ''),
            'category' => $category,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function search(): void {
        $query = trim((string)$this->request->input('q', ''));
        $products = Product::filter(['search' => $query]);

        if ($this->request->isAjax()) {
            $results = array_map(function($p) {
                return [
                    'id' => $p['id'],
                    'name' => $p['name'],
                    'slug' => $p['slug'],
                    'price' => currency_format($p['sale_price'] ?: $p['base_price']),
                    'image' => $p['featured_image'],
                    'url' => url('product/' . $p['slug']),
                    'category' => $p['category_name'] ?? 'Heritage'
                ];
            }, array_slice($products, 0, 6));

            $this->json(['success' => true, 'query' => $query, 'results' => $results]);
            return;
        }

        $this->view('shop.index', [
            'meta_title' => "Search results for '{$query}' | Legacy Food",
            'products' => $products,
            'categories' => Category::getActive(),
            'currentCategory' => null,
            'filters' => ['search' => $query],
            'searchQuery' => $query
        ]);
    }
}
