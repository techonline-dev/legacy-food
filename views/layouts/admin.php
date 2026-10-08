<?php
$admin = \App\Core\Auth::admin();
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($meta_title ?? 'Legacy Food — Admin Portal') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="<?= asset('assets/css/custom.css') ?>">
    <link rel="icon" type="image/svg+xml" href="<?= asset('assets/images/branding/legacy-seal.svg') ?>">
</head>
<body class="admin-light bg-[#faf8f5] text-[#1c1917] antialiased min-h-screen flex selection:bg-[#bc944c] selection:text-[#07160d]">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-[#e7dec8] flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen overflow-y-auto shadow-xs z-40">
        <div>
            <!-- Admin Brand Logo -->
            <div class="p-6 border-b border-[#e7dec8] flex items-center justify-between bg-white">
                <a href="<?= url('admin') ?>" class="block">
                    <img src="<?= asset('assets/images/branding/header-logo-dark.svg') ?>" class="h-8 w-auto" alt="Legacy Food Admin">
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-xs">
                <div class="px-3 py-2 text-[10px] uppercase tracking-wider text-[#bc944c] font-bold">Commerce</div>
                
                <?php $act = is_active_path('admin', true); ?>
                <a href="<?= url('admin') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/orders'); ?>
                <a href="<?= url('admin/orders') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Orders</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/products'); ?>
                <a href="<?= url('admin/products') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Products</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/categories'); ?>
                <a href="<?= url('admin/categories') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Categories</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/inventory'); ?>
                <a href="<?= url('admin/inventory') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Inventory</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/coupons'); ?>
                <a href="<?= url('admin/coupons') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        <span>Coupons</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/customers'); ?>
                <a href="<?= url('admin/customers') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Customers</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <div class="pt-4 px-3 py-2 text-[10px] uppercase tracking-wider text-[#bc944c] font-bold">Store Content</div>

                <?php $act = is_active_path('admin/banners'); ?>
                <a href="<?= url('admin/banners') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Banners & Sliders</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/reviews'); ?>
                <a href="<?= url('admin/reviews') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        <span>Reviews Moderation</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/pages'); ?>
                <a href="<?= url('admin/pages') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Pages & SEO</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/faqs'); ?>
                <a href="<?= url('admin/faqs') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>FAQs</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <?php $act = is_active_path('admin/testimonials'); ?>
                <a href="<?= url('admin/testimonials') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <span>Testimonials</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>

                <div class="pt-4 px-3 py-2 text-[10px] uppercase tracking-wider text-[#bc944c] font-bold">System</div>

                <?php $act = is_active_path('admin/settings'); ?>
                <a href="<?= url('admin/settings') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-colors <?= $act ? 'bg-[#bc944c] text-[#07160d] font-bold shadow-sm' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#07160d]' ?>" <?= $act ? 'aria-current="page"' : '' ?>>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Store Settings</span>
                    </div>
                    <?php if ($act): ?><span class="w-1.5 h-1.5 rounded-full bg-[#07160d]"></span><?php endif; ?>
                </a>
            </nav>
        </div>

        <!-- Admin Profile Bottom Card -->
        <div class="p-4 border-t border-[#e7dec8] bg-[#faf8f5]">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-[#bc944c] text-[#07160d] font-bold flex items-center justify-center text-xs shadow-xs">
                    <?= substr($admin['name'] ?? 'A', 0, 1) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-semibold text-[#07160d] truncate"><?= e($admin['name'] ?? 'Administrator') ?></div>
                    <div class="text-[10px] text-[#bc944c] font-bold"><?= e($admin['role_name'] ?? 'Super Admin') ?></div>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs pt-2 border-t border-[#e7dec8]">
                <a href="<?= url('/') ?>" target="_blank" class="text-stone-600 hover:text-[#bc944c] flex items-center gap-1 font-medium transition-colors">
                    <span>Storefront</span>
                    <span>↗</span>
                </a>
                <a href="<?= url('admin/logout') ?>" class="text-red-600 hover:text-red-700 font-semibold transition-colors">Log Out</a>
            </div>
        </div>
    </aside>

    <!-- Main Content Right Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#faf8f5]">
        <!-- Topbar -->
        <header class="h-16 bg-white/95 backdrop-blur-md border-b border-[#e7dec8] px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="text-xs text-[#07160d] font-bold uppercase tracking-wider">Legacy Food Control Panel</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="<?= url('/') ?>" target="_blank" class="btn-ghost text-xs py-1.5 px-3 border border-[#d6c7af] text-[#07160d] hover:bg-[#faf8f5]">
                    View Live Store ↗
                </a>
            </div>
        </header>

        <!-- Dynamic Admin Content -->
        <main class="flex-1 p-6 md:p-8 overflow-y-auto">
            <?php include __DIR__ . '/../components/alerts.php'; ?>
            <?= $content ?>
        </main>
    </div>
</body>
</html>
