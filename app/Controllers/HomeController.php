<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Testimonial;
use App\Models\Faq;

class HomeController extends Controller {
    public function index(): void {
        $banners = Banner::getHero();
        $promoBanner = Banner::getPromotional();
        $categories = Category::getActive();
        $featuredProducts = Product::getFeatured(6);
        $bestsellers = Product::getBestsellers(4);
        $testimonials = Testimonial::getActive();
        $faqs = Faq::where('is_active', 1);

        // Preload default variants for products in the cards
        foreach ($featuredProducts as &$prod) {
            $prod['variants'] = Product::getVariants($prod['id']);
        }
        foreach ($bestsellers as &$prod) {
            $prod['variants'] = Product::getVariants($prod['id']);
        }

        $this->view('home.index', [
            'meta_title' => 'LEGACY · Crafted in South India | Premium A2 Ghee & Pure Oils',
            'meta_description' => 'Pure heritage ghee and wood-pressed oils hand-churned in South India from fresh farm butter. NABL accredited lab tested with zero adulteration.',
            'banners' => $banners,
            'promoBanner' => $promoBanner,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'bestsellers' => $bestsellers,
            'testimonials' => $testimonials,
            'faqs' => $faqs,
        ]);
    }
}
