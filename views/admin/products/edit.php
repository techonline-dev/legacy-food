<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xs text-[#bc944c] font-semibold uppercase tracking-wider mb-1">Catalog Management</div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Edit Product: <?= e($product['name']) ?></h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('product/' . $product['slug']) ?>" target="_blank" class="btn-ghost text-xs py-2 px-3 border border-[#d6c7af] text-[#07160d] hover:bg-stone-50 flex items-center gap-1.5">
                <span>View on Store</span> ↗
            </a>
            <a href="<?= url('admin/products') ?>" class="btn-ghost text-xs py-2 px-3 border border-[#d6c7af] text-[#07160d] hover:bg-stone-50">
                ← Back
            </a>
        </div>
    </div>

    <form action="<?= url('admin/products/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">General Information</h2>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Product Title *</label>
                        <input type="text" name="name" value="<?= e($product['name']) ?>" required
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider">URL Permalink / Slug *</label>
                            <a href="<?= url('product/' . $product['slug']) ?>" target="_blank" class="text-[11px] text-[#bc944c] hover:underline font-medium">
                                Preview: /product/<?= e($product['slug']) ?> ↗
                            </a>
                        </div>
                        <div class="flex rounded-xl shadow-sm border border-[#d6c7af] overflow-hidden focus-within:border-[#bc944c]">
                            <span class="inline-flex items-center px-3 bg-stone-50 text-stone-500 text-xs font-mono border-r border-[#d6c7af]">/product/</span>
                            <input type="text" name="slug" value="<?= e($product['slug']) ?>" required
                                   class="flex-1 py-2.5 px-3 bg-white text-sm text-[#1c1917] font-mono focus:outline-none"
                                   pattern="[a-z0-9\-_]+" title="Lowercase alphanumeric, dashes, underscores only">
                        </div>
                        <p class="text-[11px] text-stone-500 mt-1">Unique SEO URL keyword string. Must be URL-safe (lowercase letters, numbers, and dashes).</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Category *</label>
                            <select name="category_id" required class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (int)$product['category_id'] === (int)$cat['id'] ? 'selected' : '' ?>>
                                        <?= e($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">SKU</label>
                            <input type="text" name="sku" value="<?= e($product['sku']) ?>" required
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none uppercase font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Short Description</label>
                        <input type="text" name="short_description" value="<?= e($product['short_description'] ?? '') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Detailed Story & Description</label>
                        <textarea name="description" rows="5"
                                  class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($product['description'] ?? '') ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Key Benefits (One per line)</label>
                            <textarea name="benefits" rows="3"
                                      class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($product['benefits'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Purity & Ingredients</label>
                            <textarea name="ingredients" rows="3"
                                      class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($product['ingredients'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pricing, GST & Master Stock Card -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-5">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Pricing, GST Tax & Inventory</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Regular Base Price (₹) *</label>
                            <input type="number" step="0.01" name="base_price" value="<?= e($product['base_price']) ?>" required
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Discounted Sale Price (₹)</label>
                            <input type="number" step="0.01" name="sale_price" value="<?= e($product['sale_price'] ?? '') ?>"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                    </div>

                    <!-- GST & HSN Fields -->
                    <div class="p-4 rounded-xl bg-[#faf8f5] border border-[#d6c7af] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#07160d] flex items-center gap-1.5 uppercase tracking-wider">
                                <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                                GST Tax Slab & HSN Classification
                            </span>
                            <a href="<?= url('admin/settings') ?>" target="_blank" class="text-[11px] text-[#bc944c] hover:underline font-semibold">Store GST Settings ↗</a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                            <div>
                                <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">GST Rate Percentage (%)</label>
                                <select name="gst_rate" class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                                    <option value="" <?= ($product['gst_rate'] === null || $product['gst_rate'] === '') ? 'selected' : '' ?>>Inherit Store Default (<?= (float)setting('gst_percentage', 5.0) ?>%)</option>
                                    <option value="0.00" <?= ($product['gst_rate'] !== null && (float)$product['gst_rate'] == 0.0) ? 'selected' : '' ?>>0.00% - Tax Exempt</option>
                                    <option value="5.00" <?= ($product['gst_rate'] !== null && (float)$product['gst_rate'] == 5.0) ? 'selected' : '' ?>>5.00% - Standard Ghee & Edible Oils</option>
                                    <option value="12.00" <?= ($product['gst_rate'] !== null && (float)$product['gst_rate'] == 12.0) ? 'selected' : '' ?>>12.00% - Processed Foods</option>
                                    <option value="18.00" <?= ($product['gst_rate'] !== null && (float)$product['gst_rate'] == 18.0) ? 'selected' : '' ?>>18.00% - Standard Commercial</option>
                                    <option value="28.00" <?= ($product['gst_rate'] !== null && (float)$product['gst_rate'] == 28.0) ? 'selected' : '' ?>>28.00% - Luxury Slabs</option>
                                </select>
                                <p class="text-[11px] text-stone-500 mt-1">Leave as "Inherit Store Default" to use the global GST rate.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">HSN / SAC Code</label>
                                <input type="text" name="hsn_code" value="<?= e($product['hsn_code'] ?? '') ?>" placeholder="e.g. 04059020 (Ghee) or 1508"
                                       class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none uppercase">
                                <p class="text-[11px] text-stone-500 mt-1">Leave blank to use store default (<?= e(setting('default_hsn_code', '04059020')) ?>).</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Stock Quantity *</label>
                            <input type="number" name="stock_quantity" value="<?= (int)$product['stock_quantity'] ?>" required
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Low Stock Warning Alert</label>
                            <input type="number" name="low_stock_threshold" value="<?= (int)$product['low_stock_threshold'] ?>"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Existing Variants Overview -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-base font-bold text-[#07160d]">Product Variants (<?= count($variants) ?>)</h2>
                    </div>

                    <?php if (empty($variants)): ?>
                        <p class="text-xs text-stone-500">No sub-variants attached. Single standard size.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-[#e7dec8] text-[#bc944c] uppercase tracking-wider text-[10px]">
                                        <th class="pb-2 font-semibold">Variant Name</th>
                                        <th class="pb-2 font-semibold">SKU</th>
                                        <th class="pb-2 font-semibold">Price</th>
                                        <th class="pb-2 font-semibold">Stock</th>
                                        <th class="pb-2 font-semibold">Default</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100">
                                    <?php foreach ($variants as $v): ?>
                                        <tr>
                                            <td class="py-2.5 font-semibold text-[#07160d]"><?= e($v['name']) ?></td>
                                            <td class="py-2.5 font-mono text-[#bc944c]"><?= e($v['sku']) ?></td>
                                            <td class="py-2.5 font-display font-semibold"><?= format_price($v['price']) ?></td>
                                            <td class="py-2.5 text-stone-600"><?= (int)$v['stock_quantity'] ?> in stock</td>
                                            <td class="py-2.5">
                                                <?= !empty($v['is_default']) ? '<span class="text-emerald-700 font-bold">✓ Primary</span>' : '—' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SEO Settings Card -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Search Engine Optimization (SEO)</h2>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Custom SEO Meta Title</label>
                        <input type="text" name="meta_title" value="<?= e($product['meta_title'] ?? '') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">SEO Meta Description</label>
                        <textarea name="meta_description" rows="2"
                                  class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($product['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Col: Media & Status -->
            <div class="space-y-6">
                <!-- Publish Status & Flags -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Publishing</h2>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="published" <?= $product['status'] === 'published' ? 'selected' : '' ?>>Published (Visible to Store)</option>
                            <option value="draft" <?= $product['status'] === 'draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
                            <option value="archived" <?= $product['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                        </select>
                    </div>

                    <div class="pt-2 space-y-2 border-t border-[#e7dec8]">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" <?= !empty($product['is_featured']) ? 'checked' : '' ?> class="rounded border-stone-300 text-[#bc944c] focus:ring-[#bc944c]">
                            <span class="text-xs text-[#07160d]">Mark as Featured on Homepage</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_bestseller" value="1" <?= !empty($product['is_bestseller']) ? 'checked' : '' ?> class="rounded border-stone-300 text-[#bc944c] focus:ring-[#bc944c]">
                            <span class="text-xs text-[#07160d]">Mark as Bestseller</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full btn-primary text-xs py-3 font-bold justify-center">
                            Save Changes
                        </button>
                    </div>
                </div>

                <!-- Product Featured Media -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Product Imagery</h2>

                    <!-- Current Image Preview -->
                    <div class="text-center p-4 bg-stone-50 rounded-xl border border-[#e7dec8]">
                        <img src="<?= e($product['featured_image']) ?>" class="w-32 h-32 object-contain mx-auto rounded-lg bg-white p-2 border border-[#e7dec8]">
                        <span class="text-[10px] text-stone-500 mt-2 block">Current Featured Image</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Image URL</label>
                        <input type="url" name="featured_image" value="<?= e($product['featured_image']) ?>"
                               class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Replace with File</label>
                        <input type="file" name="image_file" accept="image/*"
                               class="w-full text-xs text-stone-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#bc944c] file:text-[#07160d] hover:file:bg-[#d8bd99] cursor-pointer">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
