<div class="py-10 md:py-16 flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Register Card -->
        <div class="bg-white border border-[#e7dec8] rounded-3xl p-8 md:p-10 shadow-xl shadow-stone-200/50 relative overflow-hidden">
            <!-- Subtle Gold Top Border Accent -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#bc944c] via-[#d8bd99] to-[#bc944c]"></div>

            <div class="mb-6 text-center">
                <span class="eyebrow block mb-1">New Customer</span>
                <h1 class="font-display text-2xl md:text-3xl text-[#07160d] font-bold">Create Account</h1>
                <p class="text-xs text-stone-500 mt-1.5">Join Legacy Food for seamless checkout and direct order tracking.</p>
            </div>

            <form action="<?= url('register') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="redirect" value="<?= e($redirect ?? '') ?>">

                <div>
                    <label class="block text-xs text-[#07160d] mb-1.5 font-semibold uppercase tracking-wider">Full Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Anand Sharma"
                           class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                </div>

                <div>
                    <label class="block text-xs text-[#07160d] mb-1.5 font-semibold uppercase tracking-wider">Email Address *</label>
                    <input type="email" name="email" required value="<?= e($prefill_email ?? '') ?>" placeholder="name@example.com"
                           class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                </div>

                <div>
                    <label class="block text-xs text-[#07160d] mb-1.5 font-semibold uppercase tracking-wider">Phone / WhatsApp Number *</label>
                    <input type="tel" name="phone" required placeholder="9845279936"
                           class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                </div>

                <div>
                    <label class="block text-xs text-[#07160d] mb-1.5 font-semibold uppercase tracking-wider">Password (Min. 6 characters) *</label>
                    <input type="password" name="password" required minlength="6" placeholder="••••••••"
                           class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-gold w-full py-3.5 text-xs uppercase tracking-wider font-bold shadow-md shadow-[#bc944c]/20">
                        Register Account
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-stone-200 text-center text-xs text-stone-600">
                Already have an account?
                <?php
                $loginParams = [];
                if (!empty($redirect) && $redirect !== 'account') $loginParams['redirect'] = $redirect;
                if (!empty($prefill_email)) $loginParams['email'] = $prefill_email;
                $loginQuery = !empty($loginParams) ? '?' . http_build_query($loginParams) : '';
                ?>
                <a href="<?= url('login' . $loginQuery) ?>" class="text-[#bc944c] font-bold hover:underline ml-1">Sign In</a>
            </div>
        </div>

        <div class="text-center mt-6 text-xs text-stone-500">
            <a href="<?= url('/') ?>" class="hover:text-[#bc944c] transition-colors">← Return to Public Storefront</a>
        </div>
    </div>
</div>
