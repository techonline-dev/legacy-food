// ====================================================================
// Legacy Food — Interactive E-commerce Engine
// ====================================================================

document.addEventListener('DOMContentLoaded', () => {
    initHeaderScroll();
    initMobileMenu();
    initCartDrawer();
    initVariantSelectors();
    initWishlistButtons();
    initFaqAccordion();
    initLiveSearch();
    initNewsletterForm();
});

// Toast notification helper
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'toast';
    const icon = type === 'success' 
        ? '<svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' 
        : '<svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';

    toast.innerHTML = `${icon} <span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

// Sticky Header Transition
function initHeaderScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            header.classList.add('bg-white/98', 'shadow-md', 'border-b', 'border-[#e7dec8]');
            header.classList.remove('bg-[#07160d]/95', 'bg-transparent');
        } else {
            header.classList.remove('shadow-md', 'bg-[#07160d]/95', 'bg-transparent');
            header.classList.add('bg-white/95', 'shadow-sm', 'border-b', 'border-[#e7dec8]');
        }
    });
}

// Mobile Menu Toggle
function initMobileMenu() {
    const btn = document.getElementById('mobile-menu-btn');
    const drawer = document.getElementById('mobile-menu-drawer');
    const closeBtn = document.getElementById('mobile-menu-close');
    const overlay = document.getElementById('mobile-menu-overlay');

    if (!btn || !drawer) return;

    const toggle = (open) => {
        if (open) {
            drawer.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            drawer.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    btn.addEventListener('click', () => toggle(true));
    if (closeBtn) closeBtn.addEventListener('click', () => toggle(false));
    if (overlay) overlay.addEventListener('click', () => toggle(false));

    // ESC key closes mobile menu
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !drawer.classList.contains('-translate-x-full')) {
            toggle(false);
        }
    });

    // Accordion for Collections in Mobile Drawer
    const collToggle = document.getElementById('mobile-collections-toggle');
    const collList = document.getElementById('mobile-collections-list');
    const collArrow = document.getElementById('mobile-collections-arrow');
    if (collToggle && collList) {
        collToggle.addEventListener('click', (e) => {
            e.preventDefault();
            collList.classList.toggle('hidden');
            if (collArrow) {
                collArrow.classList.toggle('rotate-180');
            }
        });
    }

    // Auto-close on link click (except hash / empty anchors)
    drawer.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            const href = a.getAttribute('href');
            if (!a.getAttribute('target') && href && href !== '#' && !href.startsWith('javascript:')) {
                toggle(false);
            }
        });
    });
}

// Cart Drawer System
function initCartDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    const openBtns = document.querySelectorAll('.cart-open-btn');
    const closeBtn = document.getElementById('cart-drawer-close');

    if (!drawer) return;

    const toggle = (open) => {
        if (open) {
            drawer.classList.remove('translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            refreshCartDrawer();
        } else {
            drawer.classList.add('translate-x-full');
            if (overlay) overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    openBtns.forEach(b => b.addEventListener('click', (e) => {
        e.preventDefault();
        toggle(true);
    }));

    if (closeBtn) closeBtn.addEventListener('click', () => toggle(false));
    if (overlay) overlay.addEventListener('click', () => toggle(false));

    // Expose openCartDrawer globally
    window.openCartDrawer = () => toggle(true);
}

// Refresh Cart Drawer Content
async function refreshCartDrawer() {
    const container = document.getElementById('cart-drawer-items');
    const subtotalEl = document.getElementById('cart-drawer-subtotal');
    const badgeEls = document.querySelectorAll('.cart-count-badge');
    if (!container) return;

    try {
        const res = await fetch(window.APP_URL + '/cart/summary', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.success && json.cart) {
            const cart = json.cart;
            badgeEls.forEach(el => {
                el.textContent = cart.items_count;
                el.style.display = cart.items_count > 0 ? 'flex' : 'none';
            });

            if (subtotalEl) {
                subtotalEl.textContent = cart.subtotal_formatted;
            }

            if (cart.items.length === 0) {
                container.innerHTML = `
                    <div class="py-16 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#bc944c]/10 flex items-center justify-center text-[#bc944c]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h4 class="font-display text-lg text-[#07160d] font-bold">Your cart is empty</h4>
                        <p class="text-xs text-stone-500 mt-1 max-w-xs mx-auto">Explore our pure heritage cow ghee and cold pressed oils to add your favourites.</p>
                        <a href="${window.APP_URL}/shop" class="btn-gold mt-6 inline-flex text-xs py-2 px-6">Explore Products</a>
                    </div>
                `;
                return;
            }

            let html = '<div class="space-y-3">';
            cart.items.forEach(item => {
                html += `
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-[#faf8f5] border border-[#e7dec8]">
                        <img src="${item.image}" alt="${item.name}" class="w-14 h-14 object-contain rounded-xl bg-white p-1 border border-[#e7dec8] shrink-0">
                        <div class="flex-1 min-w-0">
                            <h5 class="text-xs font-display font-bold text-[#07160d] truncate">${item.name}</h5>
                            <div class="text-[11px] text-[#bc944c] font-semibold">${item.variant_name || 'Standard'} · ${item.unit_price_formatted}</div>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="flex items-center border border-[#d6c7af] rounded-lg bg-white shadow-2xs">
                                    <button onclick="updateCartQty('${item.key}', ${item.quantity - 1})" class="px-2 py-0.5 text-xs text-stone-600 hover:text-[#07160d] font-bold">-</button>
                                    <span class="px-2 text-xs font-bold text-[#07160d]">${item.quantity}</span>
                                    <button onclick="updateCartQty('${item.key}', ${item.quantity + 1})" class="px-2 py-0.5 text-xs text-stone-600 hover:text-[#07160d] font-bold">+</button>
                                </div>
                                <button onclick="removeCartItem('${item.key}')" class="text-xs text-red-500 hover:text-red-700 ml-auto p-1" title="Remove">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        }
    } catch (err) {
        console.error('Cart fetch failed', err);
    }
}

