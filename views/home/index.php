<!-- ====================================================================
     SECTION 1: FULL-WIDTH FULL-SCREEN E-COMMERCE BANNER SLIDER
     ==================================================================== -->
<section id="hero-slider-section" class="w-full">
    <div id="ecommerce-slider" class="relative w-full overflow-hidden shadow-sm border-b border-[#e7dec8] bg-[#07160d] h-[calc(100vh-6rem)] md:h-[calc(100vh-7rem)] min-h-[560px] group flex items-center">
        
        <!-- Slide 1: Pure Heritage Ghee -->
        <div class="banner-slide absolute inset-0 transition-opacity duration-700 opacity-100 flex items-center">
            <!-- Background Image & Gradient -->
            <div class="absolute inset-0 z-0">
                <img src="https://www.legacyfood.in/hero-desktop-1.png" alt="Pure Heritage Ghee" class="w-full h-full object-cover object-right md:object-center">
                <div class="absolute inset-0 bg-gradient-to-r from-[#07160d]/95 via-[#07160d]/75 to-transparent"></div>
            </div>

            <!-- Slide Content (Grid-Aligned with container-x) -->
            <div class="container-x w-full relative z-10 py-10 md:py-16">
                <div class="max-w-xl md:max-w-2xl">
                    <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[#bc944c] text-[#07160d] mb-4 shadow-sm">
                        Traditional Bilona Churning
                    </span>
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight drop-shadow-sm">
                        Pure Heritage Ghee
                    </h2>
                    <p class="mt-4 text-sm sm:text-base md:text-lg text-stone-200 line-clamp-3 max-w-lg leading-relaxed">
                        Hand-churned from farm-fresh South Indian butter. Granular, aromatic, and 100% lab tested.
                    </p>
                    <div class="mt-6 sm:mt-8">
                        <a href="<?= url('shop?category=ghee') ?>" class="btn-gold text-sm sm:text-base py-3 px-8 sm:py-3.5 sm:px-10 inline-flex items-center gap-2 font-semibold shadow-lg hover:shadow-xl transition-all">
                            <span>Shop Heritage Ghee</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Wood-Pressed Oils -->
        <div class="banner-slide absolute inset-0 transition-opacity duration-700 opacity-0 pointer-events-none flex items-center">
            <!-- Background Image & Gradient -->
            <div class="absolute inset-0 z-0">
                <img src="https://www.legacyfood.in/hero-desktop-2.png" alt="Wood-Pressed Oils" class="w-full h-full object-cover object-right md:object-center">
                <div class="absolute inset-0 bg-gradient-to-r from-[#07160d]/95 via-[#07160d]/75 to-transparent"></div>
            </div>

            <!-- Slide Content (Grid-Aligned with container-x) -->
            <div class="container-x w-full relative z-10 py-10 md:py-16">
                <div class="max-w-xl md:max-w-2xl">
                    <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[#bc944c] text-[#07160d] mb-4 shadow-sm">
                        Cold-Pressed Below 45°C
                    </span>
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight drop-shadow-sm">
                        Wood-Pressed Pure Oils
                    </h2>
                    <p class="mt-4 text-sm sm:text-base md:text-lg text-stone-200 line-clamp-3 max-w-lg leading-relaxed">
                        Extracted on slow wooden chekkus from sun-dried seeds. Zero heat, zero chemicals.
                    </p>
                    <div class="mt-6 sm:mt-8">
                        <a href="<?= url('shop?category=cold-pressed-oils') ?>" class="btn-gold text-sm sm:text-base py-3 px-8 sm:py-3.5 sm:px-10 inline-flex items-center gap-2 font-semibold shadow-lg hover:shadow-xl transition-all">
                            <span>Explore Pure Oils</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3: Special Welcome Offer -->
        <div class="banner-slide absolute inset-0 transition-opacity duration-700 opacity-0 pointer-events-none flex items-center">
            <!-- Background Image & Gradient -->
            <div class="absolute inset-0 z-0">
                <img src="https://www.legacyfood.in/Benefits.png" alt="Special Offer" class="w-full h-full object-cover object-right md:object-center">
                <div class="absolute inset-0 bg-gradient-to-r from-[#07160d]/95 via-[#07160d]/80 to-transparent"></div>
            </div>

            <!-- Slide Content (Grid-Aligned with container-x) -->
            <div class="container-x w-full relative z-10 py-10 md:py-16">
                <div class="max-w-xl md:max-w-2xl">
                    <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[#bc944c] text-[#07160d] mb-4 shadow-sm">
                        Welcome Discount
                    </span>
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight drop-shadow-sm">
                        10% Off First Order
                    </h2>
                    <p class="mt-4 text-sm sm:text-base md:text-lg text-stone-200 line-clamp-3 max-w-lg leading-relaxed">
                        Use coupon code <strong class="text-[#bc944c] font-mono">WELCOME10</strong> at checkout for an instant discount.
                    </p>
                    <div class="mt-6 sm:mt-8">
                        <a href="<?= url('shop') ?>" class="btn-gold text-sm sm:text-base py-3 px-8 sm:py-3.5 sm:px-10 inline-flex items-center gap-2 font-semibold shadow-lg hover:shadow-xl transition-all">
                            <span>Shop All Products</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Arrow Left -->
        <button id="slider-prev-btn" class="absolute left-4 md:left-8 lg:left-12 top-1/2 -translate-y-1/2 z-20 w-11 h-11 md:w-14 md:h-14 rounded-full bg-white/90 hover:bg-[#bc944c] text-[#07160d] hover:text-white shadow-lg flex items-center justify-center transition-all duration-200 opacity-80 group-hover:opacity-100" aria-label="Previous Slide">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <!-- Navigation Arrow Right -->
        <button id="slider-next-btn" class="absolute right-4 md:right-8 lg:right-12 top-1/2 -translate-y-1/2 z-20 w-11 h-11 md:w-14 md:h-14 rounded-full bg-white/90 hover:bg-[#bc944c] text-[#07160d] hover:text-white shadow-lg flex items-center justify-center transition-all duration-200 opacity-80 group-hover:opacity-100" aria-label="Next Slide">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Pagination Dots -->
        <div id="slider-dots" class="absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
            <button class="slider-dot w-6 h-2 rounded-full bg-[#bc944c] transition-all" data-index="0" aria-label="Slide 1"></button>
            <button class="slider-dot w-2 h-2 rounded-full bg-white/60 hover:bg-white transition-all" data-index="1" aria-label="Slide 2"></button>
            <button class="slider-dot w-2 h-2 rounded-full bg-white/60 hover:bg-white transition-all" data-index="2" aria-label="Slide 3"></button>
        </div>
    </div>
</section>

<!-- Script for Simple E-Commerce Slider -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('ecommerce-slider');
        const slides = document.querySelectorAll('.banner-slide');
        const dots = document.querySelectorAll('.slider-dot');
        const prevBtn = document.getElementById('slider-prev-btn');
        const nextBtn = document.getElementById('slider-next-btn');
        if (!slides.length) return;

        let current = 0;
        let timer = null;

        const goToSlide = (idx) => {
            current = (idx + slides.length) % slides.length;
            slides.forEach((s, i) => {
                if (i === current) {
                    s.classList.remove('opacity-0', 'pointer-events-none');
                    s.classList.add('opacity-100', 'pointer-events-auto');
                } else {
                    s.classList.add('opacity-0', 'pointer-events-none');
                    s.classList.remove('opacity-100', 'pointer-events-auto');
                }
            });

            dots.forEach((d, i) => {
                if (i === current) {
                    d.className = 'slider-dot w-6 h-2 rounded-full bg-[#bc944c] transition-all';
                } else {
                    d.className = 'slider-dot w-2 h-2 rounded-full bg-white/60 hover:bg-white transition-all';
                }
            });
        };

        const nextSlide = () => goToSlide(current + 1);
        const prevSlide = () => goToSlide(current - 1);

        const startAutoplay = () => {
            stopAutoplay();
            timer = setInterval(nextSlide, 5000);
        };

        const stopAutoplay = () => {
            if (timer) clearInterval(timer);
        };

        prevBtn?.addEventListener('click', () => {
            prevSlide();
            startAutoplay();
        });

        nextBtn?.addEventListener('click', () => {
            nextSlide();
            startAutoplay();
        });

        dots.forEach((dot, idx) => {
            dot.addEventListener('click', () => {
                goToSlide(idx);
                startAutoplay();
            });
        });

        // Pause on mouse hover for user reading ease
        slider?.addEventListener('mouseenter', stopAutoplay);
        slider?.addEventListener('mouseleave', startAutoplay);

        // Touch swipe support for mobile e-commerce feel
        let startX = 0;
        slider?.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            stopAutoplay();
        }, { passive: true });

        slider?.addEventListener('touchend', (e) => {
            const endX = e.changedTouches[0].clientX;
            const diff = startX - endX;
            if (diff > 50) {
                nextSlide();
            } else if (diff < -50) {
                prevSlide();
            }
            startAutoplay();
        }, { passive: true });

        startAutoplay();
    });
