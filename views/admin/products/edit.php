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

                <!-- Product Variants Manager (Multiple) -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-display text-base font-bold text-[#07160d]">Product Variants (<?= count($variants) ?>)</h2>
                            <p class="text-[11px] text-stone-500">Configure multiple size, volume or packaging options with individual prices & SKUs.</p>
                        </div>
                        <button type="button" id="btn-add-edit-variant" class="btn-ghost py-1.5 px-3 text-xs border border-[#bc944c] text-[#bc944c] hover:bg-[#bc944c] hover:text-[#07160d] font-bold rounded-lg transition-colors flex items-center gap-1.5">
                            <span>+ Add Variant</span>
                        </button>
                    </div>

                    <input type="hidden" name="deleted_variant_ids" id="deleted_variant_ids" value="">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs" id="edit-variants-table">
                            <thead>
                                <tr class="border-b border-[#e7dec8] text-[#bc944c] uppercase tracking-wider text-[10px]">
                                    <th class="pb-2 font-semibold w-12 text-center">Primary</th>
                                    <th class="pb-2 font-semibold">Variant Name *</th>
                                    <th class="pb-2 font-semibold">SKU</th>
                                    <th class="pb-2 font-semibold w-24">Price (₹) *</th>
                                    <th class="pb-2 font-semibold w-24">Sale (₹)</th>
                                    <th class="pb-2 font-semibold w-20">Stock</th>
                                    <th class="pb-2 font-semibold w-20">Weight (g)</th>
                                    <th class="pb-2 font-semibold w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100" id="edit-variants-body">
                                <?php if (!empty($variants)): ?>
                                    <?php foreach ($variants as $idx => $v): ?>
                                        <tr class="variant-row" data-id="<?= $v['id'] ?>">
                                            <input type="hidden" name="variants[<?= $idx ?>][id]" value="<?= $v['id'] ?>">
                                            <td class="py-2.5 text-center">
                                                <input type="radio" name="default_variant_id" value="<?= $v['id'] ?>" <?= !empty($v['is_default']) ? 'checked' : '' ?> class="text-[#bc944c] focus:ring-[#bc944c]">
                                            </td>
                                            <td class="py-2.5">
                                                <input type="text" name="variants[<?= $idx ?>][name]" value="<?= e($v['name']) ?>" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                            </td>
                                            <td class="py-2.5">
                                                <input type="text" name="variants[<?= $idx ?>][sku]" value="<?= e($v['sku']) ?>" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs uppercase font-mono focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                            </td>
                                            <td class="py-2.5">
                                                <input type="number" step="0.01" name="variants[<?= $idx ?>][price]" value="<?= e($v['price']) ?>" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                            </td>
                                            <td class="py-2.5">
                                                <input type="number" step="0.01" name="variants[<?= $idx ?>][sale_price]" value="<?= e($v['sale_price'] ?? '') ?>" placeholder="None" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                            </td>
                                            <td class="py-2.5">
                                                <input type="number" name="variants[<?= $idx ?>][stock_quantity]" value="<?= (int)$v['stock_quantity'] ?>" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                            </td>
                                            <td class="py-2.5">
                                                <input type="number" name="variants[<?= $idx ?>][weight_grams]" value="<?= e($v['weight_grams'] ?? '') ?>" placeholder="g" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                            </td>
                                            <td class="py-2.5 text-center">
                                                <button type="button" class="btn-remove-edit-variant text-stone-400 hover:text-red-500 font-bold text-sm" data-id="<?= $v['id'] ?>" title="Remove Variant">×</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr class="variant-row" data-id="0">
                                        <td class="py-2.5 text-center">
                                            <input type="radio" name="default_variant_id" value="0" checked class="text-[#bc944c] focus:ring-[#bc944c]">
                                        </td>
                                        <td class="py-2.5">
                                            <input type="text" name="variants[0][name]" value="Standard" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                        </td>
                                        <td class="py-2.5">
                                            <input type="text" name="variants[0][sku]" value="<?= e($product['sku']) ?>-STD" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs uppercase font-mono focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                        </td>
                                        <td class="py-2.5">
                                            <input type="number" step="0.01" name="variants[0][price]" value="<?= e($product['base_price']) ?>" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                        </td>
                                        <td class="py-2.5">
                                            <input type="number" step="0.01" name="variants[0][sale_price]" value="<?= e($product['sale_price'] ?? '') ?>" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                        </td>
                                        <td class="py-2.5">
                                            <input type="number" name="variants[0][stock_quantity]" value="<?= (int)$product['stock_quantity'] ?>" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                        </td>
                                        <td class="py-2.5">
                                            <input type="number" name="variants[0][weight_grams]" placeholder="g" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                        </td>
                                        <td class="py-2.5 text-center">
                                            <button type="button" class="btn-remove-edit-variant text-stone-400 hover:text-red-500 font-bold text-sm" data-id="0" title="Remove Variant">×</button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
                    <h2 class="font-display text-base font-bold text-[#07160d]">Primary Featured Image</h2>

                    <!-- Current Image Preview -->
                    <div class="text-center p-3 bg-stone-50 rounded-xl border border-[#e7dec8]" id="edit-feat-box">
                        <img id="edit-feat-img" src="<?= e($product['featured_image']) ?>" class="w-28 h-28 object-contain mx-auto rounded-lg bg-white p-2 border border-[#e7dec8]">
                        <span class="text-[10px] text-stone-500 mt-1.5 block">Current Image</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Image URL</label>
                        <input type="url" id="edit_featured_image" name="featured_image" value="<?= e($product['featured_image']) ?>"
                               class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Replace with File</label>
                        <input type="file" id="edit_image_file" name="image_file" accept="image/*"
                               class="w-full text-xs text-stone-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#bc944c] file:text-[#07160d] hover:file:bg-[#d8bd99] cursor-pointer">
                    </div>
                </div>

                <!-- Product Gallery Images (Multiple) -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-base font-bold text-[#07160d]">Product Gallery (<?= count($images ?? []) ?>)</h2>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#faf8f5] text-[#bc944c] border border-[#d6c7af] font-semibold">Gallery</span>
                    </div>

                    <!-- Existing Gallery Images -->
                    <?php if (!empty($images)): ?>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-semibold text-stone-600 uppercase tracking-wider">Current Gallery Images</label>
                            <div class="grid grid-cols-3 gap-2.5">
                                <?php foreach ($images as $img): ?>
                                    <div class="relative group rounded-xl border border-[#d6c7af] bg-white p-1.5 overflow-hidden shadow-xs">
                                        <img src="<?= e($img['image_url']) ?>" class="w-full h-16 object-contain rounded-lg">
                                        <button type="button" class="btn-delete-gallery-img absolute top-1 right-1 w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-xs opacity-90 hover:opacity-100 shadow-xs" data-id="<?= $img['id'] ?>" title="Delete image">×</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="deleted_image_ids" id="deleted_image_ids" value="">

                    <div class="pt-2 border-t border-stone-100 space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Upload More Gallery Images</label>
                            <input type="file" id="edit_gallery_files" name="gallery_files[]" multiple accept="image/*"
                                   class="w-full text-xs text-stone-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#bc944c] file:text-[#07160d] hover:file:bg-[#d8bd99] cursor-pointer">
                            <div id="edit-gallery-preview" class="mt-2 flex flex-wrap gap-2 empty:hidden"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Or Paste Gallery Image URLs</label>
                            <textarea name="gallery_urls" rows="2" placeholder="https://example.com/img1.jpg&#10;https://example.com/img2.jpg"
                                      class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none"></textarea>
                            <p class="text-[10px] text-stone-400 mt-1">One URL per line.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Featured Image Preview
    const featInput = document.getElementById('edit_featured_image');
    const featFileInput = document.getElementById('edit_image_file');
    const featPreview = document.getElementById('edit-feat-img');

    if (featInput && featPreview) {
        featInput.addEventListener('input', function() {
            if (this.value.trim()) {
                featPreview.src = this.value.trim();
            }
        });
    }

    if (featFileInput && featPreview) {
        featFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    featPreview.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Gallery Files Preview
    const galleryInput = document.getElementById('edit_gallery_files');
    const galleryPreview = document.getElementById('edit-gallery-preview');
    if (galleryInput && galleryPreview) {
        galleryInput.addEventListener('change', function(e) {
            galleryPreview.innerHTML = '';
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const thumb = document.createElement('div');
                    thumb.className = 'w-14 h-14 rounded-lg border border-[#d6c7af] bg-white p-1 overflow-hidden shadow-xs';
                    thumb.innerHTML = `<img src="${evt.target.result}" class="w-full h-full object-contain">`;
                    galleryPreview.appendChild(thumb);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // Delete existing gallery images
    const deletedImageIdsInput = document.getElementById('deleted_image_ids');
    let deletedImageIds = [];
    document.querySelectorAll('.btn-delete-gallery-img').forEach(btn => {
        btn.addEventListener('click', function() {
            const imgId = this.dataset.id;
            if (confirm('Delete this gallery image?')) {
                deletedImageIds.push(imgId);
                deletedImageIdsInput.value = deletedImageIds.join(',');
                this.closest('.relative').remove();
            }
        });
    });

    // Dynamic Variant Rows for Edit
    const btnAddVariant = document.getElementById('btn-add-edit-variant');
    const variantsBody = document.getElementById('edit-variants-body');
    const deletedVariantIdsInput = document.getElementById('deleted_variant_ids');
    let deletedVariantIds = [];
    let newVariantIndex = 1000;

    if (btnAddVariant && variantsBody) {
        btnAddVariant.addEventListener('click', function() {
            const tr = document.createElement('tr');
            tr.className = 'variant-row';
            tr.dataset.id = 'new_' + newVariantIndex;
            tr.innerHTML = `
                <td class="py-2.5 text-center">
                    <input type="radio" name="default_variant_id" value="new_${newVariantIndex}" class="text-[#bc944c] focus:ring-[#bc944c]">
                </td>
                <td class="py-2.5">
                    <input type="text" name="variants[${newVariantIndex}][name]" placeholder="e.g. 1 Litre" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="text" name="variants[${newVariantIndex}][sku]" placeholder="Auto" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs uppercase font-mono focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="number" step="0.01" name="variants[${newVariantIndex}][price]" placeholder="1850" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="number" step="0.01" name="variants[${newVariantIndex}][sale_price]" placeholder="1599" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="number" name="variants[${newVariantIndex}][stock_quantity]" value="50" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="number" name="variants[${newVariantIndex}][weight_grams]" placeholder="1000" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5 text-center">
                    <button type="button" class="btn-remove-edit-variant text-stone-400 hover:text-red-500 font-bold text-sm" data-id="new_${newVariantIndex}" title="Remove Variant">×</button>
                </td>
            `;
            variantsBody.appendChild(tr);
            newVariantIndex++;
        });

        variantsBody.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-remove-edit-variant')) {
                const tr = e.target.closest('tr');
                const rowId = e.target.dataset.id;
                const rows = variantsBody.querySelectorAll('.variant-row');
                if (rows.length <= 1) {
                    alert('You must have at least one variant.');
                    return;
                }
                if (rowId && !rowId.startsWith('new_') && rowId !== '0') {
                    if (confirm('Delete this variant?')) {
                        deletedVariantIds.push(rowId);
                        deletedVariantIdsInput.value = deletedVariantIds.join(',');
                        tr.remove();
                    }
                } else {
                    tr.remove();
                }
            }
        });
    }
});
</script>
