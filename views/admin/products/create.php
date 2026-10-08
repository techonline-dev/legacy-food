<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xs text-[#bc944c] font-semibold uppercase tracking-wider mb-1">Catalog Management</div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Add New Heritage Product</h1>
        </div>
        <a href="<?= url('admin/products') ?>" class="btn-ghost text-xs py-2 px-3 border border-[#d6c7af] text-[#07160d] hover:bg-stone-50 flex items-center gap-1.5">
            ← Back to Products
        </a>
    </div>

    <form action="<?= url('admin/products/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">General Information</h2>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Product Title *</label>
                        <input type="text" name="name" required placeholder="e.g. A2 Desi Cow Cultured Ghee"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Custom URL Slug (Optional)</label>
                        <div class="flex rounded-xl shadow-sm border border-[#d6c7af] overflow-hidden focus-within:border-[#bc944c]">
                            <span class="inline-flex items-center px-3 bg-stone-50 text-stone-500 text-xs font-mono border-r border-[#d6c7af]">/product/</span>
                            <input type="text" name="slug" placeholder="auto-generated-from-title"
                                   class="flex-1 py-2.5 px-3 bg-white text-sm text-[#1c1917] font-mono focus:outline-none">
                        </div>
                        <p class="text-[11px] text-stone-500 mt-1">Leave empty to automatically generate from product title.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Category *</label>
                            <select name="category_id" required class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">SKU (Stock Keeping Unit)</label>
                            <input type="text" name="sku" placeholder="Leave empty for auto LF-XXX"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none uppercase font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Short Highlights Description</label>
                        <textarea name="short_description" rows="2" placeholder="Brief 1-2 sentence overview for product card & top summary"
                                  class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Detailed Story & Description</label>
                        <textarea name="description" rows="5" placeholder="Full product description, traditional wood-pressed method, sourcing, etc."
                                  class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Key Health Benefits</label>
                            <textarea name="benefits" rows="3" placeholder="Enter bullet points (one per line)"
                                      class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Purity & Ingredients</label>
                            <textarea name="ingredients" rows="3" placeholder="e.g. 100% Pure Cultured Desi Cow Milk Fat. No chemicals."
                                      class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pricing, GST & Master Stock Card -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-5">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Pricing, GST Tax & Inventory Control</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Regular Base Price (₹) *</label>
                            <input type="number" step="0.01" name="base_price" required placeholder="950.00"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Discounted Sale Price (₹)</label>
                            <input type="number" step="0.01" name="sale_price" placeholder="799.00 (optional)"
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
                                    <option value="" selected>Inherit Store Default (<?= (float)setting('gst_percentage', 5.0) ?>%)</option>
                                    <option value="0.00">0.00% - Tax Exempt</option>
                                    <option value="5.00">5.00% - Standard Ghee & Edible Oils</option>
                                    <option value="12.00">12.00% - Processed Foods</option>
                                    <option value="18.00">18.00% - Standard Commercial</option>
                                    <option value="28.00">28.00% - Luxury Slabs</option>
                                </select>
                                <p class="text-[11px] text-stone-500 mt-1">Leave as "Inherit Store Default" to use the global GST rate.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">HSN / SAC Code</label>
                                <input type="text" name="hsn_code" placeholder="e.g. 04059020 (Ghee) or 1508"
                                       class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none uppercase">
                                <p class="text-[11px] text-stone-500 mt-1">Leave blank to use store default (<?= e(setting('default_hsn_code', '04059020')) ?>).</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Initial Stock Quantity *</label>
                            <input type="number" name="stock_quantity" value="50" required
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Low Stock Warning Alert</label>
                            <input type="number" name="low_stock_threshold" value="5"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Product Variants Builder Card (Multiple Variants) -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-display text-base font-bold text-[#07160d]">Product Variants (Optional)</h2>
                            <p class="text-[11px] text-stone-500">Add multiple package sizes, weights, or volumes (e.g. 250ml, 500ml, 1 Litre, 5 Litres).</p>
                        </div>
                        <button type="button" id="btn-add-variant" class="btn-ghost py-1.5 px-3 text-xs border border-[#bc944c] text-[#bc944c] hover:bg-[#bc944c] hover:text-[#07160d] font-bold rounded-lg transition-colors flex items-center gap-1.5">
                            <span>+ Add Variant</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs" id="variants-table">
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
                            <tbody class="divide-y divide-stone-100" id="variants-body">
                                <tr class="variant-row" data-idx="0">
                                    <td class="py-2.5 text-center">
                                        <input type="radio" name="default_variant_index" value="0" checked class="text-[#bc944c] focus:ring-[#bc944c]">
                                    </td>
                                    <td class="py-2.5">
                                        <input type="text" name="variants[0][name]" value="Standard 500ml" placeholder="e.g. 500 ml" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                    </td>
                                    <td class="py-2.5">
                                        <input type="text" name="variants[0][sku]" placeholder="Auto" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs uppercase font-mono focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                    </td>
                                    <td class="py-2.5">
                                        <input type="number" step="0.01" name="variants[0][price]" placeholder="950" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                    </td>
                                    <td class="py-2.5">
                                        <input type="number" step="0.01" name="variants[0][sale_price]" placeholder="799" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                    </td>
                                    <td class="py-2.5">
                                        <input type="number" name="variants[0][stock_quantity]" value="50" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                    </td>
                                    <td class="py-2.5">
                                        <input type="number" name="variants[0][weight_grams]" placeholder="500" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                                    </td>
                                    <td class="py-2.5 text-center">
                                        <button type="button" class="btn-remove-variant text-stone-400 hover:text-red-500 font-bold text-sm" title="Remove Variant">×</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-[11px] text-stone-400">If no variants are defined, the main price and stock above will be used to create the single default variant automatically.</p>
                </div>

                <!-- SEO Settings Card -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Search Engine Optimization (SEO)</h2>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Custom SEO Meta Title</label>
                        <input type="text" name="meta_title" placeholder="Buy Pure Traditional Cow Ghee Online | Legacy Food"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">SEO Meta Description</label>
                        <textarea name="meta_description" rows="2" placeholder="Explore authentic lab-certified pure cold pressed oils and Vedic cultured bilona cow ghee..."
                                  class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"></textarea>
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
                            <option value="published" selected>Published (Visible to Store)</option>
                            <option value="draft">Draft (Hidden)</option>
                        </select>
                    </div>

                    <div class="pt-2 space-y-2 border-t border-[#e7dec8]">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-stone-300 text-[#bc944c] focus:ring-[#bc944c]">
                            <span class="text-xs text-[#07160d]">Mark as Featured on Homepage</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_bestseller" value="1" class="rounded border-stone-300 text-[#bc944c] focus:ring-[#bc944c]">
                            <span class="text-xs text-[#07160d]">Mark as Bestseller</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full btn-primary text-xs py-3 font-bold justify-center">
                            Save & Publish Product
                        </button>
                    </div>
                </div>

                <!-- Product Featured Media -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <h2 class="font-display text-base font-bold text-[#07160d]">Primary Featured Image</h2>

                    <!-- Live Preview Box -->
                    <div class="text-center p-3 bg-stone-50 rounded-xl border border-[#e7dec8]" id="featured-preview-box">
                        <img id="featured-preview-img" src="https://cdn.sanity.io/images/dwps51kj/production/adbe8001d264661c9bbd49ef61f0f8d00894dba9-1080x1080.png?w=640" class="w-28 h-28 object-contain mx-auto rounded-lg bg-white p-2 border border-[#e7dec8]">
                        <span class="text-[10px] text-stone-500 mt-1.5 block">Image Preview</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Image URL (Web / CDN)</label>
                        <input type="url" id="featured_image_input" name="featured_image" placeholder="https://..."
                               class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Or Upload Image File</label>
                        <input type="file" id="image_file_input" name="image_file" accept="image/*"
                               class="w-full text-xs text-stone-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#bc944c] file:text-[#07160d] hover:file:bg-[#d8bd99] cursor-pointer">
                    </div>
                </div>

                <!-- Product Gallery Images (Multiple) -->
                <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-base font-bold text-[#07160d]">Product Gallery (Multiple)</h2>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#faf8f5] text-[#bc944c] border border-[#d6c7af] font-semibold">Multi-Angle</span>
                    </div>
                    <p class="text-[11px] text-stone-500">Upload multiple gallery photos or supply URLs for customer thumbnail gallery.</p>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Upload Multiple Images</label>
                        <input type="file" id="gallery_files_input" name="gallery_files[]" multiple accept="image/*"
                               class="w-full text-xs text-stone-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#bc944c] file:text-[#07160d] hover:file:bg-[#d8bd99] cursor-pointer">
                        <div id="gallery-preview-container" class="mt-3 flex flex-wrap gap-2 empty:hidden"></div>
                    </div>

                    <div class="pt-2 border-t border-stone-100">
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Or Paste Gallery Image URLs</label>
                        <textarea name="gallery_urls" rows="2" placeholder="https://example.com/img1.jpg&#10;https://example.com/img2.jpg"
                                  class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none"></textarea>
                        <p class="text-[10px] text-stone-400 mt-1">One URL per line.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Featured Image Preview
    const featInput = document.getElementById('featured_image_input');
    const featFileInput = document.getElementById('image_file_input');
    const featPreview = document.getElementById('featured-preview-img');

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
    const galleryInput = document.getElementById('gallery_files_input');
    const galleryContainer = document.getElementById('gallery-preview-container');
    if (galleryInput && galleryContainer) {
        galleryInput.addEventListener('change', function(e) {
            galleryContainer.innerHTML = '';
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const thumb = document.createElement('div');
                    thumb.className = 'w-14 h-14 rounded-lg border border-[#d6c7af] bg-white p-1 overflow-hidden shadow-xs';
                    thumb.innerHTML = `<img src="${evt.target.result}" class="w-full h-full object-contain">`;
                    galleryContainer.appendChild(thumb);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // Dynamic Variant Rows
    let variantIndex = 1;
    const btnAddVariant = document.getElementById('btn-add-variant');
    const variantsBody = document.getElementById('variants-body');

    if (btnAddVariant && variantsBody) {
        btnAddVariant.addEventListener('click', function() {
            const tr = document.createElement('tr');
            tr.className = 'variant-row';
            tr.dataset.idx = variantIndex;
            tr.innerHTML = `
                <td class="py-2.5 text-center">
                    <input type="radio" name="default_variant_index" value="${variantIndex}" class="text-[#bc944c] focus:ring-[#bc944c]">
                </td>
                <td class="py-2.5">
                    <input type="text" name="variants[${variantIndex}][name]" placeholder="e.g. 1 Litre" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="text" name="variants[${variantIndex}][sku]" placeholder="Auto" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs uppercase font-mono focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="number" step="0.01" name="variants[${variantIndex}][price]" placeholder="1850" required class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="number" step="0.01" name="variants[${variantIndex}][sale_price]" placeholder="1599" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="number" name="variants[${variantIndex}][stock_quantity]" value="50" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5">
                    <input type="number" name="variants[${variantIndex}][weight_grams]" placeholder="1000" class="w-full py-1.5 px-2 rounded-lg bg-stone-50 border border-stone-200 text-xs focus:bg-white focus:border-[#bc944c] focus:outline-none">
                </td>
                <td class="py-2.5 text-center">
                    <button type="button" class="btn-remove-variant text-stone-400 hover:text-red-500 font-bold text-sm" title="Remove Variant">×</button>
                </td>
            `;
            variantsBody.appendChild(tr);
            variantIndex++;
        });

        variantsBody.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-remove-variant')) {
                const rows = variantsBody.querySelectorAll('.variant-row');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('You must have at least one variant.');
                }
            }
        });
    }
});
</script>
