<?php
/** @var array $product */
$defaultVariant = null;
if (!empty($product['variants'])) {
    foreach ($product['variants'] as $v) {
        if (!empty($v['is_default'])) {
            $defaultVariant = $v;
            break;
        }
    }
    if (!$defaultVariant) {
        $defaultVariant = $product['variants'][0];
    }
}

$displayPrice = $defaultVariant ? (float)$defaultVariant['price'] : (float)$product['base_price'];
$displayImage = ($defaultVariant && !empty($defaultVariant['image'])) ? $defaultVariant['image'] : $product['featured_image'];
$defaultVariantId = $defaultVariant ? $defaultVariant['id'] : '';
?>

<div class="card-glow product-variant-container group relative flex flex-col justify-between overflow-hidden rounded-2xl p-4 md:p-6 transition-all duration-300 bg-white border border-[#e7dec8] hover:border-[#bc944c]/60 shadow-sm hover:shadow-xl hover:shadow-stone-200/50">
    <!-- Top Badges & Wishlist -->
    <div class="flex items-center justify-between mb-2 relative z-10">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#faf8f5] border border-[#d6c7af] text-[#07160d]">
            <?= e($product['category_name'] ?? 'Heritage') ?>
        </span>
        <button data-product-id="<?= $product['id'] ?>" class="wishlist-toggle-btn p-1.5 rounded-full bg-stone-50 hover:bg-[#bc944c]/20 border border-stone-200 text-stone-600 hover:text-[#bc944c] transition-all" title="Save to Wishlist">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </button>
    </div>

    <!-- Product Image -->
    <a href="<?= url('product/' . $product['slug']) ?>" class="relative my-3 flex h-40 md:h-52 items-center justify-center overflow-hidden">
        <img src="<?= e($displayImage) ?>" alt="<?= e($product['name']) ?>" loading="lazy" class="product-main-image max-h-36 md:max-h-48 w-auto object-contain transition-transform duration-500 group-hover:scale-105 drop-shadow-[0_8px_16px_rgba(0,0,0,0.1)]">
    </a>

    <!-- Details -->
    <div class="relative z-10 flex-1 flex flex-col justify-between">
        <div>
            <!-- Star Ratings -->
            <div class="flex items-center gap-1.5 mb-1.5">
                <div class="flex text-amber-500">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <?php endfor; ?>
                </div>
                <span class="text-[11px] font-bold text-[#bc944c]"><?= number_format((float)$product['rating_cache'], 1) ?></span>
                <span class="text-[10px] text-stone-500">(<?= (int)$product['review_count_cache'] ?>)</span>
            </div>

            <!-- Product Title -->
            <h3 class="font-display text-base md:text-lg text-[#07160d] group-hover:text-[#bc944c] transition-colors leading-snug font-bold">
                <a href="<?= url('product/' . $product['slug']) ?>">
                    <?= e($product['name']) ?>
                </a>
            </h3>

            <!-- Short Description -->
            <p class="mt-1 text-xs text-stone-600 line-clamp-2 leading-relaxed">
                <?= e($product['short_description'] ?? '') ?>
            </p>
        </div>

        <!-- Variant Pills Selector -->
        <?php if (!empty($product['variants']) && count($product['variants']) > 1): ?>
            <div class="mt-3 flex flex-wrap gap-1.5">
                <?php foreach ($product['variants'] as $idx => $v): ?>
                    <?php $isActive = ($v['id'] == $defaultVariantId); ?>
                    <button type="button"
                            class="variant-selector-btn px-2.5 py-1 text-[11px] font-medium rounded-lg border transition-all <?= $isActive ? 'bg-[#bc944c] text-[#07160d] border-[#bc944c] font-bold shadow-sm' : 'bg-stone-50 text-stone-700 border-stone-200 hover:border-[#bc944c]' ?>"
                            data-variant-id="<?= $v['id'] ?>"
                            data-price="<?= $v['price'] ?>"
                            data-sku="<?= e($v['sku']) ?>"
                            data-image="<?= e($v['image'] ?: $product['featured_image']) ?>">
                        <?= e($v['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Hidden input for variant selection -->
        <input type="hidden" class="selected-variant-input" value="<?= $defaultVariantId ?>">

        <!-- Pricing & Action Buttons -->
        <div class="mt-4 pt-3 border-t border-[#e7dec8] flex items-center justify-between gap-2">
            <div>
                <span class="text-[10px] text-[#bc944c] font-semibold uppercase tracking-wider block">Price</span>
                <span class="product-price-display font-display text-base md:text-xl font-bold text-[#07160d]">
                    <?= currency_format($displayPrice) ?>
                </span>
            </div>

            <div class="flex items-center gap-1.5">
                <!-- Quick Add To Cart Button -->
                <button type="button" onclick="const vid = this.closest('.product-variant-container').querySelector('.selected-variant-input').value; addToCart(<?= $product['id'] ?>, vid, 1);" class="h-9 w-9 rounded-full border border-[#bc944c]/50 bg-[#faf8f5] text-[#07160d] hover:bg-[#bc944c] hover:text-[#07160d] flex items-center justify-center transition-all shadow-sm" title="Add to Cart">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </button>

                <!-- Buy Now Button -->
                <a href="<?= url('product/' . $product['slug']) ?>" class="btn-gold text-[11px] py-2 px-3 shadow-sm">
                    Buy Now
                </a>
            </div>
        </div>
    </div>
</div>
