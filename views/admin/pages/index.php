<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-xs text-[#bc944c] font-semibold uppercase tracking-wider mb-1">Search Engine Optimization</div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Pages & SEO Directory</h1>
            <p class="text-xs text-stone-500 mt-1">Manage and optimize SEO titles, descriptions, and keywords across all static and dynamic pages in one place.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('admin/pages/create') ?>" class="btn-primary text-xs py-2.5 px-4 font-bold flex items-center gap-2 shadow-sm">
                <span>+ Create New CMS Page</span>
            </a>
        </div>
    </div>

    <?php
    $totalStatic = count($staticPages ?? []);
    $totalCms = count($cmsPages ?? []);
    $totalProducts = count($productPages ?? []);
    $totalCategories = count($categoryPages ?? []);
    $totalAll = $totalStatic + $totalCms + $totalProducts + $totalCategories;
    ?>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-4 rounded-xl bg-white border border-[#e7dec8] shadow-xs">
            <span class="text-[10px] uppercase tracking-wider text-stone-500 font-bold block">Total Pages</span>
            <span class="text-xl font-display font-bold text-[#07160d] mt-1 block"><?= $totalAll ?></span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e7dec8] shadow-xs">
            <span class="text-[10px] uppercase tracking-wider text-blue-600 font-bold block">Static Core</span>
            <span class="text-xl font-display font-bold text-[#07160d] mt-1 block"><?= $totalStatic ?></span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e7dec8] shadow-xs">
            <span class="text-[10px] uppercase tracking-wider text-purple-600 font-bold block">Editorial CMS</span>
            <span class="text-xl font-display font-bold text-[#07160d] mt-1 block"><?= $totalCms ?></span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e7dec8] shadow-xs">
            <span class="text-[10px] uppercase tracking-wider text-amber-600 font-bold block">Products</span>
            <span class="text-xl font-display font-bold text-[#07160d] mt-1 block"><?= $totalProducts ?></span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e7dec8] shadow-xs col-span-2 sm:col-span-1">
            <span class="text-[10px] uppercase tracking-wider text-emerald-600 font-bold block">Categories</span>
            <span class="text-xl font-display font-bold text-[#07160d] mt-1 block"><?= $totalCategories ?></span>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0" id="page-tabs">
            <button type="button" data-filter="all" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-[#bc944c] text-[#07160d] shadow-xs">
                All (<?= $totalAll ?>)
            </button>
            <button type="button" data-filter="static" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 transition-all">
                Static (<?= $totalStatic ?>)
            </button>
            <button type="button" data-filter="cms" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 transition-all">
                CMS Pages (<?= $totalCms ?>)
            </button>
            <button type="button" data-filter="product" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 transition-all">
                Products (<?= $totalProducts ?>)
            </button>
            <button type="button" data-filter="category" class="tab-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 transition-all">
                Categories (<?= $totalCategories ?>)
            </button>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full md:w-72">
            <input type="text" id="page-search-input" placeholder="Search pages, URLs, meta..." class="w-full pl-9 pr-3.5 py-1.5 text-xs rounded-xl bg-stone-50 border border-[#d6c7af] text-[#1c1917] focus:bg-white focus:border-[#bc944c] focus:outline-none">
            <svg class="w-4 h-4 text-stone-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    <!-- Pages Table Card -->
    <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="pages-seo-table">
                <thead>
                    <tr class="border-b border-[#e7dec8] text-stone-500 uppercase tracking-wider text-[10px]">
                        <th class="pb-3 font-bold">Type</th>
                        <th class="pb-3 font-bold">Page Title / Target</th>
                        <th class="pb-3 font-bold">URL Permalink</th>
                        <th class="pb-3 font-bold">SEO Meta Title</th>
                        <th class="pb-3 font-bold">SEO Meta Description</th>
                        <th class="pb-3 font-bold">Status</th>
                        <th class="pb-3 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    <!-- 1. STATIC PAGES -->
                    <?php foreach ($staticPages as $sp): ?>
                        <?php 
                        $hasTitle = !empty($sp['meta_title']);
                        $hasDesc = !empty($sp['meta_description']);
                        $isOptimized = $hasTitle && $hasDesc;
                        ?>
                        <tr class="page-row hover:bg-[#faf8f5] transition-colors" 
                            data-type="static" 
                            data-title="<?= strtolower(e($sp['title'])) ?>" 
                            data-url="<?= strtolower(e($sp['path'])) ?>"
                            data-meta="<?= strtolower(e($sp['meta_title'] . ' ' . $sp['meta_description'])) ?>">
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">Static</span>
                            </td>
                            <td class="py-3.5 font-bold text-[#07160d]">
                                <?= e($sp['title']) ?>
                            </td>
                            <td class="py-3.5 font-mono text-[#bc944c]">
                                <a href="<?= e($sp['url']) ?>" target="_blank" class="hover:underline inline-flex items-center gap-1">
                                    <span><?= e($sp['path']) ?></span>
                                    <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </td>
                            <td class="py-3.5 max-w-xs truncate text-stone-800" title="<?= e($sp['meta_title']) ?>">
                                <div class="font-medium truncate"><?= e($sp['meta_title']) ?></div>
                                <span class="text-[10px] text-stone-400"><?= strlen($sp['meta_title']) ?> chars</span>
                            </td>
                            <td class="py-3.5 max-w-xs truncate text-stone-500" title="<?= e($sp['meta_description']) ?>">
                                <div class="truncate text-[11px]"><?= e($sp['meta_description']) ?></div>
                                <span class="text-[10px] text-stone-400"><?= strlen($sp['meta_description']) ?> chars</span>
                            </td>
                            <td class="py-3.5">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold <?= $isOptimized ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' ?>">
                                    <?= $isOptimized ? '✓ Optimized' : 'Needs Review' ?>
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <button type="button" 
                                        class="btn-open-seo-modal btn-ghost py-1 px-3 text-[11px] border border-[#d6c7af] hover:bg-white text-[#07160d] font-bold"
                                        data-type="static"
                                        data-id="<?= e($sp['key']) ?>"
                                        data-title="<?= e($sp['title']) ?>"
                                        data-url="<?= e($sp['path']) ?>"
                                        data-meta-title="<?= e($sp['meta_title']) ?>"
                                        data-meta-desc="<?= e($sp['meta_description']) ?>"
                                        data-meta-keywords="<?= e($sp['meta_keywords'] ?? '') ?>">
                                    Edit SEO
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- 2. DYNAMIC CMS EDITORIAL PAGES -->
                    <?php foreach ($cmsPages as $cp): ?>
                        <?php 
                        $hasTitle = !empty($cp['meta_title']);
                        $hasDesc = !empty($cp['meta_description']);
                        $isOptimized = $hasTitle && $hasDesc;
                        ?>
                        <tr class="page-row hover:bg-[#faf8f5] transition-colors" 
                            data-type="cms" 
                            data-title="<?= strtolower(e($cp['title'])) ?>" 
                            data-url="<?= strtolower(e($cp['slug'])) ?>"
                            data-meta="<?= strtolower(e(($cp['meta_title'] ?? '') . ' ' . ($cp['meta_description'] ?? ''))) ?>">
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 uppercase">CMS</span>
                            </td>
                            <td class="py-3.5 font-bold text-[#07160d]">
                                <?= e($cp['title']) ?>
                            </td>
                            <td class="py-3.5 font-mono text-[#bc944c]">
                                <a href="<?= url($cp['slug']) ?>" target="_blank" class="hover:underline inline-flex items-center gap-1">
                                    <span>/<?= e($cp['slug']) ?></span>
                                    <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </td>
                            <td class="py-3.5 max-w-xs truncate text-stone-800" title="<?= e($cp['meta_title'] ?? $cp['title']) ?>">
                                <div class="font-medium truncate"><?= e($cp['meta_title'] ?? $cp['title']) ?></div>
                                <span class="text-[10px] text-stone-400"><?= strlen($cp['meta_title'] ?? '') ?> chars</span>
                            </td>
                            <td class="py-3.5 max-w-xs truncate text-stone-500" title="<?= e($cp['meta_description'] ?? '') ?>">
                                <div class="truncate text-[11px]"><?= e($cp['meta_description'] ?: 'Default store description') ?></div>
                                <span class="text-[10px] text-stone-400"><?= strlen($cp['meta_description'] ?? '') ?> chars</span>
                            </td>
                            <td class="py-3.5">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold <?= $isOptimized ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' ?>">
                                    <?= $isOptimized ? '✓ Optimized' : 'Needs Review' ?>
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            class="btn-open-seo-modal btn-ghost py-1 px-2.5 text-[11px] border border-[#d6c7af] hover:bg-white text-[#07160d] font-bold"
                                            data-type="cms"
                                            data-id="<?= $cp['id'] ?>"
                                            data-title="<?= e($cp['title']) ?>"
                                            data-url="/<?= e($cp['slug']) ?>"
                                            data-meta-title="<?= e($cp['meta_title'] ?? '') ?>"
                                            data-meta-desc="<?= e($cp['meta_description'] ?? '') ?>">
                                        Edit SEO
                                    </button>
                                    <a href="<?= url('admin/pages/edit/' . $cp['id']) ?>" class="p-1 text-stone-400 hover:text-[#07160d]" title="Edit Content & Layout">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- 3. DYNAMIC PRODUCT PAGES -->
                    <?php foreach ($productPages as $prod): ?>
                        <?php 
                        $hasTitle = !empty($prod['meta_title']);
                        $hasDesc = !empty($prod['meta_description']);
                        $isOptimized = $hasTitle && $hasDesc;
                        ?>
                        <tr class="page-row hover:bg-[#faf8f5] transition-colors" 
                            data-type="product" 
                            data-title="<?= strtolower(e($prod['name'])) ?>" 
                            data-url="<?= strtolower(e($prod['slug'])) ?>"
                            data-meta="<?= strtolower(e(($prod['meta_title'] ?? '') . ' ' . ($prod['meta_description'] ?? ''))) ?>">
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase">Product</span>
                            </td>
                            <td class="py-3.5 font-bold text-[#07160d]">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($prod['featured_image'])): ?>
                                        <img src="<?= e($prod['featured_image']) ?>" class="w-6 h-6 object-contain rounded bg-white border border-stone-200">
                                    <?php endif; ?>
                                    <span><?= e($prod['name']) ?></span>
                                </div>
                            </td>
                            <td class="py-3.5 font-mono text-[#bc944c]">
                                <a href="<?= url('product/' . $prod['slug']) ?>" target="_blank" class="hover:underline inline-flex items-center gap-1">
                                    <span>/product/<?= e($prod['slug']) ?></span>
                                    <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </td>
                            <td class="py-3.5 max-w-xs truncate text-stone-800" title="<?= e($prod['meta_title'] ?? $prod['name']) ?>">
                                <div class="font-medium truncate"><?= e($prod['meta_title'] ?: ($prod['name'] . ' — Pure Heritage | Legacy Food')) ?></div>
                                <span class="text-[10px] text-stone-400"><?= strlen($prod['meta_title'] ?? '') ?> chars</span>
                            </td>
                            <td class="py-3.5 max-w-xs truncate text-stone-500" title="<?= e($prod['meta_description'] ?? '') ?>">
                                <div class="truncate text-[11px]"><?= e($prod['meta_description'] ?: 'Auto-generated product excerpt') ?></div>
                                <span class="text-[10px] text-stone-400"><?= strlen($prod['meta_description'] ?? '') ?> chars</span>
                            </td>
                            <td class="py-3.5">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold <?= $isOptimized ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' ?>">
                                    <?= $isOptimized ? '✓ Custom SEO' : 'Auto Dynamic' ?>
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            class="btn-open-seo-modal btn-ghost py-1 px-2.5 text-[11px] border border-[#d6c7af] hover:bg-white text-[#07160d] font-bold"
                                            data-type="product"
                                            data-id="<?= $prod['id'] ?>"
                                            data-title="<?= e($prod['name']) ?>"
                                            data-url="/product/<?= e($prod['slug']) ?>"
                                            data-meta-title="<?= e($prod['meta_title'] ?? '') ?>"
                                            data-meta-desc="<?= e($prod['meta_description'] ?? '') ?>">
                                        Edit SEO
                                    </button>
                                    <a href="<?= url('admin/products/edit/' . $prod['id']) ?>" class="p-1 text-stone-400 hover:text-[#07160d]" title="Full Product Editor">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- 4. DYNAMIC CATEGORY PAGES -->
                    <?php foreach ($categoryPages as $cat): ?>
                        <?php 
                        $hasTitle = !empty($cat['meta_title']);
                        $hasDesc = !empty($cat['meta_description']);
                        $isOptimized = $hasTitle && $hasDesc;
                        ?>
                        <tr class="page-row hover:bg-[#faf8f5] transition-colors" 
                            data-type="category" 
                            data-title="<?= strtolower(e($cat['name'])) ?>" 
                            data-url="<?= strtolower(e($cat['slug'])) ?>"
                            data-meta="<?= strtolower(e(($cat['meta_title'] ?? '') . ' ' . ($cat['meta_description'] ?? ''))) ?>">
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">Category</span>
                            </td>
                            <td class="py-3.5 font-bold text-[#07160d]">
                                <?= e($cat['name']) ?>
                            </td>
                            <td class="py-3.5 font-mono text-[#bc944c]">
                                <a href="<?= url('category/' . $cat['slug']) ?>" target="_blank" class="hover:underline inline-flex items-center gap-1">
                                    <span>/category/<?= e($cat['slug']) ?></span>
                                    <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </td>
                            <td class="py-3.5 max-w-xs truncate text-stone-800" title="<?= e($cat['meta_title'] ?? $cat['name']) ?>">
                                <div class="font-medium truncate"><?= e($cat['meta_title'] ?: ($cat['name'] . ' | Legacy Food')) ?></div>
                                <span class="text-[10px] text-stone-400"><?= strlen($cat['meta_title'] ?? '') ?> chars</span>
                            </td>
                            <td class="py-3.5 max-w-xs truncate text-stone-500" title="<?= e($cat['meta_description'] ?? '') ?>">
                                <div class="truncate text-[11px]"><?= e($cat['meta_description'] ?: 'Default category description') ?></div>
                                <span class="text-[10px] text-stone-400"><?= strlen($cat['meta_description'] ?? '') ?> chars</span>
                            </td>
                            <td class="py-3.5">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold <?= $isOptimized ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' ?>">
                                    <?= $isOptimized ? '✓ Custom SEO' : 'Auto Dynamic' ?>
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            class="btn-open-seo-modal btn-ghost py-1 px-2.5 text-[11px] border border-[#d6c7af] hover:bg-white text-[#07160d] font-bold"
                                            data-type="category"
                                            data-id="<?= $cat['id'] ?>"
                                            data-title="<?= e($cat['name']) ?>"
                                            data-url="/category/<?= e($cat['slug']) ?>"
                                            data-meta-title="<?= e($cat['meta_title'] ?? '') ?>"
                                            data-meta-desc="<?= e($cat['meta_description'] ?? '') ?>">
                                        Edit SEO
                                    </button>
                                    <a href="<?= url('admin/categories/edit/' . $cat['id']) ?>" class="p-1 text-stone-400 hover:text-[#07160d]" title="Edit Category Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Quick SEO Editor with Google Search Snippet Preview -->
