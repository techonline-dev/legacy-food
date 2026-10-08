<?php
use App\Models\Setting;

$cartSummary = \App\Models\Cart::getSummary();
$cartCount = $cartSummary['items_count'];
$wishlistCount = \App\Core\Auth::check() ? \App\Models\Wishlist::getCount(\App\Core\Auth::id()) : 0;
$categories = \App\Models\Category::getActive();
$authUser = \App\Core\Auth::user();
$authCheck = !empty($authUser);
$authName = $authUser['name'] ?? ($_SESSION['user_name'] ?? '');
$firstName = !empty($authName) ? explode(' ', trim($authName))[0] : 'Account';

// Editable Header Settings
$announcementEnabled = Setting::get('header_announcement_enabled', '1');
$announcementText = Setting::get('header_announcement_text', 'Hand-Churned Farm Ghee · Lab Tested Purity · <strong class="text-white">FREE Shipping</strong> on Orders Above ₹999');
$headerLogoUrl = Setting::get('header_logo_url', '');
if (empty($headerLogoUrl)) {
    $headerLogoUrl = asset('assets/images/branding/header-logo-dark.svg');
}
$showWhatsapp = Setting::get('header_show_whatsapp', '1');
$whatsappText = Setting::get('header_whatsapp_text', 'Order on WhatsApp');
$whatsappNumber = Setting::get('whatsapp_number', '919845279936');
$whatsappNumberClean = preg_replace('/[^0-9]/', '', $whatsappNumber);
?>

