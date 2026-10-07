<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Product Categories</h1>
            <p class="text-xs text-stone-500 mt-1">Organize heritage food collections (Ghee, Cold Pressed Oils, etc.).</p>
        </div>
        <?php if (!empty($editCategory)): ?>
            <div>
                <a href="<?= url('admin/categories') ?>" class="btn-ghost text-xs py-2 px-4 border border-[#d6c7af] text-[#07160d] hover:bg-white inline-flex items-center gap-2">
                    <span>+ Add New Category</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add / Edit Category Form -->
        <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm h-fit space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-base font-bold text-[#07160d]">
                    <?= !empty($editCategory) ? 'Edit Category #' . $editCategory['id'] : 'Add New Category' ?>
                </h2>
                <?php if (!empty($editCategory)): ?>
                    <a href="<?= url('admin/categories') ?>" class="text-[11px] text-[#bc944c] font-semibold hover:underline">
                        Cancel Edit
                    </a>
                <?php endif; ?>
            </div>

            <form action="<?= !empty($editCategory) ? url('admin/categories/update/' . $editCategory['id']) : url('admin/categories/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Category Name *</label>
                    <input type="text" name="name" required value="<?= e($editCategory['name'] ?? '') ?>" placeholder="e.g. Wood Pressed Oils"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Slug (Optional)</label>
                    <input type="text" name="slug" value="<?= e($editCategory['slug'] ?? '') ?>" placeholder="e.g. wood-pressed-oils"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Icon / Image URL</label>
                    <input type="url" name="image" value="<?= e($editCategory['image'] ?? '') ?>" placeholder="https://cdn.sanity.io/.../image.png"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Description</label>
                    <textarea name="description" rows="3" placeholder="Category highlights and nutritional notes..."
                              class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($editCategory['description'] ?? '') ?></textarea>
                </div>

                <div class="pt-2 border-t border-[#e7dec8] space-y-3">
                    <span class="text-xs font-bold text-[#07160d] block">SEO Settings</span>
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Meta Title</label>
                        <input type="text" name="meta_title" value="<?= e($editCategory['meta_title'] ?? '') ?>" placeholder="e.g. Pure Wood Pressed Oils | Legacy Food"
                               class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Meta Description</label>
                        <textarea name="meta_description" rows="2" placeholder="Explore authentic cold-pressed oils..."
                                  class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($editCategory['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>

                <?php if (!empty($editCategory)): ?>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Status</label>
                        <select name="is_active" class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="1" <?= ($editCategory['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= ($editCategory['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                <?php endif; ?>

                <button type="submit" class="w-full btn-gold text-xs py-3 font-bold justify-center shadow-sm">
                    <?= !empty($editCategory) ? 'Save Category Changes' : 'Create Category' ?>
                </button>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm">
            <h2 class="font-display text-base font-bold text-[#07160d] mb-4">Existing Categories (<?= count($categories) ?>)</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-600 uppercase tracking-wider text-[10px] bg-stone-50">
                            <th class="p-3 font-bold">Image</th>
                            <th class="p-3 font-bold">Name</th>
                            <th class="p-3 font-bold">Slug</th>
                            <th class="p-3 font-bold">Status</th>
                            <th class="p-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php foreach ($categories as $cat): ?>
                            <?php $isEditingThis = !empty($editCategory) && $editCategory['id'] == $cat['id']; ?>
                            <tr class="transition-colors <?= $isEditingThis ? 'bg-amber-50/60 font-medium' : 'hover:bg-[#faf8f5]' ?>">
                                <td class="p-3">
                                    <?php if (!empty($cat['image'])): ?>
                                        <img src="<?= e($cat['image']) ?>" class="w-10 h-10 object-contain rounded-lg border border-[#e7dec8] bg-white p-1">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-lg bg-stone-100 border border-stone-200 flex items-center justify-center text-[10px] text-stone-400">
                                            No Img
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3">
                                    <div class="font-bold text-[#07160d]"><?= e($cat['name']) ?></div>
                                    <div class="text-[10px] text-stone-500 line-clamp-1 max-w-xs"><?= e($cat['description'] ?? '') ?></div>
                                </td>
                                <td class="p-3 font-mono text-stone-600">
                                    <?= e($cat['slug']) ?>
                                </td>
                                <td class="p-3">
                                    <?php if (!empty($cat['is_active'])): ?>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                    <?php else: ?>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-stone-100 text-stone-600">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Edit Button -->
                                        <a href="<?= url('admin/categories?edit=' . $cat['id']) ?>" title="Edit Category" class="p-1.5 rounded-lg border border-stone-200 hover:border-[#bc944c] hover:bg-[#faf8f5] text-stone-700 hover:text-[#bc944c] transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="<?= url('admin/categories/delete/' . $cat['id']) ?>" method="POST" onsubmit="return confirm('Delete this category? Associated products may be affected.');" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" title="Delete Category" class="p-1.5 rounded-lg border border-red-200 hover:bg-red-50 text-red-600 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
