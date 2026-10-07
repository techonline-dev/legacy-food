<div id="cart-drawer" class="fixed inset-y-0 right-0 w-96 max-w-[90vw] bg-white border-l border-[#e7dec8] shadow-2xl z-50 transform translate-x-full transition-transform duration-300 flex flex-col justify-between text-[#1c1917]">
    <!-- Header -->
    <div class="p-5 border-b border-[#e7dec8] flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <h3 class="font-display text-lg text-[#07160d] font-bold">Shopping Cart</h3>
        </div>
        <button id="cart-drawer-close" class="text-stone-400 hover:text-[#07160d] text-2xl leading-none">&times;</button>
    </div>

    <!-- Free Shipping Progress Tracker -->
    <div class="bg-[#faf8f5] p-3 border-b border-[#e7dec8] text-center">
        <div class="text-[11px] text-stone-700">
            Enjoy <strong class="text-[#bc944c]">FREE SHIPPING</strong> on all orders above ₹999!
        </div>
    </div>

    <!-- Items List (Loaded via AJAX) -->
    <div id="cart-drawer-items" class="flex-1 overflow-y-auto p-5 space-y-4">
        <div class="py-12 text-center text-stone-400 text-xs">
            Loading cart...
        </div>
    </div>

    <!-- Footer Checkout Actions -->
    <div class="p-5 border-t border-[#e7dec8] bg-[#faf8f5] space-y-3">
        <div class="flex items-center justify-between text-sm">
            <span class="text-stone-600">Subtotal</span>
            <span id="cart-drawer-subtotal" class="font-bold text-lg text-[#07160d] font-display">₹0.00</span>
        </div>
        <p class="text-[10px] text-stone-500">Taxes calculated and promotional coupons applied at checkout.</p>
        <div class="grid grid-cols-2 gap-2 pt-1">
            <a href="<?= url('cart') ?>" class="btn-ghost text-xs py-2.5 text-center">
                View Cart
            </a>
            <a href="<?= url('checkout') ?>" class="btn-gold text-xs py-2.5 text-center shadow-sm">
                Checkout Now
            </a>
        </div>
    </div>
</div>
<div id="cart-overlay" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40"></div>