<header id="site-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-[#e7dec8] shadow-sm">
    <?php if ($announcementEnabled == '1' || $announcementEnabled === true): ?>
    <!-- Top Announcement Bar -->
    <div class="bg-[#07160d] text-[#d8bd99] py-1.5 px-4 text-center border-b border-[#bc944c]/20">
        <p class="text-[11px] md:text-xs font-body tracking-wider flex items-center justify-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-[#bc944c] animate-pulse"></span>
            <span><?= $announcementText ?></span>
        </p>
    </div>
    <?php endif; ?>

    <!-- Main Navigation Bar -->
    <div class="container-x">
        <div class="flex items-center justify-between h-20">
            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 text-stone-900 hover:text-[#bc944c] transition-colors" aria-label="Open Navigation">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Brand Logo -->
            <a href="<?= url('/') ?>" class="flex items-center gap-3 transition-transform hover:scale-[1.02]">
                <img src="<?= e($headerLogoUrl) ?>" alt="Legacy Food" class="h-10 md:h-12 w-auto">
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center gap-8">
                <?php $isHome = is_active_path('/', true); ?>
                <a href="<?= url('/') ?>" class="relative py-1 text-xs uppercase tracking-widest font-bold transition-colors <?= $isHome ? 'text-[#bc944c]' : 'text-stone-900 hover:text-[#bc944c]' ?>">
                    <span>Home</span>
                    <?php if ($isHome): ?>
                        <span class="absolute -bottom-1 left-0 right-0 h-[2px] bg-[#bc944c] rounded-full"></span>
                    <?php endif; ?>
                </a>

                <!-- Categories Dropdown -->
                <?php $isCat = is_active_path('category'); ?>
                <div class="relative group">
                    <button class="relative py-1 flex items-center gap-1 text-xs uppercase tracking-widest font-bold transition-colors <?= $isCat ? 'text-[#bc944c]' : 'text-stone-900 hover:text-[#bc944c]' ?>">
                        <span>Categories</span>
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:rotate-180 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        <?php if ($isCat): ?>
                            <span class="absolute -bottom-1 left-0 right-0 h-[2px] bg-[#bc944c] rounded-full"></span>
                        <?php endif; ?>
                    </button>
                    <div class="absolute top-full left-0 w-60 pt-2 opacity-0 translate-y-2 pointer-events-none group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-200">
                        <div class="rounded-2xl bg-white border border-[#e7dec8] shadow-2xl p-2.5 space-y-1">
                            <?php foreach ($categories as $cat): ?>
                                <?php $isSubCat = is_active_path('category/' . $cat['slug'], true); ?>
                                <a href="<?= url('category/' . $cat['slug']) ?>" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition-colors <?= $isSubCat ? 'bg-amber-50 text-[#bc944c] font-bold border border-[#d6c7af]' : 'text-stone-900 hover:bg-[#faf8f5] hover:text-[#bc944c]' ?>">
                                    <span><?= e($cat['name']) ?></span>
                                    <span class="text-[10px] text-[#bc944c] font-bold"><?= $cat['product_count'] ?? '' ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <?php $isAbout = is_active_path('about'); ?>
                <a href="<?= url('about') ?>" class="relative py-1 text-xs uppercase tracking-widest font-bold transition-colors <?= $isAbout ? 'text-[#bc944c]' : 'text-stone-900 hover:text-[#bc944c]' ?>">
                    <span>About Us</span>
                    <?php if ($isAbout): ?>
                        <span class="absolute -bottom-1 left-0 right-0 h-[2px] bg-[#bc944c] rounded-full"></span>
                    <?php endif; ?>
                </a>

                <?php $isContact = is_active_path('contact'); ?>
                <a href="<?= url('contact') ?>" class="relative py-1 text-xs uppercase tracking-widest font-bold transition-colors <?= $isContact ? 'text-[#bc944c]' : 'text-stone-900 hover:text-[#bc944c]' ?>">
                    <span>Contact</span>
                    <?php if ($isContact): ?>
                        <span class="absolute -bottom-1 left-0 right-0 h-[2px] bg-[#bc944c] rounded-full"></span>
                    <?php endif; ?>
                </a>
            </nav>

            <!-- Account, Wishlist, Cart, WhatsApp Actions -->
            <div class="flex items-center gap-2 md:gap-4">
                <?php if ($showWhatsapp == '1' || $showWhatsapp === true): ?>
                <!-- WhatsApp Quick Order -->
                <a href="https://wa.me/<?= e($whatsappNumberClean) ?>?text=<?= urlencode('Hi Legacy, I would like to order ghee') ?>" target="_blank" rel="noopener" class="hidden md:inline-flex btn-gold text-xs py-2 px-4 shadow-sm">
                    <span><?= e($whatsappText) ?></span>
                </a>
                <?php endif; ?>

                <!-- Account / User Profile -->
                <?php $isAccount = is_active_path(['account', 'login', 'register']); ?>
                <?php if ($authCheck): ?>
                    <div class="relative group">
                        <a href="<?= url('account') ?>" 
                           class="inline-flex items-center gap-2 py-1.5 px-3 rounded-full bg-[#faf8f5] hover:bg-amber-50/80 border border-[#e7dec8] hover:border-[#bc944c]/60 transition-all text-xs font-semibold text-[#07160d] <?= $isAccount ? 'ring-1 ring-[#bc944c] bg-amber-50 border-[#bc944c]/60' : '' ?>" 
                           title="<?= e($authName) ?> - My Account">
                            <span class="w-6 h-6 rounded-full bg-[#bc944c] text-[#07160d] font-bold text-[11px] flex items-center justify-center uppercase shadow-2xs shrink-0">
                                <?= mb_strtoupper(mb_substr($firstName, 0, 1)) ?>
                            </span>
                            <span class="hidden sm:inline max-w-[130px] truncate text-[#07160d] group-hover:text-[#bc944c] transition-colors">
                                <?= e($authName) ?>
                            </span>
                            <svg class="w-3.5 h-3.5 text-stone-400 group-hover:text-[#bc944c] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>

                        <!-- Dropdown Menu on Desktop Hover -->
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute right-0 top-full pt-2 w-56 z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-[#e7dec8] p-2 text-xs space-y-0.5">
                                <div class="px-3 py-2.5 border-b border-stone-100 mb-1 bg-[#faf8f5] rounded-xl">
                                    <span class="text-[10px] uppercase font-bold text-[#bc944c] tracking-wider block">Signed In</span>
                                    <p class="font-bold text-[#07160d] truncate"><?= e($authName) ?></p>
                                    <p class="text-[10px] text-stone-500 truncate"><?= e($authUser['email'] ?? '') ?></p>
                                </div>
                                <a href="<?= url('account') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-stone-700 hover:text-[#bc944c] hover:bg-amber-50/60 font-semibold transition-colors">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span>My Dashboard</span>
                                </a>
                                <a href="<?= url('account/orders') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-stone-700 hover:text-[#bc944c] hover:bg-amber-50/60 font-semibold transition-colors">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>Order History</span>
                                </a>
                                <a href="<?= url('account/addresses') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-stone-700 hover:text-[#bc944c] hover:bg-amber-50/60 font-semibold transition-colors">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Saved Addresses</span>
                                </a>
                                <a href="<?= url('wishlist') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-stone-700 hover:text-[#bc944c] hover:bg-amber-50/60 font-semibold transition-colors">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    <span>Wishlist</span>
                                </a>
                                <div class="border-t border-stone-100 my-1"></div>
                                <a href="<?= url('logout') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-red-600 hover:bg-red-50 font-semibold transition-colors">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>Log Out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= url('login') ?>" class="p-2 transition-colors relative <?= $isAccount ? 'text-[#bc944c]' : 'text-stone-900 hover:text-[#bc944c]' ?>" title="Sign In">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <?php if ($isAccount): ?>
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#bc944c]"></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <!-- Wishlist Icon -->
                <?php $isWishlist = is_active_path('wishlist'); ?>
                <a href="<?= url('wishlist') ?>" class="p-2 transition-colors relative <?= $isWishlist ? 'text-[#bc944c]' : 'text-stone-900 hover:text-[#bc944c]' ?>" title="Wishlist">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span id="wishlist-count-badge" class="<?= $wishlistCount > 0 ? 'flex' : 'hidden' ?> absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full bg-[#bc944c] text-white text-[10px] font-bold items-center justify-center">
                        <?= $wishlistCount ?>
                    </span>
                    <?php if ($isWishlist): ?>
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#bc944c]"></span>
                    <?php endif; ?>
                </a>

                <!-- Cart Button -->
                <?php $isCart = is_active_path(['cart', 'checkout']); ?>
                <button class="cart-open-btn p-2 transition-colors relative <?= $isCart ? 'text-[#bc944c]' : 'text-stone-900 hover:text-[#bc944c]' ?>" aria-label="Open Cart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="cart-count-badge <?= $cartCount > 0 ? 'flex' : 'hidden' ?> absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full bg-[#bc944c] text-white text-[10px] font-bold items-center justify-center">
                        <?= $cartCount ?>
                    </span>
                    <?php if ($isCart): ?>
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#bc944c]"></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Navigation Overlay -->
<div id="mobile-menu-overlay" class="hidden fixed inset-0 bg-stone-950/60 backdrop-blur-xs z-[65] transition-opacity duration-300"></div>

<!-- Mobile Navigation Drawer -->
<div id="mobile-menu-drawer" class="fixed inset-y-0 left-0 w-80 max-w-[85vw] h-full h-[100dvh] bg-[#faf8f5] border-r border-[#e7dec8] z-[70] transform -translate-x-full transition-transform duration-300 ease-out flex flex-col justify-between text-[#1c1917] shadow-2xl overflow-hidden">
    
    <!-- Top Header Bar inside Drawer -->
    <div class="px-5 py-4 bg-white border-b border-[#e7dec8] flex items-center justify-between shrink-0 shadow-xs">
        <a href="<?= url('/') ?>" class="flex items-center gap-2" aria-label="Legacy Food">
            <img src="<?= e($headerLogoUrl) ?>" class="h-8 w-auto" alt="Legacy Food">
        </a>
        <button id="mobile-menu-close" class="w-8 h-8 rounded-full bg-stone-100 hover:bg-[#07160d] text-stone-500 hover:text-white flex items-center justify-center transition-all duration-200" aria-label="Close navigation menu">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Scrollable Drawer Content -->
    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4">
        
        <!-- User Status Card -->
        <?php if ($authCheck): ?>
            <div class="p-3.5 rounded-2xl bg-white border border-[#e7dec8] shadow-xs">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-gradient-to-br from-[#bc944c] to-[#8d6d33] text-white font-bold text-sm flex items-center justify-center uppercase shadow-xs shrink-0 ring-2 ring-[#bc944c]/20">
                        <?= mb_strtoupper(mb_substr($firstName, 0, 1)) ?>
                    </span>
                    <div class="flex-1 min-w-0">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-[#bc944c] bg-amber-50 px-1.5 py-0.5 rounded inline-block">Signed In</span>
                        <h4 class="font-bold text-xs text-[#07160d] truncate mt-0.5"><?= e($authName) ?></h4>
                        <span class="text-[10px] text-stone-500 truncate block"><?= e($authUser['email'] ?? '') ?></span>
                    </div>
                </div>
                <div class="mt-3 pt-2.5 border-t border-[#f2ece0] flex items-center justify-between text-xs">
                    <a href="<?= url('account') ?>" class="text-[#bc944c] font-bold hover:underline flex items-center gap-1">
                        <span>My Account</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="<?= url('logout') ?>" class="text-[11px] text-rose-600 font-bold hover:underline px-2 py-1 rounded-lg hover:bg-rose-50 transition-colors">Sign Out</a>
                </div>
            </div>
        <?php else: ?>
            <div class="p-4 rounded-2xl bg-gradient-to-br from-white to-[#fbf8f2] border border-[#e7dec8] shadow-xs">
                <div class="flex items-center gap-2.5 mb-2.5">
                    <span class="w-8 h-8 rounded-full bg-amber-100 text-[#bc944c] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-display font-bold text-xs text-[#07160d]">Welcome to Legacy Food</h4>
                        <p class="text-[10px] text-stone-500">Pure South Indian Traditions</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-3">
                    <a href="<?= url('login') ?>" class="btn-gold text-xs py-2 text-center rounded-xl font-bold shadow-xs">
                        Sign In
                    </a>
                    <a href="<?= url('register') ?>" class="text-xs py-2 text-center rounded-xl font-bold border border-[#bc944c] text-[#07160d] hover:bg-amber-50 transition-colors">
                        Register
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Primary Navigation List -->
        <nav class="space-y-1">
            <!-- Home -->
            <?php $isHome = is_active_path('/', true); ?>
            <a href="<?= url('/') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?= $isHome ? 'bg-[#07160d] text-white shadow-xs' : 'text-stone-800 hover:bg-white hover:text-[#bc944c]' ?>">
                <svg class="w-4 h-4 <?= $isHome ? 'text-[#bc944c]' : 'text-stone-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Home</span>
            </a>

            <!-- Collections Accordion -->
            <?php $hasActiveCat = is_active_path('category'); ?>
            <div class="rounded-xl overflow-hidden border border-[#e7dec8] bg-white">
                <button type="button" id="mobile-collections-toggle" class="w-full flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-stone-800 hover:text-[#bc944c] transition-colors">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4 <?= $hasActiveCat ? 'text-[#bc944c]' : 'text-stone-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Collections</span>
                    </span>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-semibold bg-amber-50 text-[#bc944c] px-2 py-0.5 rounded-full"><?= count($categories) ?></span>
                        <svg id="mobile-collections-arrow" class="w-3.5 h-3.5 text-stone-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>
                <div id="mobile-collections-list" class="divide-y divide-[#f5efe4] border-t border-[#f0e9dc] bg-[#faf8f5]/60">
                    <?php foreach ($categories as $cat): ?>
                        <?php $isCatActive = is_active_path('category/' . $cat['slug'], true); ?>
                        <a href="<?= url('category/' . $cat['slug']) ?>" class="flex items-center justify-between px-4 py-2 text-xs transition-colors <?= $isCatActive ? 'font-bold text-[#bc944c] bg-amber-50/80' : 'text-stone-700 hover:text-[#07160d] hover:bg-white' ?>">
                            <span class="flex items-center gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full <?= $isCatActive ? 'bg-[#bc944c]' : 'bg-stone-300' ?>"></span>
                                <span><?= e($cat['name']) ?></span>
                            </span>
                            <span class="text-[10px] text-stone-400"><?= !empty($cat['product_count']) ? $cat['product_count'] . ' items' : '' ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Wishlist -->
            <?php $isWishlist = is_active_path('wishlist'); ?>
            <a href="<?= url('wishlist') ?>" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?= $isWishlist ? 'bg-[#07160d] text-white shadow-xs' : 'text-stone-800 hover:bg-white hover:text-[#bc944c]' ?>">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4 <?= $isWishlist ? 'text-[#bc944c]' : 'text-stone-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>My Wishlist</span>
                </span>
                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full <?= $wishlistCount > 0 ? 'bg-[#bc944c] text-white' : 'bg-stone-100 text-stone-400' ?>">
                    <?= $wishlistCount ?>
                </span>
            </a>

            <!-- Orders / Track Order -->
            <?php $isOrders = is_active_path('account/orders'); ?>
            <a href="<?= $authCheck ? url('account/orders') : url('login') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?= $isOrders ? 'bg-[#07160d] text-white shadow-xs' : 'text-stone-800 hover:bg-white hover:text-[#bc944c]' ?>">
                <svg class="w-4 h-4 <?= $isOrders ? 'text-[#bc944c]' : 'text-stone-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span><?= $authCheck ? 'Order History' : 'Track Order / Orders' ?></span>
            </a>

            <!-- About Us -->
            <?php $isAbout = is_active_path('about'); ?>
            <a href="<?= url('about') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?= $isAbout ? 'bg-[#07160d] text-white shadow-xs' : 'text-stone-800 hover:bg-white hover:text-[#bc944c]' ?>">
                <svg class="w-4 h-4 <?= $isAbout ? 'text-[#bc944c]' : 'text-stone-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Our Story & Heritage</span>
            </a>

            <!-- Contact Us -->
            <?php $isContact = is_active_path('contact'); ?>
            <a href="<?= url('contact') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?= $isContact ? 'bg-[#07160d] text-white shadow-xs' : 'text-stone-800 hover:bg-white hover:text-[#bc944c]' ?>">
                <svg class="w-4 h-4 <?= $isContact ? 'text-[#bc944c]' : 'text-stone-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Contact Us</span>
            </a>
        </nav>
    </div>

    <!-- Drawer Footer Actions -->
    <div class="p-4 border-t border-[#e7dec8] bg-white space-y-2.5 shrink-0 shadow-xs">
        <?php if ($showWhatsapp == '1' || $showWhatsapp === true): ?>
        <a href="https://wa.me/<?= e($whatsappNumberClean) ?>" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 bg-[#25D366] hover:bg-[#20ba59] text-white rounded-xl text-xs font-bold shadow-xs transition-transform active:scale-95">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.541 1.769.819 2.796.819 3.18 0 5.767-2.587 5.767-5.766.001-3.182-2.585-5.806-5.767-5.806zm0 10.455c-.908 0-1.745-.252-2.463-.717l-.176-.104-1.83.479.489-1.782-.115-.184c-.512-.816-.782-1.637-.781-2.365.001-2.569 2.091-4.659 4.876-4.659 2.784 0 4.658 2.089 4.658 4.659 0 2.57-1.874 4.669-4.658 4.669z"/>
            </svg>
            <span><?= e($whatsappText) ?></span>
        </a>
        <?php endif; ?>
        <div class="text-center">
            <p class="text-[10px] text-stone-400 font-medium">✦ 100% Bilona Churned · Lab Tested Pure ✦</p>
        </div>
    </div>
</div>