</script>

<!-- ====================================================================
     SECTION 2: PRODUCT CATEGORIES
     ==================================================================== -->
<section class="py-16 md:py-24 border-b border-[#bc944c]/15 relative">
    <div class="container-x">
        <div class="text-center max-w-xl mx-auto mb-12">
            <div class="mb-3 flex items-center justify-center gap-3">
                <span class="gold-line"></span>
                <span class="eyebrow">Heritage Collections</span>
                <span class="gold-line rotate-180"></span>
            </div>
            <h2 class="font-display text-2xl md:text-4xl text-[#07160d]">
                Artisanal Goodness <span class="text-shimmer">Direct from Origin</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
            <!-- Ghee Category Card -->
            <a href="<?= url('category/ghee') ?>" class="group relative rounded-3xl overflow-hidden border border-[#e7dec8] bg-white p-8 md:p-10 transition-all duration-500 hover:border-[#bc944c] hover:shadow-[0_20px_50px_rgba(188,148,76,0.15)] flex flex-col justify-between min-h-[300px]">
                <div class="absolute right-0 bottom-0 w-1/2 h-full opacity-40 md:opacity-60 transition-transform duration-700 group-hover:scale-110 flex items-end justify-end pointer-events-none">
                    <img src="https://cdn.sanity.io/images/dwps51kj/production/adbe8001d264661c9bbd49ef61f0f8d00894dba9-1080x1080.png?w=640" alt="Ghee" class="max-h-64 object-contain">
                </div>
                <div class="relative z-10 max-w-sm">
                    <span class="px-3 py-1 rounded-full text-[11px] font-semibold uppercase tracking-wider bg-[#bc944c]/15 text-[#bc944c] border border-[#bc944c]/30">Collection 01</span>
                    <h3 class="font-display text-2xl md:text-3xl text-[#07160d] mt-3 group-hover:text-[#bc944c] transition-colors">
                        Pure Heritage Ghee
                    </h3>
                    <p class="mt-2 text-xs md:text-sm text-stone-600 leading-relaxed">
                        Hand-churned Cow Ghee & rich Buffalo Ghee made strictly from grassroots South Indian farm butter.
                    </p>
                </div>
                <div class="relative z-10 mt-8 flex items-center gap-2 text-xs uppercase tracking-widest font-semibold text-[#bc944c] group-hover:translate-x-2 transition-transform">
                    <span>Explore Ghee Range</span>
                    <span>→</span>
                </div>
            </a>

            <!-- Cold Pressed Oils Category Card -->
            <a href="<?= url('category/cold-pressed-oils') ?>" class="group relative rounded-3xl overflow-hidden border border-[#e7dec8] bg-white p-8 md:p-10 transition-all duration-500 hover:border-[#bc944c] hover:shadow-[0_20px_50px_rgba(188,148,76,0.15)] flex flex-col justify-between min-h-[300px]">
                <div class="absolute right-0 bottom-0 w-1/2 h-full opacity-40 md:opacity-60 transition-transform duration-700 group-hover:scale-110 flex items-end justify-end pointer-events-none">
                    <img src="https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640" alt="Oils" class="max-h-64 object-contain">
                </div>
                <div class="relative z-10 max-w-sm">
                    <span class="px-3 py-1 rounded-full text-[11px] font-semibold uppercase tracking-wider bg-[#bc944c]/15 text-[#bc944c] border border-[#bc944c]/30">Collection 02</span>
                    <h3 class="font-display text-2xl md:text-3xl text-[#07160d] mt-3 group-hover:text-[#bc944c] transition-colors">
                        Wood-Pressed Oils
                    </h3>
                    <p class="mt-2 text-xs md:text-sm text-stone-600 leading-relaxed">
                        Extracted at ambient temperature on slow wooden chekkus. Groundnut, Coconut, Sesame & Mustard.
                    </p>
                </div>
                <div class="relative z-10 mt-8 flex items-center gap-2 text-xs uppercase tracking-widest font-semibold text-[#bc944c] group-hover:translate-x-2 transition-transform">
                    <span>Explore Oils Range</span>
                    <span>→</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ====================================================================
     SECTION 3: FEATURED PRODUCTS
     ==================================================================== -->
