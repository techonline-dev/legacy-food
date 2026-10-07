<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#fdf6e3]">Products Catalog</h1>
            <p class="text-xs text-[#fdf6e3]/60 mt-1">Manage heritage food catalog, pricing, variants, and inventory.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('admin/products/create') ?>" class="btn-primary text-xs py-2 px-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add New Product</span>
            </a>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
            <div class="text-xs text-[#fdf6e3]/60">
                Total Products: <span class="font-bold text-[#bc944c]"><?= count($products) ?> items</span>
            </div>
            <!-- Search bar -->
            <div class="w-full sm:w-64">
                <input type="text" id="productSearchInput" placeholder="Filter by title or SKU..." 
                       class="w-full py-1.5 px-3 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs text-[#fdf6e3] placeholder-[#fdf6e3]/30 focus:border-[#bc944c] focus:outline-none">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="productsTable">
                <thead>
                    <tr class="border-b border-[#bc944c]/20 text-[#bc944c] uppercase tracking-wider text-[10px]">
                        <th class="pb-3 font-semibold">Product</th>
                        <th class="pb-3 font-semibold">Category</th>
                        <th class="pb-3 font-semibold">SKU</th>
                        <th class="pb-3 font-semibold">Price</th>
                        <th class="pb-3 font-semibold">Stock</th>
                        <th class="pb-3 font-semibold">Variants</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.05]">
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8" class="py-12 text-center text-[#fdf6e3]/50">
                                No products found in the catalog.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr class="hover:bg-white/[0.02] transition-colors product-row">
                                <td class="py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="<?= e($p['featured_image']) ?>" 
                                             alt="<?= e($p['name']) ?>" 
                                             class="w-12 h-12 rounded-lg object-contain bg-[#0e2319] p-1 border border-[#bc944c]/20 shrink-0">
                                        <div>
                                            <a href="<?= url('admin/products/edit/' . $p['id']) ?>" class="font-semibold text-[#fdf6e3] hover:text-[#bc944c] transition-colors product-name">
                                                <?= e($p['name']) ?>
                                            </a>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <?php if (!empty($p['is_featured'])): ?>
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-[#bc944c]/20 text-[#bc944c]">Featured</span>
                                                <?php endif; ?>
                                                <?php if (!empty($p['is_bestseller'])): ?>
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/20 text-amber-300">Bestseller</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-[#fdf6e3]/75">
                                    <?= e($p['category_name'] ?? 'Uncategorized') ?>
                                </td>
                                <td class="py-3 font-mono text-[#bc944c] product-sku">
                                    <?= e($p['sku']) ?>
                                </td>
                                <td class="py-3 font-serif">
                                    <?php if (!empty($p['sale_price']) && $p['sale_price'] < $p['base_price']): ?>
                                        <div class="font-bold text-[#fdf6e3]"><?= format_price($p['sale_price']) ?></div>
                                        <div class="text-[10px] text-[#fdf6e3]/40 line-through"><?= format_price($p['base_price']) ?></div>
                                    <?php else: ?>
                                        <div class="font-bold text-[#fdf6e3]"><?= format_price($p['base_price']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3">
                                    <?php if ($p['stock_quantity'] <= 0): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/20 text-red-300 border border-red-500/30">Out of Stock</span>
                                    <?php elseif ($p['stock_quantity'] <= $p['low_stock_threshold']): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30"><?= (int)$p['stock_quantity'] ?> left (Low)</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30"><?= (int)$p['stock_quantity'] ?> in stock</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-[#fdf6e3]/75">
                                    <span class="px-2 py-0.5 rounded bg-white/[0.05] text-[10px] font-mono">
                                        <?= (int)$p['variants_count'] ?> variant<?= (int)$p['variants_count'] !== 1 ? 's' : '' ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $p['status'] === 'published' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-white/10 text-white/60' ?>">
                                        <?= ucfirst(e($p['status'])) ?>
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= url('product/' . $p['slug']) ?>" target="_blank" title="View on Store" class="p-1.5 rounded-lg hover:bg-white/[0.08] text-[#fdf6e3]/60 hover:text-[#bc944c]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                        <a href="<?= url('admin/products/edit/' . $p['id']) ?>" title="Edit Product" class="p-1.5 rounded-lg hover:bg-[#bc944c]/20 text-[#bc944c]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="<?= url('admin/products/delete/' . $p['id']) ?>" method="POST" onsubmit="return confirm('Archive or delete this product?');" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" title="Delete Product" class="p-1.5 rounded-lg hover:bg-red-500/20 text-red-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

<script>
document.getElementById('productSearchInput')?.addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.product-row').forEach(row => {
        const name = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
        const sku = row.querySelector('.product-sku')?.textContent.toLowerCase() || '';
        if (name.includes(q) || sku.includes(q)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
