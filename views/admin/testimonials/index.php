<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Testimonials & Social Proof</h1>
            <p class="text-xs text-stone-500 mt-1">Manage verified customer endorsements displayed on the storefront.</p>
        </div>
        <?php if (!empty($editTestimonial)): ?>
            <div>
                <a href="<?= url('admin/testimonials') ?>" class="btn-ghost text-xs py-2 px-4 border border-[#d6c7af] text-[#07160d] hover:bg-white inline-flex items-center gap-2">
                    <span>+ Add New Testimonial</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add / Edit Testimonial Form -->
        <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4 h-fit">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-base font-bold text-[#07160d]">
                    <?= !empty($editTestimonial) ? 'Edit Testimonial #' . $editTestimonial['id'] : 'Add Testimonial' ?>
                </h2>
                <?php if (!empty($editTestimonial)): ?>
                    <a href="<?= url('admin/testimonials') ?>" class="text-[11px] text-[#bc944c] font-semibold hover:underline">
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>

            <form action="<?= !empty($editTestimonial) ? url('admin/testimonials/update/' . $editTestimonial['id']) : url('admin/testimonials/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Customer Name *</label>
                    <input type="text" name="customer_name" required value="<?= e($editTestimonial['customer_name'] ?? '') ?>" placeholder="e.g. Ananya Raman"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Location</label>
                        <input type="text" name="location" value="<?= e($editTestimonial['location'] ?? 'Bangalore') ?>" placeholder="e.g. Bangalore, KA"
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Rating (Stars)</label>
                        <select name="rating" class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="5" <?= ($editTestimonial['rating'] ?? 5) == 5 ? 'selected' : '' ?>>5 Stars ★★★★★</option>
                            <option value="4" <?= ($editTestimonial['rating'] ?? 5) == 4 ? 'selected' : '' ?>>4 Stars ★★★★☆</option>
                            <option value="3" <?= ($editTestimonial['rating'] ?? 5) == 3 ? 'selected' : '' ?>>3 Stars ★★★☆☆</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Testimonial Quote *</label>
                    <textarea name="review" rows="4" required placeholder="Genuine customer feedback on aroma, purity, texture..."
                              class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($editTestimonial['review'] ?? '') ?></textarea>
                </div>

                <?php if (!empty($editTestimonial)): ?>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Status</label>
                        <select name="is_active" class="w-full py-2.5 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="1" <?= ($editTestimonial['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Active / Display</option>
                            <option value="0" <?= ($editTestimonial['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Inactive / Hidden</option>
                        </select>
                    </div>
                <?php endif; ?>

                <button type="submit" class="w-full btn-gold text-xs py-3 font-bold justify-center shadow-sm">
                    <?= !empty($editTestimonial) ? 'Save Testimonial Changes' : 'Publish Testimonial' ?>
                </button>
            </form>
        </div>

        <!-- Testimonials Table -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
            <h2 class="font-display text-base font-bold text-[#07160d] mb-4">Customer Voices (<?= count($testimonials) ?>)</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-600 uppercase tracking-wider text-[10px] bg-stone-50">
                            <th class="p-3 font-bold">Customer</th>
                            <th class="p-3 font-bold">Feedback</th>
                            <th class="p-3 font-bold">Rating</th>
                            <th class="p-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php if (empty($testimonials)): ?>
                            <tr>
                                <td colspan="4" class="py-8 text-center text-stone-400">No testimonials published.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($testimonials as $t): ?>
                                <?php $isEditingThis = !empty($editTestimonial) && $editTestimonial['id'] == $t['id']; ?>
                                <tr class="transition-colors <?= $isEditingThis ? 'bg-amber-50/60 font-medium' : 'hover:bg-[#faf8f5]' ?>">
                                    <td class="p-3">
                                        <div class="font-bold text-[#07160d]"><?= e($t['customer_name']) ?></div>
                                        <div class="text-[10px] text-stone-500"><?= e($t['location'] ?? 'India') ?></div>
                                    </td>
                                    <td class="p-3">
                                        <p class="text-xs text-stone-700 italic line-clamp-2 max-w-md">“<?= e($t['review']) ?>”</p>
                                    </td>
                                    <td class="p-3">
                                        <span class="text-amber-500 text-xs tracking-wider">
                                            <?= str_repeat('★', (int)$t['rating']) ?>
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Edit Button -->
                                            <a href="<?= url('admin/testimonials?edit=' . $t['id']) ?>" title="Edit Testimonial" class="p-1.5 rounded-lg border border-stone-200 hover:border-[#bc944c] hover:bg-[#faf8f5] text-stone-700 hover:text-[#bc944c] transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="<?= url('admin/testimonials/delete/' . $t['id']) ?>" method="POST" onsubmit="return confirm('Remove testimonial?');" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" title="Delete Testimonial" class="p-1.5 rounded-lg border border-red-200 hover:bg-red-50 text-red-600 transition-colors">
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
