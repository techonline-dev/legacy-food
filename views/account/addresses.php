<div class="py-8 md:py-14">
    <div class="container-x max-w-3xl">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4">
            <a href="<?= url('account') ?>" class="hover:text-[#bc944c]">My Account</a>
            <span>/</span>
            <span class="text-[#bc944c] font-medium">Saved Addresses</span>
        </nav>

        <h1 class="font-display text-2xl md:text-3xl text-[#07160d] font-bold mb-6">
            Saved Delivery Addresses
        </h1>

        <?php include __DIR__ . '/../components/account_tabs.php'; ?>

        <?php if (empty($addresses)): ?>
            <div class="bg-white border border-[#e7dec8] p-8 rounded-3xl text-center text-xs text-stone-500 shadow-sm">
                You have not saved any delivery addresses yet. Addresses are saved automatically during checkout.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($addresses as $addr): ?>
                    <div class="bg-white border border-[#e7dec8] p-5 rounded-2xl relative text-xs space-y-1.5 shadow-sm">
                        <?php if ($addr['is_default']): ?>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#faf8f5] text-[#bc944c] border border-[#d6c7af] absolute top-4 right-4">
                                Default
                            </span>
                        <?php endif; ?>
                        <h4 class="font-bold text-sm text-[#07160d]"><?= e($addr['full_name']) ?></h4>
                        <p class="text-stone-600 leading-relaxed">
                            <?= e($addr['address_line1']) ?><br>
                            <?php if (!empty($addr['address_line2'])): ?>
                                <?= e($addr['address_line2']) ?><br>
                            <?php endif; ?>
                            <?= e($addr['city']) ?>, <?= e($addr['state']) ?> - <?= e($addr['postal_code']) ?>
                        </p>
                        <p class="text-[#bc944c] font-semibold pt-1">Phone: <?= e($addr['phone']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
