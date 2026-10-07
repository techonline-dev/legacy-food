<div class="py-12 md:py-20 flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white border border-[#e7dec8] rounded-3xl p-8 md:p-10 shadow-xl shadow-stone-200/50 relative overflow-hidden">
            <!-- Subtle Gold Top Border Accent -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#bc944c] via-[#d8bd99] to-[#bc944c]"></div>

            <div class="mb-6 text-center">
                <span class="eyebrow block mb-1">Merchant Operations</span>
                <h1 class="font-display text-2xl md:text-3xl text-[#07160d] font-bold">Administrator Login</h1>
                <p class="text-xs text-stone-500 mt-1.5">Authorized personnel only. Sessions and operations are audited.</p>
            </div>

            <!-- Flash Error Message -->
            <?php if ($flashError = flash('error')): ?>
                <div class="mb-4 p-3.5 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span><?= e($flashError) ?></span>
                </div>
            <?php endif; ?>

            <!-- Flash Success Message -->
            <?php if ($flashSuccess = flash('success')): ?>
                <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span><?= e($flashSuccess) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= url('admin/login') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs text-[#07160d] mb-1.5 font-semibold uppercase tracking-wider">Admin Email</label>
                    <div class="relative">
                        <input type="email" name="email" value="<?= e(old('email', 'admin@legacyfood.in')) ?>" required autofocus placeholder="admin@legacyfood.in"
                               class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs text-[#07160d] font-semibold uppercase tracking-wider">Access Key / Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" required placeholder="••••••••••••"
                               class="w-full px-4 py-3 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] placeholder-stone-400 focus:outline-none focus:border-[#bc944c] focus:ring-1 focus:ring-[#bc944c] transition-colors">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-gold w-full py-3.5 text-xs uppercase tracking-wider font-bold shadow-md shadow-[#bc944c]/20 flex items-center justify-center gap-2">
                        <span>Authenticate Portal</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-stone-200 text-center text-xs text-stone-600 space-y-2">
                <p class="text-[11px] text-stone-500">
                    Default Credentials (Seeds):<br>
                    <span class="font-semibold text-[#bc944c] font-mono">admin@legacyfood.in</span> / <span class="font-semibold text-[#bc944c] font-mono">Admin@LegacyFood2026</span>
                </p>
                <div>
                    <a href="<?= url('/') ?>" class="text-[#bc944c] font-semibold hover:underline">← Return to Public Storefront</a>
                </div>
            </div>
        </div>
    </div>
</div>
