<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Category;

class PageController extends Controller {
    public function about(): void {
        $page = Page::getBySlug('about-us') ?? Page::getBySlug('about');
        if (!$page) {
            $page = Database::fetch("SELECT * FROM `pages` WHERE `slug` LIKE 'about%' OR `title` LIKE '%About%' LIMIT 1");
        }

        $this->view('pages.about', [
            'meta_title' => !empty($page['meta_title']) ? $page['meta_title'] : 'About Us | Legacy Food — Crafted in South India',
            'meta_description' => !empty($page['meta_description']) ? $page['meta_description'] : 'The story of Legacy Food. Pure farm butter, small batch traditional churning, NABL certified purity, and generations of culinary heritage.',
            'page' => $page,
        ]);
    }

    public function faq(): void {
        $faqs = Faq::where('is_active', 1);

        $this->view('pages.faq', [
            'meta_title' => 'Frequently Asked Questions (FAQ) | Legacy Food',
            'meta_description' => 'Find answers to common questions about Legacy ghee, GC lab testing, wood-pressed oils, storage tips, and delivery details.',
            'faqs' => $faqs,
        ]);
    }

    public function show(string $slug): void {
        $slug = trim(strtolower($slug));

        if ($slug === 'about' || $slug === 'about-us') {
            $this->about();
            return;
        }

        if ($slug === 'faq') {
            $this->faq();
            return;
        }

        $page = Page::getBySlug($slug);
        if (!$page || empty($page['is_active'])) {
            $this->response->status(404);
            $this->view('errors.404', ['title' => 'Page Not Found | Legacy Food']);
            return;
        }

        $this->view('pages.legal', [
            'meta_title' => (!empty($page['meta_title']) ? $page['meta_title'] : $page['title']) . ' | Legacy Food',
            'meta_description' => !empty($page['meta_description']) ? $page['meta_description'] : setting('meta_description_default'),
            'page' => $page,
        ]);
    }

    public function legal(string $slug): void {
        $this->show($slug);
    }

    /**
     * Dynamic XML Sitemap generation for search engines (Google, Bing)
     */
    public function sitemap(): void {
        header('Content-Type: application/xml; charset=utf-8');

        $baseUrl = rtrim(url('/'), '/');
        $products = Product::where('status', 'published');
        $categories = Category::getActive();
        $pages = Database::fetchAll("SELECT slug, updated_at FROM `pages` WHERE `is_active` = 1");

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // 1. Homepage
        echo "  <url>\n";
        echo "    <loc>{$baseUrl}/</loc>\n";
        echo "    <changefreq>daily</changefreq>\n";
        echo "    <priority>1.0</priority>\n";
        echo "  </url>\n";

        // 2. Shop page
        echo "  <url>\n";
        echo "    <loc>{$baseUrl}/shop</loc>\n";
        echo "    <changefreq>daily</changefreq>\n";
        echo "    <priority>0.9</priority>\n";
        echo "  </url>\n";

        // 3. Category pages
        foreach ($categories as $cat) {
            echo "  <url>\n";
            echo "    <loc>{$baseUrl}/category/" . htmlspecialchars($cat['slug']) . "</loc>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }

        // 4. Product pages
        foreach ($products as $prod) {
            $lastmod = !empty($prod['updated_at']) ? date('Y-m-d', strtotime($prod['updated_at'])) : date('Y-m-d');
            echo "  <url>\n";
            echo "    <loc>{$baseUrl}/product/" . htmlspecialchars($prod['slug']) . "</loc>\n";
            echo "    <lastmod>{$lastmod}</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }

        // 5. CMS Pages
        foreach ($pages as $pg) {
            $lastmod = !empty($pg['updated_at']) ? date('Y-m-d', strtotime($pg['updated_at'])) : date('Y-m-d');
            echo "  <url>\n";
            echo "    <loc>{$baseUrl}/" . htmlspecialchars($pg['slug']) . "</loc>\n";
            echo "    <lastmod>{$lastmod}</lastmod>\n";
            echo "    <changefreq>monthly</changefreq>\n";
            echo "    <priority>0.6</priority>\n";
            echo "  </url>\n";
        }

        // 6. Contact & FAQ
        echo "  <url>\n";
        echo "    <loc>{$baseUrl}/contact</loc>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.5</priority>\n";
        echo "  </url>\n";

        echo "  <url>\n";
        echo "    <loc>{$baseUrl}/faq</loc>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.5</priority>\n";
        echo "  </url>\n";

        echo '</urlset>';
        exit;
    }
}
