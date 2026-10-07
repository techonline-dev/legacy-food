<div class="flex items-center gap-2 border-b border-[#e7dec8] pb-3 mb-8 overflow-x-auto no-scrollbar">
    <?php $isOverview = is_active_path('account', true); ?>
    <a href="<?= url('account') ?>" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition-all flex items-center gap-2 <?= $isOverview ? 'bg-[#bc944c] text-[#07160d] shadow-sm ring-1 ring-[#bc944c]/30' : 'text-stone-600 hover:text-[#07160d] hover:bg-white' ?>" <?= $isOverview ? 'aria-current="page"' : '' ?>>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span>Overview</span>
    </a>

    <?php $isOrders = is_active_path(['account/orders', 'account/order']); ?>
    <a href="<?= url('account/orders') ?>" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition-all flex items-center gap-2 <?= $isOrders ? 'bg-[#bc944c] text-[#07160d] shadow-sm ring-1 ring-[#bc944c]/30' : 'text-stone-600 hover:text-[#07160d] hover:bg-white' ?>" <?= $isOrders ? 'aria-current="page"' : '' ?>>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        <span>Order History</span>
    </a>

    <?php $isProfile = is_active_path('account/profile'); ?>
    <a href="<?= url('account/profile') ?>" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition-all flex items-center gap-2 <?= $isProfile ? 'bg-[#bc944c] text-[#07160d] shadow-sm ring-1 ring-[#bc944c]/30' : 'text-stone-600 hover:text-[#07160d] hover:bg-white' ?>" <?= $isProfile ? 'aria-current="page"' : '' ?>>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span>Profile & Security</span>
    </a>

    <?php $isAddr = is_active_path('account/addresses'); ?>
    <a href="<?= url('account/addresses') ?>" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition-all flex items-center gap-2 <?= $isAddr ? 'bg-[#bc944c] text-[#07160d] shadow-sm ring-1 ring-[#bc944c]/30' : 'text-stone-600 hover:text-[#07160d] hover:bg-white' ?>" <?= $isAddr ? 'aria-current="page"' : '' ?>>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <span>Saved Addresses</span>
    </a>

    <?php $isWish = is_active_path('wishlist'); ?>
    <a href="<?= url('wishlist') ?>" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition-all flex items-center gap-2 <?= $isWish ? 'bg-[#bc944c] text-[#07160d] shadow-sm ring-1 ring-[#bc944c]/30' : 'text-stone-600 hover:text-[#07160d] hover:bg-white' ?>" <?= $isWish ? 'aria-current="page"' : '' ?>>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        <span>My Wishlist</span>
    </a>
</div>