// Global Cart Actions
window.updateCartQty = async function(itemKey, qty) {
    try {
        const formData = new FormData();
        formData.append('item_key', itemKey);
        formData.append('quantity', qty);

        const res = await fetch(window.APP_URL + '/cart/update', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        if (json.success) {
            refreshCartDrawer();
            if (window.location.pathname.includes('/cart')) {
                window.location.reload();
            }
        } else {
            showToast(json.error || 'Could not update quantity', 'error');
        }
    } catch (e) {
        console.error(e);
    }
};

window.removeCartItem = async function(itemKey) {
    try {
        const formData = new FormData();
        formData.append('item_key', itemKey);

        const res = await fetch(window.APP_URL + '/cart/remove', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        if (json.success) {
            showToast('Item removed from cart');
            refreshCartDrawer();
            if (window.location.pathname.includes('/cart')) {
                window.location.reload();
            }
        }
    } catch (e) {
        console.error(e);
    }
};

// Alias for removeCartItem
window.removeFromCart = window.removeCartItem;

// Add to Cart Action
window.addToCart = async function(productId, variantId = null, quantity = 1, openDrawer = true) {
    try {
        const formData = new FormData();
        formData.append('product_id', productId);
        if (variantId) formData.append('variant_id', variantId);
        formData.append('quantity', quantity);

        const res = await fetch(window.APP_URL + '/cart/add', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.success) {
            showToast(json.message || 'Added to cart!');
            await refreshCartDrawer();
            if (openDrawer && typeof window.openCartDrawer === 'function') {
                window.openCartDrawer();
            }
            return true;
        } else {
            showToast(json.error || 'Could not add to cart', 'error');
            return false;
        }
    } catch (e) {
        console.error(e);
        showToast('Network error adding to cart', 'error');
        return false;
    }
};

// Instant Buy Now Flow (awaits cart addition then redirects)
window.buyNow = async function(productId, variantId = null, quantity = 1) {
    const success = await window.addToCart(productId, variantId, quantity, false);
    if (success) {
        window.location.href = window.APP_URL + '/checkout';
    }
};

// Global Coupon Handlers
window.applyCoupon = async function(customCode = null) {
    const input = document.getElementById('coupon-code-input') || document.getElementById('coupon_code') || document.getElementById('checkout-coupon-code');
    const code = customCode || (input ? input.value.trim() : '');
    if (!code) {
        showToast('Please enter a coupon code', 'error');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('coupon_code', code);

        const res = await fetch(window.APP_URL + '/cart/coupon/apply', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.success) {
            showToast(json.message || 'Coupon applied successfully!');
            setTimeout(() => window.location.reload(), 250);
        } else {
            showToast(json.error || 'Invalid or expired coupon', 'error');
        }
    } catch (e) {
        console.error(e);
        showToast('Network error applying coupon', 'error');
    }
};

window.removeCoupon = async function() {
    try {
        const res = await fetch(window.APP_URL + '/cart/coupon/remove', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.success) {
            showToast(json.message || 'Coupon removed');
            setTimeout(() => window.location.reload(), 250);
        } else {
            showToast(json.error || 'Could not remove coupon', 'error');
        }
    } catch (e) {
        console.error(e);
        showToast('Network error removing coupon', 'error');
    }
};

// Variant Selectors on Product Details & Cards
function initVariantSelectors() {
    document.querySelectorAll('.variant-selector-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const container = this.closest('.product-variant-container') || document;
            const parentBtns = container.querySelectorAll('.variant-selector-btn');
            parentBtns.forEach(b => {
                b.classList.remove('bg-[#bc944c]', 'text-[#07160d]', 'border-[#bc944c]');
                b.classList.add('bg-transparent', 'text-[#fdf6e3]/70', 'border-[#bc944c]/30');
            });

            this.classList.add('bg-[#bc944c]', 'text-[#07160d]', 'border-[#bc944c]');
            this.classList.remove('bg-transparent', 'text-[#fdf6e3]/70', 'border-[#bc944c]/30');

            const price = this.dataset.price;
            const sku = this.dataset.sku;
            const variantId = this.dataset.variantId;
            const image = this.dataset.image;

            // Update price displays in context
            const priceEl = container.querySelector('.product-price-display');
            if (priceEl && price) {
                priceEl.textContent = '₹' + parseFloat(price).toLocaleString('en-IN');
            }

            // Update hidden variant input
            const inputEl = container.querySelector('.selected-variant-input');
            if (inputEl) {
                inputEl.value = variantId;
            }

            // Update main image if available
            const mainImg = container.querySelector('.product-main-image');
            if (mainImg && image) {
                mainImg.src = image;
            }
        });
    });
}

