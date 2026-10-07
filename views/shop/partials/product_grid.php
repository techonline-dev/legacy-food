<?php if (empty($products)): ?>
    <div class="col-span-full py-16 text-center card-glow p-8 rounded-3xl">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#bc944c]/10 flex items-center justify-center text-[#bc944c]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <h3 class="font-display text-xl text-[#fdf6e3]">No products found</h3>
        <p class="text-xs text-[#fdf6e3]/60 mt-1 max-w-sm mx-auto">Try clearing your filters or searching with a different term.</p>
        <a href="<?= url('shop') ?>" class="btn-gold mt-6 inline-flex text-xs py-2 px-6">Clear All Filters</a>
    </div>
<?php else: ?>
    <?php foreach ($products as $prod): ?>
        <?php $product = $prod; include __DIR__ . '/../../components/product_card.php'; ?>
    <?php endforeach; ?>
<?php endif; ?>
