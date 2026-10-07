<div class="py-8 md:py-12">
    <div class="container-x">
        <!-- Breadcrumb & Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3">
                <a href="<?= url('/') ?>" class="hover:text-[#bc944c]">Home</a>
                <span>/</span>
                <span class="text-[#bc944c] font-medium">Shop</span>
                <?php if ($currentCategory): ?>
                    <span>/</span>
                    <span class="text-[#07160d] font-medium"><?= e($currentCategory['name']) ?></span>
                <?php endif; ?>
            </nav>
            <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold">
                <?= $currentCategory ? e($currentCategory['name']) : 'All Heritage Products' ?>
            </h1>
            <p class="text-xs md:text-sm text-stone-600 mt-2 max-w-xl">
                <?= $currentCategory ? e($currentCategory['description']) : 'Traditional South Indian farm ghee and wood-pressed oils. Every batch tested for zero adulteration.' ?>
            </p>
        </div>

        <!-- Filter Bar & Sorting Row -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-sm mb-8">
            <!-- Category Tabs -->
            <div class="flex flex-wrap gap-2">
                <a href="<?= url('shop') ?>" class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all <?= empty($filters['category']) ? 'bg-[#bc944c] text-[#07160d] shadow-sm font-bold' : 'bg-stone-100 text-stone-700 hover:bg-[#bc944c]/15 hover:text-[#07160d]' ?>">
                    All Products
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= url('shop?category=' . $cat['slug']) ?>" class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all <?= ($filters['category'] ?? '') === $cat['slug'] ? 'bg-[#bc944c] text-[#07160d] shadow-sm font-bold' : 'bg-stone-100 text-stone-700 hover:bg-[#bc944c]/15 hover:text-[#07160d]' ?>">
                        <?= e($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Sorting & Search Form -->
            <form id="shop-filter-form" method="GET" action="<?= url('shop') ?>" class="flex items-center gap-3">
                <?php if (!empty($filters['category'])): ?>
                    <input type="hidden" name="category" value="<?= e($filters['category']) ?>">
                <?php endif; ?>

                <div class="relative">
                    <select name="sort" onchange="this.form.submit()" class="pl-3 pr-8 py-2 rounded-xl text-xs bg-white border border-[#d6c7af] text-[#07160d] focus:outline-none focus:border-[#bc944c] cursor-pointer">
                        <option value="featured" <?= ($filters['sort'] ?? '') === 'featured' ? 'selected' : '' ?>>Sort: Featured</option>
                        <option value="price_low" <?= ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_high" <?= ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="bestseller" <?= ($filters['sort'] ?? '') === 'bestseller' ? 'selected' : '' ?>>Best Selling</option>
                        <option value="rating" <?= ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' ?>>Top Rated</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div id="shop-products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php include __DIR__ . '/partials/product_grid.php'; ?>
        </div>
    </div>
</div>