// Wishlist Toggle
function initWishlistButtons() {
    document.querySelectorAll('.wishlist-toggle-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const pid = this.dataset.productId;
            if (!pid) return;

            try {
                const formData = new FormData();
                formData.append('product_id', pid);

                const res = await fetch(window.APP_URL + '/wishlist/toggle', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();

                if (json.require_login) {
                    showToast('Please log in to manage your wishlist', 'error');
                    setTimeout(() => window.location.href = window.APP_URL + '/login?redirect=' + encodeURIComponent(window.location.pathname), 1200);
                    return;
                }

                if (json.success) {
                    showToast(json.message);
                    const icon = this.querySelector('svg');
                    if (icon) {
                        if (json.added) {
                            icon.setAttribute('fill', '#bc944c');
                            icon.setAttribute('stroke', '#bc944c');
                        } else {
                            icon.setAttribute('fill', 'none');
                            icon.setAttribute('stroke', 'currentColor');
                        }
                    }
                    const badge = document.getElementById('wishlist-count-badge');
                    if (badge) {
                        badge.textContent = json.wishlist_count;
                        badge.style.display = json.wishlist_count > 0 ? 'flex' : 'none';
                    }
                }
            } catch (err) {
                console.error(err);
            }
        });
    });
}

// FAQ Accordion
function initFaqAccordion() {
    document.querySelectorAll('.faq-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.faq-item');
            const content = item.querySelector('.faq-content');
            const icon = btn.querySelector('.faq-icon');

            const isOpen = !content.classList.contains('hidden');
            if (isOpen) {
                content.classList.add('hidden');
                if (icon) icon.textContent = '+';
            } else {
                content.classList.remove('hidden');
                if (icon) icon.textContent = '×';
            }
        });
    });
}

// Live Search Suggestions
function initLiveSearch() {
    const input = document.getElementById('header-search-input');
    const dropdown = document.getElementById('header-search-results');
    if (!input || !dropdown) return;

    let timeout = null;
    input.addEventListener('input', (e) => {
        clearTimeout(timeout);
        const query = e.target.value.trim();
        if (query.length < 2) {
            dropdown.classList.add('hidden');
            dropdown.innerHTML = '';
            return;
        }

        timeout = setTimeout(async () => {
            try {
                const res = await fetch(`${window.APP_URL}/search?q=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();

                if (json.success && json.results && json.results.length > 0) {
                    let html = '<div class="p-2 space-y-1">';
                    json.results.forEach(prod => {
                        html += `
                            <a href="${prod.url}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/[0.08] transition-colors">
                                <img src="${prod.image}" class="w-10 h-10 object-contain rounded bg-[#07160d] p-0.5">
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-[#fdf6e3] truncate">${prod.name}</div>
                                    <div class="text-[10px] text-[#bc944c]">${prod.price} · ${prod.category}</div>
                                </div>
                            </a>
                        `;
                    });
                    html += `
                        <div class="pt-2 border-t border-[#bc944c]/20 text-center">
                            <a href="${window.APP_URL}/search?q=${encodeURIComponent(query)}" class="text-[11px] text-[#bc944c] hover:underline">View all results →</a>
                        </div>
                    </div>`;
                    dropdown.innerHTML = html;
                    dropdown.classList.remove('hidden');
                } else {
                    dropdown.innerHTML = `<div class="p-4 text-center text-xs text-[#fdf6e3]/60">No products found for "${query}"</div>`;
                    dropdown.classList.remove('hidden');
                }
            } catch (err) {
                console.error(err);
            }
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
}

// Newsletter Subscription
function initNewsletterForm() {
    const form = document.getElementById('newsletter-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = form.querySelector('input[type="email"]');
        const email = input ? input.value : '';

        const formData = new FormData();
        formData.append('email', email);

        try {
            const res = await fetch(window.APP_URL + '/newsletter/subscribe', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const json = await res.json();
            if (json.success) {
                showToast(json.message);
                if (input) input.value = '';
            } else {
                showToast(json.error || 'Subscription failed', 'error');
            }
        } catch (err) {
            console.error(err);
        }
    });
}
