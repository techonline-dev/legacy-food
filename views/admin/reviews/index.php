<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#fdf6e3]">Product Reviews Moderation</h1>
            <p class="text-xs text-[#fdf6e3]/60 mt-1">Audit customer testimonials, approve verified reviews, and filter spam.</p>
        </div>
    </div>

    <!-- Reviews Table Card -->
    <div class="p-6 rounded-2xl bg-[#07160d] border border-[#bc944c]/20">
        <?php if (empty($reviews)): ?>
            <div class="py-16 text-center text-xs text-[#fdf6e3]/50">
                No customer reviews submitted yet.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#bc944c]/20 text-[#bc944c] uppercase tracking-wider text-[10px]">
                            <th class="pb-3 font-semibold">Product</th>
                            <th class="pb-3 font-semibold">Author</th>
                            <th class="pb-3 font-semibold">Rating</th>
                            <th class="pb-3 font-semibold">Review Snippet</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.05]">
                        <?php foreach ($reviews as $rev): ?>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-3.5 font-semibold text-[#fdf6e3]">
                                    <?= e($rev['product_name']) ?>
                                </td>
                                <td class="py-3.5">
                                    <div class="font-semibold text-[#fdf6e3]"><?= e($rev['customer_name']) ?></div>
                                    <div class="text-[10px] text-[#fdf6e3]/50"><?= e($rev['customer_email']) ?></div>
                                </td>
                                <td class="py-3.5">
                                    <div class="flex items-center text-amber-400">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span><?= $i <= (int)$rev['rating'] ? '★' : '☆' ?></span>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="py-3.5 max-w-xs text-[#fdf6e3]/80">
                                    <p class="truncate" title="<?= e($rev['review_text'] ?? '') ?>">
                                        <?= e($rev['review_text'] ?? '') ?>
                                    </p>
                                </td>
                                <td class="py-3.5">
                                    <?php if ($rev['status'] === 'approved'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-300">
                                            Approved (Live)
                                        </span>
                                    <?php elseif ($rev['status'] === 'rejected'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-500/20 text-red-300">
                                            Rejected
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/20 text-amber-300">
                                            Pending Review
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <?php if ($rev['status'] !== 'approved'): ?>
                                            <form action="<?= url('admin/reviews/status/' . $rev['id'] . '/approved') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="p-1.5 rounded-lg bg-emerald-500/20 text-emerald-300 hover:bg-emerald-500/30 text-[11px] font-semibold px-2 py-1 flex items-center gap-1">
                                                    <span>✓</span>
                                                    <span>Approve</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($rev['status'] !== 'rejected'): ?>
                                            <form action="<?= url('admin/reviews/status/' . $rev['id'] . '/rejected') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="p-1.5 rounded-lg bg-amber-500/20 text-amber-300 hover:bg-amber-500/30 text-[11px] font-semibold px-2 py-1">
                                                    Reject
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <form action="<?= url('admin/reviews/delete/' . $rev['id']) ?>" method="POST" onsubmit="return confirm('Permanently delete review?');" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="p-1.5 rounded-lg hover:bg-red-500/20 text-red-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
