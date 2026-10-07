<?php
/** @var array $product */
/** @var array $relatedProducts */
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
$displaySku = $defaultVariant ? $defaultVariant['sku'] : $product['sku'];
$displayStock = $defaultVariant ? (int)$defaultVariant['stock_quantity'] : (int)$product['stock_quantity'];
$defaultVariantId = $defaultVariant ? $defaultVariant['id'] : '';
$productUrl = url('product/' . $product['slug']);
$whatsappEnquire = "https://wa.me/919845279936?text=" . urlencode("Hi Legacy, I'd like to enquire about {$product['name']} ({$productUrl})");
?>

<div class="py-8 md:py-14 product-variant-container">
    <div class="container-x">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-6">
            <a href="<?= url('/') ?>" class="hover:text-[#bc944c]">Home</a>
            <span>/</span>
            <a href="<?= url('shop') ?>" class="hover:text-[#bc944c]">Shop</a>
            <span>/</span>
            <?php if (!empty($product['category_slug'])): ?>
                <a href="<?= url('category/' . $product['category_slug']) ?>" class="hover:text-[#bc944c]"><?= e($product['category_name']) ?></a>
                <span>/</span>
            <?php endif; ?>
            <span class="text-[#bc944c] font-medium truncate max-w-xs"><?= e($product['name']) ?></span>
        </nav>

        <!-- Main Product Section: Gallery + Purchase Form -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">
            <!-- Left: Product Image Gallery -->
            <div class="space-y-4">
                <div class="bg-white border border-[#e7dec8] rounded-3xl p-6 md:p-10 flex items-center justify-center relative overflow-hidden shadow-sm">
                    <img id="main-product-img" src="<?= e($product['featured_image']) ?>" alt="<?= e($product['name']) ?>" class="product-main-image max-h-[380px] md:max-h-[460px] w-auto object-contain transition-transform duration-500 hover:scale-105 drop-shadow-[0_16px_30px_rgba(0,0,0,0.12)]">
                </div>

                <!-- Thumbnails Gallery if multiple images exist -->
                <?php if (!empty($product['images']) && count($product['images']) > 1): ?>
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        <?php foreach ($product['images'] as $img): ?>
                            <button onclick="document.getElementById('main-product-img').src = '<?= e($img['image_url']) ?>'" class="w-16 h-16 rounded-2xl border border-[#d6c7af] p-1.5 bg-white hover:border-[#bc944c] shadow-sm shrink-0">
                                <img src="<?= e($img['image_url']) ?>" class="w-full h-full object-contain">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Product Overview & Buy Box -->
            <div class="flex flex-col">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#faf8f5] text-[#bc944c] border border-[#d6c7af]">
                        <?= e($product['category_name'] ?? 'Pure Heritage') ?>
                    </span>
                    <button data-product-id="<?= $product['id'] ?>" class="wishlist-toggle-btn flex items-center gap-1.5 text-xs text-stone-600 hover:text-[#bc944c] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>Save to Wishlist</span>
                    </button>
                </div>

                <h1 class="font-display text-2xl sm:text-4xl text-[#07160d] mt-3 font-bold">
                    <?= e($product['name']) ?>
                </h1>

                <!-- Rating and SKU -->
                <div class="flex items-center gap-4 mt-3 pb-4 border-b border-[#e7dec8] text-xs">
                    <div class="flex items-center gap-1 text-amber-500">
                        <div class="flex">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                        </div>
                        <span class="font-bold text-[#bc944c]"><?= number_format((float)$product['rating_cache'], 1) ?></span>
                        <a href="#reviews" class="text-stone-500 hover:underline">(<?= count($product['reviews']) ?> reviews)</a>
                    </div>
                    <span class="text-stone-300">|</span>
                    <span class="text-stone-500">SKU: <span id="product-sku-display" class="font-mono text-[#07160d] font-semibold"><?= e($displaySku) ?></span></span>
                </div>

                <!-- Price and Stock -->
                <div class="my-5 flex items-baseline gap-4">
                    <span class="product-price-display font-display text-3xl md:text-4xl font-bold text-[#07160d]">
                        <?= currency_format($displayPrice) ?>
                    </span>
                    <span class="text-xs text-emerald-700 font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-300">
                        In Stock & Ready to Ship
                    </span>
                </div>

                <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                    <?= e($product['short_description']) ?>
                </p>

                <!-- Variant Selector (e.g. 250 ml, 500 ml, 1 L) -->
                <?php if (!empty($product['variants'])): ?>
                    <div class="mt-6">
                        <label class="block text-xs uppercase tracking-wider text-[#bc944c] font-bold mb-2">
                            Select Size / Quantity
                        </label>
                        <div class="flex flex-wrap gap-2.5">
                            <?php foreach ($product['variants'] as $v): ?>
                                <?php $isActive = ($v['id'] == $defaultVariantId); ?>
                                <button type="button"
                                        class="variant-selector-btn px-4 py-2.5 text-xs font-semibold rounded-xl border transition-all <?= $isActive ? 'bg-[#bc944c] text-[#07160d] border-[#bc944c] font-bold shadow-sm' : 'bg-stone-50 text-stone-700 border-stone-200 hover:border-[#bc944c]' ?>"
                                        data-variant-id="<?= $v['id'] ?>"
                                        data-price="<?= $v['price'] ?>"
                                        data-sku="<?= e($v['sku']) ?>"
                                        data-image="<?= e($v['image'] ?: $product['featured_image']) ?>">
                                    <?= e($v['name']) ?> · ₹<?= number_format($v['price'], 0) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <input type="hidden" class="selected-variant-input" value="<?= $defaultVariantId ?>">

                <!-- Quantity & Add to Cart Controls -->
                <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <!-- Quantity Picker -->
                    <div class="flex items-center justify-between border border-[#d6c7af] rounded-full bg-white px-3 py-2 w-32 shadow-sm">
                        <button type="button" onclick="const q = document.getElementById('detail-qty'); q.value = Math.max(1, parseInt(q.value) - 1);" class="w-8 h-8 rounded-full text-base text-stone-600 hover:text-[#07160d] flex items-center justify-center font-bold">-</button>
                        <input type="number" id="detail-qty" value="1" min="1" max="50" class="w-10 text-center bg-transparent font-bold text-sm text-[#07160d] focus:outline-none">
                        <button type="button" onclick="const q = document.getElementById('detail-qty'); q.value = parseInt(q.value) + 1;" class="w-8 h-8 rounded-full text-base text-stone-600 hover:text-[#07160d] flex items-center justify-center font-bold">+</button>
                    </div>

                    <!-- Add to Cart Button -->
                    <button type="button" onclick="const vid = document.querySelector('.selected-variant-input').value; const qty = parseInt(document.getElementById('detail-qty').value); addToCart(<?= $product['id'] ?>, vid, qty);" class="btn-ghost flex-1 py-3.5 text-xs sm:text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Add to Cart</span>
                    </button>

                    <!-- Buy Now Button -->
                    <button type="button" onclick="const vid = document.querySelector('.selected-variant-input').value; const qty = parseInt(document.getElementById('detail-qty').value); buyNow(<?= $product['id'] ?>, vid, qty);" class="btn-gold flex-1 py-3.5 text-xs sm:text-sm shadow-md">
                        <span>Buy Now</span>
                    </button>
                </div>

                <!-- WhatsApp Enquire Link -->
                <div class="mt-4 text-center sm:text-left">
                    <a href="<?= $whatsappEnquire ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0d8036] hover:underline">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Have questions? Enquire on WhatsApp</span>
                    </a>
                </div>

                <!-- Quality Guarantees Badges -->
                <div class="mt-8 pt-6 border-t border-[#e7dec8] grid grid-cols-2 gap-3 text-xs text-stone-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>NABL Accredited Lab Tested</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Tamper-Proof Glass Packaging</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Free Shipping Over ₹999</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Cash on Delivery & UPI Accepted</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information Tabs: Description, Ingredients, Benefits, Specifications -->
        <div class="mt-16 md:mt-24 pt-12 border-t border-[#e7dec8]">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Main Editorial Story -->
                <div class="md:col-span-2 space-y-8">
                    <div>
                        <h3 class="font-display text-xl text-[#07160d] font-bold border-b border-[#e7dec8] pb-3 mb-4">
                            Product Description
                        </h3>
                        <div class="text-xs md:text-sm text-stone-700 leading-relaxed space-y-4">
                            <?= $product['description'] ?>
                        </div>
                    </div>

                    <?php if (!empty($product['benefits'])): ?>
                        <div>
                            <h3 class="font-display text-xl text-[#07160d] font-bold border-b border-[#e7dec8] pb-3 mb-4">
                                Health Benefits & Heritage Craft
                            </h3>
                            <p class="text-xs md:text-sm text-stone-700 leading-relaxed">
                                <?= nl2br(e($product['benefits'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($product['ingredients'])): ?>
                        <div>
                            <h3 class="font-display text-xl text-[#07160d] font-bold border-b border-[#e7dec8] pb-3 mb-4">
                                Ingredients & Purity Statement
                            </h3>
                            <p class="text-xs md:text-sm text-stone-700 leading-relaxed">
                                <?= e($product['ingredients']) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Specifications Table Sidebar -->
                <div class="bg-white border border-[#e7dec8] p-6 rounded-3xl h-fit shadow-sm">
                    <h3 class="font-display text-lg text-[#07160d] font-bold border-b border-[#e7dec8] pb-3 mb-4">
                        Specifications
                    </h3>
                    <div class="space-y-3 text-xs">
                        <?php if (is_array($product['specifications'])): ?>
                            <?php foreach ($product['specifications'] as $key => $val): ?>
                                <div class="flex justify-between py-1.5 border-b border-stone-100">
                                    <span class="text-stone-500"><?= e($key) ?></span>
                                    <span class="font-medium text-[#07160d] text-right"><?= e($val) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="flex justify-between py-1.5 border-b border-stone-100">
                            <span class="text-stone-500">Country of Origin</span>
                            <span class="font-medium text-[#07160d]">India</span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-stone-500">Preservatives</span>
                            <span class="font-bold text-[#bc944c]">0% (Zero Added)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Reviews Section -->
        <div id="reviews" class="mt-16 md:mt-24 pt-12 border-t border-[#e7dec8]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h3 class="font-display text-2xl text-[#07160d] font-bold">
                        Customer Reviews
                    </h3>
                    <p class="text-xs text-stone-500 mt-1">Verified reviews from authentic Legacy Food patrons.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-display text-3xl font-bold text-[#bc944c]"><?= number_format((float)$product['rating_cache'], 1) ?></span>
                    <span class="text-xs text-stone-500">out of 5 stars</span>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <?php if (empty($product['reviews'])): ?>
                    <div class="col-span-full py-8 text-center text-xs text-stone-500">
                        Be the first to review this product!
                    </div>
                <?php else: ?>
                    <?php foreach ($product['reviews'] as $rev): ?>
                        <div class="bg-white border border-[#e7dec8] p-5 rounded-2xl shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex text-amber-500">
                                    <?php for ($i = 0; $i < (int)$rev['rating']; $i++): ?>
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-[11px] text-stone-400"><?= date('d M Y', strtotime($rev['created_at'])) ?></span>
                            </div>
                            <?php if (!empty($rev['title'])): ?>
                                <h5 class="text-sm font-bold text-[#07160d]"><?= e($rev['title']) ?></h5>
                            <?php endif; ?>
                            <p class="text-xs text-stone-600 mt-1 leading-relaxed"><?= e($rev['review_text']) ?></p>
                            <div class="mt-3 text-[11px] text-[#bc944c] font-semibold flex items-center gap-1.5">
                                <span><?= e($rev['customer_name']) ?></span>
                                <?php if (!empty($rev['is_verified_purchase'])): ?>
                                    <span class="text-emerald-700">✓ Verified Buyer</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Submit Review Form -->
            <div class="bg-white border border-[#e7dec8] p-6 md:p-8 rounded-3xl max-w-xl mx-auto shadow-sm">
                <h4 class="font-display text-lg text-[#07160d] font-bold mb-4">Write a Product Review</h4>
                <form action="<?= url('reviews/store') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-stone-700 font-semibold mb-1">Your Name</label>
                            <input type="text" name="customer_name" required value="<?= auth_check() ? e(auth_user()['name']) : '' ?>" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                        </div>
                        <div>
                            <label class="block text-xs text-stone-700 font-semibold mb-1">Rating</label>
                            <select name="rating" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                                <option value="5">5 Stars — Exceptional Purity</option>
                                <option value="4">4 Stars — Very Good</option>
                                <option value="3">3 Stars — Average</option>
                                <option value="2">2 Stars — Below Expectations</option>
                                <option value="1">1 Star — Poor</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-stone-700 font-semibold mb-1">Review Title</label>
                        <input type="text" name="title" placeholder="e.g. Unmatched aroma and grainy texture" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                    </div>

                    <div>
                        <label class="block text-xs text-stone-700 font-semibold mb-1">Your Review</label>
                        <textarea name="review_text" rows="3" required placeholder="Describe the aroma, texture, and cooking experience..." class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]"></textarea>
                    </div>

                    <button type="submit" class="btn-gold w-full text-xs py-3 font-bold shadow-md">
                        Submit Review
                    </button>
                </form>
            </div>
        </div>

        <!-- Related Products Grid -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="mt-16 md:mt-24 pt-12 border-t border-[#e7dec8]">
                <div class="mb-8">
                    <span class="eyebrow">Pair With</span>
                    <h3 class="font-display text-2xl text-[#07160d] mt-1 font-bold">Related Heritage Items</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <?php foreach ($relatedProducts as $rel): ?>
                        <?php $product = $rel; include __DIR__ . '/../components/product_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
