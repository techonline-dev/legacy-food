<?php

use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;

/** @var Router $router */

// Customer Pages
$router->get('/', 'HomeController@index');
$router->get('/shop', 'ShopController@index');
$router->get('/category/{slug}', 'ShopController@category');
$router->get('/product/{slug}', 'ProductController@show');
$router->get('/search', 'ShopController@search');

// Cart Routes
$router->get('/cart', 'CartController@index');
$router->get('/cart/summary', 'CartController@summary');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->post('/cart/coupon/apply', 'CartController@applyCoupon');
$router->post('/cart/coupon/remove', 'CartController@removeCoupon');
$router->post('/cart/apply-coupon', 'CartController@applyCoupon');
$router->post('/cart/remove-coupon', 'CartController@removeCoupon');

// Checkout Routes
$router->get('/checkout', 'CheckoutController@index');
$router->post('/checkout/process', 'CheckoutController@process');
$router->post('/checkout/verify-payment', 'CheckoutController@verifyPayment');
$router->get('/checkout/success/{order_number}', 'CheckoutController@success');

// Authentication Routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@showForgotPassword');
$router->post('/forgot-password', 'AuthController@forgotPassword');

// Customer Account Routes (Protected by AuthMiddleware)
$router->group(['middleware' => [AuthMiddleware::class]], function(Router $r) {
    $r->get('/account', 'AccountController@dashboard');
    $r->get('/account/orders', 'AccountController@orders');
    $r->get('/account/order/{id}', 'AccountController@orderDetail');
    $r->post('/account/order/{id}/cancel', 'AccountController@cancelOrder');
    $r->get('/account/profile', 'AccountController@profile');
    $r->post('/account/profile/update', 'AccountController@updateProfile');
    $r->get('/account/addresses', 'AccountController@addresses');
});

// Wishlist
$router->get('/wishlist', 'WishlistController@index');
$router->post('/wishlist/toggle', 'WishlistController@toggle');
$router->post('/wishlist/move-to-cart', 'WishlistController@moveToCart');

// Reviews
$router->post('/reviews/store', 'ReviewController@store');

// Content & Static Pages
$router->get('/about', 'PageController@about');
$router->get('/faq', 'PageController@faq');
$router->get('/contact', 'ContactController@index');
$router->post('/contact/submit', 'ContactController@submit');
$router->post('/newsletter/subscribe', 'ContactController@subscribeNewsletter');
$router->get('/privacy-policy', function($req, $res) { (new \App\Controllers\PageController($req, $res))->show('privacy-policy'); });
$router->get('/terms-and-conditions', function($req, $res) { (new \App\Controllers\PageController($req, $res))->show('terms-and-conditions'); });
$router->get('/shipping-policy', function($req, $res) { (new \App\Controllers\PageController($req, $res))->show('shipping-policy'); });
$router->get('/return-refund-policy', function($req, $res) { (new \App\Controllers\PageController($req, $res))->show('return-refund-policy'); });
$router->get('/page/{slug}', 'PageController@show');

// XML Sitemap for Search Engines
$router->get('/sitemap.xml', 'PageController@sitemap');

// Invoice
$router->get('/invoice/{order_number}', 'InvoiceController@show');

// System Diagnostics & Installer
$router->get('/install', 'InstallController@index');
$router->post('/install/run', 'InstallController@run');

// Admin Auth
$router->get('/admin/login', 'Admin\AdminAuthController@showLogin');
$router->post('/admin/login', 'Admin\AdminAuthController@login');
$router->get('/admin/logout', 'Admin\AdminAuthController@logout');

