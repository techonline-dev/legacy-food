<div class="py-12 md:py-20">
    <div class="container-x max-w-4xl">
        <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold mb-8">
            <?= e($page['title'] ?? 'Policy') ?>
        </h1>

        <div class="card-glow bg-white p-8 md:p-12 rounded-3xl border border-[#e7dec8] shadow-sm max-w-none text-xs md:text-sm text-stone-700 leading-relaxed font-body">
            <?= $page['content'] ?? '' ?>
        </div>

        <div class="mt-8 text-xs text-stone-500 text-right">
            Last Updated: <?= date('d M Y', strtotime($page['updated_at'] ?? 'now')) ?>
        </div>
    </div>
</div>
