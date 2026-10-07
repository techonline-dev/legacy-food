<div class="py-8 md:py-14">
    <div class="container-x">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-3">
            <a href="<?= url('account') ?>" class="hover:text-[#bc944c]">My Account</a>
            <span>/</span>
            <span class="text-[#bc944c] font-medium">Orders</span>
        </nav>

        <h1 class="font-display text-3xl text-[#07160d] font-bold mb-6">
            My Orders History
        </h1>

        <?php include __DIR__ . '/../components/account_tabs.php'; ?>

        <?php if (empty($orders)): ?>
            <div class="bg-white border border-[#e7dec8] p-12 rounded-3xl text-center max-w-md mx-auto shadow-sm">
                <p class="text-xs text-stone-500">You have no previous orders.</p>
                <a href="<?= url('shop') ?>" class="btn-gold text-xs py-2.5 px-6 mt-4 inline-block font-bold">Shop Now</a>
            </div>
        <?php else: ?>
            <div class="bg-white border border-[#e7dec8] rounded-3xl shadow-sm overflow-hidden divide-y divide-stone-100 text-xs">
                <?php foreach ($orders as $ord): ?>
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="font-mono text-sm font-bold text-[#07160d]">#<?= e($ord['order_number']) ?></div>
                            <div class="text-[11px] text-stone-500 mt-0.5"><?= date('d M Y, h:i A', strtotime($ord['created_at'])) ?></div>
                            <div class="text-[11px] text-[#bc944c] font-semibold mt-1 capitalize">Payment: <?= e($ord['payment_method']) ?> (<?= e($ord['payment_status']) ?>)</div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#faf8f5] text-[#07160d] border border-[#d6c7af]">
                                <?= e(str_replace('_', ' ', $ord['status'])) ?>
                            </span>
                            <span class="font-display text-base font-bold text-[#07160d] min-w-[80px] text-right">
                                <?= currency_format($ord['total_amount']) ?>
                            </span>
                            <a href="<?= url('account/order/' . $ord['id']) ?>" class="btn-ghost text-xs py-1.5 px-3 font-semibold">
                                Details →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
