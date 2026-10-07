<div class="py-8 md:py-14">
    <div class="container-x max-w-2xl">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4">
            <a href="<?= url('account') ?>" class="hover:text-[#bc944c]">My Account</a>
            <span>/</span>
            <span class="text-[#bc944c] font-medium">Profile Settings</span>
        </nav>

        <?php include __DIR__ . '/../components/account_tabs.php'; ?>

        <div class="bg-white border border-[#e7dec8] p-8 md:p-10 rounded-3xl shadow-sm">
            <h1 class="font-display text-2xl text-[#07160d] font-bold mb-6">
                Profile & Security
            </h1>

            <form action="<?= url('account/profile/update') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Full Name</label>
                    <input type="text" name="name" required value="<?= e($user['name']) ?>" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                </div>

                <div>
                    <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Email Address (Read Only)</label>
                    <input type="email" readonly value="<?= e($user['email']) ?>" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-stone-100 border border-stone-200 text-stone-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Phone / WhatsApp Number</label>
                    <input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                </div>

                <div class="pt-4 border-t border-stone-200">
                    <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Change Password (Leave blank to keep unchanged)</label>
                    <input type="password" name="password" minlength="6" placeholder="New password" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                </div>

                <button type="submit" class="btn-gold w-full py-3.5 text-xs uppercase tracking-wider font-bold mt-2 shadow-md">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
