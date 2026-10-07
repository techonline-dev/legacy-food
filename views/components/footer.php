<footer class="border-t-2 border-[#bc944c]/30 bg-[#08130c] pt-16 pb-12 text-[#e9dcc2]">
    <div class="container-x">
        <!-- Main Footer Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-[#bc944c]/15">
            <!-- Brand Column -->
            <div class="lg:col-span-2">
                <a href="<?= url('/') ?>" class="inline-block">
                    <img src="<?= asset('assets/images/branding/header-logo-light.svg') ?>" alt="Legacy Food" class="h-12 w-auto">
                </a>
                <p class="mt-4 text-sm text-[#e9dcc2]/70 leading-relaxed max-w-sm">
                    Rooted in purity and craftsmanship. Hand-churned South Indian butter ghee and cold-pressed oils prepared the traditional way. 100% lab certified, zero adulteration.
                </p>
                <div class="mt-6 flex items-center gap-4">
                    <a href="https://www.instagram.com/legacy.ghee?utm_source=qr" target="_blank" rel="noopener" class="w-9 h-9 rounded-full border border-[#bc944c]/30 flex items-center justify-center text-[#bc944c] hover:bg-[#bc944c] hover:text-[#07160d] transition-all" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/share/1E2eLKSKph/?mibextid=wwXIfr" target="_blank" rel="noopener" class="w-9 h-9 rounded-full border border-[#bc944c]/30 flex items-center justify-center text-[#bc944c] hover:bg-[#bc944c] hover:text-[#07160d] transition-all" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </a>
                    <a href="https://wa.me/919845279936" target="_blank" rel="noopener" class="w-9 h-9 rounded-full border border-[#bc944c]/30 flex items-center justify-center text-[#bc944c] hover:bg-[#bc944c] hover:text-[#07160d] transition-all" aria-label="WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Products Links -->
            <div>
                <h4 class="font-body text-xs uppercase tracking-widest text-[#bc944c] font-semibold">Our Heritage</h4>
                <ul class="mt-4 space-y-2.5 text-xs text-[#e9dcc2]/70">
                    <li><a href="<?= url('shop') ?>" class="text-[#bc944c] font-bold hover:underline flex items-center gap-1.5 pb-1"><span>Shop All Products</span> <span>→</span></a></li>
                    <li><a href="<?= url('product/cow-ghee') ?>" class="hover:text-[#bc944c] transition-colors">Pure Cow Ghee</a></li>
                    <li><a href="<?= url('product/buffalo-ghee') ?>" class="hover:text-[#bc944c] transition-colors">Buffalo Ghee</a></li>
                    <li><a href="<?= url('product/cold-pressed-groundnut-oil') ?>" class="hover:text-[#bc944c] transition-colors">Wood-Pressed Groundnut Oil</a></li>
                    <li><a href="<?= url('product/cold-pressed-coconut-oil') ?>" class="hover:text-[#bc944c] transition-colors">Sun-Dried Coconut Oil</a></li>
                    <li><a href="<?= url('product/cold-pressed-sesame-oil') ?>" class="hover:text-[#bc944c] transition-colors">Traditional Gingelly Oil</a></li>
                    <li><a href="<?= url('product/cold-pressed-mustard-oil') ?>" class="hover:text-[#bc944c] transition-colors">Pungent Mustard Oil</a></li>
                </ul>
            </div>

            <!-- Company & Support -->
            <div>
                <h4 class="font-body text-xs uppercase tracking-widest text-[#bc944c] font-semibold">Company & Support</h4>
                <ul class="mt-4 space-y-2.5 text-xs text-[#e9dcc2]/70">
                    <li><a href="<?= url('about') ?>" class="hover:text-[#bc944c] transition-colors">Our Story & Dairy</a></li>
                    <li><a href="<?= url('faq') ?>" class="hover:text-[#bc944c] transition-colors font-medium text-[#fdf6e3]">FAQs & Help</a></li>
                    <li><a href="<?= url('contact') ?>" class="hover:text-[#bc944c] transition-colors">Contact Support</a></li>
                    <li><a href="<?= url('privacy-policy') ?>" class="hover:text-[#bc944c] transition-colors">Privacy Policy</a></li>
                    <li><a href="<?= url('terms-and-conditions') ?>" class="hover:text-[#bc944c] transition-colors">Terms of Service</a></li>
                    <li><a href="<?= url('shipping-policy') ?>" class="hover:text-[#bc944c] transition-colors">Shipping & Returns</a></li>
                </ul>
            </div>

            <!-- Contact & Newsletter -->
            <div>
                <h4 class="font-body text-xs uppercase tracking-widest text-[#bc944c] font-semibold">Stay Connected</h4>
                <p class="mt-4 text-xs text-[#e9dcc2]/70 leading-relaxed">
                    Subscribe for seasonal harvest announcements, festive offers and culinary secrets.
                </p>
                <form id="newsletter-form" class="mt-3">
                    <div class="relative">
                        <input type="email" placeholder="Your email address" required class="w-full px-3 py-2 text-xs rounded-lg bg-white/[0.06] border border-[#bc944c]/30 text-[#fdf6e3] placeholder-[#e9dcc2]/40 focus:outline-none focus:border-[#bc944c]">
                        <button type="submit" class="mt-2 w-full btn-gold text-xs py-2">Subscribe</button>
                    </div>
                </form>
                <div class="mt-4 text-[11px] text-[#e9dcc2]/60">
                    <span>Call: +91 81234 50509</span><br>
                    <span>Email: Contact@legacyfood.in</span>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-[#e9dcc2]/50 gap-4">
            <p>© <?= date('Y') ?> Legacy Food. All rights reserved. Crafted in South India.</p>
            <div class="flex items-center gap-4 sm:gap-6 flex-wrap">
                <a href="<?= url('shop') ?>" class="hover:text-[#bc944c] transition-colors font-medium">Shop All</a>
                <span>•</span>
                <a href="<?= url('faq') ?>" class="hover:text-[#bc944c] transition-colors font-medium">FAQ</a>
                <span>•</span>
                <span>100% NABL Accredited Lab Verified</span>
                <span>•</span>
                <span>FSSAI Compliant</span>
                <span>•</span>
                <a href="<?= url('admin/login') ?>" class="hover:text-[#bc944c] transition-colors">Admin Portal</a>
            </div>
        </div>
    </div>
</footer>
