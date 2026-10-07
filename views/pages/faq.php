<div class="py-12 md:py-20">
    <div class="container-x max-w-3xl">
        <div class="text-center mb-12">
            <span class="eyebrow">Clear & Transparent</span>
            <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold mt-2">
                Frequently Asked Questions
            </h1>
            <p class="text-xs md:text-sm text-stone-600 mt-3">
                Everything you need to know about our South Indian ghee churning process, certifications, and delivery.
            </p>
        </div>

        <div class="space-y-4">
            <?php foreach ($faqs as $idx => $faq): ?>
                <div class="faq-item card-glow bg-white rounded-2xl overflow-hidden border border-[#e7dec8] shadow-sm">
                    <button class="faq-toggle w-full p-5 text-left flex items-center justify-between gap-4 text-sm md:text-base font-display text-[#07160d] hover:text-[#bc944c] transition-colors">
                        <span><?= e($faq['question']) ?></span>
                        <span class="faq-icon text-[#bc944c] font-bold text-xl leading-none"><?= $idx === 0 ? '×' : '+' ?></span>
                    </button>
                    <div class="faq-content p-5 pt-0 text-xs md:text-sm text-stone-600 leading-relaxed <?= $idx === 0 ? '' : 'hidden' ?>">
                        <?= nl2br(e($faq['answer'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