<section id="products" class="py-16 md:py-28 relative">
    <div class="container-x">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <div class="mb-3 flex items-center gap-3">
                    <span class="gold-line"></span>
                    <span class="eyebrow">Our Products</span>
                </div>
                <h2 class="font-display text-2xl md:text-4xl text-[#07160d]">
                    Choose Your <span class="text-shimmer">Legacy</span>
                </h2>
                <p class="text-xs md:text-sm text-stone-600 mt-2 max-w-lg">
                    Pure ghee and wood-pressed oils. Select your desired size and add to cart or order instantly on WhatsApp.
                </p>
            </div>
            <div>
                <a href="<?= url('shop') ?>" class="inline-flex items-center gap-2 text-xs uppercase tracking-wider text-[#bc944c] hover:underline font-semibold">
                    <span>View Full Catalog</span>
                    <span>→</span>
                </a>
            </div>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($featuredProducts as $prod): ?>
                <?php $product = $prod; include __DIR__ . '/../components/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====================================================================
     SECTION 4: SIGNATURE JAR 3D SHOWCASE
     ==================================================================== -->
<section id="showcase" class="relative overflow-hidden bg-[#f5eee2] py-20 md:py-32 border-y border-[#bc944c]/20">
    <div class="pointer-events-none absolute left-1/2 top-1/2 h-[60vmin] w-[60vmin] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[radial-gradient(circle,rgba(188,148,76,0.18),transparent_65%)] blur-3xl"></div>

    <div class="container-x relative z-10">
        <div class="mb-14 text-center max-w-xl mx-auto">
            <div class="mb-3 flex items-center justify-center gap-3">
                <span class="gold-line"></span>
                <span class="eyebrow text-[#bc944c]">The Signature Jar</span>
                <span class="gold-line rotate-180"></span>
            </div>
            <h2 class="font-display text-2xl md:text-5xl text-[#07160d]">
                Made to be <span class="text-shimmer">Savoured</span>
            </h2>
            <p class="mx-auto mt-3 text-xs md:text-sm text-stone-600">Every detail crafted for absolute purity and heritage.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto_1fr] items-center gap-8 md:gap-14">
            <!-- Left Points -->
            <div class="flex flex-col gap-8 md:gap-12">
                <div class="text-left lg:text-right">
                    <span class="font-body text-[11px] tracking-[0.3em] text-[#bc944c] font-bold">01</span>
                    <h3 class="mt-1 font-display text-lg text-[#07160d]">Natural Granular Texture</h3>
                    <div class="my-2 h-px w-10 bg-[#bc944c]/40 lg:ml-auto"></div>
                    <p class="font-body text-xs text-stone-600 leading-relaxed">
                        A universal hallmark of authentic cow butter ghee. Real ghee granulates naturally at room temperature with zero binders or stabilizers.
                    </p>
                </div>

                <div class="text-left lg:text-right">
                    <span class="font-body text-[11px] tracking-[0.3em] text-[#bc944c] font-bold">03</span>
                    <h3 class="mt-1 font-display text-lg text-[#07160d]">Traditional Small Batch Churning</h3>
                    <div class="my-2 h-px w-10 bg-[#bc944c]/40 lg:ml-auto"></div>
                    <p class="font-body text-xs text-stone-600 leading-relaxed">
                        Crafted patiently in brass vessels following authentic bilona principles. Patience and time over industrial speed.
                    </p>
                </div>
            </div>

            <!-- Center Signature Jar Image with Floating Effect -->
            <div class="relative mx-auto h-[320px] w-[260px] md:h-[400px] md:w-[320px] flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border border-[#bc944c]/30"></div>
                <div class="absolute inset-4 rounded-full border border-dashed border-[#bc944c]/20"></div>
                <img src="https://www.legacyfood.in/Desi%20Cow%20Ghee.png" alt="Legacy Desi Cow Ghee Jar" class="animate-float max-h-[340px] md:max-h-[420px] object-contain drop-shadow-[0_25px_50px_rgba(188,148,76,0.35)]">
            </div>

            <!-- Right Points -->
            <div class="flex flex-col gap-8 md:gap-12">
                <div class="text-left">
                    <span class="font-body text-[11px] tracking-[0.3em] text-[#bc944c] font-bold">02</span>
                    <h3 class="mt-1 font-display text-lg text-[#07160d]">Sealed for Peak Freshness</h3>
                    <div class="my-2 h-px w-10 bg-[#bc944c]/40"></div>
                    <p class="font-body text-xs text-stone-600 leading-relaxed">
                        Our signature airtight heritage-gold lid protects the precious nutty aroma and volatile buttery notes from the churn straight to your kitchen.
                    </p>
                </div>

                <div class="text-left">
                    <span class="font-body text-[11px] tracking-[0.3em] text-[#bc944c] font-bold">04</span>
                    <h3 class="mt-1 font-display text-lg text-[#07160d]">Glass Packaging Protection</h3>
                    <div class="my-2 h-px w-10 bg-[#bc944c]/40"></div>
                    <p class="font-body text-xs text-stone-600 leading-relaxed">
                        We never pack our pure ghee in plastic jars. 100% recyclable lead-free glass protects taste, prevents leaching, and preserves freshness.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-14 text-center">
            <a href="https://wa.me/919845279936?text=<?= urlencode('Hi Legacy, I would like to order Cow Ghee') ?>" target="_blank" class="btn-gold">
                Order Ghee on WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- ====================================================================
     SECTION 5: WHY CHOOSE LEGACY FOOD
     ==================================================================== -->
