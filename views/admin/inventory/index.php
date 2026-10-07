<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#fdf6e3]">Inventory & Stock Control</h1>
            <p class="text-xs text-[#fdf6e3]/60 mt-1">Real-time stock monitoring, threshold warnings, and transaction logs.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stock Adjustment Card -->
        <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20 space-y-4 h-fit">
            <h2 class="font-serif text-base font-bold text-[#fdf6e3]">Adjust Stock Level</h2>

            <form action="<?= url('admin/inventory/adjust') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Select Item *</label>
                    <select name="product_id" id="stockProductSelect" required class="w-full py-2.5 px-3.5 rounded-xl bg-[#07160d] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
                        <option value="">Select Item to Adjust</option>
                        <?php foreach ($inventory as $item): ?>
                            <option value="<?= $item['product_id'] ?>" data-variant="<?= $item['variant_id'] ?? '' ?>">
                                <?= e($item['product_name']) ?> <?= !empty($item['variant_name']) ? '(' . e($item['variant_name']) . ')' : '' ?> — Curr: <?= (int)$item['stock'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="variant_id" id="variantIdHidden" value="">

                <div>
                    <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Adjustment Quantity *</label>
                    <input type="number" name="adjustment_quantity" required placeholder="e.g. +25 for restock or -5 for damage"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
                    <p class="text-[10px] text-[#fdf6e3]/50 mt-1">Use positive numbers to add stock, negative to decrease.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Audit Reason *</label>
                    <input type="text" name="reason" required placeholder="e.g. Fresh batch milling received, Lab sampling, etc."
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
                </div>

                <button type="submit" class="w-full btn-primary text-xs py-2.5 font-bold justify-center">
                    Confirm Stock Adjustment
                </button>
            </form>
        </div>

        <!-- Inventory List Table -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
            <h2 class="font-serif text-base font-bold text-[#fdf6e3] mb-4">Stock Levels by Product / Variant</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#bc944c]/20 text-[#bc944c] uppercase tracking-wider text-[10px]">
                            <th class="pb-3 font-semibold">Item & Variant</th>
                            <th class="pb-3 font-semibold">SKU</th>
                            <th class="pb-3 font-semibold">Current Stock</th>
                            <th class="pb-3 font-semibold">Safety Level</th>
                            <th class="pb-3 font-semibold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.05]">
                        <?php foreach ($inventory as $inv): ?>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-3">
                                    <div class="flex items-center gap-2.5">
                                        <img src="<?= e($inv['featured_image']) ?>" class="w-8 h-8 rounded object-contain bg-[#0e2319] p-0.5 border border-[#bc944c]/20">
                                        <div>
                                            <div class="font-semibold text-[#fdf6e3]"><?= e($inv['product_name']) ?></div>
                                            <?php if (!empty($inv['variant_name'])): ?>
                                                <div class="text-[10px] text-[#bc944c]"><?= e($inv['variant_name']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 font-mono text-[#fdf6e3]/70">
                                    <?= e($inv['variant_sku'] ?: $inv['product_sku']) ?>
                                </td>
                                <td class="py-3 font-bold font-mono text-sm text-[#fdf6e3]">
                                    <?= (int)$inv['stock'] ?> units
                                </td>
                                <td class="py-3 text-[#fdf6e3]/60">
                                    <?= (int)$inv['low_stock_threshold'] ?> min
                                </td>
                                <td class="py-3 text-right">
                                    <?php if ($inv['stock'] <= 0): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/20 text-red-300">Out of Stock</span>
                                    <?php elseif ($inv['stock'] <= $inv['low_stock_threshold']): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300">Low Stock</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-300">Adequate</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Inventory Audit History -->
    <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
        <h2 class="font-serif text-base font-bold text-[#fdf6e3] mb-4">Stock Transaction Audit Log (Latest 30 Events)</h2>

        <?php if (empty($transactions)): ?>
            <p class="text-xs text-[#fdf6e3]/50 py-4 text-center">No inventory adjustments logged yet.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#bc944c]/20 text-[#bc944c] uppercase tracking-wider text-[10px]">
                            <th class="pb-3 font-semibold">Date & Time</th>
                            <th class="pb-3 font-semibold">Product</th>
                            <th class="pb-3 font-semibold">Action / Type</th>
                            <th class="pb-3 font-semibold">Delta</th>
                            <th class="pb-3 font-semibold">Before → After</th>
                            <th class="pb-3 font-semibold">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.05]">
                        <?php foreach ($transactions as $t): ?>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-2.5 text-[#fdf6e3]/60 font-mono text-[11px]">
                                    <?= date('d M Y, h:i A', strtotime($t['created_at'])) ?>
                                </td>
                                <td class="py-2.5 font-semibold text-[#fdf6e3]">
                                    <?= e($t['product_name'] ?? ('Product #' . $t['product_id'])) ?>
                                </td>
                                <td class="py-2.5">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] uppercase font-mono bg-white/[0.05] text-[#bc944c]">
                                        <?= e($t['type']) ?>
                                    </span>
                                </td>
                                <td class="py-2.5 font-mono font-bold <?= (int)$t['quantity_change'] >= 0 ? 'text-emerald-400' : 'text-red-400' ?>">
                                    <?= (int)$t['quantity_change'] >= 0 ? '+' : '' ?><?= (int)$t['quantity_change'] ?>
                                </td>
                                <td class="py-2.5 text-[#fdf6e3]/70 font-mono">
                                    <?= (int)$t['quantity_before'] ?> → <?= (int)$t['quantity_after'] ?>
                                </td>
                                <td class="py-2.5 text-[#fdf6e3]/70">
                                    <?= e($t['reason'] ?? '') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('stockProductSelect')?.addEventListener('change', function(e) {
    const selectedOption = e.target.selectedOptions[0];
    const variantId = selectedOption ? selectedOption.getAttribute('data-variant') : '';
    document.getElementById('variantIdHidden').value = variantId || '';
});
</script>
