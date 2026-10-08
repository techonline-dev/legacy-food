<?php
/**
 * @var array $menuTree
 * @var array $flatItems
 * @var array $categories
 * @var array $pages
 * @var array $presetLinks
 * @var string $group
 */
?>

<div class="space-y-6 max-w-6xl mx-auto pb-16">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider">
                    Header & Submenu Navigation
                </span>
                <span id="unsaved-indicator" class="hidden items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-[10px] font-bold animate-pulse">
                    ● Unsaved Changes
                </span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d] mt-1">Navigation Menu Builder</h1>
            <p class="text-xs text-stone-500 mt-0.5">Customize your storefront header navigation, drag and drop items to reorder, and create multi-level submenus.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Reset to Defaults Form -->
            <form action="<?= url('admin/menu/reset') ?>" method="POST" onsubmit="return confirm('Reset all navigation items to factory defaults? Any custom items will be overwritten.');">
                <?= csrf_field() ?>
                <input type="hidden" name="menu_group" value="<?= e($group) ?>">
                <button type="submit" class="btn-ghost text-xs py-2 px-3 border border-stone-300 text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-colors">
                    Reset Defaults
                </button>
            </form>

            <!-- Save Hierarchy Button -->
            <button type="button" id="btn-save-hierarchy" onclick="saveMenuHierarchy()" class="btn-primary text-xs py-2 px-5 font-bold flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Menu Hierarchy</span>
            </button>
        </div>
    </div>

    <!-- Instructions Banner -->
    <div class="p-4 rounded-2xl bg-gradient-to-r from-amber-50 via-white to-amber-50/50 border border-amber-200/80 shadow-xs flex items-start gap-3.5">
        <div class="w-8 h-8 rounded-full bg-[#bc944c]/15 text-[#bc944c] flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="text-xs text-[#07160d] space-y-1">
            <h4 class="font-bold">How to manage menu & submenus:</h4>
            <p class="text-stone-600 leading-relaxed">
                • <strong>Drag & Drop:</strong> Grab the <span class="font-mono font-bold text-[#bc944c]">⋮⋮</span> handle to drag any item up or down.<br>
                • <strong>Create Submenus:</strong> Click the <span class="px-1.5 py-0.5 bg-white border border-stone-200 rounded font-bold text-[10px]">→ Indent</span> button or drag an item under a parent to make it a dropdown submenu.<br>
                • <strong>Promote to Main:</strong> Click the <span class="px-1.5 py-0.5 bg-white border border-stone-200 rounded font-bold text-[10px]">← Outdent</span> button to return a submenu back to top-level.
            </p>
        </div>
    </div>

    <!-- Main Two-Column Workspace -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT COLUMN: Add Menu Items (4 Cols) -->
        <div class="lg:col-span-4 space-y-4">
            
            <!-- Accordion 1: Custom Link -->
            <div class="rounded-2xl bg-white border border-[#e7dec8] shadow-xs overflow-hidden">
                <button type="button" class="w-full p-4 flex items-center justify-between text-left font-bold text-xs text-[#07160d] hover:bg-stone-50 transition-colors" onclick="toggleAccordion('acc-custom-link')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <span>Add Custom Link</span>
                    </span>
                    <svg id="icon-acc-custom-link" class="w-4 h-4 text-stone-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div id="acc-custom-link" class="p-4 border-t border-[#f0e9dc] bg-[#faf8f5]/50 space-y-3">
                    <form action="<?= url('admin/menu/store') ?>" method="POST" class="space-y-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="menu_group" value="<?= e($group) ?>">
                        <input type="hidden" name="type" value="custom">

                        <div>
                            <label class="block text-[11px] font-semibold text-[#07160d] uppercase tracking-wider mb-1">Navigation Label *</label>
                            <input type="text" name="title" required placeholder="e.g. Special Festive Ghee" class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-[#07160d] uppercase tracking-wider mb-1">URL or Relative Path *</label>
                            <input type="text" name="url" required placeholder="e.g. /shop or https://..." class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-[#07160d] uppercase tracking-wider mb-1">Parent Item (Optional)</label>
                            <select name="parent_id" class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                                <option value="">Top Level (Main Menu)</option>
                                <?php foreach ($flatItems as $fi): ?>
                                    <?php if (empty($fi['parent_id'])): ?>
                                        <option value="<?= $fi['id'] ?>"><?= e($fi['title']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-xs text-stone-700">
                                <input type="checkbox" name="target" value="_blank" class="rounded border-[#d6c7af] text-[#bc944c] focus:ring-[#bc944c]">
                                <span>Open in new tab</span>
                            </label>

                            <button type="submit" class="btn-primary text-xs py-1.5 px-3.5">
                                Add to Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Accordion 2: Product Categories -->
            <div class="rounded-2xl bg-white border border-[#e7dec8] shadow-xs overflow-hidden">
                <button type="button" class="w-full p-4 flex items-center justify-between text-left font-bold text-xs text-[#07160d] hover:bg-stone-50 transition-colors" onclick="toggleAccordion('acc-categories')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Product Categories</span>
                    </span>
                    <svg id="icon-acc-categories" class="w-4 h-4 text-stone-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div id="acc-categories" class="p-4 border-t border-[#f0e9dc] bg-[#faf8f5]/50 space-y-3">
                    <form action="<?= url('admin/menu/store') ?>" method="POST" class="space-y-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="menu_group" value="<?= e($group) ?>">
                        <input type="hidden" name="type" value="categories">

                        <p class="text-[11px] text-stone-500">Select categories to add to the menu navigation:</p>

                        <div class="max-h-48 overflow-y-auto space-y-1.5 p-2 rounded-xl bg-white border border-[#e7dec8]">
                            <?php foreach ($categories as $cat): ?>
                                <label class="flex items-center justify-between p-1.5 rounded-lg hover:bg-amber-50/50 cursor-pointer text-xs text-stone-800">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="category_ids[]" value="<?= $cat['id'] ?>" class="rounded border-[#d6c7af] text-[#bc944c] focus:ring-[#bc944c]">
                                        <span class="font-semibold"><?= e($cat['name']) ?></span>
                                    </div>
                                    <span class="text-[10px] text-stone-400 font-mono">/category/<?= e($cat['slug']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-[#07160d] uppercase tracking-wider mb-1">Add Under Parent (Optional)</label>
                            <select name="parent_id" class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                                <option value="">Top Level (Main Menu)</option>
                                <?php foreach ($flatItems as $fi): ?>
                                    <?php if (empty($fi['parent_id'])): ?>
                                        <option value="<?= $fi['id'] ?>"><?= e($fi['title']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex justify-end pt-1">
                            <button type="submit" class="btn-primary text-xs py-1.5 px-3.5">
                                Add Selected Categories
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Accordion 3: Pages & Preset Links -->
            <div class="rounded-2xl bg-white border border-[#e7dec8] shadow-xs overflow-hidden">
                <button type="button" class="w-full p-4 flex items-center justify-between text-left font-bold text-xs text-[#07160d] hover:bg-stone-50 transition-colors" onclick="toggleAccordion('acc-pages')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Pages & Preset Links</span>
                    </span>
                    <svg id="icon-acc-pages" class="w-4 h-4 text-stone-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div id="acc-pages" class="p-4 border-t border-[#f0e9dc] bg-[#faf8f5]/50 space-y-3">
                    <form action="<?= url('admin/menu/store') ?>" method="POST" class="space-y-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="menu_group" value="<?= e($group) ?>">
                        <input type="hidden" name="type" value="pages">

                        <div class="max-h-48 overflow-y-auto space-y-1.5 p-2 rounded-xl bg-white border border-[#e7dec8]">
                            <?php foreach ($presetLinks as $link): ?>
                                <label class="flex items-center justify-between p-1.5 rounded-lg hover:bg-amber-50/50 cursor-pointer text-xs text-stone-800">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="page_slugs[]" value="<?= e(ltrim($link['url'], '/') ?: 'home') ?>" class="rounded border-[#d6c7af] text-[#bc944c] focus:ring-[#bc944c]">
                                        <span class="font-semibold"><?= e($link['title']) ?></span>
                                    </div>
                                    <span class="text-[10px] text-stone-400 font-mono"><?= e($link['url']) ?></span>
                                </label>
                            <?php endforeach; ?>

                            <?php foreach ($pages as $p): ?>
                                <label class="flex items-center justify-between p-1.5 rounded-lg hover:bg-amber-50/50 cursor-pointer text-xs text-stone-800">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="page_slugs[]" value="<?= e($p['slug']) ?>" class="rounded border-[#d6c7af] text-[#bc944c] focus:ring-[#bc944c]">
                                        <span class="font-semibold"><?= e($p['title']) ?></span>
                                    </div>
                                    <span class="text-[10px] text-stone-400 font-mono">/<?= e($p['slug']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-[#07160d] uppercase tracking-wider mb-1">Add Under Parent (Optional)</label>
                            <select name="parent_id" class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                                <option value="">Top Level (Main Menu)</option>
                                <?php foreach ($flatItems as $fi): ?>
                                    <?php if (empty($fi['parent_id'])): ?>
                                        <option value="<?= $fi['id'] ?>"><?= e($fi['title']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex justify-end pt-1">
                            <button type="submit" class="btn-primary text-xs py-1.5 px-3.5">
                                Add Selected Pages
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Interactive Drag & Drop Canvas (8 Cols) -->
        <div class="lg:col-span-8 space-y-4">
            
            <div class="rounded-2xl bg-white border border-[#e7dec8] shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#e7dec8]">
                    <div class="flex items-center gap-2">
                        <h2 class="font-display text-base font-bold text-[#07160d]">Menu Structure</h2>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-[#bc944c] border border-amber-200/80">
                            <?= count($flatItems) ?> Items
                        </span>
                    </div>
                    <span class="text-[11px] text-stone-500">Drag items to rearrange & indent for submenus</span>
                </div>

                <!-- Drop List Container -->
                <div id="menu-items-list" class="space-y-2.5 min-h-[250px] relative">
                    <?php if (empty($menuTree)): ?>
                        <div class="text-center py-12 border-2 border-dashed border-[#e7dec8] rounded-2xl bg-[#faf8f5]/60 space-y-3">
                            <div class="w-12 h-12 rounded-full bg-amber-100 text-[#bc944c] flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                            </div>
                            <h3 class="font-bold text-sm text-[#07160d]">No Menu Items Configured Yet</h3>
                            <p class="text-xs text-stone-500 max-w-sm mx-auto">Add items using the sidebar on the left or seed the default header navigation.</p>
                            <form action="<?= url('admin/menu/reset') ?>" method="POST" class="pt-2">
                                <?= csrf_field() ?>
                                <input type="hidden" name="menu_group" value="<?= e($group) ?>">
                                <button type="submit" class="btn-primary text-xs py-2 px-4 font-bold shadow-xs">
                                    Load Default Navigation
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <?php 
                        // Render recursive or flattened tree with parent identification
                        foreach ($menuTree as $parent): 
                        ?>
                            <!-- Top Level Item -->
                            <?= renderMenuItemRow($parent, null, $flatItems) ?>

                            <?php if (!empty($parent['children'])): ?>
                                <?php foreach ($parent['children'] as $child): ?>
                                    <!-- Child / Submenu Item -->
                                    <?= renderMenuItemRow($child, $parent, $flatItems) ?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Sticky Bottom Bar when order changed -->
                <div class="pt-4 border-t border-[#e7dec8] flex items-center justify-between text-xs text-stone-500">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Drag items freely. Changes take effect on the live store upon clicking <strong>Save Menu Hierarchy</strong>.</span>
                    </div>
                    <button type="button" onclick="saveMenuHierarchy()" class="btn-primary text-xs py-2 px-5 font-bold flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Save Menu Hierarchy</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
/**
 * Helper to render a menu item row card
 */
function renderMenuItemRow(array $item, ?array $parent = null, array $allFlat = []): string {
    $isChild = !empty($parent) || !empty($item['parent_id']);
    $parentId = $item['parent_id'] ?? ($parent['id'] ?? '');
    $id = (int)$item['id'];
    $title = htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8');
    $url = htmlspecialchars($item['url'] ?? '', ENT_QUOTES, 'UTF-8');
    $target = $item['target'] ?? '_self';
    $isActive = (int)($item['is_active'] ?? 1);
    
    ob_start();
    ?>
    <div class="menu-item-row group/row transition-all duration-200 <?= $isChild ? 'ml-8 sm:ml-12 border-l-4 border-l-[#bc944c]' : 'border-l-4 border-l-[#07160d]' ?> rounded-xl bg-white border border-[#e7dec8] shadow-xs hover:border-[#bc944c]/60 hover:shadow-sm"
         draggable="true"
         data-id="<?= $id ?>"
         data-parent-id="<?= $parentId ?>"
         data-title="<?= $title ?>"
         data-url="<?= $url ?>"
         data-target="<?= $target ?>"
         data-is-active="<?= $isActive ?>">

        <!-- Row Header (Handle, Title, Badges, Quick Controls) -->
        <div class="p-3 sm:p-3.5 flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <!-- Drag Handle -->
                <div class="cursor-grab active:cursor-grabbing p-1 text-stone-400 hover:text-[#bc944c] transition-colors rounded-lg hover:bg-stone-100 shrink-0" title="Click and drag to reorder">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                    </svg>
                </div>

                <!-- Submenu indicator icon -->
                <?php if ($isChild): ?>
                    <span class="text-[#bc944c] font-bold text-xs shrink-0" title="Submenu item">↳</span>
                <?php endif; ?>

                <!-- Title & URL Details -->
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="font-bold text-xs sm:text-sm text-[#07160d] truncate"><?= $title ?></h4>
                        
                        <?php if ($isChild): ?>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-50 text-[#bc944c] border border-amber-200">
                                Submenu
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-stone-100 text-stone-600">
                                Main
                            </span>
                        <?php endif; ?>

                        <?php if ($target === '_blank'): ?>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700">↗ New Tab</span>
                        <?php endif; ?>

                        <?php if ($isActive === 0): ?>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700">Inactive</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-[11px] font-mono text-stone-400 truncate block mt-0.5"><?= $url ?></span>
                </div>
            </div>

            <!-- Action Buttons Toolbar -->
            <div class="flex items-center gap-1 shrink-0">
                <!-- Reorder buttons (Move Up, Move Down) -->
                <div class="hidden sm:flex items-center gap-0.5 bg-stone-50 border border-stone-200 rounded-lg p-0.5">
                    <button type="button" onclick="moveItemUp(this)" class="p-1 text-stone-500 hover:text-[#07160d] hover:bg-white rounded transition-colors" title="Move Up">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <button type="button" onclick="moveItemDown(this)" class="p-1 text-stone-500 hover:text-[#07160d] hover:bg-white rounded transition-colors" title="Move Down">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                <!-- Hierarchy Indent / Outdent Buttons -->
                <div class="flex items-center gap-0.5 bg-stone-50 border border-stone-200 rounded-lg p-0.5">
                    <button type="button" onclick="outdentItem(this)" class="px-1.5 py-1 text-[11px] font-bold text-stone-600 hover:text-[#07160d] hover:bg-white rounded transition-colors <?= !$isChild ? 'opacity-30 cursor-not-allowed' : '' ?>" title="Outdent (Make Top-Level Main Menu)">
                        ← Outdent
                    </button>
                    <button type="button" onclick="indentItem(this)" class="px-1.5 py-1 text-[11px] font-bold text-stone-600 hover:text-[#07160d] hover:bg-white rounded transition-colors <?= $isChild ? 'opacity-30 cursor-not-allowed' : '' ?>" title="Indent (Make Submenu of Item Above)">
                        → Submenu
                    </button>
                </div>

                <!-- Toggle Edit Button -->
                <button type="button" onclick="toggleEditPanel(<?= $id ?>)" class="p-1.5 text-stone-500 hover:text-[#bc944c] hover:bg-stone-50 rounded-lg transition-colors" title="Edit Item Details">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>

                <!-- Delete Item Form -->
                <form action="<?= url('admin/menu/delete/' . $id) ?>" method="POST" onsubmit="return confirm('Delete \'<?= addslashes($title) ?>\' from navigation?');" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="p-1.5 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Inline Edit Panel (Hidden by default) -->
        <div id="edit-panel-<?= $id ?>" class="hidden px-4 pb-4 pt-3 border-t border-[#f0e9dc] bg-[#faf8f5]/60">
            <form action="<?= url('admin/menu/update/' . $id) ?>" method="POST" class="space-y-3">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-[#07160d] uppercase tracking-wider mb-1">Title</label>
                        <input type="text" name="title" value="<?= $title ?>" required class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs font-bold text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-[#07160d] uppercase tracking-wider mb-1">URL / Path</label>
                        <input type="text" name="url" value="<?= $url ?>" required class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-[11px] font-semibold text-[#07160d] uppercase tracking-wider mb-1">Parent Hierarchy</label>
                        <select name="parent_id" class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="">Top Level (Main Menu)</option>
                            <?php foreach ($allFlat as $parentOpt): ?>
                                <?php if ($parentOpt['id'] != $id && empty($parentOpt['parent_id'])): ?>
                                    <option value="<?= $parentOpt['id'] ?>" <?= ($parentId == $parentOpt['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($parentOpt['title'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex items-center gap-4 pt-5">
                        <label class="flex items-center gap-1.5 cursor-pointer text-xs text-stone-700 font-medium">
                            <input type="checkbox" name="target" value="_blank" <?= $target === '_blank' ? 'checked' : '' ?> class="rounded border-[#d6c7af] text-[#bc944c] focus:ring-[#bc944c]">
                            <span>Open in new tab</span>
                        </label>

                        <label class="flex items-center gap-1.5 cursor-pointer text-xs text-stone-700 font-medium">
                            <input type="checkbox" name="is_active" value="1" <?= $isActive === 1 ? 'checked' : '' ?> class="rounded border-[#d6c7af] text-[#bc944c] focus:ring-[#bc944c]">
                            <span>Active / Visible</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#e7dec8]">
                    <button type="button" onclick="toggleEditPanel(<?= $id ?>)" class="btn-ghost text-xs py-1.5 px-3">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary text-xs py-1.5 px-4 font-bold">
                        Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>

<!-- Drag and Drop & Hierarchy Management JavaScript -->
<script>
let draggedItem = null;
let isDirty = false;

// Toggle accordion helper
function toggleAccordion(id) {
    const el = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (!el) return;
    el.classList.toggle('hidden');
    if (icon) {
        icon.classList.toggle('rotate-180');
    }
}

// Toggle row edit panel helper
function toggleEditPanel(id) {
    const panel = document.getElementById('edit-panel-' + id);
    if (panel) {
        panel.classList.toggle('hidden');
    }
}

// Set dirty state
function markDirty() {
    isDirty = true;
    const ind = document.getElementById('unsaved-indicator');
    if (ind) {
        ind.classList.remove('hidden');
        ind.classList.add('inline-flex');
    }
    const btn = document.getElementById('btn-save-hierarchy');
    if (btn) {
        btn.classList.add('ring-2', 'ring-[#bc944c]', 'animate-pulse');
    }
}

// Clear dirty state
function markClean() {
    isDirty = false;
    const ind = document.getElementById('unsaved-indicator');
    if (ind) {
        ind.classList.add('hidden');
        ind.classList.remove('inline-flex');
    }
    const btn = document.getElementById('btn-save-hierarchy');
    if (btn) {
        btn.classList.remove('ring-2', 'ring-[#bc944c]', 'animate-pulse');
    }
}

// Move Item Up
function moveItemUp(btn) {
    const row = btn.closest('.menu-item-row');
    if (!row) return;
    const prev = row.previousElementSibling;
    if (prev && prev.classList.contains('menu-item-row')) {
        row.parentNode.insertBefore(row, prev);
        markDirty();
    }
}

// Move Item Down
function moveItemDown(btn) {
    const row = btn.closest('.menu-item-row');
    if (!row) return;
    const next = row.nextElementSibling;
    if (next && next.classList.contains('menu-item-row')) {
        row.parentNode.insertBefore(next, row);
        markDirty();
    }
}

// Indent Item (Make Submenu of preceding item)
function indentItem(btn) {
    const row = btn.closest('.menu-item-row');
    if (!row) return;
    const prev = row.previousElementSibling;
    if (!prev || !prev.classList.contains('menu-item-row')) {
        alert('An item must have a top-level menu item above it to become a submenu.');
        return;
    }

    // Determine target parent ID
    const parentId = prev.getAttribute('data-id');
    row.setAttribute('data-parent-id', parentId);
    row.classList.remove('border-l-[#07160d]');
    row.classList.add('ml-8', 'sm:ml-12', 'border-l-4', 'border-l-[#bc944c]');
    
    markDirty();
    saveMenuHierarchy(); // auto-save for smooth feedback
}

// Outdent Item (Promote to Main Menu)
function outdentItem(btn) {
    const row = btn.closest('.menu-item-row');
    if (!row) return;

    row.setAttribute('data-parent-id', '');
    row.classList.remove('ml-8', 'sm:ml-12', 'border-l-[#bc944c]');
    row.classList.add('border-l-4', 'border-l-[#07160d]');
    
    markDirty();
    saveMenuHierarchy();
}

// HTML5 Drag and Drop Event Listeners
function initDragAndDrop() {
    const container = document.getElementById('menu-items-list');
    if (!container) return;

    container.addEventListener('dragstart', (e) => {
        const row = e.target.closest('.menu-item-row');
        if (!row) return;
        draggedItem = row;
        row.classList.add('opacity-40', 'scale-[0.98]', 'ring-2', 'ring-[#bc944c]');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', row.getAttribute('data-id'));
    });

    container.addEventListener('dragend', (e) => {
        const row = e.target.closest('.menu-item-row');
        if (row) {
            row.classList.remove('opacity-40', 'scale-[0.98]', 'ring-2', 'ring-[#bc944c]');
        }
        document.querySelectorAll('.menu-item-row').forEach(r => {
            r.classList.remove('border-t-2', 'border-t-[#bc944c]', 'border-b-2', 'border-b-[#bc944c]', 'bg-amber-50/40');
        });
        draggedItem = null;
    });

    container.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';

        const targetRow = e.target.closest('.menu-item-row');
        if (!targetRow || targetRow === draggedItem) return;

        const rect = targetRow.getBoundingClientRect();
        const offset = e.clientY - rect.top;
        const height = rect.height;

        document.querySelectorAll('.menu-item-row').forEach(r => {
            r.classList.remove('border-t-2', 'border-t-[#bc944c]', 'border-b-2', 'border-b-[#bc944c]', 'bg-amber-50/40');
        });

        if (offset < height / 2) {
            targetRow.classList.add('border-t-2', 'border-t-[#bc944c]');
        } else {
            targetRow.classList.add('border-b-2', 'border-b-[#bc944c]');
        }
    });

    container.addEventListener('drop', (e) => {
        e.preventDefault();
        const targetRow = e.target.closest('.menu-item-row');
        if (!targetRow || !draggedItem || targetRow === draggedItem) return;

        const rect = targetRow.getBoundingClientRect();
        const offset = e.clientY - rect.top;
        const height = rect.height;

        if (offset < height / 2) {
            targetRow.parentNode.insertBefore(draggedItem, targetRow);
        } else {
            targetRow.parentNode.insertBefore(draggedItem, targetRow.nextElementSibling);
        }

        // Check if dragged item should inherit target's parent or stay top
        const prevSibling = draggedItem.previousElementSibling;
        if (e.clientX - rect.left > 60 && prevSibling && prevSibling.classList.contains('menu-item-row')) {
            // Nest as submenu
            draggedItem.setAttribute('data-parent-id', prevSibling.getAttribute('data-id'));
            draggedItem.classList.remove('border-l-[#07160d]');
            draggedItem.classList.add('ml-8', 'sm:ml-12', 'border-l-4', 'border-l-[#bc944c]');
        }

        markDirty();
    });
}

// Serialize & Save Hierarchy via AJAX
async function saveMenuHierarchy() {
    const rows = document.querySelectorAll('#menu-items-list .menu-item-row');
    if (!rows || rows.length === 0) return;

    const items = [];
    rows.forEach((row, index) => {
        const id = parseInt(row.getAttribute('data-id'), 10);
        const parentId = row.getAttribute('data-parent-id') ? parseInt(row.getAttribute('data-parent-id'), 10) : null;
        items.push({
            id: id,
            parent_id: parentId,
            sort_order: index + 1
        });
    });

    const btn = document.getElementById('btn-save-hierarchy');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#07160d]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Saving...`;

    try {
        const formData = new FormData();
        formData.append('_csrf_token', '<?= csrf_token() ?>');
        formData.append('items', JSON.stringify(items));

        const res = await fetch('<?= url('admin/menu/reorder') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await res.json();
        if (data.success) {
            markClean();
            btn.innerHTML = `✓ Saved!`;
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                window.location.reload();
            }, 700);
        } else {
            alert(data.message || 'Error saving menu.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    } catch (err) {
        console.error(err);
        alert('Network error while saving menu hierarchy.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', () => {
    initDragAndDrop();
});
</script>
