<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="<?= url('admin/customers') ?>" class="text-xs text-[#bc944c] hover:underline flex items-center gap-1 font-semibold">
                    ← Customers
                </a>
                <span class="text-xs text-stone-400">/</span>
                <span class="text-xs text-stone-500 font-mono">User #<?= $customer['id'] ?></span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]"><?= e($customer['name']) ?></h1>
                <?php if ($customer['status'] === 'active'): ?>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Active Account
                    </span>
                <?php else: ?>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                        Blocked Account
                    </span>
                <?php endif; ?>
            </div>
            <p class="text-xs text-stone-500 mt-1">
                Registered on <?= date('d M Y, h:i A', strtotime($customer['created_at'])) ?> · Customer ID: #<?= $customer['id'] ?>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <form action="<?= url('admin/customers/status/' . $customer['id']) ?>" method="POST" onsubmit="return confirm('Change status for this customer?');">
                <?= csrf_field() ?>
                <?php if ($customer['status'] === 'active'): ?>
                    <button type="submit" class="btn-ghost py-2 px-3 text-xs border border-red-300 text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors">
                        Block Customer
                    </button>
                <?php else: ?>
                    <button type="submit" class="btn-ghost py-2 px-3 text-xs border border-emerald-300 text-emerald-700 hover:bg-emerald-50 font-semibold rounded-xl transition-colors">
                        Activate Customer
                    </button>
                <?php endif; ?>
            </form>
            <a href="mailto:<?= e($customer['email']) ?>" class="btn-primary py-2 px-3.5 text-xs font-bold flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Send Email</span>
            </a>
        </div>
    </div>

    <!-- Lifetime Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-xs">
            <span class="text-[10px] uppercase tracking-wider text-stone-500 font-bold block">Lifetime Spent</span>
            <span class="text-xl font-display font-bold text-[#07160d] mt-1 block"><?= format_price($stats['totalSpent']) ?></span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-xs">
            <span class="text-[10px] uppercase tracking-wider text-stone-500 font-bold block">Total Orders</span>
            <span class="text-xl font-display font-bold text-[#07160d] mt-1 block"><?= (int)$stats['ordersCount'] ?></span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-xs">
            <span class="text-[10px] uppercase tracking-wider text-emerald-600 font-bold block">Delivered Orders</span>
            <span class="text-xl font-display font-bold text-emerald-700 mt-1 block"><?= (int)$stats['deliveredCount'] ?></span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-xs">
            <span class="text-[10px] uppercase tracking-wider text-amber-600 font-bold block">Pending / In-Transit</span>
            <span class="text-xl font-display font-bold text-amber-700 mt-1 block"><?= (int)$stats['pendingCount'] ?></span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-[#e7dec8] shadow-xs col-span-2 sm:col-span-1">
            <span class="text-[10px] uppercase tracking-wider text-stone-500 font-bold block">Average Order (AOV)</span>
            <span class="text-xl font-display font-bold text-[#07160d] mt-1 block"><?= format_price($stats['avgOrderValue']) ?></span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Orders & Past Addresses -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order History Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-base font-bold text-[#07160d]">Order History (<?= count($orders) ?>)</h2>
                        <p class="text-[11px] text-stone-500">Chronological history of transactions placed by this user.</p>
                    </div>
                </div>

                <?php if (empty($orders)): ?>
                    <div class="p-8 text-center text-xs text-stone-400 bg-stone-50 rounded-xl border border-stone-200">
                        This customer hasn't placed any orders yet.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-[#e7dec8] text-[#bc944c] uppercase tracking-wider text-[10px]">
                                    <th class="pb-2.5 font-bold">Order ID</th>
                                    <th class="pb-2.5 font-bold">Date</th>
                                    <th class="pb-2.5 font-bold">Items</th>
                                    <th class="pb-2.5 font-bold">Payment</th>
                                    <th class="pb-2.5 font-bold">Status</th>
                                    <th class="pb-2.5 font-bold text-right">Amount</th>
                                    <th class="pb-2.5 font-bold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                <?php foreach ($orders as $ord): ?>
                                    <tr class="hover:bg-[#faf8f5] transition-colors">
                                        <td class="py-3 font-mono font-bold text-[#07160d]">
                                            #<?= e($ord['order_number']) ?>
                                        </td>
                                        <td class="py-3 text-stone-500">
                                            <?= date('d M Y', strtotime($ord['created_at'])) ?>
                                        </td>
                                        <td class="py-3 text-stone-600">
                                            <?= (int)$ord['items_count'] ?> item(s)
                                        </td>
                                        <td class="py-3">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold <?= $ord['payment_status'] === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' ?>">
                                                <?= strtoupper(e($ord['payment_method'])) ?> (<?= ucfirst(e($ord['payment_status'])) ?>)
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <?php
                                            $statusClasses = [
                                                'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'processing' => 'bg-blue-50 text-blue-800 border-blue-200',
                                                'shipped' => 'bg-purple-50 text-purple-800 border-purple-200',
                                                'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                'cancelled' => 'bg-red-50 text-red-800 border-red-200',
                                            ];
                                            $st = $ord['status'];
                                            $cls = $statusClasses[$st] ?? 'bg-stone-50 text-stone-700 border-stone-200';
                                            ?>
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border <?= $cls ?>">
                                                <?= ucfirst(str_replace('_', ' ', $st)) ?>
                                            </span>
                                        </td>
                                        <td class="py-3 text-right font-display font-bold text-[#07160d]">
                                            <?= format_price($ord['total_amount']) ?>
                                        </td>
                                        <td class="py-3 text-right">
                                            <a href="<?= url('admin/orders/' . $ord['id']) ?>" class="btn-ghost py-1 px-2.5 text-[11px] border border-[#d6c7af] hover:bg-white text-[#07160d] font-semibold">
                                                View →
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Saved Delivery Addresses -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-base font-bold text-[#07160d]">Shipping Addresses</h2>
                        <p class="text-[11px] text-stone-500">Destination addresses utilized by customer for shipping.</p>
                    </div>
                </div>

                <?php if (empty($addresses)): ?>
                    <p class="text-xs text-stone-400">No physical shipping address records logged.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php foreach ($addresses as $idx => $addr): ?>
                            <div class="p-4 rounded-xl bg-[#faf8f5] border border-[#e7dec8] text-xs space-y-1">
                                <div class="font-bold text-[#07160d] flex items-center justify-between">
                                    <span><?= e($addr['full_name']) ?></span>
                                    <span class="text-[10px] text-stone-400 uppercase font-mono">#<?= $idx + 1 ?></span>
                                </div>
                                <div class="text-stone-600 leading-relaxed">
                                    <?= e($addr['address_line1']) ?><?= !empty($addr['address_line2']) ? ', ' . e($addr['address_line2']) : '' ?><br>
                                    <?= e($addr['city']) ?>, <?= e($addr['state']) ?> - <?= e($addr['postal_code']) ?><br>
                                    <?= e($addr['country'] ?? 'India') ?>
                                </div>
                                <div class="pt-1.5 text-[11px] text-stone-500 flex items-center gap-2">
                                    <span>📞 <?= e($addr['phone']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Customer Product Reviews -->
            <?php if (!empty($reviews)): ?>
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Product Reviews (<?= count($reviews) ?>)</h2>
                    <div class="divide-y divide-stone-100">
                        <?php foreach ($reviews as $rev): ?>
                            <div class="py-3 flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-amber-500 font-bold text-xs"><?= str_repeat('★', (int)$rev['rating']) ?></span>
                                        <span class="text-xs font-bold text-[#07160d]"><?= e($rev['title'] ?: 'Review') ?></span>
                                    </div>
                                    <p class="text-xs text-stone-600 mt-1"><?= e($rev['review_text']) ?></p>
                                    <div class="text-[10px] text-stone-400 mt-1">
                                        Product: <a href="<?= url('product/' . $rev['product_slug']) ?>" target="_blank" class="text-[#bc944c] hover:underline font-semibold"><?= e($rev['product_name']) ?></a> · <?= date('d M Y', strtotime($rev['created_at'])) ?>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $rev['status'] === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?>">
                                    <?= ucfirst(e($rev['status'])) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Customer Wishlist -->
            <?php if (!empty($wishlist)): ?>
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Saved Wishlist (<?= count($wishlist) ?>)</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php foreach ($wishlist as $wItem): ?>
                            <div class="p-3 rounded-xl border border-[#e7dec8] bg-[#faf8f5] flex items-center gap-3">
                                <img src="<?= e($wItem['featured_image'] ?? '') ?>" class="w-10 h-10 object-contain rounded-lg bg-white p-1 border border-stone-200">
                                <div class="min-w-0">
                                    <a href="<?= url('product/' . ($wItem['slug'] ?? $wItem['product_slug'] ?? '')) ?>" target="_blank" class="font-semibold text-xs text-[#07160d] hover:text-[#bc944c] truncate block">
                                        <?= e($wItem['name'] ?? $wItem['product_name'] ?? 'Product') ?>
                                    </a>
                                    <div class="text-[11px] text-[#bc944c] font-bold"><?= format_price($wItem['base_price'] ?? 0) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Col: Profile Editor & Metadata -->
        <div class="space-y-6">
            <!-- Edit Profile Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <h2 class="font-display text-base font-bold text-[#07160d]">Edit Profile Details</h2>

                <form action="<?= url('admin/customers/update/' . $customer['id']) ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Full Name *</label>
                        <input type="text" name="name" value="<?= e($customer['name']) ?>" required
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Email Address *</label>
                        <input type="email" name="email" value="<?= e($customer['email']) ?>" required
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="text" name="phone" value="<?= e($customer['phone'] ?? '') ?>" placeholder="+91 98765 43210"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Account Status</label>
                        <select name="status" class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="active" <?= $customer['status'] === 'active' ? 'selected' : '' ?>>Active (Can Login & Order)</option>
                            <option value="blocked" <?= $customer['status'] === 'blocked' ? 'selected' : '' ?>>Blocked (Access Suspended)</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn-primary text-xs py-3 font-bold justify-center shadow-xs">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customer Meta Info Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-3">
                <h3 class="font-display text-sm font-bold text-[#07160d]">Account System Metadata</h3>

                <div class="divide-y divide-stone-100 text-xs">
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="text-stone-500">Database User ID</span>
                        <span class="font-mono font-bold text-[#07160d]">#<?= $customer['id'] ?></span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="text-stone-500">Registered On</span>
                        <span class="font-semibold text-[#07160d]"><?= date('d M Y, h:i A', strtotime($customer['created_at'])) ?></span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="text-stone-500">Last Profile Update</span>
                        <span class="text-stone-700"><?= $customer['updated_at'] ? date('d M Y, h:i A', strtotime($customer['updated_at'])) : '—' ?></span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="text-stone-500">Last Login At</span>
                        <span class="text-stone-700"><?= !empty($customer['last_login_at']) ? date('d M Y, h:i A', strtotime($customer['last_login_at'])) : 'Never' ?></span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="text-stone-500">Email Verification</span>
                        <span class="font-semibold <?= !empty($customer['email_verified_at']) ? 'text-emerald-700' : 'text-stone-500' ?>">
                            <?= !empty($customer['email_verified_at']) ? '✓ Verified' : 'Unverified' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
