<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-xs text-[#bc944c] font-semibold uppercase tracking-wider mb-1">Customer CRM</div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Registered Customers</h1>
            <p class="text-xs text-stone-500 mt-1">Directory of shoppers, order frequencies, and lifetime purchase values.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-white border border-[#d6c7af] text-[#07160d] shadow-xs">
                Total Customers: <strong class="text-[#bc944c]"><?= count($customers) ?></strong>
            </span>
        </div>
    </div>

    <!-- Toolbar: Search Bar -->
    <div class="p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <form action="<?= url('admin/customers') ?>" method="GET" class="relative flex-1 max-w-md">
            <input type="text" name="q" value="<?= e($searchQuery ?? '') ?>" placeholder="Search customer by name, email, or phone..." 
                   class="w-full pl-9 pr-24 py-2 text-xs rounded-xl bg-stone-50 border border-[#d6c7af] text-[#1c1917] focus:bg-white focus:border-[#bc944c] focus:outline-none">
            <svg class="w-4 h-4 text-stone-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <button type="submit" class="absolute right-1.5 top-1.5 px-2.5 py-1 text-[11px] font-bold rounded-lg bg-[#bc944c] text-[#07160d] hover:bg-[#a6803b] transition-colors">
                Search
            </button>
        </form>
        <?php if (!empty($searchQuery)): ?>
            <a href="<?= url('admin/customers') ?>" class="text-xs text-stone-500 hover:text-red-600 font-medium">
                ✕ Clear Search
            </a>
        <?php endif; ?>
    </div>

    <!-- Customers Table Card -->
    <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
        <?php if (empty($customers)): ?>
            <div class="py-16 text-center text-xs text-stone-400">
                <?= !empty($searchQuery) ? 'No customers matched your search query.' : 'No customer accounts registered yet.' ?>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#e7dec8] text-stone-500 uppercase tracking-wider text-[10px]">
                            <th class="pb-3 font-bold">Customer</th>
                            <th class="pb-3 font-bold">Contact Email</th>
                            <th class="pb-3 font-bold">Phone</th>
                            <th class="pb-3 font-bold">Status</th>
                            <th class="pb-3 font-bold">Member Since</th>
                            <th class="pb-3 font-bold text-center">Orders</th>
                            <th class="pb-3 font-bold text-right">Lifetime Spent</th>
                            <th class="pb-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php foreach ($customers as $c): ?>
                            <tr class="hover:bg-[#faf8f5] transition-colors">
                                <td class="py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#faf8f5] border border-[#d6c7af] text-[#bc944c] font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                            <?= strtoupper(substr($c['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <a href="<?= url('admin/customers/' . $c['id']) ?>" class="font-bold text-[#07160d] hover:text-[#bc944c] transition-colors">
                                                <?= e($c['name']) ?>
                                            </a>
                                            <div class="text-[10px] text-stone-400 font-mono">ID #<?= $c['id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 text-stone-700">
                                    <a href="mailto:<?= e($c['email']) ?>" class="hover:text-[#bc944c] transition-colors">
                                        <?= e($c['email']) ?>
                                    </a>
                                </td>
                                <td class="py-3.5 text-stone-600 font-mono">
                                    <?= e($c['phone'] ?? '—') ?>
                                </td>
                                <td class="py-3.5">
                                    <?php if ($c['status'] === 'active'): ?>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                            Blocked
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 text-stone-500">
                                    <?= date('d M Y', strtotime($c['created_at'])) ?>
                                </td>
                                <td class="py-3.5 text-center">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold bg-stone-100 text-stone-800">
                                        <?= (int)$c['orders_count'] ?>
                                    </span>
                                </td>
                                <td class="py-3.5 text-right font-display font-bold text-[#07160d]">
                                    <?= format_price($c['total_spent']) ?>
                                </td>
                                <td class="py-3.5 text-right">
                                    <a href="<?= url('admin/customers/' . $c['id']) ?>" class="btn-ghost py-1 px-3 text-[11px] border border-[#d6c7af] hover:bg-white text-[#07160d] font-semibold">
                                        View Profile →
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
