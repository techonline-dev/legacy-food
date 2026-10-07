<div class="py-8 md:py-14">
    <div class="container-x">
        <!-- Dashboard Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-[#e7dec8]">
            <div>
                <span class="eyebrow">Customer Portal</span>
                <h1 class="font-display text-2xl md:text-4xl text-[#07160d] font-bold mt-1">
                    Welcome, <?= e($user['name']) ?>
                </h1>
                <p class="text-xs text-stone-500 mt-1"><?= e($user['email']) ?> · Customer since <?= date('M Y', strtotime($user['created_at'])) ?></p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= url('shop') ?>" class="btn-ghost text-xs py-2 px-4 font-bold">Continue Shopping</a>
                <a href="<?= url('logout') ?>" class="text-xs text-red-500 font-semibold hover:underline">Log Out</a>
            </div>
        </div>
        
        <?php include __DIR__ . '/../components/account_tabs.php'; ?>

        <!-- Account Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm border-l-4 border-l-[#bc944c]">
                <span class="text-xs uppercase tracking-wider text-[#bc944c] font-bold">Total Orders</span>
                <div class="font-display text-3xl font-bold text-[#07160d] mt-2"><?= $totalOrders ?></div>
            </div>

            <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm border-l-4 border-l-amber-500">
                <span class="text-xs uppercase tracking-wider text-amber-600 font-bold">In Progress</span>
                <div class="font-display text-3xl font-bold text-[#07160d] mt-2"><?= $pendingOrders ?></div>
            </div>

            <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm border-l-4 border-l-emerald-500">
                <span class="text-xs uppercase tracking-wider text-emerald-700 font-bold">Delivered</span>
                <div class="font-display text-3xl font-bold text-[#07160d] mt-2"><?= $deliveredOrders ?></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Recent Orders (2 cols) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-display text-lg text-[#07160d] font-bold">Recent Orders</h3>
                    <a href="<?= url('account/orders') ?>" class="text-xs text-[#bc944c] font-bold hover:underline">View All (<?= $totalOrders ?>) →</a>
                </div>

                <?php if (empty($recentOrders)): ?>
                    <div class="bg-white border border-[#e7dec8] p-8 rounded-2xl text-center text-xs text-stone-500 shadow-sm">
                        You have not placed any orders yet. 
                        <a href="<?= url('shop') ?>" class="text-[#bc944c] font-bold hover:underline block mt-2">Start shopping pure ghee →</a>
                    </div>
                <?php else: ?>
                    <div class="bg-white border border-[#e7dec8] rounded-2xl shadow-sm overflow-hidden divide-y divide-stone-100 text-xs">
                        <?php foreach ($recentOrders as $ord): ?>
                            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="font-bold text-sm font-mono text-[#07160d]">
                                        #<?= e($ord['order_number']) ?>
                                    </div>
                                    <div class="text-[11px] text-stone-500 mt-0.5">
                                        Placed on <?= date('d M Y, h:i A', strtotime($ord['created_at'])) ?>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#faf8f5] border border-[#d6c7af] text-[#07160d]">
                                        <?= e($ord['status']) ?>
                                    </span>
                                    <span class="font-display font-bold text-sm text-[#07160d]">
                                        <?= currency_format($ord['total_amount']) ?>
                                    </span>
                                    <a href="<?= url('account/order/' . $ord['id']) ?>" class="btn-ghost text-[11px] py-1 px-3 font-semibold">
                                        Details →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Account Navigation Card -->
            <div class="bg-white border border-[#e7dec8] rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="font-display text-lg text-[#07160d] font-bold border-b border-[#e7dec8] pb-3">
                    Account Menu
                </h3>

                <nav class="space-y-1 text-xs">
                    <?php $act = is_active_path('account', true); ?>
                    <a href="<?= url('account') ?>" class="flex items-center justify-between p-2.5 rounded-xl transition-colors <?= $act ? 'bg-amber-50 text-[#bc944c] font-bold border border-[#d6c7af]' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#bc944c] font-medium' ?>">
                        <span>Overview</span>
                        <span>→</span>
                    </a>

                    <?php $act = is_active_path(['account/orders', 'account/order']); ?>
                    <a href="<?= url('account/orders') ?>" class="flex items-center justify-between p-2.5 rounded-xl transition-colors <?= $act ? 'bg-amber-50 text-[#bc944c] font-bold border border-[#d6c7af]' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#bc944c] font-medium' ?>">
                        <span>Order History</span>
                        <span>→</span>
                    </a>

                    <?php $act = is_active_path('account/profile'); ?>
                    <a href="<?= url('account/profile') ?>" class="flex items-center justify-between p-2.5 rounded-xl transition-colors <?= $act ? 'bg-amber-50 text-[#bc944c] font-bold border border-[#d6c7af]' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#bc944c] font-medium' ?>">
                        <span>Personal Profile</span>
                        <span>→</span>
                    </a>

                    <?php $act = is_active_path('account/addresses'); ?>
                    <a href="<?= url('account/addresses') ?>" class="flex items-center justify-between p-2.5 rounded-xl transition-colors <?= $act ? 'bg-amber-50 text-[#bc944c] font-bold border border-[#d6c7af]' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#bc944c] font-medium' ?>">
                        <span>Saved Addresses</span>
                        <span>→</span>
                    </a>

                    <?php $act = is_active_path('wishlist'); ?>
                    <a href="<?= url('wishlist') ?>" class="flex items-center justify-between p-2.5 rounded-xl transition-colors <?= $act ? 'bg-amber-50 text-[#bc944c] font-bold border border-[#d6c7af]' : 'text-stone-700 hover:bg-[#faf8f5] hover:text-[#bc944c] font-medium' ?>">
                        <span>My Wishlist</span>
                        <span>→</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</div>
