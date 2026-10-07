<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Coupons & Conditional Rules</h1>
            <p class="text-xs text-stone-500 mt-1">Configure promotional discount codes, spend thresholds, customer limits, and conditional rules.</p>
        </div>
        <?php if (!empty($editCoupon)): ?>
            <div>
                <a href="<?= url('admin/coupons') ?>" class="btn-ghost text-xs py-2 px-4 border border-[#d6c7af] text-[#07160d] hover:bg-white inline-flex items-center gap-2">
                    <span>+ Add New Coupon</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add / Edit Coupon Form -->
        <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4 h-fit">
            <div class="flex items-center justify-between border-b border-[#e7dec8] pb-3">
                <h2 class="font-display text-base font-bold text-[#07160d]">
                    <?= !empty($editCoupon) ? 'Edit Coupon #' . $editCoupon['id'] : 'Create New Coupon' ?>
                </h2>
                <?php if (!empty($editCoupon)): ?>
                    <a href="<?= url('admin/coupons') ?>" class="text-[11px] text-[#bc944c] font-semibold hover:underline">
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>

            <form action="<?= !empty($editCoupon) ? url('admin/coupons/update/' . $editCoupon['id']) : url('admin/coupons/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Code & Description -->
                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Coupon Code *</label>
                    <input type="text" name="code" required value="<?= e($editCoupon['code'] ?? '') ?>" placeholder="e.g. WELCOME10"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] font-mono uppercase focus:border-[#bc944c] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Description (Internal or Promo Note)</label>
                    <input type="text" name="description" value="<?= e($editCoupon['description'] ?? '') ?>" placeholder="e.g. 10% off for first-time buyers"
                           class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <!-- Discount Type & Value -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Discount Type</label>
                        <select name="type" class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="percentage" <?= ($editCoupon['type'] ?? '') === 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
                            <option value="fixed" <?= ($editCoupon['type'] ?? '') === 'fixed' ? 'selected' : '' ?>>Fixed (₹)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Discount Value *</label>
                        <input type="number" step="0.01" min="0.01" name="value" required value="<?= (float)($editCoupon['value'] ?? '') ?>" placeholder="10"
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none font-bold">
                    </div>
                </div>

                <!-- Min Order Amount & Max Discount Cap -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Min Order (₹)</label>
                        <input type="number" step="0.01" min="0" name="min_order_amount" value="<?= (float)($editCoupon['min_order_amount'] ?? 0) ?>" placeholder="499"
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <span class="text-[10px] text-stone-400">0 = No minimum</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Max Discount Cap (₹)</label>
                        <input type="number" step="0.01" min="0" name="max_discount_amount" value="<?= $editCoupon['max_discount_amount'] ?? '' ?>" placeholder="150"
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <span class="text-[10px] text-stone-400">Blank = No cap</span>
                    </div>
                </div>

                <!-- Conditional Rules Section -->
                <div class="pt-2 border-t border-[#e7dec8] space-y-3">
                    <span class="text-[11px] font-bold text-[#bc944c] uppercase tracking-wider block">Conditional Rules</span>

                    <!-- Applicable Category -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Applicable Collection</label>
                        <select name="category_id" class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="">All Categories (Storewide)</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (!empty($editCoupon['category_id']) && (int)$editCoupon['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Min Items in Cart -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Min Quantity (Items in Cart)</label>
                        <input type="number" min="0" name="min_quantity" value="<?= (int)($editCoupon['min_quantity'] ?? 0) ?>" placeholder="0 (any quantity)"
                               class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <!-- First Order Only Checkbox -->
                    <div class="p-3 rounded-xl bg-[#faf8f5] border border-[#e7dec8]">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="first_order_only" value="1" <?= !empty($editCoupon['first_order_only']) ? 'checked' : '' ?> class="h-4 w-4 rounded border-[#d6c7af] text-[#bc944c] focus:ring-[#bc944c]">
                            <div>
                                <span class="text-xs font-bold text-[#07160d] block">First-Time Customers Only</span>
                                <span class="text-[10px] text-stone-500">Restricts coupon to users who haven't placed an order yet</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Usage Limits & Validity Period -->
                <div class="pt-2 border-t border-[#e7dec8] space-y-3">
                    <span class="text-[11px] font-bold text-[#bc944c] uppercase tracking-wider block">Usage Limits & Validity</span>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Total Store Limit</label>
                            <input type="number" min="1" name="usage_limit_total" value="<?= $editCoupon['usage_limit_total'] ?? '' ?>" placeholder="e.g. 500"
                                   class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <span class="text-[10px] text-stone-400">Blank = Unlimited</span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Per-Customer Limit</label>
                            <input type="number" min="1" name="usage_limit_per_user" value="<?= $editCoupon['usage_limit_per_user'] ?? '1' ?>" placeholder="1"
                                   class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <span class="text-[10px] text-stone-400">Default: 1 use</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Valid From</label>
                            <input type="date" name="start_date" value="<?= !empty($editCoupon['start_date']) ? date('Y-m-d', strtotime($editCoupon['start_date'])) : '' ?>"
                                   class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Expires On</label>
                            <input type="date" name="end_date" value="<?= !empty($editCoupon['end_date']) ? date('Y-m-d', strtotime($editCoupon['end_date'])) : '' ?>"
                                   class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="pt-2 border-t border-[#e7dec8]">
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Coupon Status</label>
                    <select name="is_active" class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <option value="1" <?= ($editCoupon['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Active (Can be redeemed)</option>
                        <option value="0" <?= ($editCoupon['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Inactive (Disabled)</option>
                    </select>
                </div>

                <button type="submit" class="w-full btn-gold text-xs py-3 font-bold justify-center shadow-sm">
                    <?= !empty($editCoupon) ? 'Save Coupon Changes' : 'Generate Coupon' ?>
                </button>
            </form>
        </div>

        <!-- Coupons Table -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-display text-base font-bold text-[#07160d]">Configured Coupons (<?= count($coupons) ?>)</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-600 uppercase tracking-wider text-[10px] bg-stone-50">
                            <th class="p-3 font-bold">Code</th>
                            <th class="p-3 font-bold">Discount</th>
                            <th class="p-3 font-bold">Conditions</th>
                            <th class="p-3 font-bold">Validity</th>
                            <th class="p-3 font-bold">Usage</th>
                            <th class="p-3 font-bold">Status</th>
                            <th class="p-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php if (empty($coupons)): ?>
                            <tr>
                                <td colspan="7" class="py-8 text-center text-stone-400">No active coupons created.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($coupons as $c): ?>
                                <?php 
                                    $isEditingThis = !empty($editCoupon) && $editCoupon['id'] == $c['id'];
                                    $now = date('Y-m-d H:i:s');
                                    $isExpired = !empty($c['end_date']) && $now > $c['end_date'];
                                    $isUpcoming = !empty($c['start_date']) && $now < $c['start_date'];
                                ?>
                                <tr class="transition-colors <?= $isEditingThis ? 'bg-amber-50/60 font-medium' : 'hover:bg-[#faf8f5]' ?>">
                                    <td class="p-3">
                                        <span class="font-mono font-bold text-xs text-[#07160d] px-2 py-0.5 rounded bg-stone-100 border border-stone-200 inline-block">
                                            <?= e($c['code']) ?>
                                        </span>
                                        <?php if (!empty($c['description'])): ?>
                                            <div class="text-[10px] text-stone-500 mt-1 truncate max-w-[150px]"><?= e($c['description']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-semibold text-[#bc944c] block">
                                            <?= $c['type'] === 'percentage' ? (float)$c['value'] . '%' : format_price($c['value']) ?> off
                                        </span>
                                        <?php if (!empty($c['max_discount_amount'])): ?>
                                            <span class="text-[10px] text-stone-500">Cap: <?= format_price($c['max_discount_amount']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3">
                                        <div class="space-y-0.5 text-[11px] text-stone-600">
                                            <div>Min Spend: <strong class="text-[#07160d]"><?= (float)$c['min_order_amount'] > 0 ? format_price($c['min_order_amount']) : 'None' ?></strong></div>
                                            <?php if (!empty($c['first_order_only'])): ?>
                                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold bg-purple-50 text-purple-700 border border-purple-200">1st Order Only</span>
                                            <?php endif; ?>
                                            <?php if (!empty($c['category_id'])): ?>
                                                <?php 
                                                    $matchedCat = array_filter($categories, fn($cat) => $cat['id'] == $c['category_id']);
                                                    $catObj = reset($matchedCat);
                                                ?>
                                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <?= e($catObj['name'] ?? 'Category') ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($c['min_quantity']) && (int)$c['min_quantity'] > 0): ?>
                                                <span class="inline-block text-[10px] text-stone-500">Min items: <?= (int)$c['min_quantity'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="p-3 text-[11px] text-stone-600">
                                        <?php if (!empty($c['start_date']) || !empty($c['end_date'])): ?>
                                            <div>From: <?= !empty($c['start_date']) ? date('d M Y', strtotime($c['start_date'])) : 'Any' ?></div>
                                            <div>Until: <?= !empty($c['end_date']) ? date('d M Y', strtotime($c['end_date'])) : 'Never' ?></div>
                                            <?php if ($isExpired): ?>
                                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold bg-red-50 text-red-700 border border-red-200 mt-0.5">Expired</span>
                                            <?php elseif ($isUpcoming): ?>
                                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 mt-0.5">Upcoming</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-stone-400">Always valid</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 font-mono text-stone-600">
                                        <div class="font-bold text-[#07160d]"><?= (int)$c['usage_count'] ?> <?= $c['usage_limit_total'] ? '/ ' . (int)$c['usage_limit_total'] : 'uses' ?></div>
                                        <div class="text-[10px] text-stone-400">Limit: <?= (int)($c['usage_limit_per_user'] ?? 1) ?>/user</div>
                                    </td>
                                    <td class="p-3">
                                        <?php if (!empty($c['is_active'])): ?>
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                        <?php else: ?>
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-stone-100 text-stone-600">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Edit Button -->
                                            <a href="<?= url('admin/coupons?edit=' . $c['id']) ?>" title="Edit Coupon" class="p-1.5 rounded-lg border border-stone-200 hover:border-[#bc944c] hover:bg-[#faf8f5] text-stone-700 hover:text-[#bc944c] transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="<?= url('admin/coupons/delete/' . $c['id']) ?>" method="POST" onsubmit="return confirm('Delete coupon <?= e($c['code']) ?>?');" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" title="Delete Coupon" class="p-1.5 rounded-lg border border-red-200 hover:bg-red-50 text-red-600 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
