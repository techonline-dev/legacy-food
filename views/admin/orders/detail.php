<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="<?= url('admin/orders') ?>" class="text-xs text-[#bc944c] hover:underline">← Orders</a>
                <span class="text-xs text-[#fdf6e3]/40">/</span>
                <span class="text-xs text-[#fdf6e3]/60 font-mono">#<?= e($order['order_number']) ?></span>
            </div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#fdf6e3]">Order #<?= e($order['order_number']) ?></h1>
            <p class="text-xs text-[#fdf6e3]/60 mt-0.5">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('invoice/' . $order['order_number']) ?>" target="_blank" class="btn-primary text-xs py-2 px-3.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Tax Invoice</span> ↗
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Order Items & Customer Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Line Items -->
            <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
                <h2 class="font-serif text-base font-bold text-[#fdf6e3] mb-4">Purchased Items (<?= count($order['items'] ?? []) ?>)</h2>

                <div class="divide-y divide-white/[0.06]">
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                <img src="<?= e($item['image']) ?>" class="w-12 h-12 rounded-lg object-contain bg-[#0e2319] p-1 border border-[#bc944c]/20 shrink-0">
                                <div>
                                    <div class="font-semibold text-xs text-[#fdf6e3]"><?= e($item['product_name']) ?></div>
                                    <div class="text-[11px] text-[#bc944c] font-medium">Variant: <?= e($item['variant_name'] ?: 'Standard') ?></div>
                                    <div class="text-[10px] text-[#fdf6e3]/50 font-mono">SKU: <?= e($item['sku']) ?></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-serif font-bold text-[#fdf6e3]"><?= format_price($item['total_price']) ?></div>
                                <div class="text-[10px] text-[#fdf6e3]/60"><?= format_price($item['unit_price']) ?> × <?= (int)$item['quantity'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Financial Calculation Breakdown -->
                <div class="pt-4 mt-4 border-t border-white/[0.08] space-y-2 text-xs">
                    <div class="flex justify-between text-[#fdf6e3]/70">
                        <span>Cart Subtotal</span>
                        <span><?= format_price($order['subtotal']) ?></span>
                    </div>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <div class="flex justify-between text-emerald-400">
                            <span>Coupon Discount <?= !empty($order['coupon_code']) ? '(' . e($order['coupon_code']) . ')' : '' ?></span>
                            <span>- <?= format_price($order['discount_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-[#fdf6e3]/70">
                        <span>Shipping Fee</span>
                        <span><?= $order['shipping_fee'] > 0 ? format_price($order['shipping_fee']) : '<span class="text-emerald-400 font-semibold">FREE</span>' ?></span>
                    </div>
                    <?php if ($order['tax_amount'] > 0): ?>
                        <div class="flex justify-between text-[#fdf6e3]/70">
                            <span>GST / Tax (<?= (float)setting('gst_percentage', 5.0) ?>% <?= setting('tax_inclusive', '1') == '1' ? 'Included' : 'Added' ?>)</span>
                            <span><?= format_price($order['tax_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-base font-bold font-serif text-[#bc944c] pt-2 border-t border-white/[0.08]">
                        <span>Grand Total</span>
                        <span><?= format_price($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Customer & Addresses -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Shipping Address -->
                <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#bc944c] mb-3">Shipping Destination</h3>
                    <?php $ship = $order['shipping_address'] ?? []; ?>
                    <div class="text-xs space-y-1 text-[#fdf6e3]/80">
                        <div class="font-bold text-[#fdf6e3]"><?= e($order['customer_name']) ?></div>
                        <div><?= e($ship['address_line1'] ?? '') ?></div>
                        <?php if (!empty($ship['address_line2'])): ?>
                            <div><?= e($ship['address_line2']) ?></div>
                        <?php endif; ?>
                        <div><?= e($ship['city'] ?? '') ?>, <?= e($ship['state'] ?? '') ?> - <?= e($ship['postal_code'] ?? '') ?></div>
                        <div><?= e($ship['country'] ?? 'India') ?></div>
                        <div class="pt-2 text-[11px] text-[#fdf6e3]/60">
                            Phone: <span class="text-[#fdf6e3] font-mono"><?= e($order['customer_phone'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Customer Details & Order Notes -->
                <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#bc944c] mb-3">Customer Information</h3>
                    <div class="text-xs space-y-2 text-[#fdf6e3]/80">
                        <div>
                            <span class="text-[#fdf6e3]/50">Customer Name:</span>
                            <div class="font-semibold text-[#fdf6e3]"><?= e($order['customer_name']) ?></div>
                        </div>
                        <div>
                            <span class="text-[#fdf6e3]/50">Email Address:</span>
                            <div class="font-semibold text-[#fdf6e3]"><?= e($order['customer_email']) ?></div>
                        </div>
                        <div>
                            <span class="text-[#fdf6e3]/50">Customer Notes:</span>
                            <div class="italic text-[11px] text-[#fdf6e3]/70 bg-white/[0.02] p-2 rounded-lg mt-0.5">
                                <?= !empty($order['notes']) ? e($order['notes']) : 'No special delivery instructions provided.' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Col: Status Management Form -->
        <div class="space-y-6">
            <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20 space-y-5">
                <h2 class="font-serif text-base font-bold text-[#fdf6e3]">Order Management</h2>

                <form action="<?= url('admin/orders/' . $order['id'] . '/status') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <div>
                        <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Fulfillment Status</label>
                        <select name="status" class="w-full py-2.5 px-3.5 rounded-xl bg-[#07160d] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
                            <?php 
                            $statuses = ['pending', 'payment_confirmed', 'processing', 'packed', 'shipped', 'delivered', 'cancelled', 'refunded'];
                            foreach ($statuses as $st): 
                            ?>
                                <option value="<?= $st ?>" <?= $order['status'] === $st ? 'selected' : '' ?>>
                                    <?= ucfirst(str_replace('_', ' ', $st)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Payment Status</label>
                        <select name="payment_status" class="w-full py-2.5 px-3.5 rounded-xl bg-[#07160d] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
                            <option value="pending" <?= $order['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $order['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid (Verified)</option>
                            <option value="failed" <?= $order['payment_status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                            <option value="refunded" <?= $order['payment_status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>

                    <div class="pt-2 border-t border-white/[0.06] space-y-3">
                        <div class="text-[10px] uppercase font-semibold text-[#bc944c] tracking-wider">Logistics & Tracking</div>

                        <div>
                            <label class="block text-xs font-semibold text-[#fdf6e3]/80 mb-1">Courier Service</label>
                            <input type="text" name="courier_name" value="<?= e($order['courier_name'] ?? '') ?>" placeholder="e.g. Delhivery, Bluedart, DTDC"
                                   class="w-full py-2 px-3 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#fdf6e3]/80 mb-1">Tracking Number / AWB</label>
                            <input type="text" name="tracking_number" value="<?= e($order['tracking_number'] ?? '') ?>" placeholder="AWB123456789IN"
                                   class="w-full py-2 px-3 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs text-[#fdf6e3] font-mono focus:border-[#bc944c] focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#fdf6e3]/80 mb-1">Admin Audit Notes</label>
                        <textarea name="admin_notes" rows="2" placeholder="Internal remarks regarding dispatch or customer request..."
                                  class="w-full py-2 px-3 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full btn-primary text-xs py-3 font-bold justify-center">
                        Update Order & Notify Customer
                    </button>
                </form>
            </div>

            <!-- Payment Gateway Summary Card -->
            <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20 text-xs space-y-2.5">
                <h3 class="font-serif font-bold text-[#fdf6e3]">Payment Gateway Metadata</h3>
                <div class="flex justify-between text-[#fdf6e3]/70">
                    <span>Method</span>
                    <span class="font-semibold text-[#fdf6e3] uppercase"><?= e($order['payment_method']) ?></span>
                </div>
                <div class="flex justify-between text-[#fdf6e3]/70">
                    <span>Payment ID</span>
                    <span class="font-mono text-[#bc944c]"><?= e($order['payment_id'] ?? 'COD_PENDING') ?></span>
                </div>
                <div class="flex justify-between text-[#fdf6e3]/70">
                    <span>Created Timestamp</span>
                    <span><?= date('Y-m-d H:i:s', strtotime($order['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
