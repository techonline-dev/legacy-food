<!-- Include Razorpay Checkout JS for online payment popup -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<div class="py-8 md:py-14">
    <div class="container-x">
        <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold mb-8">
            Complete Your Order
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Left: Checkout Steps Form -->
            <div class="lg:col-span-2">
                <form id="checkout-form" class="space-y-8">
                    <?= csrf_field() ?>

                    <?php if (!\App\Core\Auth::check()): ?>
                        <!-- Quick Sign In Banner for Returning Customers -->
                        <div class="p-4 rounded-2xl bg-amber-50 border border-[#d6c7af] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-3 text-[#07160d]">
                                <div class="w-8 h-8 rounded-full bg-[#bc944c]/20 text-[#bc944c] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold text-[#07160d]">Already have an account?</span>
                                    <p class="text-[11px] text-stone-600 mt-0.5">Sign in to auto-fill saved addresses and keep this order in your customer portal.</p>
                                </div>
                            </div>
                            <a href="<?= url('login?redirect=checkout') ?>" class="btn-ghost text-xs py-2 px-4 font-bold border border-[#bc944c] text-[#07160d] hover:bg-[#bc944c] hover:text-[#07160d] shrink-0 text-center">
                                Sign In →
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- 1. Customer Contact Details -->
                    <div class="bg-white border border-[#e7dec8] p-6 md:p-8 rounded-3xl shadow-sm">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#e7dec8]">
                            <span class="w-7 h-7 rounded-full bg-[#bc944c] text-[#07160d] font-bold text-xs flex items-center justify-center">1</span>
                            <h3 class="font-display text-lg md:text-xl text-[#07160d] font-bold">Contact Information</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Full Name *</label>
                                <input type="text" name="full_name" required value="<?= e($user['name'] ?? '') ?>" placeholder="e.g. Ramesh Kumar" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                            </div>

                            <div>
                                <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Email Address *</label>
                                <input type="email" name="email" required value="<?= e($user['email'] ?? '') ?>" placeholder="For order & invoice confirmation" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Phone / WhatsApp Number (10 digits) *</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3.5 rounded-l-xl border border-r-0 border-[#d6c7af] bg-[#faf8f5] text-xs text-[#07160d] font-bold">+91</span>
                                    <input type="tel" name="phone" required maxlength="12" value="<?= e($user['phone'] ?? '') ?>" placeholder="9845279936" class="flex-1 px-3.5 py-2.5 text-xs rounded-r-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                                </div>
                            </div>

                            <?php if (!\App\Core\Auth::check()): ?>
                                <div class="sm:col-span-2 pt-3 border-t border-[#e7dec8]">
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-[#07160d]">
                                        <input type="checkbox" id="create_account_toggle" name="create_account" value="1" class="rounded border-[#d6c7af] text-[#bc944c] focus:ring-0">
                                        <span>Create an account with these details to track this order online</span>
                                    </label>
                                    <div id="account_password_container" class="hidden mt-3 max-w-sm">
                                        <label class="block text-xs text-stone-700 mb-1 font-semibold">Choose Account Password (min. 6 characters)</label>
                                        <input type="password" name="account_password" minlength="6" placeholder="Choose a password" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 2. Shipping Address -->
                    <div class="bg-white border border-[#e7dec8] p-6 md:p-8 rounded-3xl shadow-sm">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#e7dec8]">
                            <span class="w-7 h-7 rounded-full bg-[#bc944c] text-[#07160d] font-bold text-xs flex items-center justify-center">2</span>
                            <h3 class="font-display text-lg md:text-xl text-[#07160d] font-bold">Shipping Address</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Flat / House No., Building Name, Street *</label>
                                <input type="text" name="address_line1" required value="<?= e($savedAddress['address_line1'] ?? '') ?>" placeholder="e.g. Flat 302, Sai Heritage Apartments, 4th Cross" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                            </div>

                            <div>
                                <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Landmark / Area (Optional)</label>
                                <input type="text" name="address_line2" value="<?= e($savedAddress['address_line2'] ?? '') ?>" placeholder="e.g. Near Dollars Colony Club" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs text-stone-700 mb-1.5 font-semibold">City *</label>
                                    <input type="text" name="city" required value="<?= e($savedAddress['city'] ?? 'Bengaluru') ?>" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                                </div>

                                <div>
                                    <label class="block text-xs text-stone-700 mb-1.5 font-semibold">State *</label>
                                    <select name="state" required class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                                        <option value="Karnataka" selected>Karnataka</option>
                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                        <option value="Telangana">Telangana</option>
                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                        <option value="Kerala">Kerala</option>
                                        <option value="Maharashtra">Maharashtra</option>
                                        <option value="Delhi">Delhi</option>
                                        <option value="Other">Other States</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs text-stone-700 mb-1.5 font-semibold">PIN Code (6 Digits) *</label>
                                    <input type="text" name="postal_code" required maxlength="6" value="<?= e($savedAddress['postal_code'] ?? '560078') ?>" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                                </div>
                            </div>

                            <div class="pt-2">
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-stone-700">
                                    <input type="checkbox" id="same_billing" name="same_billing" value="1" checked class="rounded border-[#d6c7af] text-[#bc944c] focus:ring-0">
                                    <span>Billing address is same as shipping address</span>
                                </label>
                            </div>

                            <!-- Optional separate billing fields -->
                            <div id="billing-address-fields" class="hidden pt-4 border-t border-[#e7dec8] space-y-4">
                                <h4 class="text-xs uppercase tracking-wider text-[#07160d] font-bold">Billing Address Details</h4>
                                <input type="text" name="billing_full_name" placeholder="Billing Name" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917]">
                                <input type="text" name="billing_address_line1" placeholder="Billing Street Address" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917]">
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="text" name="billing_city" placeholder="City" class="px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917]">
                                    <input type="text" name="billing_state" placeholder="State" class="px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917]">
                                    <input type="text" name="billing_postal_code" placeholder="PIN Code" class="px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Delivery Notes / Instructions</label>
                                <textarea name="order_notes" rows="2" placeholder="e.g. Leave with security or ring bell" class="w-full px-3.5 py-2 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Payment Method Selection -->
                    <div class="bg-white border border-[#e7dec8] p-6 md:p-8 rounded-3xl shadow-sm">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#e7dec8]">
                            <span class="w-7 h-7 rounded-full bg-[#bc944c] text-[#07160d] font-bold text-xs flex items-center justify-center">3</span>
                            <h3 class="font-display text-lg md:text-xl text-[#07160d] font-bold">Payment Method</h3>
                        </div>

                        <div class="space-y-3">
                            <!-- Online Razorpay Option -->
                            <label class="flex items-center justify-between p-4 rounded-2xl border border-[#d6c7af] bg-[#faf8f5] cursor-pointer hover:border-[#bc944c] transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="razorpay" checked class="text-[#bc944c] focus:ring-0">
                                    <div>
                                        <div class="text-xs font-bold text-[#07160d]">UPI / Credit & Debit Cards / Net Banking</div>
                                        <div class="text-[11px] text-[#bc944c] font-medium">Instant online payment via Google Pay, PhonePe, Cards</div>
                                    </div>
                                </div>
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-300 font-semibold">Fastest</span>
                            </label>

                            <!-- Cash on Delivery Option -->
                            <label class="flex items-center justify-between p-4 rounded-2xl border border-[#d6c7af] bg-[#faf8f5] cursor-pointer hover:border-[#bc944c] transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cod" class="text-[#bc944c] focus:ring-0">
                                    <div>
                                        <div class="text-xs font-bold text-[#07160d]">Cash on Delivery (COD)</div>
                                        <div class="text-[11px] text-stone-500">Pay in cash or UPI upon courier arrival at doorstep</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Place Order Button (Large) -->
                    <button type="submit" class="btn-gold w-full py-4 text-sm font-bold tracking-wider uppercase shadow-lg shadow-[#bc944c]/20">
                        Confirm & Place Order →
                    </button>
                </form>
            </div>

            <!-- Right: Order Items & Pricing Breakdown -->
            <div class="bg-white border border-[#e7dec8] p-6 rounded-3xl space-y-5 sticky top-28 shadow-sm">
                <h3 class="font-display text-lg text-[#07160d] font-bold border-b border-[#e7dec8] pb-3 flex items-center justify-between">
                    <span>Order Review</span>
                    <span class="text-xs font-mono text-[#bc944c] font-bold"><?= $cart['items_count'] ?> Item(s)</span>
                </h3>

                <!-- Mini Items List -->
                <div class="divide-y divide-stone-100 max-h-60 overflow-y-auto pr-1">
                    <?php foreach ($cart['items'] as $it): ?>
                        <div class="py-3 flex items-center justify-between text-xs gap-3">
                            <img src="<?= e($it['image']) ?>" class="w-12 h-12 object-contain rounded-xl bg-[#faf8f5] p-1 border border-[#e7dec8] shrink-0">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#07160d] truncate"><?= e($it['name']) ?></div>
                                <div class="text-[11px] text-[#bc944c] font-semibold"><?= e($it['variant_name'] ?: 'Standard') ?> × <?= $it['quantity'] ?></div>
                            </div>
                            <div class="font-bold text-[#07160d]">
                                <?= $it['line_total_formatted'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Checkout Coupon Code Section -->
                <div class="pt-3 border-t border-[#e7dec8] space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] uppercase tracking-wider text-[#07160d] font-bold">Promo / Coupon Code</label>
                        <?php if (!empty($cart['coupon_code'])): ?>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Applied</span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($cart['coupon_code'])): ?>
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs">
                            <div>
                                <span class="font-bold text-emerald-800 font-mono"><?= e($cart['coupon_code']) ?></span>
                                <span class="text-[11px] text-emerald-600 block">Saved <?= $cart['discount_formatted'] ?></span>
                            </div>
                            <button type="button" id="checkout-remove-coupon-btn" class="text-xs text-red-600 hover:text-red-700 font-semibold px-2 py-1 bg-white border border-red-200 rounded-lg shadow-2xs">
                                Remove
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="flex gap-2">
                            <input type="text" id="checkout-coupon-code" placeholder="Enter coupon code" class="flex-1 px-3 py-2 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] uppercase font-mono focus:outline-none focus:border-[#bc944c]">
                            <button type="button" id="checkout-apply-coupon-btn" class="btn-ghost text-xs px-3.5 py-2 font-bold shrink-0">
                                Apply
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Price Calculations -->
                <div class="pt-3 border-t border-[#e7dec8] space-y-2 text-xs text-stone-600">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span class="font-semibold text-[#07160d]"><?= $cart['subtotal_formatted'] ?></span>
                    </div>

                    <?php if ($cart['discount'] > 0): ?>
                        <div class="flex justify-between text-emerald-700 font-semibold">
                            <span>Coupon Discount (<?= e($cart['coupon_code']) ?>)</span>
                            <span>-<?= $cart['discount_formatted'] ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between">
                        <span>Shipping Delivery</span>
                        <span class="font-semibold text-[#07160d]"><?= $cart['shipping_formatted'] ?></span>
                    </div>

                    <div class="flex justify-between">
                        <span>GST <?= (float)($cart['tax_rate'] ?? 5) ?>% (<?= !empty($cart['tax_inclusive']) ? 'Included' : 'Added' ?>)</span>
                        <span class="font-medium text-[#07160d]"><?= $cart['tax_formatted'] ?></span>
                    </div>
                    <?php if (!empty($cart['cgst']) && !empty($cart['sgst'])): ?>
                        <div class="flex justify-between text-[11px] text-stone-500 pl-2">
                            <span>CGST (<?= round(((float)($cart['tax_rate'] ?? 5)) / 2, 2) ?>%) + SGST (<?= round(((float)($cart['tax_rate'] ?? 5)) / 2, 2) ?>%)</span>
                            <span><?= $cart['cgst_formatted'] ?> + <?= $cart['sgst_formatted'] ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="pt-3 border-t border-[#e7dec8] flex justify-between items-baseline">
                    <span class="font-display text-base text-[#07160d] font-bold">Grand Total</span>
                    <span class="font-display text-2xl font-bold text-[#07160d]">
                        <?= $cart['total_formatted'] ?>
                    </span>
                </div>

                <div class="pt-2 text-[11px] text-center text-stone-400 leading-relaxed">
                    By confirming this order, you agree to Legacy Food's Terms of Service and Shipping Policy.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('assets/js/checkout.js') ?>"></script>
