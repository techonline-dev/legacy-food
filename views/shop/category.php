<div class="py-8 md:py-12">
    <div class="container-x">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3">
            <a href="<?= url('/') ?>" class="hover:text-[#bc944c]">Home</a>
            <span>/</span>
            <a href="<?= url('shop') ?>" class="hover:text-[#bc944c]">Shop</a>
            <span>/</span>
            <span class="text-[#bc944c] font-medium"><?= e($category['name']) ?></span>
        </nav>

        <div class="mb-10 p-8 md:p-10 rounded-3xl bg-white border border-[#e7dec8] shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="eyebrow">Category Collection</span>
                <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold mt-1">
                    <?= e($category['name']) ?>
                </h1>
                <p class="text-xs md:text-sm text-stone-600 mt-2 max-w-xl leading-relaxed">
                    <?= e($category['description']) ?>
                </p>
            </div>
            <?php if (!empty($category['image'])): ?>
                <img src="<?= e($category['image']) ?>" alt="<?= e($category['name']) ?>" class="max-h-36 object-contain">
            <?php endif; ?>
        </div>

        <!-- Category Switcher Tabs -->
        <?php if (!empty($categories)): ?>
            <div class="flex flex-wrap gap-2 mb-8 p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
                <a href="<?= url('shop') ?>" class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all bg-stone-100 text-stone-700 hover:bg-[#bc944c]/15 hover:text-[#07160d]">
                    All Products
                </a>
                <?php foreach ($categories as $cat): ?>
                    <?php $isCurrent = ($cat['slug'] === $category['slug']); ?>
                    <a href="<?= url('category/' . $cat['slug']) ?>" class="px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all <?= $isCurrent ? 'bg-[#bc944c] text-[#07160d] shadow-sm font-bold' : 'bg-stone-100 text-stone-700 hover:bg-[#bc944c]/15 hover:text-[#07160d]' ?>" <?= $isCurrent ? 'aria-current="page"' : '' ?>>
                        <?= e($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($products as $prod): ?>
                <?php $product = $prod; include __DIR__ . '/../components/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
