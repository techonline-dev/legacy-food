<div class="py-8 md:py-14">
    <div class="container-x">
        <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold mb-8">
            Shopping Cart
        </h1>

        <?php if (empty($cart['items'])): ?>
            <div class="bg-white border border-[#e7dec8] p-12 rounded-3xl text-center max-w-lg mx-auto shadow-sm">
                <div class="w-20 h-20 rounded-full bg-[#bc944c]/10 text-[#bc944c] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h3 class="font-display text-2xl text-[#07160d] font-bold">Your cart is currently empty</h3>
                <p class="text-xs md:text-sm text-stone-500 mt-2">
                    Explore our hand-churned South Indian butter ghee and cold-pressed oils.
                </p>
                <a href="<?= url('shop') ?>" class="btn-gold mt-6 inline-flex text-xs py-3 px-8 shadow-sm">
                    Discover Our Products
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Items Table / List -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Free Shipping Bar Notice -->
                    <div class="p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-sm text-xs flex items-center justify-between">
                        <?php if ($cart['subtotal'] >= $cart['free_shipping_threshold']): ?>
                            <span class="text-emerald-700 font-semibold flex items-center gap-1.5">
                                <span>🎉</span> You qualify for <strong>FREE DELIVERY</strong>!
                            </span>
                        <?php else: ?>
                            <span class="text-stone-700">
                                Add <strong class="text-[#bc944c]"><?= currency_format($cart['amount_needed_for_free_shipping']) ?></strong> more to unlock <strong>FREE DELIVERY</strong>!
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Items Card -->
                    <div class="bg-white border border-[#e7dec8] rounded-3xl p-6 shadow-sm overflow-hidden">
                        <div class="divide-y divide-stone-100">
                            <?php foreach ($cart['items'] as $item): ?>
                                <div class="py-5 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" class="w-16 h-16 object-contain rounded-xl bg-[#faf8f5] p-1 border border-[#e7dec8] shrink-0">
                                        <div>
                                            <h4 class="font-display text-base font-bold text-[#07160d]">
                                                <a href="<?= url('product/' . $item['slug']) ?>" class="hover:text-[#bc944c]">
                                                    <?= e($item['name']) ?>
                                                </a>
                                            </h4>
                                            <span class="text-xs text-[#bc944c] font-semibold block mt-0.5">
                                                <?= e($item['variant_name'] ?: 'Standard') ?> · <?= $item['unit_price_formatted'] ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between w-full sm:w-auto gap-6">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center border border-[#d6c7af] rounded-full bg-white px-2 py-1 shadow-sm">
                                            <button onclick="updateCartQty('<?= $item['key'] ?>', <?= $item['quantity'] - 1 ?>)" class="w-6 h-6 text-sm text-stone-600 hover:text-[#07160d] font-bold flex items-center justify-center">-</button>
                                            <span class="w-8 text-center text-xs font-bold text-[#07160d]"><?= $item['quantity'] ?></span>
                                            <button onclick="updateCartQty('<?= $item['key'] ?>', <?= $item['quantity'] + 1 ?>)" class="w-6 h-6 text-sm text-stone-600 hover:text-[#07160d] font-bold flex items-center justify-center">+</button>
                                        </div>

                                        <div class="text-right">
                                            <div class="font-display font-bold text-base text-[#07160d]">
                                                <?= $item['total_price_formatted'] ?>
                                            </div>
                                            <button onclick="removeFromCart('<?= $item['key'] ?>')" class="text-[11px] text-red-500 hover:underline mt-0.5">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary & Coupon -->
                <div class="space-y-4">
                    <!-- Coupon Card -->
                    <div class="bg-white border border-[#e7dec8] rounded-3xl p-6 shadow-sm space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs uppercase tracking-wider text-[#07160d] font-bold">
                                Promotional Coupon
                            </label>
                            <?php if (!empty($cart['coupon_code'])): ?>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    ✓ Applied: <?= e($cart['coupon_code']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($cart['coupon_code'])): ?>
                            <div class="p-3 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 flex items-center justify-between">
                                <div class="text-xs">
                                    <div class="font-bold text-emerald-800">Coupon <?= e($cart['coupon_code']) ?></div>
                                    <div class="text-[11px] text-emerald-600">Saved <?= $cart['discount_formatted'] ?> on this order</div>
                                </div>
                                <button type="button" onclick="removeCoupon()" class="px-3 py-1.5 text-xs font-semibold bg-white text-red-600 border border-red-200 rounded-xl hover:bg-red-50 shadow-2xs">
                                    Remove
                                </button>
                            </div>
                        <?php else: ?>
                            <form id="coupon-form" onsubmit="event.preventDefault(); applyCoupon();" class="flex gap-2">
                                <input type="text" id="coupon-code-input" placeholder="e.g. WELCOME10" class="flex-1 px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 uppercase font-mono focus:outline-none focus:border-[#bc944c]">
                                <button type="submit" class="btn-ghost text-xs px-4 py-2.5 font-bold">
                                    Apply
                                </button>
                            </form>

                            <?php if (!empty($cart['available_coupons'])): ?>
                                <div class="pt-2 border-t border-[#f0e8d8] space-y-2">
                                    <span class="text-[10px] uppercase tracking-wider text-stone-500 font-bold block">Available Offers:</span>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($cart['available_coupons'] as $ac): ?>
                                            <?php 
                                            $cType = $ac['type'] ?? ($ac['discount_type'] ?? 'fixed');
                                            $cVal = (float)($ac['value'] ?? ($ac['discount_value'] ?? 0));
                                            ?>
                                            <button type="button" onclick="applyCoupon('<?= e($ac['code']) ?>')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#faf8f5] hover:bg-amber-50 border border-[#d6c7af] hover:border-[#bc944c] text-[11px] text-[#07160d] font-mono transition-colors text-left" title="Click to apply <?= e($ac['code']) ?>">
                                                <span class="font-bold text-[#bc944c]"><?= e($ac['code']) ?></span>
                                                <span class="text-stone-500 font-sans text-[10px]">
                                                    (<?= $cType === 'percentage' ? ($cVal . '% off') : ('₹' . number_format($cVal, 0) . ' off') ?>)
                                                </span>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Summary Card -->
                    <div class="bg-white border border-[#e7dec8] rounded-3xl p-6 shadow-sm space-y-4">
                        <h3 class="font-display text-lg text-[#07160d] font-bold border-b border-[#e7dec8] pb-3">
                            Order Summary
                        </h3>

                        <div class="space-y-2 text-xs text-stone-600">
                            <div class="flex justify-between">
                                <span>Cart Subtotal</span>
                                <span class="font-semibold text-[#07160d]"><?= $cart['subtotal_formatted'] ?></span>
                            </div>

                            <?php if ($cart['discount'] > 0): ?>
                                <div class="flex justify-between text-emerald-700">
                                    <span>Promotional Discount (<?= e($cart['coupon_code']) ?>)</span>
                                    <span>- <?= $cart['discount_formatted'] ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="flex justify-between">
                                <span>Estimated Shipping</span>
                                <span><?= $cart['shipping_formatted'] ?></span>
                            </div>

                            <?php if ($cart['tax'] > 0): ?>
                                <div class="flex justify-between">
                                    <span>Estimated GST <?= (float)($cart['tax_rate'] ?? 5) ?>% (<?= !empty($cart['tax_inclusive']) ? 'Included' : 'Added' ?>)</span>
                                    <span class="font-medium text-[#07160d]"><?= $cart['tax_formatted'] ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="pt-3 border-t border-[#e7dec8] flex justify-between items-baseline text-base font-bold text-[#07160d]">
                                <span class="font-display">Grand Total</span>
                                <span class="font-display text-xl text-[#07160d]"><?= $cart['total_formatted'] ?></span>
                            </div>
                        </div>

                        <a href="<?= url('checkout') ?>" class="btn-gold w-full py-3.5 text-xs uppercase tracking-wider font-bold block text-center shadow-md">
                            Proceed to Checkout
                        </a>

                        <?php if (!\App\Core\Auth::check()): ?>
                            <div class="mt-3 p-2.5 rounded-xl bg-[#faf8f5] border border-[#e7dec8] text-center text-[11px] text-stone-600">
                                <span>Have an account?</span>
                                <a href="<?= url('login?redirect=checkout') ?>" class="text-[#bc944c] font-bold hover:underline ml-1">Sign In</a>
                                <span class="text-stone-500 block sm:inline">to sync cart & autofill address</span>
                            </div>
                        <?php endif; ?>

                        <div class="pt-2 text-center">
                            <a href="<?= url('shop') ?>" class="text-xs text-stone-500 hover:text-[#bc944c]">
                                ← Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