<section id="why" class="py-16 md:py-28 relative">
    <div class="container-x">
        <div class="mb-14 text-center max-w-xl mx-auto">
            <div class="mb-3 flex items-center justify-center gap-3">
                <span class="gold-line"></span>
                <span class="eyebrow">Why Legacy</span>
                <span class="gold-line rotate-180"></span>
            </div>
            <h2 class="font-display text-2xl md:text-4xl text-[#07160d]">
                A Standard Set by Tradition, <span class="text-shimmer">Kept by Us</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- USP 1 -->
            <div class="card-glow bg-white p-6 md:p-8 rounded-2xl border border-[#e7dec8] shadow-sm group hover:-translate-y-2 transition-transform duration-300">
                <div class="w-14 h-14 rounded-2xl bg-[#bc944c]/10 border border-[#bc944c]/30 text-[#bc944c] flex items-center justify-center mb-6 group-hover:bg-[#bc944c] group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zM9 22V12h6v10"/></svg>
                </div>
                <h3 class="font-display text-lg text-[#07160d]">Farm Butter, Directly Sourced</h3>
                <p class="mt-3 text-xs md:text-sm text-stone-600 leading-relaxed">
                    We work with smallholder dairy farmers across South India — sourcing authentic butter before middlemen ever enter the picture.
                </p>
            </div>

            <!-- USP 2 -->
            <div class="card-glow bg-white p-6 md:p-8 rounded-2xl border border-[#e7dec8] shadow-sm group hover:-translate-y-2 transition-transform duration-300">
                <div class="w-14 h-14 rounded-2xl bg-[#bc944c]/10 border border-[#bc944c]/30 text-[#bc944c] flex items-center justify-center mb-6 group-hover:bg-[#bc944c] group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-display text-lg text-[#07160d]">Lab-Certified GC Purity</h3>
                <p class="mt-3 text-xs md:text-sm text-stone-600 leading-relaxed">
                    Every batch undergoes Gaschromat (GC) fatty-acid testing at government-recognised NABL accredited laboratories. Zero shortcuts.
                </p>
            </div>

            <!-- USP 3 -->
            <div class="card-glow bg-white p-6 md:p-8 rounded-2xl border border-[#e7dec8] shadow-sm group hover:-translate-y-2 transition-transform duration-300">
                <div class="w-14 h-14 rounded-2xl bg-[#bc944c]/10 border border-[#bc944c]/30 text-[#bc944c] flex items-center justify-center mb-6 group-hover:bg-[#bc944c] group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-display text-lg text-[#07160d]">Freshness You Can Taste</h3>
                <p class="mt-3 text-xs md:text-sm text-stone-600 leading-relaxed">
                    No chemical deodorizers, no palm oil blending, no long storage warehouse cycles. Pure goodness from churn to jar.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ====================================================================
     SECTION 6: LIVING NUTRITION — MARA CHEKKU WOOD-PRESSED OILS
     ==================================================================== -->
