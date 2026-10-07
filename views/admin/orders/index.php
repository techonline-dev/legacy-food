<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#fdf6e3]">Customer Orders</h1>
            <p class="text-xs text-[#fdf6e3]/60 mt-1">Review orders, manage fulfillment workflows, and track deliveries.</p>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs">
        <a href="<?= url('admin/orders') ?>" class="px-4 py-2 rounded-xl font-medium transition-colors shrink-0 <?= empty($currentStatus) ? 'bg-[#bc944c] text-[#07160d] font-bold' : 'bg-white/[0.04] text-[#fdf6e3]/70 hover:bg-white/[0.08]' ?>">
            All Orders (<?= (int)$counts['all'] ?>)
        </a>
        <a href="<?= url('admin/orders?status=pending') ?>" class="px-4 py-2 rounded-xl font-medium transition-colors shrink-0 <?= $currentStatus === 'pending' ? 'bg-[#bc944c] text-[#07160d] font-bold' : 'bg-white/[0.04] text-[#fdf6e3]/70 hover:bg-white/[0.08]' ?>">
            Pending (<?= (int)$counts['pending'] ?>)
        </a>
        <a href="<?= url('admin/orders?status=processing') ?>" class="px-4 py-2 rounded-xl font-medium transition-colors shrink-0 <?= $currentStatus === 'processing' ? 'bg-[#bc944c] text-[#07160d] font-bold' : 'bg-white/[0.04] text-[#fdf6e3]/70 hover:bg-white/[0.08]' ?>">
            Processing (<?= (int)$counts['processing'] ?>)
        </a>
        <a href="<?= url('admin/orders?status=shipped') ?>" class="px-4 py-2 rounded-xl font-medium transition-colors shrink-0 <?= $currentStatus === 'shipped' ? 'bg-[#bc944c] text-[#07160d] font-bold' : 'bg-white/[0.04] text-[#fdf6e3]/70 hover:bg-white/[0.08]' ?>">
            Shipped (<?= (int)$counts['shipped'] ?>)
        </a>
        <a href="<?= url('admin/orders?status=delivered') ?>" class="px-4 py-2 rounded-xl font-medium transition-colors shrink-0 <?= $currentStatus === 'delivered' ? 'bg-[#bc944c] text-[#07160d] font-bold' : 'bg-white/[0.04] text-[#fdf6e3]/70 hover:bg-white/[0.08]' ?>">
            Delivered (<?= (int)$counts['delivered'] ?>)
        </a>
        <a href="<?= url('admin/orders?status=cancelled') ?>" class="px-4 py-2 rounded-xl font-medium transition-colors shrink-0 <?= $currentStatus === 'cancelled' ? 'bg-[#bc944c] text-[#07160d] font-bold' : 'bg-white/[0.04] text-[#fdf6e3]/70 hover:bg-white/[0.08]' ?>">
            Cancelled (<?= (int)$counts['cancelled'] ?>)
        </a>
    </div>

    <!-- Orders Table Card -->
    <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
        <?php if (empty($orders)): ?>
            <div class="py-16 text-center text-xs text-[#fdf6e3]/50">
                <svg class="w-12 h-12 mx-auto mb-3 text-[#bc944c]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                No orders match this status criteria.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#bc944c]/20 text-[#bc944c] uppercase tracking-wider text-[10px]">
                            <th class="pb-3 font-semibold">Order Number</th>
                            <th class="pb-3 font-semibold">Date & Time</th>
                            <th class="pb-3 font-semibold">Customer</th>
                            <th class="pb-3 font-semibold">Payment</th>
                            <th class="pb-3 font-semibold">Fulfillment</th>
                            <th class="pb-3 font-semibold text-right">Total Amount</th>
                            <th class="pb-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.05]">
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-3.5 font-mono font-bold text-[#fdf6e3]">
                                    #<?= e($order['order_number']) ?>
                                </td>
                                <td class="py-3.5 text-[#fdf6e3]/60">
                                    <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                                </td>
                                <td class="py-3.5">
                                    <div class="font-semibold text-[#fdf6e3]"><?= e($order['customer_name']) ?></div>
                                    <div class="text-[10px] text-[#fdf6e3]/50"><?= e($order['customer_phone'] ?? $order['customer_email']) ?></div>
                                </td>
                                <td class="py-3.5">
                                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $order['payment_status'] === 'paid' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $order['payment_status'] === 'paid' ? 'bg-emerald-400' : 'bg-amber-400' ?>"></span>
                                        <span><?= strtoupper(e($order['payment_method'])) ?></span>
                                        <span>(<?= ucfirst(e($order['payment_status'])) ?>)</span>
                                    </div>
                                </td>
                                <td class="py-3.5">
                                    <?php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                                        'processing' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                        'shipped' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                                        'delivered' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                        'cancelled' => 'bg-red-500/20 text-red-300 border-red-500/30',
                                    ];
                                    $st = $order['status'];
                                    $cls = $statusClasses[$st] ?? 'bg-white/10 text-white/70 border-white/20';
                                    ?>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border <?= $cls ?>">
                                        <?= ucfirst(str_replace('_', ' ', $st)) ?>
                                    </span>
                                </td>
                                <td class="py-3.5 text-right font-serif font-bold text-[#bc944c]">
                                    <?= format_price($order['total_amount']) ?>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= url('invoice/' . $order['order_number']) ?>" target="_blank" title="Invoice" class="p-1.5 rounded-lg hover:bg-white/[0.08] text-[#fdf6e3]/60 hover:text-[#bc944c]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                        <a href="<?= url('admin/orders/' . $order['id']) ?>" class="btn-ghost py-1 px-2.5 text-[11px]">
                                            Manage →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
