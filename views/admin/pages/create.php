<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xs text-[#bc944c] font-semibold uppercase tracking-wider mb-1">CMS Management</div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#fdf6e3]">Create New Editorial Page</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('admin/pages') ?>" class="btn-ghost text-xs py-2 px-3">
                ← Back to Pages
            </a>
        </div>
    </div>

    <form action="<?= url('admin/pages/store') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Page Title *</label>
                <input type="text" name="title" required placeholder="e.g. Lab Testing & Certifications"
                       class="w-full py-2.5 px-3.5 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-sm text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">URL Permalink Slug (Optional)</label>
                <div class="flex items-center">
                    <span class="inline-flex items-center px-3.5 py-2.5 rounded-l-xl bg-white/[0.08] border border-r-0 border-[#bc944c]/30 text-xs text-[#bc944c] font-mono">
                        <?= url('/') ?>/
                    </span>
                    <input type="text" name="slug" placeholder="e.g. lab-testing-and-certifications (auto-generated if empty)"
                           class="flex-1 py-2.5 px-3.5 rounded-r-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs font-mono text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Page Visibility Status</label>
                <select name="is_active" class="w-full py-2.5 px-3.5 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
                    <option value="1" selected>Published / Active (Live on website)</option>
                    <option value="0">Draft / Hidden</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Page Content (HTML / Text) *</label>
                <textarea name="content" rows="16" required placeholder="<h2>Headline</h2><p>Page story content...</p>"
                          class="w-full py-3 px-4 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs font-mono text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none leading-relaxed"></textarea>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20 space-y-4">
            <h2 class="font-serif text-base font-bold text-[#fdf6e3]">SEO Meta Information</h2>

            <div>
                <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">Custom SEO Meta Title</label>
                <input type="text" name="meta_title" placeholder="e.g. Lab Testing & Purity Reports | Legacy Food"
                       class="w-full py-2.5 px-3.5 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-sm text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#fdf6e3]/80 uppercase tracking-wider mb-1.5">SEO Meta Description</label>
                <textarea name="meta_description" rows="3" placeholder="Brief search snippet description..."
                          class="w-full py-2 px-3 rounded-xl bg-white/[0.04] border border-[#bc944c]/30 text-xs text-[#fdf6e3] focus:border-[#bc944c] focus:outline-none"></textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary text-xs py-3 px-8 font-bold">
                Create & Publish Page
            </button>
        </div>
    </form>
</div>