// Admin Protected Routes
$router->group(['middleware' => [AdminMiddleware::class]], function(Router $r) {
    $r->get('/admin', 'Admin\AdminDashboardController@index');
    
    // Products
    $r->get('/admin/products', 'Admin\AdminProductController@index');
    $r->get('/admin/products/create', 'Admin\AdminProductController@create');
    $r->post('/admin/products/store', 'Admin\AdminProductController@store');
    $r->get('/admin/products/edit/{id}', 'Admin\AdminProductController@edit');
    $r->post('/admin/products/update/{id}', 'Admin\AdminProductController@update');
    $r->post('/admin/products/delete/{id}', 'Admin\AdminProductController@delete');
    $r->post('/admin/products/delete-image/{id}', 'Admin\AdminProductController@deleteImage');
    $r->post('/admin/products/delete-variant/{id}', 'Admin\AdminProductController@deleteVariant');

    // Categories
    $r->get('/admin/categories', 'Admin\AdminCategoryController@index');
    $r->get('/admin/categories/edit/{id}', 'Admin\AdminCategoryController@edit');
    $r->post('/admin/categories/store', 'Admin\AdminCategoryController@store');
    $r->post('/admin/categories/update/{id}', 'Admin\AdminCategoryController@update');
    $r->post('/admin/categories/delete/{id}', 'Admin\AdminCategoryController@delete');

    // Orders
    $r->get('/admin/orders', 'Admin\AdminOrderController@index');
    $r->get('/admin/orders/{id}', 'Admin\AdminOrderController@detail');
    $r->post('/admin/orders/update-status/{id}', 'Admin\AdminOrderController@updateStatus');

    // Inventory
    $r->get('/admin/inventory', 'Admin\AdminInventoryController@index');
    $r->post('/admin/inventory/adjust', 'Admin\AdminInventoryController@adjust');

    // Customers
    $r->get('/admin/customers', 'Admin\AdminCustomerController@index');
    $r->get('/admin/customers/{id}', 'Admin\AdminCustomerController@detail');
    $r->post('/admin/customers/update/{id}', 'Admin\AdminCustomerController@update');
    $r->post('/admin/customers/status/{id}', 'Admin\AdminCustomerController@updateStatus');

    // Coupons
    $r->get('/admin/coupons', 'Admin\AdminCouponController@index');
    $r->get('/admin/coupons/edit/{id}', 'Admin\AdminCouponController@edit');
    $r->post('/admin/coupons/store', 'Admin\AdminCouponController@store');
    $r->post('/admin/coupons/update/{id}', 'Admin\AdminCouponController@update');
    $r->post('/admin/coupons/delete/{id}', 'Admin\AdminCouponController@delete');

    // Reviews
    $r->get('/admin/reviews', 'Admin\AdminReviewController@index');
    $r->post('/admin/reviews/status/{id}/{status}', 'Admin\AdminReviewController@updateStatus');
    $r->post('/admin/reviews/delete/{id}', 'Admin\AdminReviewController@delete');

    // Banners
    $r->get('/admin/banners', 'Admin\AdminBannerController@index');
    $r->get('/admin/banners/edit/{id}', 'Admin\AdminBannerController@edit');
    $r->post('/admin/banners/store', 'Admin\AdminBannerController@store');
    $r->post('/admin/banners/update/{id}', 'Admin\AdminBannerController@update');
    $r->post('/admin/banners/delete/{id}', 'Admin\AdminBannerController@delete');

    // Pages (CMS & SEO)
    $r->get('/admin/pages', 'Admin\AdminPageController@index');
    $r->get('/admin/pages/create', 'Admin\AdminPageController@create');
    $r->post('/admin/pages/store', 'Admin\AdminPageController@store');
    $r->get('/admin/pages/edit/{id}', 'Admin\AdminPageController@edit');
    $r->post('/admin/pages/update/{id}', 'Admin\AdminPageController@update');
    $r->post('/admin/pages/delete/{id}', 'Admin\AdminPageController@delete');
    $r->post('/admin/pages/update-seo', 'Admin\AdminPageController@updateSeo');

    // FAQs
    $r->get('/admin/faqs', 'Admin\AdminFaqController@index');
    $r->get('/admin/faqs/edit/{id}', 'Admin\AdminFaqController@edit');
    $r->post('/admin/faqs/store', 'Admin\AdminFaqController@store');
    $r->post('/admin/faqs/update/{id}', 'Admin\AdminFaqController@update');
    $r->post('/admin/faqs/delete/{id}', 'Admin\AdminFaqController@delete');

    // Testimonials
    $r->get('/admin/testimonials', 'Admin\AdminTestimonialController@index');
    $r->get('/admin/testimonials/edit/{id}', 'Admin\AdminTestimonialController@edit');
    $r->post('/admin/testimonials/store', 'Admin\AdminTestimonialController@store');
    $r->post('/admin/testimonials/update/{id}', 'Admin\AdminTestimonialController@update');
    $r->post('/admin/testimonials/delete/{id}', 'Admin\AdminTestimonialController@delete');

    // Settings
    $r->get('/admin/settings', 'Admin\AdminSettingController@index');
    $r->post('/admin/settings', 'Admin\AdminSettingController@update');
    $r->post('/admin/settings/update', 'Admin\AdminSettingController@update');
});

// Dynamic CMS Page Route (fallback for any custom or edited page slugs)
$router->get('/{slug}', 'PageController@show');

