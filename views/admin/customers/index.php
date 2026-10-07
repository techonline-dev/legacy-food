<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#fdf6e3]">Registered Customers</h1>
            <p class="text-xs text-[#fdf6e3]/60 mt-1">Directory of shoppers, order counts, and lifetime purchase values.</p>
        </div>
        <div class="text-xs text-[#bc944c] font-semibold bg-[#bc944c]/10 px-3 py-1.5 rounded-full border border-[#bc944c]/30">
            Total Customers: <?= count($customers) ?>
        </div>
    </div>

    <!-- Customers Table Card -->
    <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
        <?php if (empty($customers)): ?>
            <div class="py-16 text-center text-xs text-[#fdf6e3]/50">
                No customer accounts registered yet.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#bc944c]/20 text-[#bc944c] uppercase tracking-wider text-[10px]">
                            <th class="pb-3 font-semibold">Customer</th>
                            <th class="pb-3 font-semibold">Contact Email</th>
                            <th class="pb-3 font-semibold">Phone</th>
                            <th class="pb-3 font-semibold">Member Since</th>
                            <th class="pb-3 font-semibold text-center">Orders</th>
                            <th class="pb-3 font-semibold text-right">Lifetime Spent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.05]">
                        <?php foreach ($customers as $c): ?>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#bc944c]/20 border border-[#bc944c]/30 text-[#bc944c] font-bold flex items-center justify-center text-xs shrink-0">
                                            <?= strtoupper(substr($c['name'], 0, 1)) ?>
                                        </div>
                                        <div class="font-semibold text-[#fdf6e3]"><?= e($c['name']) ?></div>
                                    </div>
                                </td>
                                <td class="py-3.5 text-[#fdf6e3]/80">
                                    <a href="mailto:<?= e($c['email']) ?>" class="hover:text-[#bc944c] transition-colors">
                                        <?= e($c['email']) ?>
                                    </a>
                                </td>
                                <td class="py-3.5 text-[#fdf6e3]/70 font-mono">
                                    <?= e($c['phone'] ?? '—') ?>
                                </td>
                                <td class="py-3.5 text-[#fdf6e3]/60">
                                    <?= date('d M Y', strtotime($c['created_at'])) ?>
                                </td>
                                <td class="py-3.5 text-center">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-mono font-semibold bg-white/[0.05] text-[#fdf6e3]">
                                        <?= (int)$c['orders_count'] ?>
                                    </span>
                                </td>
                                <td class="py-3.5 text-right font-serif font-bold text-[#bc944c]">
                                    <?= format_price($c['total_spent']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
