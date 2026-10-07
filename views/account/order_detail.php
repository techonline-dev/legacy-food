<div class="py-8 md:py-14">
    <div class="container-x max-w-4xl">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4">
            <a href="<?= url('account') ?>" class="hover:text-[#bc944c]">My Account</a>
            <span>/</span>
            <a href="<?= url('account/orders') ?>" class="hover:text-[#bc944c]">Orders</a>
            <span>/</span>
            <span class="text-[#bc944c] font-medium">#<?= e($order['order_number']) ?></span>
        </nav>

        <?php include __DIR__ . '/../components/account_tabs.php'; ?>

        <div class="bg-white border border-[#e7dec8] p-6 md:p-10 rounded-3xl shadow-sm space-y-8">
            <!-- Top Summary Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#e7dec8]">
                <div>
                    <span class="eyebrow">Order Details</span>
                    <h1 class="font-display text-2xl md:text-3xl font-bold text-[#07160d] mt-1 font-mono">
                        #<?= e($order['order_number']) ?>
                    </h1>
                    <p class="text-xs text-stone-500 mt-1">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="<?= url('invoice/' . $order['order_number']) ?>" target="_blank" class="btn-ghost text-xs py-2 px-4 font-semibold">
                        Download Invoice
                    </a>
                    <?php if (in_array($order['status'], ['pending', 'payment_confirmed'])): ?>
                        <form action="<?= url('account/order/' . $order['id'] . '/cancel') ?>" method="POST" onsubmit="return confirm('Are you sure you wish to cancel this order?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 border border-red-200 bg-red-50 px-3 py-2 rounded-full font-semibold">
                                Cancel Order
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tracking & Status Banner -->
            <div class="p-4 rounded-2xl bg-[#faf8f5] border border-[#e7dec8] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div>
                    <span class="text-stone-500">Current Status:</span>
                    <span class="font-bold text-[#07160d] uppercase tracking-wider ml-1"><?= e(str_replace('_', ' ', $order['status'])) ?></span>
                </div>
                <?php if (!empty($order['tracking_number'])): ?>
                    <div>
                        <span class="text-stone-500">Courier:</span>
                        <strong class="text-[#07160d]"><?= e($order['shipping_courier'] ?? 'Standard Partner') ?></strong>
                        <span class="text-stone-500 ml-2">Tracking #:</span>
                        <span class="font-mono text-[#bc944c] font-bold"><?= e($order['tracking_number']) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Items List -->
            <div>
                <h3 class="font-display text-lg text-[#07160d] font-bold mb-4">Items in this Order</h3>
                <div class="divide-y divide-stone-100 text-xs">
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div>
                                <h5 class="font-bold text-sm text-[#07160d]"><?= e($item['product_name']) ?></h5>
                                <span class="text-[#bc944c] font-semibold"><?= e($item['variant_name'] ?: 'Standard') ?> · <?= currency_format($item['price']) ?> × <?= $item['quantity'] ?></span>
                            </div>
                            <div class="font-bold text-sm text-[#07160d]">
                                <?= currency_format($item['total']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="pt-4 border-t border-[#e7dec8] space-y-2 text-xs text-stone-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold text-[#07160d]"><?= currency_format($order['subtotal']) ?></span>
                    </div>
                    <?php if (($order['discount_amount'] ?? 0) > 0): ?>
                        <div class="flex justify-between text-emerald-700 font-semibold">
                            <span>Coupon Discount <?= !empty($order['coupon_code']) ? '(' . e($order['coupon_code']) . ')' : '' ?></span>
                            <span>- <?= currency_format($order['discount_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span>Shipping Delivery</span>
                        <span><?= currency_format($order['shipping_amount'] ?? ($order['shipping_fee'] ?? 0)) ?></span>
                    </div>
                    <?php if (!empty($order['tax_amount']) && (float)$order['tax_amount'] > 0): ?>
                        <div class="flex justify-between text-stone-500 text-[11px]">
                            <span>GST Tax</span>
                            <span><?= currency_format($order['tax_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between font-bold text-base text-[#07160d] pt-2 border-t border-stone-200">
                        <span>Total Paid</span>
                        <span><?= currency_format($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Delivery Address Card -->
            <?php if (!empty($order['shipping_address'])): ?>
                <div class="p-5 rounded-2xl bg-[#faf8f5] border border-[#e7dec8] text-xs space-y-1">
                    <span class="eyebrow block mb-1">Delivered To</span>
                    <div class="font-bold text-[#07160d] text-sm"><?= e($order['shipping_address']['full_name']) ?></div>
                    <div class="text-stone-600"><?= e($order['shipping_address']['address_line1']) ?>, <?= e($order['shipping_address']['address_line2']) ?></div>
                    <div class="text-stone-600"><?= e($order['shipping_address']['city']) ?>, <?= e($order['shipping_address']['state']) ?> - <?= e($order['shipping_address']['postal_code']) ?></div>
                    <div class="text-stone-600">Phone: <?= e($order['shipping_address']['phone']) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
