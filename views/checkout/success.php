<div class="py-12 md:py-20">
    <div class="container-x max-w-3xl">
        <!-- Success Banner Card -->
        <div class="card-glow p-8 md:p-12 rounded-3xl text-center border-t-4 border-t-[#bc944c]">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-6 border border-emerald-500/30">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>

            <span class="eyebrow">Order Successfully Placed</span>
            <h1 class="font-display text-3xl md:text-5xl text-[#fdf6e3] font-bold mt-2">
                Thank You for Choosing Pure Heritage
            </h1>
            <p class="text-xs md:text-sm text-[#fdf6e3]/70 mt-3 max-w-lg mx-auto">
                We've received your order and sent a confirmation summary to <strong class="text-[#fdf6e3]"><?= e($order['customer_email'] ?? ($order['guest_email'] ?? '')) ?></strong>.
            </p>

            <div class="mt-6 inline-flex items-center gap-3 p-3 px-6 rounded-2xl bg-[#0e2319] border border-[#bc944c]/30">
                <span class="text-xs text-[#bc944c] uppercase tracking-wider font-semibold">Order Number:</span>
                <span class="font-mono text-base font-bold text-[#fdf6e3]"><?= e($order['order_number']) ?></span>
            </div>

            <!-- Order Status Timeline -->
            <div class="mt-10 pt-8 border-t border-[#bc944c]/15">
                <h4 class="text-xs uppercase tracking-widest text-[#bc944c] font-semibold mb-6">Order Status Timeline</h4>
                <div class="grid grid-cols-4 gap-2 text-center text-xs">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-[#bc944c] text-[#07160d] font-bold flex items-center justify-center text-xs mb-2">✓</div>
                        <span class="font-semibold text-[#fdf6e3]">Placed</span>
                        <span class="text-[10px] text-[#bc944c]">Confirmed</span>
                    </div>
                    <div class="flex flex-col items-center opacity-60">
                        <div class="w-8 h-8 rounded-full bg-[#0e2319] border border-[#bc944c]/30 text-[#fdf6e3] flex items-center justify-center text-xs mb-2">2</div>
                        <span class="text-[#fdf6e3]">Processing</span>
                        <span class="text-[10px]">Small batch pack</span>
                    </div>
                    <div class="flex flex-col items-center opacity-60">
                        <div class="w-8 h-8 rounded-full bg-[#0e2319] border border-[#bc944c]/30 text-[#fdf6e3] flex items-center justify-center text-xs mb-2">3</div>
                        <span class="text-[#fdf6e3]">Shipped</span>
                        <span class="text-[10px]">In transit</span>
                    </div>
                    <div class="flex flex-col items-center opacity-60">
                        <div class="w-8 h-8 rounded-full bg-[#0e2319] border border-[#bc944c]/30 text-[#fdf6e3] flex items-center justify-center text-xs mb-2">4</div>
                        <span class="text-[#fdf6e3]">Delivered</span>
                        <span class="text-[10px]">At your doorstep</span>
                    </div>
                </div>
            </div>

            <!-- Delivery & Address Summary -->
            <div class="mt-10 p-6 rounded-2xl bg-[#0e2319] text-left text-xs space-y-3">
                <div class="flex justify-between items-center pb-3 border-b border-white/[0.08]">
                    <span class="text-[#fdf6e3]/60">Payment Method:</span>
                    <span class="font-bold uppercase text-[#bc944c]"><?= e($order['payment_method']) ?> (<?= ucfirst($order['payment_status']) ?>)</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-white/[0.08]">
                    <span class="text-[#fdf6e3]/60">Delivery Address:</span>
                    <span class="font-medium text-[#fdf6e3] text-right">
                        <?= e($order['shipping_address']['full_name']) ?>, <?= e($order['shipping_address']['address_line1']) ?>, <?= e($order['shipping_address']['city']) ?>, <?= e($order['shipping_address']['state']) ?> - <?= e($order['shipping_address']['postal_code']) ?>
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[#fdf6e3]/60">Estimated Delivery:</span>
                    <span class="font-semibold text-emerald-400">Within 3 - 5 Business Days</span>
                </div>
            </div>

            <!-- Items Purchased Table -->
            <div class="mt-8 text-left">
                <h4 class="text-xs uppercase tracking-widest text-[#bc944c] font-semibold mb-3">Purchased Items</h4>
                <div class="divide-y divide-white/[0.06] text-xs">
                    <?php foreach ($order['items'] as $it): ?>
                        <div class="py-2.5 flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-[#fdf6e3]"><?= e($it['product_name']) ?></span>
                                <span class="text-[11px] text-[#bc944c] ml-2">(<?= e($it['variant_name'] ?: 'Standard') ?>) × <?= $it['quantity'] ?></span>
                            </div>
                            <span class="font-mono text-[#fdf6e3]"><?= currency_format($it['total']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 pt-3 border-t border-[#bc944c]/20 space-y-1.5 text-xs text-[#fdf6e3]/70">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span class="font-mono text-[#fdf6e3]"><?= currency_format($order['subtotal']) ?></span>
                    </div>
                    <?php if (($order['discount_amount'] ?? 0) > 0): ?>
                        <div class="flex justify-between text-emerald-400">
                            <span>Coupon Discount <?= !empty($order['coupon_code']) ? '(' . e($order['coupon_code']) . ')' : '' ?></span>
                            <span class="font-mono">- <?= currency_format($order['discount_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span>Shipping Delivery</span>
                        <span class="font-mono text-[#fdf6e3]"><?= currency_format($order['shipping_amount'] ?? 0) ?></span>
                    </div>
                    <?php if (!empty($order['tax_amount']) && (float)$order['tax_amount'] > 0): ?>
                        <div class="flex justify-between text-[11px] text-[#fdf6e3]/50">
                            <span>GST Tax</span>
                            <span class="font-mono"><?= currency_format($order['tax_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="pt-2 border-t border-white/[0.08] flex justify-between text-sm font-bold text-[#fdf6e3]">
                        <span>Grand Total</span>
                        <span class="font-display text-lg text-[#bc944c]"><?= currency_format($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Customer Account Tracking Status Card -->
            <?php 
            $customerEmail = $order['customer_email'] ?: ($order['guest_email'] ?: '');
            ?>
            <?php if (\App\Core\Auth::check()): ?>
                <div class="mt-8 p-6 rounded-2xl bg-[#0e2319] border border-emerald-500/30 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm shrink-0">✓</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-display text-sm font-bold text-[#fdf6e3]">Order Linked to Your Account</h4>
                            <p class="text-xs text-[#fdf6e3]/70 mt-0.5">
                                Logged in as <strong class="text-[#fdf6e3]"><?= e(\App\Core\Auth::user()['email'] ?? $customerEmail) ?></strong>. You can view tracking updates, re-order favourite items, and download VAT/GST tax invoices anytime.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/[0.08] flex items-center justify-end">
                        <a href="<?= url('account/orders') ?>" class="text-xs font-bold text-[#bc944c] hover:underline flex items-center gap-1.5">
                            <span>View All Orders in Account Dashboard</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="mt-8 p-6 rounded-2xl bg-[#0e2319] border border-[#bc944c]/30 text-left">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#bc944c]/20 text-[#bc944c] mb-1.5 border border-[#bc944c]/30">Guest Checkout Complete</span>
                            <h4 class="font-display text-base font-bold text-[#fdf6e3]">Track Your Order in Customer Portal</h4>
                            <p class="text-xs text-[#fdf6e3]/70 mt-1 max-w-xl">
                                Login is <strong>not required</strong> to place an order or download your invoice. However, creating a free account allows you to track shipments live and save multiple delivery addresses.
                            </p>
                            <?php if (!empty($customerEmail)): ?>
                                <p class="text-[11px] text-[#bc944c] mt-2 flex items-center gap-1">
                                    <span>💡</span> <span>Tip: Registering or signing in with <strong><?= e($customerEmail) ?></strong> will automatically link this order and any past orders to your account!</span>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2.5 shrink-0 w-full sm:w-auto">
                            <a href="<?= url('register' . (!empty($customerEmail) ? '?email=' . urlencode($customerEmail) : '')) ?>" class="btn-gold text-xs py-2.5 px-5 text-center font-bold shadow-sm">
                                Create Account
                            </a>
                            <a href="<?= url('login' . (!empty($customerEmail) ? '?email=' . urlencode($customerEmail) : '')) ?>" class="btn-ghost text-xs py-2.5 px-4 text-center border border-[#bc944c]/40 text-[#fdf6e3] hover:text-[#bc944c]">
                                Sign In
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Buttons: Print Invoice / Continue Shopping -->
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="<?= url('invoice/' . $order['order_number']) ?>" target="_blank" class="btn-ghost text-xs py-3 px-6">
                    <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Download / Print Invoice</span>
                </a>
                <a href="<?= url('shop') ?>" class="btn-gold text-xs py-3 px-8">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
