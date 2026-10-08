<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Dashboard Overview</h1>
                <?php $dbTarget = config('database.target', 'local'); ?>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $dbTarget === 'local' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= $dbTarget === 'local' ? 'bg-amber-500' : 'bg-emerald-500' ?>"></span>
                    <?= $dbTarget === 'local' ? 'Local DB: ' . e(config('database.database')) : 'Prod DB: ' . e(config('database.database')) ?>
                </span>
            </div>
            <p class="text-xs text-stone-500 mt-1">Real-time performance metrics and store management overview.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('admin/products/create') ?>" class="btn-gold text-xs py-2 px-4 flex items-center gap-2 shadow-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Product</span>
            </a>
            <a href="<?= url('admin/orders') ?>" class="btn-ghost text-xs py-2 px-4 flex items-center gap-2 border border-[#d6c7af] text-[#07160d] hover:bg-[#faf8f5] font-semibold">
                <span>View Orders</span>
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Sales -->
        <div class="p-5 rounded-2xl bg-white border border-[#e7dec8] shadow-sm relative overflow-hidden group hover:border-[#bc944c]/60 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#bc944c] uppercase tracking-wider">Gross Sales</span>
                <span class="p-2 rounded-xl bg-[#bc944c]/10 text-[#bc944c]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold font-display text-[#07160d]"><?= format_price($totalSales) ?></div>
                <div class="text-[11px] text-stone-500 mt-1 flex items-center gap-1.5">
                    <span>This month:</span>
                    <span class="text-emerald-600 font-bold"><?= format_price($monthlySales) ?></span>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="p-5 rounded-2xl bg-white border border-[#e7dec8] shadow-sm relative overflow-hidden group hover:border-[#bc944c]/60 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#bc944c] uppercase tracking-wider">Total Orders</span>
                <span class="p-2 rounded-xl bg-[#bc944c]/10 text-[#bc944c]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold font-display text-[#07160d]"><?= number_format($totalOrders) ?></div>
                <div class="text-[11px] text-stone-500 mt-1 flex items-center gap-2">
                    <span class="text-amber-600 font-bold"><?= $pendingOrders ?> pending</span>
                    <span>•</span>
                    <span class="text-emerald-600 font-bold"><?= $deliveredOrders ?> delivered</span>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="p-5 rounded-2xl bg-white border border-[#e7dec8] shadow-sm relative overflow-hidden group hover:border-[#bc944c]/60 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#bc944c] uppercase tracking-wider">Registered Users</span>
                <span class="p-2 rounded-xl bg-[#bc944c]/10 text-[#bc944c]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold font-display text-[#07160d]"><?= number_format($totalCustomers) ?></div>
                <div class="text-[11px] text-stone-500 mt-1">
                    Direct heritage shoppers
                </div>
            </div>
        </div>

        <!-- Active Catalog -->
        <div class="p-5 rounded-2xl bg-white border border-[#e7dec8] shadow-sm relative overflow-hidden group hover:border-[#bc944c]/60 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#bc944c] uppercase tracking-wider">Active Products</span>
                <span class="p-2 rounded-xl bg-[#bc944c]/10 text-[#bc944c]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold font-display text-[#07160d]"><?= number_format($totalProducts) ?></div>
                <div class="text-[11px] text-stone-500 mt-1">
                    <?= count($lowStockProducts) > 0 ? '<span class="text-red-600 font-bold">' . count($lowStockProducts) . ' low stock alert</span>' : '<span class="text-emerald-600 font-bold">All stock healthy</span>' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Low Stock Alerts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 7-Day Revenue Curve Chart -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display text-lg font-bold text-[#07160d]">Sales Velocity (Last 7 Days)</h2>
                    <p class="text-xs text-stone-500">Revenue trends and transaction volume</p>
                </div>
                <div class="text-xs text-[#bc944c] font-bold bg-[#bc944c]/10 px-3 py-1 rounded-full border border-[#bc944c]/30">
                    INR Revenue (₹)
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Low Stock Alert Box -->
        <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display text-lg font-bold text-[#07160d]">Stock Watchlist</h2>
                    <a href="<?= url('admin/inventory') ?>" class="text-[11px] text-[#bc944c] font-bold hover:underline">Manage All →</a>
                </div>

                <?php if (empty($lowStockProducts)): ?>
                    <div class="py-8 text-center text-xs text-stone-500">
                        <svg class="w-8 h-8 mx-auto mb-2 text-emerald-600/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        All inventory levels are above safety thresholds.
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($lowStockProducts as $p): ?>
                            <div class="p-3 rounded-xl bg-[#faf8f5] border border-[#e7dec8] flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="<?= e($p['featured_image']) ?>" class="w-10 h-10 rounded-lg object-contain bg-white p-1 border border-[#e7dec8] shrink-0">
                                    <div class="min-w-0">
                                        <div class="text-xs font-semibold text-[#07160d] truncate"><?= e($p['name']) ?></div>
                                        <div class="text-[10px] text-stone-500"><?= e($p['sku']) ?></div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold <?= $p['stock_quantity'] <= 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' ?>">
                                        <?= (int)$p['stock_quantity'] ?> left
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pt-4 mt-4 border-t border-stone-100">
                <a href="<?= url('admin/inventory') ?>" class="w-full btn-ghost text-xs py-2 block text-center border border-[#d6c7af] text-[#07160d] hover:bg-[#faf8f5] font-semibold">
                    Review Inventory Logs
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-lg font-bold text-[#07160d]">Recent Orders</h2>
                <p class="text-xs text-stone-500">Latest transactions from public storefront</p>
            </div>
            <a href="<?= url('admin/orders') ?>" class="text-xs text-[#bc944c] font-bold hover:underline">View All Orders →</a>
        </div>

        <?php if (empty($recentOrders)): ?>
            <div class="py-12 text-center text-xs text-stone-400">
                No orders received yet. Make a test order on the storefront!
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-600 uppercase tracking-wider text-[10px] bg-stone-50">
                            <th class="p-3 font-bold">Order ID</th>
                            <th class="p-3 font-bold">Date</th>
                            <th class="p-3 font-bold">Customer</th>
                            <th class="p-3 font-bold">Payment</th>
                            <th class="p-3 font-bold">Status</th>
                            <th class="p-3 font-bold text-right">Amount</th>
                            <th class="p-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php foreach ($recentOrders as $order): ?>
                            <tr class="hover:bg-[#faf8f5] transition-colors">
                                <td class="p-3 font-mono font-bold text-[#07160d]">
                                    #<?= e($order['order_number']) ?>
                                </td>
                                <td class="p-3 text-stone-500">
                                    <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                                </td>
                                <td class="p-3">
                                    <div class="font-semibold text-[#07160d]"><?= e($order['customer_name'] ?? 'Customer') ?></div>
                                    <div class="text-[10px] text-stone-500"><?= e($order['customer_phone'] ?? $order['customer_email'] ?? 'N/A') ?></div>
                                </td>
                                <td class="p-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $order['payment_status'] === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' ?>">
                                        <?= strtoupper(e($order['payment_method'])) ?> (<?= ucfirst(e($order['payment_status'])) ?>)
                                    </span>
                                </td>
                                <td class="p-3">
                                    <?php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'processing' => 'bg-blue-50 text-blue-800 border-blue-200',
                                        'shipped' => 'bg-purple-50 text-purple-800 border-purple-200',
                                        'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        'cancelled' => 'bg-red-50 text-red-800 border-red-200',
                                    ];
                                    $st = $order['status'];
                                    $cls = $statusClasses[$st] ?? 'bg-stone-50 text-stone-700 border-stone-200';
                                    ?>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold border <?= $cls ?>">
                                        <?= ucfirst(str_replace('_', ' ', $st)) ?>
                                    </span>
                                </td>
                                <td class="p-3 text-right font-display font-bold text-[#07160d]">
                                    <?= format_price($order['total_amount']) ?>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="<?= url('admin/orders/' . $order['id']) ?>" class="btn-ghost py-1 px-3 text-[11px] border border-[#d6c7af] hover:bg-white text-[#07160d]">
                                        Details →
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    const dates = <?= json_encode($chartDates ?? []) ?>;
    const sales = <?= json_encode($chartSales ?? []) ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Revenue (₹)',
                data: sales,
                borderColor: '#bc944c',
                backgroundColor: 'rgba(188, 148, 76, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#bc944c',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#07160d',
                    bodyColor: '#1c1917',
                    borderColor: '#e7dec8',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return ' ₹ ' + context.parsed.y.toLocaleString('en-IN', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0, 0, 0, 0.04)' },
                    ticks: { color: '#78716c', font: { size: 10 } }
                },
                y: {
                    grid: { color: 'rgba(0, 0, 0, 0.04)' },
                    ticks: {
                        color: '#78716c',
                        font: { size: 10 },
                        callback: function(val) {
                            return '₹' + val;
                        }
                    }
                }
            }
        }
    });
});
</script>
