<?php
$waNumber = setting('whatsapp_number', '919845279936');
$waMsg = setting('whatsapp_message', "Hi Legacy, I'd like to order ghee");
?>

<div class="fixed bottom-6 left-6 z-40">
    <a href="https://wa.me/<?= e($waNumber) ?>?text=<?= urlencode($waMsg) ?>" target="_blank" rel="noopener" class="group relative flex items-center gap-2.5 p-3 rounded-full bg-[#25D366] text-white shadow-[0_6px_25px_rgba(37,211,102,0.45)] hover:bg-[#20ba59] transition-all duration-300 hover:scale-105" aria-label="Order on WhatsApp">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
        <span class="hidden md:inline-block pr-1 font-semibold text-xs tracking-wide">
            Chat on WhatsApp
        </span>
        <!-- Ping ripple effect -->
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-400"></span>
        </span>
    </a>
</div>