<section id="science" class="py-16 md:py-24 bg-[#f8f5ee] border-y border-[#e7dec8] relative">
    <div class="container-x">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <div class="mb-3 flex items-center justify-center gap-3">
                <span class="gold-line"></span>
                <span class="eyebrow">Living Nutrition · Mara Chekku Heritage</span>
                <span class="gold-line rotate-180"></span>
            </div>
            <h2 class="font-display text-2xl md:text-5xl text-[#07160d] font-bold">
                Pressed the Way Nature <span class="text-shimmer">Intended</span>
            </h2>
            <p class="mt-4 text-xs md:text-sm text-stone-600 leading-relaxed font-body">
                Extracted strictly below 45°C on slow wooden pestles without frictional heat or chemical solvents. Retaining live enzymes, uncompromised micronutrients, and authentic South Indian aroma.
            </p>

            <!-- Quality Badges Row -->
            <div class="mt-6 flex flex-wrap justify-center gap-2.5">
                <span class="px-3.5 py-1.5 rounded-full border border-[#bc944c]/30 text-[#bc944c] bg-white text-[11px] font-bold uppercase tracking-wider shadow-sm">
                    🪵 Mara Chekku &lt; 45°C
                </span>
                <span class="px-3.5 py-1.5 rounded-full border border-[#bc944c]/30 text-[#bc944c] bg-white text-[11px] font-bold uppercase tracking-wider shadow-sm">
                    🔬 NABL Lab GC Certified
                </span>
                <span class="px-3.5 py-1.5 rounded-full border border-[#bc944c]/30 text-[#bc944c] bg-white text-[11px] font-bold uppercase tracking-wider shadow-sm">
                    🚫 Zero Hexane / Solvents
                </span>
                <span class="px-3.5 py-1.5 rounded-full border border-[#bc944c]/30 text-[#bc944c] bg-white text-[11px] font-bold uppercase tracking-wider shadow-sm">
                    🌱 100% Raw & Unrefined
                </span>
            </div>
        </div>

        <!-- 4-Column Oil Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Oil 1: Groundnut -->
            <div class="bg-white rounded-3xl p-6 border border-[#e7dec8] shadow-sm hover:shadow-xl hover:border-[#bc944c] transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#bc944c]/15 text-[#bc944c]">
                            Heart Health
                        </span>
                        <span class="text-[11px] text-stone-600 font-bold">High MUFA</span>
                    </div>

                    <div class="h-36 w-full flex items-center justify-center p-2 mb-3 bg-[#faf8f5] rounded-2xl group-hover:scale-105 transition-transform duration-300">
                        <img src="https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640" alt="Wood Pressed Groundnut Oil" class="max-h-32 object-contain drop-shadow-md">
                    </div>

                    <h3 class="font-display text-lg font-bold text-[#07160d] group-hover:text-[#bc944c] transition-colors">
                        Wood-Pressed Groundnut
                    </h3>
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        Pressed from sun-dried Saurashtra peanuts. High natural Vitamin E with smoke point of 225°C.
                    </p>

                    <div class="mt-4 pt-3 border-t border-stone-100 space-y-1.5 text-[11px] text-stone-600 font-medium">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#bc944c]">✔</span>
                            <span>Smoke Point: <strong>225°C (High Heat)</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#bc944c]">✔</span>
                            <span>Best for: <strong>Daily Curries & Tadka</strong></span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="<?= url('product/cold-pressed-groundnut-oil') ?>" class="w-full py-2.5 px-4 text-center rounded-xl border border-[#bc944c]/30 text-xs font-bold text-[#07160d] group-hover:bg-[#bc944c] group-hover:text-white group-hover:border-[#bc944c] transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Explore Groundnut Oil</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Oil 2: Coconut -->
            <div class="bg-white rounded-3xl p-6 border border-[#e7dec8] shadow-sm hover:shadow-xl hover:border-[#bc944c] transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#bc944c]/15 text-[#bc944c]">
                            Immunity & MCTs
                        </span>
                        <span class="text-[11px] text-stone-600 font-bold">&gt;50% Lauric</span>
                    </div>

                    <div class="h-36 w-full flex items-center justify-center p-2 mb-3 bg-[#faf8f5] rounded-2xl group-hover:scale-105 transition-transform duration-300">
                        <img src="https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640" alt="Wood Pressed Coconut Oil" class="max-h-32 object-contain drop-shadow-md">
                    </div>

                    <h3 class="font-display text-lg font-bold text-[#07160d] group-hover:text-[#bc944c] transition-colors">
                        Wood-Pressed Coconut
                    </h3>
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        Extracted from sulfur-free sun-dried coconut copra. Abundant in Lauric Acid and clean Medium-Chain Triglycerides.
                    </p>

                    <div class="mt-4 pt-3 border-t border-stone-100 space-y-1.5 text-[11px] text-stone-600 font-medium">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#bc944c]">✔</span>
                            <span>Nutrient: <strong>50%+ Lauric Acid</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#bc944c]">✔</span>
                            <span>Best for: <strong>Raw Drizzle & Kerala Dishes</strong></span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="<?= url('product/cold-pressed-coconut-oil') ?>" class="w-full py-2.5 px-4 text-center rounded-xl border border-[#bc944c]/30 text-xs font-bold text-[#07160d] group-hover:bg-[#bc944c] group-hover:text-white group-hover:border-[#bc944c] transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Explore Coconut Oil</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Oil 3: Sesame (Gingelly) -->
            <div class="bg-white rounded-3xl p-6 border border-[#e7dec8] shadow-sm hover:shadow-xl hover:border-[#bc944c] transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#bc944c]/15 text-[#bc944c]">
                            Antioxidant Rich
                        </span>
                        <span class="text-[11px] text-stone-600 font-bold">Sesamol Plus</span>
                    </div>

                    <div class="h-36 w-full flex items-center justify-center p-2 mb-3 bg-[#faf8f5] rounded-2xl group-hover:scale-105 transition-transform duration-300">
                        <img src="https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640" alt="Wood Pressed Sesame Oil" class="max-h-32 object-contain drop-shadow-md">
                    </div>

                    <h3 class="font-display text-lg font-bold text-[#07160d] group-hover:text-[#bc944c] transition-colors">
                        Traditional Gingelly Sesame
                    </h3>
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        Cold-pressed with traditional palm jaggery on wooden chekkus. Packed with natural sesamol and minerals.
                    </p>

                    <div class="mt-4 pt-3 border-t border-stone-100 space-y-1.5 text-[11px] text-stone-600 font-medium">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#bc944c]">✔</span>
                            <span>Traditional: <strong>Palm Jaggery Blend</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#bc944c]">✔</span>
                            <span>Best for: <strong>Idli Podi, Sambar & Gravies</strong></span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="<?= url('product/cold-pressed-sesame-oil') ?>" class="w-full py-2.5 px-4 text-center rounded-xl border border-[#bc944c]/30 text-xs font-bold text-[#07160d] group-hover:bg-[#bc944c] group-hover:text-white group-hover:border-[#bc944c] transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Explore Sesame Oil</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Oil 4: Mustard -->
            <div class="bg-white rounded-3xl p-6 border border-[#e7dec8] shadow-sm hover:shadow-xl hover:border-[#bc944c] transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#bc944c]/15 text-[#bc944c]">
                            Digestive Fire
                        </span>
                        <span class="text-[11px] text-stone-600 font-bold">Omega-3 ALA</span>
                    </div>

                    <div class="h-36 w-full flex items-center justify-center p-2 mb-3 bg-[#faf8f5] rounded-2xl group-hover:scale-105 transition-transform duration-300">
                        <img src="https://cdn.sanity.io/images/dwps51kj/production/aea21b16ec9f46ed11c9ec0b7e2e1383018bb170-810x1080.png?w=640" alt="Kachi Ghani Mustard Oil" class="max-h-32 object-contain drop-shadow-md">
                    </div>

                    <h3 class="font-display text-lg font-bold text-[#07160d] group-hover:text-[#bc944c] transition-colors">
                        Kachi Ghani Mustard
                    </h3>
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        Cold-churned black mustard seeds with intense natural pungency. High in essential Omega-3 and allyl isothiocyanate.
                    </p>

                    <div class="mt-4 pt-3 border-t border-stone-100 space-y-1.5 text-[11px] text-stone-600 font-medium">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#bc944c]">✔</span>
                            <span>Nutrient: <strong>Natural Omega-3 (ALA)</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#bc944c]">✔</span>
                            <span>Best for: <strong>Pickles, Roasting & Gravies</strong></span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="<?= url('product/cold-pressed-mustard-oil') ?>" class="w-full py-2.5 px-4 text-center rounded-xl border border-[#bc944c]/30 text-xs font-bold text-[#07160d] group-hover:bg-[#bc944c] group-hover:text-white group-hover:border-[#bc944c] transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Explore Mustard Oil</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Purity Assurance Bar -->
        <div class="mt-12 bg-white rounded-3xl p-6 sm:p-8 border border-[#e7dec8] shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-center">
            <div class="p-2">
                <div class="w-10 h-10 rounded-full bg-[#bc944c]/15 text-[#bc944c] font-bold mx-auto flex items-center justify-center mb-2.5 text-base">
                    🪵
                </div>
                <h4 class="font-display text-sm font-bold text-[#07160d]">Vaagai Mara Chekku</h4>
                <p class="text-[11px] text-stone-500 mt-1">Slow wood press below 45°C. No thermal destruction of delicate vitamins.</p>
            </div>
            <div class="p-2">
                <div class="w-10 h-10 rounded-full bg-[#bc944c]/15 text-[#bc944c] font-bold mx-auto flex items-center justify-center mb-2.5 text-base">
                    🔬
                </div>
                <h4 class="font-display text-sm font-bold text-[#07160d]">NABL GC Testing</h4>
                <p class="text-[11px] text-stone-500 mt-1">Every batch tested for genuine fatty-acid distribution and zero adulterants.</p>
            </div>
            <div class="p-2">
                <div class="w-10 h-10 rounded-full bg-[#bc944c]/15 text-[#bc944c] font-bold mx-auto flex items-center justify-center mb-2.5 text-base">
                    🌿
                </div>
                <h4 class="font-display text-sm font-bold text-[#07160d]">Zero Chemical Solvents</h4>
                <p class="text-[11px] text-stone-500 mt-1">100% chemical free. Never treated with hexane, bleach, or artificial aroma.</p>
            </div>
            <div class="p-2">
                <div class="w-10 h-10 rounded-full bg-[#bc944c]/15 text-[#bc944c] font-bold mx-auto flex items-center justify-center mb-2.5 text-base">
                    🫙
                </div>
                <h4 class="font-display text-sm font-bold text-[#07160d]">UV-Safe Packaging</h4>
                <p class="text-[11px] text-stone-500 mt-1">Bottled to shield active antioxidants and phytosterols from light degradation.</p>
            </div>
        </div>
    </div>
