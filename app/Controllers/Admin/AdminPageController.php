<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Page;
use App\Models\Setting;

class AdminPageController extends Controller {
    public function index(): void {
        $staticPages = [
            [
                'type' => 'static',
                'key' => 'home',
                'title' => 'Home Page',
                'url' => url('/'),
                'path' => '/',
                'meta_title' => Setting::get('seo_page_home_title', 'LEGACY · Crafted in South India | Premium A2 Ghee & Pure Oils'),
                'meta_description' => Setting::get('seo_page_home_desc', 'Pure heritage ghee and wood-pressed oils hand-churned in South India from fresh farm butter. NABL accredited lab tested.'),
                'meta_keywords' => Setting::get('seo_page_home_keywords', 'ghee, A2 ghee, bilona ghee, pure cold pressed oil'),
                'status' => 'active',
            ],
            [
                'type' => 'static',
                'key' => 'shop',
                'title' => 'Shop Catalog',
                'url' => url('shop'),
                'path' => '/shop',
                'meta_title' => Setting::get('seo_page_shop_title', 'Shop Pure Heritage Ghee & Cold Pressed Oils | Legacy Food'),
                'meta_description' => Setting::get('seo_page_shop_desc', 'Explore our complete range of hand-churned South Indian butter ghee and cold-pressed oils.'),
                'meta_keywords' => Setting::get('seo_page_shop_keywords', 'shop ghee, buy oil online, pure ghee bangalore'),
                'status' => 'active',
            ],
            [
                'type' => 'static',
                'key' => 'about',
                'title' => 'About Us',
                'url' => url('about'),
                'path' => '/about',
                'meta_title' => Setting::get('seo_page_about_title', 'About Us | Legacy Food — Crafted in South India'),
                'meta_description' => Setting::get('seo_page_about_desc', 'The story of Legacy Food. Pure farm butter, small batch traditional churning, NABL certified purity.'),
                'meta_keywords' => Setting::get('seo_page_about_keywords', 'about legacy food, traditional bilona method'),
                'status' => 'active',
            ],
            [
                'type' => 'static',
                'key' => 'contact',
                'title' => 'Contact Us',
                'url' => url('contact'),
                'path' => '/contact',
                'meta_title' => Setting::get('seo_page_contact_title', 'Contact Us | Legacy Food Heritage Customer Care'),
                'meta_description' => Setting::get('seo_page_contact_desc', 'Get in touch with Legacy Food. Customer support for orders, delivery status, and bulk ghee inquiries.'),
                'meta_keywords' => Setting::get('seo_page_contact_keywords', 'contact legacy food, support phone whatsapp'),
                'status' => 'active',
            ],
            [
                'type' => 'static',
                'key' => 'faq',
                'title' => 'FAQs Help Center',
                'url' => url('faq'),
                'path' => '/faq',
                'meta_title' => Setting::get('seo_page_faq_title', 'Frequently Asked Questions (FAQ) | Legacy Food'),
                'meta_description' => Setting::get('seo_page_faq_desc', 'Find answers to common questions about Legacy ghee, GC lab testing, wood-pressed oils, storage tips.'),
                'meta_keywords' => Setting::get('seo_page_faq_keywords', 'faq ghee, pure oil shelf life questions'),
                'status' => 'active',
            ],
            [
                'type' => 'static',
                'key' => 'cart',
                'title' => 'Shopping Cart',
                'url' => url('cart'),
                'path' => '/cart',
                'meta_title' => Setting::get('seo_page_cart_title', 'Your Shopping Cart | Legacy Food'),
                'meta_description' => Setting::get('seo_page_cart_desc', 'View items in your Legacy Food shopping cart. Free nationwide shipping on eligible orders.'),
                'meta_keywords' => Setting::get('seo_page_cart_keywords', 'cart, checkout pure ghee'),
                'status' => 'active',
            ],
            [
                'type' => 'static',
                'key' => 'checkout',
                'title' => 'Secure Checkout',
                'url' => url('checkout'),
                'path' => '/checkout',
                'meta_title' => Setting::get('seo_page_checkout_title', 'Secure Checkout | Legacy Food'),
                'meta_description' => Setting::get('seo_page_checkout_desc', 'Fast, 100% secure checkout with UPI, Card, Net Banking, and Cash on Delivery.'),
                'meta_keywords' => Setting::get('seo_page_checkout_keywords', 'checkout, order payment'),
                'status' => 'active',
            ],
        ];

        $cmsPages = Database::fetchAll("SELECT * FROM `pages` ORDER BY id ASC");
        $productPages = Database::fetchAll("SELECT id, name, slug, meta_title, meta_description, status, featured_image FROM `products` ORDER BY id DESC");
        $categoryPages = Database::fetchAll("SELECT id, name, slug, meta_title, meta_description, is_active FROM `product_categories` ORDER BY id ASC");

        $this->view('admin.pages.index', [
            'meta_title' => 'Pages & SEO Directory | Legacy Food Admin',
            'staticPages' => $staticPages,
            'cmsPages' => $cmsPages,
            'productPages' => $productPages,
            'categoryPages' => $categoryPages,
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

    public function updateSeo(): void {
        if (!$this->validateCsrf()) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => 'Security token expired.']);
                return;
            }
            flash('error', 'Token expired.');
            $this->redirect('admin/pages');
            return;
        }

        $pageType = $this->request->input('page_type');
        $pageId = $this->request->input('page_id');
        $metaTitle = sanitize($this->request->input('meta_title', ''));
        $metaDesc = sanitize($this->request->input('meta_description', ''));
        $metaKeywords = sanitize($this->request->input('meta_keywords', ''));

        if ($pageType === 'static') {
            $key = sanitize($pageId);
            Setting::set("seo_page_{$key}_title", $metaTitle, 'seo_page');
            Setting::set("seo_page_{$key}_desc", $metaDesc, 'seo_page');
            if ($metaKeywords) {
                Setting::set("seo_page_{$key}_keywords", $metaKeywords, 'seo_page');
            }
        } elseif ($pageType === 'cms') {
            Database::update('pages', [
                'meta_title' => $metaTitle ?: null,
                'meta_description' => $metaDesc ?: null,
            ], "`id` = :id", ['id' => (int)$pageId]);
        } elseif ($pageType === 'product') {
            Database::update('products', [
                'meta_title' => $metaTitle ?: null,
                'meta_description' => $metaDesc ?: null,
            ], "`id` = :id", ['id' => (int)$pageId]);
        } elseif ($pageType === 'category') {
            Database::update('product_categories', [
                'meta_title' => $metaTitle ?: null,
                'meta_description' => $metaDesc ?: null,
            ], "`id` = :id", ['id' => (int)$pageId]);
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'SEO metadata updated successfully!']);
            return;
        }

        flash('success', 'SEO metadata updated successfully!');
        $this->redirect('admin/pages');
    }
}
