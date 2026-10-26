<div class="py-10 md:py-16 flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white border border-[#e7dec8] rounded-3xl p-8 md:p-10 shadow-xl shadow-stone-200/50 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#bc944c] via-[#d8bd99] to-[#bc944c]"></div>

            <div class="mb-6 text-center">
                <span class="eyebrow block mb-1">Account Recovery</span>
                <h1 class="font-display text-2xl md:text-3xl text-[#07160d] font-bold">Choose a New Password</h1>
                <p class="text-xs text-stone-500 mt-1.5">Your reset link is valid for one hour and can be used only once.</p>
            </div>

            <form action="<?= url('reset-password') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= e($token ?? '') ?>">

                <div>
                    <label class="block text-xs text-[#07160d] mb-1.5 font-semibold uppercase tracking-wider">New Password</label>
                    <input type="password" name="password" required minlength="8" maxlength="72" autocomplete="new-password"
                           placeholder="At least 8 characters"
                           class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                </div>

                <div>
                    <label class="block text-xs text-[#07160d] mb-1.5 font-semibold uppercase tracking-wider">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" maxlength="72" autocomplete="new-password"
                           placeholder="Re-enter your new password"
                           class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-gold w-full py-3.5 text-xs uppercase tracking-wider font-bold shadow-md shadow-[#bc944c]/20">
                        Update Password
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-stone-200 text-center text-xs text-stone-600">
                Remember your credentials?
                <a href="<?= url('login') ?>" class="text-[#bc944c] font-bold hover:underline ml-1">Sign In</a>
            </div>
        </div>
    </div>
</div>