<div id="seo-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 border border-[#e7dec8] shadow-2xl space-y-6 relative max-h-[90vh] overflow-y-auto">
        <!-- Close Button -->
        <button type="button" id="btn-close-seo-modal" class="absolute top-5 right-5 text-stone-400 hover:text-[#07160d] text-lg font-bold">✕</button>

        <!-- Modal Header -->
        <div>
            <span class="text-[10px] uppercase tracking-wider font-bold text-[#bc944c] px-2 py-0.5 rounded-full bg-[#faf8f5] border border-[#d6c7af]" id="modal-type-badge">STATIC PAGE</span>
            <h2 class="font-display text-xl sm:text-2xl font-bold text-[#07160d] mt-2" id="modal-page-title">Edit SEO Metadata</h2>
            <p class="text-xs text-stone-500 font-mono mt-0.5" id="modal-page-url">/path</p>
        </div>

        <!-- Live Google Search Result Snippet Simulation -->
        <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-1.5">
            <span class="text-[10px] uppercase tracking-wider text-stone-400 font-bold block mb-1">Google SERP Snippet Preview</span>
            <div class="text-[11px] text-[#202124] flex items-center gap-1.5 truncate">
                <span class="font-bold text-[#1a0dab]">legacyfood.in</span>
                <span class="text-stone-400">›</span>
                <span class="text-stone-600 font-mono" id="serp-preview-path">/</span>
            </div>
            <div class="text-sm font-medium text-[#1a0dab] hover:underline cursor-pointer truncate" id="serp-preview-title">
                Page Title
            </div>
            <div class="text-xs text-[#4d5156] line-clamp-2 leading-relaxed" id="serp-preview-desc">
                Page description preview appears here in Google search engine search result card.
            </div>
        </div>

        <!-- Form -->
        <form action="<?= url('admin/pages/update-seo') ?>" method="POST" id="seo-update-form" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="page_type" id="modal_page_type" value="">
            <input type="hidden" name="page_id" id="modal_page_id" value="">

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider">SEO Meta Title</label>
                    <span class="text-[10px] text-stone-400" id="title-char-counter">0 / 60 recommended</span>
                </div>
                <input type="text" name="meta_title" id="modal_meta_title" placeholder="e.g. Buy Pure A2 Cow Ghee Online | Legacy Food"
                       class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider">SEO Meta Description</label>
                    <span class="text-[10px] text-stone-400" id="desc-char-counter">0 / 160 recommended</span>
                </div>
                <textarea name="meta_description" id="modal_meta_desc" rows="3" placeholder="Crafted traditionally from pure butter in small batches..."
                          class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Meta Keywords (Optional)</label>
                <input type="text" name="meta_keywords" id="modal_meta_keywords" placeholder="ghee, A2 cow ghee, cold pressed oil"
                       class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
            </div>

            <div class="pt-2 flex items-center justify-end gap-3 border-t border-stone-100">
                <button type="button" id="btn-cancel-seo" class="btn-ghost py-2.5 px-4 text-xs font-bold text-stone-600">Cancel</button>
                <button type="submit" class="btn-primary py-2.5 px-5 text-xs font-bold shadow-md">Save SEO Metadata</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-btn');
    const searchInput = document.getElementById('page-search-input');
    const rows = document.querySelectorAll('.page-row');

    let currentFilter = 'all';

    function applyFilter() {
        const query = searchInput.value.trim().toLowerCase();

        rows.forEach(row => {
            const rowType = row.dataset.type;
            const rowTitle = row.dataset.title;
            const rowUrl = row.dataset.url;
            const rowMeta = row.dataset.meta;

            const matchesTab = (currentFilter === 'all' || rowType === currentFilter);
            const matchesQuery = !query || rowTitle.includes(query) || rowUrl.includes(query) || rowMeta.includes(query);

            if (matchesTab && matchesQuery) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.className = 'tab-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-stone-600 hover:bg-stone-100 transition-all';
            });
            this.className = 'tab-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-[#bc944c] text-[#07160d] shadow-xs';
            currentFilter = this.dataset.filter;
            applyFilter();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
    }

    // Modal Interaction
    const modal = document.getElementById('seo-modal');
    const btnCloseModal = document.getElementById('btn-close-seo-modal');
    const btnCancelSeo = document.getElementById('btn-cancel-seo');

    const modalTypeBadge = document.getElementById('modal-type-badge');
    const modalPageTitle = document.getElementById('modal-page-title');
    const modalPageUrl = document.getElementById('modal-page-url');
    const modalPageType = document.getElementById('modal_page_type');
    const modalPageId = document.getElementById('modal_page_id');
    const modalMetaTitle = document.getElementById('modal_meta_title');
    const modalMetaDesc = document.getElementById('modal_meta_desc');
    const modalMetaKeywords = document.getElementById('modal_meta_keywords');

    const serpTitle = document.getElementById('serp-preview-title');
    const serpPath = document.getElementById('serp-preview-path');
    const serpDesc = document.getElementById('serp-preview-desc');
    const titleCounter = document.getElementById('title-char-counter');
    const descCounter = document.getElementById('desc-char-counter');

    function updateSerpPreview() {
        const titleVal = modalMetaTitle.value.trim() || modalPageTitle.textContent;
        const descVal = modalMetaDesc.value.trim() || 'No custom description provided yet. Google will automatically generate snippet from page body.';
        serpTitle.textContent = titleVal;
        serpDesc.textContent = descVal;

        titleCounter.textContent = `${modalMetaTitle.value.length} / 60 recommended`;
        descCounter.textContent = `${modalMetaDesc.value.length} / 160 recommended`;

        titleCounter.className = modalMetaTitle.value.length > 60 ? 'text-[10px] text-amber-600 font-bold' : 'text-[10px] text-stone-400';
        descCounter.className = modalMetaDesc.value.length > 160 ? 'text-[10px] text-amber-600 font-bold' : 'text-[10px] text-stone-400';
    }

    modalMetaTitle.addEventListener('input', updateSerpPreview);
    modalMetaDesc.addEventListener('input', updateSerpPreview);

    document.querySelectorAll('.btn-open-seo-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            const id = this.dataset.id;
            const title = this.dataset.title;
            const url = this.dataset.url;
            const metaTitle = this.dataset.metaTitle || '';
            const metaDesc = this.dataset.metaDesc || '';
            const metaKeywords = this.dataset.metaKeywords || '';

            modalTypeBadge.textContent = type.toUpperCase() + ' PAGE';
            modalPageTitle.textContent = title;
            modalPageUrl.textContent = url;
            serpPath.textContent = url;

            modalPageType.value = type;
            modalPageId.value = id;
            modalMetaTitle.value = metaTitle;
            modalMetaDesc.value = metaDesc;
            modalMetaKeywords.value = metaKeywords;

            updateSerpPreview();
            modal.classList.remove('hidden');
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
    }

    if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
    if (btnCancelSeo) btnCancelSeo.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
});
</script>