</section>


<!-- ====================================================================
     SECTION 8: CUSTOMER TESTIMONIALS
     ==================================================================== -->
<section id="testimonials" class="py-16 md:py-28 bg-[#f5eee2] border-y border-[#bc944c]/15 relative">
    <div class="container-x">
        <div class="mb-14 text-center max-w-xl mx-auto">
            <div class="mb-3 flex items-center justify-center gap-3">
                <span class="gold-line"></span>
                <span class="eyebrow">Customer Voices</span>
                <span class="gold-line rotate-180"></span>
            </div>
            <h2 class="font-display text-2xl md:text-5xl text-[#07160d]">
                Loved Across <span class="text-shimmer">India</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($testimonials as $t): ?>
                <div class="card-glow bg-white p-6 md:p-8 rounded-3xl border border-[#e7dec8] shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex text-amber-500 mb-4">
                            <?php for ($i = 0; $i < (int)$t['rating']; $i++): ?>
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                        </div>
                        <p class="font-display text-sm md:text-base text-stone-800 leading-relaxed italic">
                            “<?= e($t['review']) ?>”
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-3 pt-4 border-t border-[#bc944c]/15">
                        <div class="w-9 h-9 rounded-full bg-[#bc944c]/15 text-[#bc944c] flex items-center justify-center font-bold text-sm">
                            ✦
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-[#07160d]"><?= e($t['customer_name']) ?></div>
                            <div class="text-[11px] text-[#bc944c]"><?= e($t['location']) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


