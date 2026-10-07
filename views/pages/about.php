<div class="py-12 md:py-20">
    <div class="container-x max-w-5xl">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-8" aria-label="Breadcrumb">
            <a href="<?= url('/') ?>" class="hover:text-[#bc944c] transition-colors">Home</a>
            <span>/</span>
            <span class="text-stone-900 font-semibold">About Us</span>
        </nav>

        <!-- Page Header -->
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="eyebrow">Our Heritage & Origins</span>
            <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold mt-2">
                Rooted in <span class="text-shimmer">Purity</span>
            </h1>
            <p class="text-xs md:text-sm text-stone-600 mt-3 leading-relaxed">
                Hand-churned farm butter, small batches, and 100% NABL lab certification. The golden standard set by South Indian tradition, preserved by Legacy Food.
            </p>
        </div>

        <!-- ====================================================================
             FEATURED ABOUT COMPANY SECTION (From Homepage)
             ==================================================================== -->
        <div class="bg-white border border-[#e7dec8] rounded-3xl p-8 md:p-14 shadow-sm mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Brand Seal Visual -->
                <div class="relative mx-auto w-full max-w-md aspect-square rounded-3xl border border-[#bc944c]/30 bg-gradient-to-b from-[#faf8f5] to-[#f4ebe1] p-10 shadow-inner flex flex-col items-center justify-center text-center relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#bc944c]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <img src="<?= asset('assets/images/branding/legacy-seal.svg') ?>" class="w-44 h-44 object-contain opacity-95 drop-shadow-[0_15px_30px_rgba(188,148,76,0.25)] transition-transform duration-500 group-hover:scale-105" alt="Legacy Seal">
                    
                    <div class="mt-6 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 border border-[#bc944c]/30 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-[#bc944c] animate-pulse"></span>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-[#07160d]">Crafted in South India</span>
                    </div>
                </div>

                <!-- Story & Narrative -->
                <div>
                    <div class="mb-3 flex items-center gap-3">
                        <span class="gold-line"></span>
                        <span class="eyebrow">About Company</span>
                    </div>
                    <h2 class="font-display text-2xl md:text-4xl text-[#07160d] font-bold leading-tight">
                        A Passion for Authentic Indian Food
                    </h2>
                    
                    <div class="mt-5 space-y-4 text-xs md:text-sm text-stone-700 leading-relaxed font-body">
                        <p>
                            <strong>LEGACY</strong> was born from a simple belief — that the best ghee in the world is still made in South Indian kitchens, by people who treat it as more than just a commodity.
                        </p>
                        <p>
                            We source farm butter directly from smallholder South Indian farmers across Tamil Nadu and Karnataka, churn in small batches using traditional methods, test every jar at NABL-accredited labs, and pack in glass because pure ghee deserves nothing less.
                        </p>
                        <p>
                            The art of churning butter and extracting cold-pressed oils has been passed down through generations here. The standards have never changed. This is not industrial ghee. It never was.
                        </p>
                    </div>

                    <!-- Stats Counters -->
                    <div class="mt-8 grid grid-cols-3 gap-4 pt-6 border-t border-[#bc944c]/20">
                        <div>
                            <div class="font-display text-2xl md:text-3xl font-bold text-[#bc944c]">Est. 2011</div>
                            <div class="text-[11px] text-stone-500 mt-1">Family dairy kitchen</div>
                        </div>
                        <div>
                            <div class="font-display text-2xl md:text-3xl font-bold text-[#bc944c]">50,000+</div>
                            <div class="text-[11px] text-stone-500 mt-1">Jars delivered</div>
                        </div>
                        <div>
                            <div class="font-display text-2xl md:text-3xl font-bold text-[#bc944c]">4.9★</div>
                            <div class="text-[11px] text-stone-500 mt-1">Customer rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================================================
             OUR PILLARS OF CRAFTSMANSHIP
             ==================================================================== -->
        <div class="mb-16">
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="eyebrow">Our Philosophy</span>
                <h2 class="font-display text-2xl md:text-3xl text-[#07160d] font-bold mt-2">
                    How We Ensure Absolute Purity
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-[#e7dec8] p-7 rounded-2xl shadow-sm hover:border-[#bc944c]/50 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-[#bc944c]/10 text-[#bc944c] flex items-center justify-center font-bold text-lg mb-4">
                        01
                    </div>
                    <h3 class="font-display text-base font-bold text-[#07160d]">Smallholder Dairy Partners</h3>
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        We work directly with grassroots dairy farmers across Karnataka and Tamil Nadu, ensuring fair ethical compensation and the freshest morning butter.
                    </p>
                </div>

                <div class="bg-white border border-[#e7dec8] p-7 rounded-2xl shadow-sm hover:border-[#bc944c]/50 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-[#bc944c]/10 text-[#bc944c] flex items-center justify-center font-bold text-lg mb-4">
                        02
                    </div>
                    <h3 class="font-display text-base font-bold text-[#07160d]">Traditional Slow Boiling</h3>
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        Slow-boiled in small batches over controlled low heat to allow milk solids to gently caramelize, releasing the signature nutty aroma and natural golden grains.
                    </p>
                </div>

                <div class="bg-white border border-[#e7dec8] p-7 rounded-2xl shadow-sm hover:border-[#bc944c]/50 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-[#bc944c]/10 text-[#bc944c] flex items-center justify-center font-bold text-lg mb-4">
                        03
                    </div>
                    <h3 class="font-display text-base font-bold text-[#07160d]">Scientific Lab Verification</h3>
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        Every single batch undergoes Gaschromat (GC) testing at NABL-accredited government laboratories to certify zero palm fat, mineral oils, or synthetic adulterants.
                    </p>
                </div>
            </div>
        </div>

        <!-- ====================================================================
             TRUST BADGES
             ==================================================================== -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-16 text-center">
            <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm">
                <div class="font-display text-xl md:text-2xl font-bold text-[#bc944c]">100% NABL</div>
                <div class="text-[11px] text-stone-600 mt-1 font-medium">Lab Certified</div>
            </div>
            <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm">
                <div class="font-display text-xl md:text-2xl font-bold text-[#bc944c]">Zero Palm Fat</div>
                <div class="text-[11px] text-stone-600 mt-1 font-medium">0% Adulterants</div>
            </div>
            <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm">
                <div class="font-display text-xl md:text-2xl font-bold text-[#bc944c]">Glass Packed</div>
                <div class="text-[11px] text-stone-600 mt-1 font-medium">UV-Safe Jars</div>
            </div>
            <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm">
                <div class="font-display text-xl md:text-2xl font-bold text-[#bc944c]">Direct Sourced</div>
                <div class="text-[11px] text-stone-600 mt-1 font-medium">Grassroots Farmers</div>
            </div>
        </div>

        <!-- ====================================================================
             EXPERIENCE AUTHENTIC SOUTH INDIA (CONTACT & CTA BANNER)
             ==================================================================== -->
        <div class="bg-gradient-to-b from-[#f5eee2] to-[#e8decb] border border-[#bc944c]/30 rounded-3xl p-8 md:p-14 text-center relative overflow-hidden shadow-sm">
            <div class="relative z-10 max-w-2xl mx-auto">
                <span class="eyebrow">Experience Authentic South India</span>
                <h2 class="font-display text-3xl md:text-5xl text-[#07160d] mt-3 font-bold">
                    Taste the <span class="text-shimmer">Legacy</span> Today
                </h2>
                <p class="mt-4 text-xs md:text-sm text-stone-700 leading-relaxed">
                    Order online in seconds or message our team directly on WhatsApp for prompt South Indian delivery.
                </p>

                <div class="mt-8 flex flex-wrap justify-center items-center gap-4">
                    <a href="<?= url('shop') ?>" class="btn-gold text-xs sm:text-sm py-3 px-8 shadow-sm">
                        Browse All Products
                    </a>
                    <a href="https://wa.me/919845279936" target="_blank" class="btn-ghost text-xs sm:text-sm py-3 px-8">
                        Chat on WhatsApp
                    </a>
                </div>

                <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 pt-8 border-t border-[#bc944c]/20">
                    <div class="p-3">
                        <span class="text-[10px] uppercase tracking-widest text-[#bc944c] block font-semibold">Call Us</span>
                        <span class="text-sm font-semibold text-[#07160d] mt-1 block">+91 81234 50509</span>
                    </div>
                    <div class="p-3">
                        <span class="text-[10px] uppercase tracking-widest text-[#bc944c] block font-semibold">Email Support</span>
                        <span class="text-sm font-semibold text-[#07160d] mt-1 block">Contact@legacyfood.in</span>
                    </div>
                    <div class="p-3">
                        <span class="text-[10px] uppercase tracking-widest text-[#bc944c] block font-semibold">Location</span>
                        <span class="text-sm font-semibold text-[#07160d] mt-1 block">Bangalore, Karnataka</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
