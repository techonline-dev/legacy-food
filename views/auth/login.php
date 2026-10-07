<div class="py-10 md:py-16 flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white border border-[#e7dec8] rounded-3xl p-8 md:p-10 shadow-xl shadow-stone-200/50 relative overflow-hidden">
            <!-- Subtle Gold Top Border Accent -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#bc944c] via-[#d8bd99] to-[#bc944c]"></div>

            <div class="mb-6 text-center">
                <span class="eyebrow block mb-1">Customer Portal</span>
                <h1 class="font-display text-2xl md:text-3xl text-[#07160d] font-bold">Welcome Back</h1>
                <p class="text-xs text-stone-500 mt-1.5">Sign in to manage your orders, saved addresses and wishlist.</p>
            </div>

            <form action="<?= url('login') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="redirect" value="<?= e($redirect ?? 'account') ?>">

                <div>
                    <label class="block text-xs text-[#07160d] mb-1.5 font-semibold uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" required value="<?= e($prefill_email ?? '') ?>" placeholder="name@example.com"
                           class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs text-[#07160d] font-semibold uppercase tracking-wider">Password</label>
                        <a href="<?= url('forgot-password') ?>" class="text-[11px] text-[#bc944c] hover:underline font-medium">Forgot?</a>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-gold w-full py-3.5 text-xs uppercase tracking-wider font-bold shadow-md shadow-[#bc944c]/20">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-stone-200 text-center text-xs text-stone-600">
                Don't have an account yet?
                <?php
                $regParams = [];
                if (!empty($redirect) && $redirect !== 'account') $regParams['redirect'] = $redirect;
                if (!empty($prefill_email)) $regParams['email'] = $prefill_email;
                $regQuery = !empty($regParams) ? '?' . http_build_query($regParams) : '';
                ?>
                <a href="<?= url('register' . $regQuery) ?>" class="text-[#bc944c] font-bold hover:underline ml-1">Create Account</a>
            </div>
        </div>

        <div class="text-center mt-6 text-xs text-stone-500">
            <a href="<?= url('/') ?>" class="hover:text-[#bc944c] transition-colors">← Return to Public Storefront</a>
        </div>
    </div>
</div>
