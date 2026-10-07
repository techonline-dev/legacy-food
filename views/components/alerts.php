<?php if (has_flash('success')): ?>
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium"><?= e(flash('success')) ?></span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-xl leading-none">&times;</button>
    </div>
<?php endif; ?>

<?php if (has_flash('error')): ?>
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="font-medium"><?= e(flash('error')) ?></span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-800 text-xl leading-none">&times;</button>
    </div>
<?php endif; ?>

<?php if (has_flash('info')): ?>
    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium"><?= e(flash('info')) ?></span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-800 text-xl leading-none">&times;</button>
    </div>
<?php endif; ?>
