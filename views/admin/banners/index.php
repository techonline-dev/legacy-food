<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Banners & Hero Sliders</h1>
            <p class="text-xs text-stone-500 mt-1">Manage homepage carousel slides, promotional banners, and visual storytelling.</p>
        </div>
        <?php if (!empty($editBanner)): ?>
            <div>
                <a href="<?= url('admin/banners') ?>" class="btn-ghost text-xs py-2 px-4 border border-[#d6c7af] text-[#07160d] hover:bg-white inline-flex items-center gap-2">
                    <span>+ Add New Banner</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add / Edit Banner Form -->
        <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4 h-fit">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-base font-bold text-[#07160d]">
                    <?= !empty($editBanner) ? 'Edit Banner #' . $editBanner['id'] : 'Add New Banner Slide' ?>
                </h2>
                <?php if (!empty($editBanner)): ?>
                    <a href="<?= url('admin/banners') ?>" class="text-[11px] text-[#bc944c] font-semibold hover:underline">
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>

            <form action="<?= !empty($editBanner) ? url('admin/banners/update/' . $editBanner['id']) : url('admin/banners/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Headline Title *</label>
                    <input type="text" name="title" required value="<?= e($editBanner['title'] ?? '') ?>" placeholder="e.g. Pure Heritage In Every Drop"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Subtitle / Tagline</label>
                    <input type="text" name="subtitle" value="<?= e($editBanner['subtitle'] ?? '') ?>" placeholder="e.g. Slow churned South Indian ghee made strictly from fresh farm butter"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Banner Image URL *</label>
                    <input type="url" name="image" required value="<?= e($editBanner['image'] ?? '') ?>" placeholder="https://cdn.sanity.io/.../banner.jpg"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Button Text</label>
                        <input type="text" name="cta_text" value="<?= e($editBanner['cta_text'] ?? '') ?>" placeholder="Shop Heritage Ghee"
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Button URL</label>
                        <input type="text" name="cta_url" value="<?= e($editBanner['cta_url'] ?? '') ?>" placeholder="/shop?category=ghee"
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Placement</label>
                        <select name="type" class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="hero" <?= ($editBanner['type'] ?? '') === 'hero' ? 'selected' : '' ?>>Hero Main Slider</option>
                            <option value="promo" <?= ($editBanner['type'] ?? '') === 'promo' ? 'selected' : '' ?>>Mid-Page Promo</option>
                            <option value="footer" <?= ($editBanner['type'] ?? '') === 'footer' ? 'selected' : '' ?>>Bottom Banner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" value="<?= (int)($editBanner['sort_order'] ?? 0) ?>"
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <option value="active" <?= ($editBanner['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active / Display</option>
                        <option value="inactive" <?= ($editBanner['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive / Hidden</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full btn-gold text-xs py-3 font-bold justify-center shadow-sm">
                        <?= !empty($editBanner) ? 'Save Changes' : 'Publish Banner' ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- Banners Table -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
            <h2 class="font-display text-base font-bold text-[#07160d] mb-4">Active Banners & Sliders (<?= count($banners) ?>)</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-600 uppercase tracking-wider text-[10px] bg-stone-50">
                            <th class="p-3 font-bold">Preview</th>
                            <th class="p-3 font-bold">Title & Tagline</th>
                            <th class="p-3 font-bold">Type</th>
                            <th class="p-3 font-bold">Order</th>
                            <th class="p-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php if (empty($banners)): ?>
                            <tr>
                                <td colspan="5" class="py-8 text-center text-stone-400">No promotional banners configured.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($banners as $b): ?>
                                <?php $isEditingThis = !empty($editBanner) && $editBanner['id'] == $b['id']; ?>
                                <tr class="transition-colors <?= $isEditingThis ? 'bg-amber-50/60 font-medium' : 'hover:bg-[#faf8f5]' ?>">
                                    <td class="p-3">
                                        <img src="<?= e($b['image']) ?>" class="w-20 h-10 object-cover rounded-lg border border-[#e7dec8] bg-stone-100">
                                    </td>
                                    <td class="p-3">
                                        <div class="font-bold text-[#07160d]"><?= e($b['title']) ?></div>
                                        <?php if (!empty($b['subtitle'])): ?>
                                            <div class="text-[10px] text-stone-500 truncate max-w-xs"><?= e($b['subtitle']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-mono font-semibold bg-[#bc944c]/15 text-[#bc944c] border border-[#bc944c]/30">
                                            <?= e($b['type']) ?>
                                        </span>
                                    </td>
                                    <td class="p-3 font-mono text-stone-600">
                                        <?= (int)$b['sort_order'] ?>
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Edit Button -->
                                            <a href="<?= url('admin/banners?edit=' . $b['id']) ?>" title="Edit Banner" class="p-1.5 rounded-lg border border-stone-200 hover:border-[#bc944c] hover:bg-[#faf8f5] text-stone-700 hover:text-[#bc944c] transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="<?= url('admin/banners/delete/' . $b['id']) ?>" method="POST" onsubmit="return confirm('Remove this banner slide?');" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" title="Delete Banner" class="p-1.5 rounded-lg border border-red-200 hover:bg-red-50 text-red-600 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
