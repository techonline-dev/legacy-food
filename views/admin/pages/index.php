<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#fdf6e3]">Content Management (CMS)</h1>
            <p class="text-xs text-[#fdf6e3]/60 mt-1">Manage static editorial pages, policies, terms, and custom URLs.</p>
        </div>
        <div>
            <a href="<?= url('admin/pages/create') ?>" class="btn-primary text-xs py-2.5 px-4 font-bold flex items-center gap-2">
                <span>+ Create New Page</span>
            </a>
        </div>
    </div>

    <!-- Pages Table Card -->
    <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#bc944c]/20 text-[#bc944c] uppercase tracking-wider text-[10px]">
                        <th class="pb-3 font-semibold">Page Title</th>
                        <th class="pb-3 font-semibold">Slug / URL Permalink</th>
                        <th class="pb-3 font-semibold">SEO Meta Title</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.05]">
                    <?php foreach ($pages as $pg): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 font-semibold text-[#fdf6e3]">
                                <?= e($pg['title']) ?>
                            </td>
                            <td class="py-3.5 font-mono text-[#bc944c]">
                                <a href="<?= url($pg['slug']) ?>" target="_blank" class="hover:underline flex items-center gap-1">
                                    <span>/<?= e($pg['slug']) ?></span>
                                    <svg class="w-3 h-3 text-[#bc944c]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </td>
                            <td class="py-3.5 text-[#fdf6e3]/60">
                                <?= e($pg['meta_title'] ?? $pg['title']) ?>
                            </td>
                            <td class="py-3.5">
                                <?php if (!empty($pg['is_active'])): ?>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Published</span>
                                <?php else: ?>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/10 text-white/50">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= url('admin/pages/edit/' . $pg['id']) ?>" class="btn-ghost py-1 px-3 text-[11px]">
                                        Edit Slug & Content →
                                    </a>
                                    <form action="<?= url('admin/pages/delete/' . $pg['id']) ?>" method="POST" onsubmit="return confirm('Delete page <?= e($pg['title']) ?>?');" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" title="Delete Page" class="p-1 rounded-lg hover:bg-red-500/20 text-red-400 hover:text-red-300 transition-colors">
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
