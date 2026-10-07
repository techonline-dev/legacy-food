<div class="py-8 md:py-14">
    <div class="container-x">
        <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold mb-6">
            My Wishlist
        </h1>

        <?php if (\App\Core\Auth::check()): ?>
            <?php include __DIR__ . '/../components/account_tabs.php'; ?>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <div class="bg-white border border-[#e7dec8] p-12 rounded-3xl text-center max-w-md mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-full bg-[#bc944c]/10 text-[#bc944c] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="font-display text-xl text-[#07160d] font-bold">Your wishlist is empty</h3>
                <p class="text-xs text-stone-500 mt-1">Tap the heart icon on any product to save it here for later.</p>
                <a href="<?= url('shop') ?>" class="btn-gold mt-6 inline-flex text-xs py-2.5 px-6 font-bold shadow-sm">Explore Products</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($items as $prod): ?>
                    <div class="bg-white border border-[#e7dec8] p-5 rounded-2xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                        <div class="relative mb-3 flex h-40 items-center justify-center">
                            <img src="<?= e($prod['featured_image']) ?>" alt="<?= e($prod['name']) ?>" class="max-h-36 object-contain">
                        </div>

                        <div>
                            <span class="text-[10px] text-[#bc944c] uppercase tracking-wider block font-bold"><?= e($prod['category_name']) ?></span>
                            <h4 class="font-display text-base font-bold text-[#07160d] mt-0.5">
                                <a href="<?= url('product/' . $prod['slug']) ?>" class="hover:text-[#bc944c]"><?= e($prod['name']) ?></a>
                            </h4>
                            <div class="font-display text-base font-bold text-[#07160d] mt-2">
                                <?= currency_format($prod['base_price']) ?>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-stone-100 flex items-center gap-2">
                            <form action="<?= url('wishlist/move-to-cart') ?>" method="POST" class="flex-1">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                <button type="submit" class="btn-gold w-full text-[11px] py-2 font-bold shadow-sm">Move to Cart</button>
                            </form>

                            <button onclick="fetch('<?= url('wishlist/toggle') ?>', {method: 'POST', body: new URLSearchParams({product_id: <?= $prod['id'] ?>})}).then(() => window.location.reload())" class="p-2 text-xs text-stone-400 hover:text-red-500 border border-stone-200 rounded-xl hover:border-red-400 transition-colors" title="Remove">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
