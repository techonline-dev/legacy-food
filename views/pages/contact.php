<div class="py-12 md:py-20">
    <div class="container-x max-w-5xl">
        <div class="text-center max-w-xl mx-auto mb-14">
            <span class="eyebrow">Connect With Legacy</span>
            <h1 class="font-display text-3xl md:text-5xl text-[#07160d] font-bold mt-2">
                We're Here to Help
            </h1>
            <p class="text-xs md:text-sm text-stone-600 mt-3">
                Questions about batch certifications, corporate orders, or delivery status? Reach out anytime.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Contact Info Cards -->
            <div class="space-y-4">
                <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm">
                    <span class="text-[10px] uppercase tracking-widest text-[#bc944c] font-bold block">Phone Support</span>
                    <h4 class="font-display text-base font-bold text-[#07160d] mt-1"><?= e($phone) ?></h4>
                    <p class="text-[11px] text-stone-500 mt-1">Mon - Sat: 9:00 AM - 7:00 PM IST</p>
                </div>

                <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm">
                    <span class="text-[10px] uppercase tracking-widest text-[#bc944c] font-bold block">Direct Email</span>
                    <h4 class="font-display text-base font-bold text-[#07160d] mt-1"><?= e($email) ?></h4>
                    <p class="text-[11px] text-stone-500 mt-1">Replies typically within 24 hours</p>
                </div>

                <div class="bg-white border border-[#e7dec8] p-6 rounded-2xl shadow-sm">
                    <span class="text-[10px] uppercase tracking-widest text-[#bc944c] font-bold block">Registered Kitchen & Office</span>
                    <p class="text-xs text-stone-700 leading-relaxed mt-1">
                        <?= e($address) ?>
                    </p>
                </div>

                <a href="https://wa.me/<?= e($whatsapp) ?>" target="_blank" class="btn-gold w-full text-center text-xs py-3.5 block font-bold shadow-md">
                    Chat on WhatsApp Directly
                </a>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 bg-white border border-[#e7dec8] p-8 md:p-10 rounded-3xl shadow-sm">
                <h3 class="font-display text-xl text-[#07160d] font-bold mb-6">Send Us a Message</h3>

                <form action="<?= url('contact/submit') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <!-- Honeypot -->
                    <input type="text" name="website_check" class="hidden">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Your Name *</label>
                            <input type="text" name="name" required class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                        </div>

                        <div>
                            <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Email Address *</label>
                            <input type="email" name="email" required class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                        </div>

                        <div>
                            <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Phone Number</label>
                            <input type="tel" name="phone" placeholder="Optional" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                        </div>

                        <div>
                            <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Subject</label>
                            <input type="text" name="subject" placeholder="e.g. Order query / Purity test" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-stone-700 mb-1.5 font-semibold">Your Message *</label>
                        <textarea name="message" rows="5" required placeholder="How can our dairy specialists assist you today?" class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-[#d6c7af] text-[#1c1917] focus:outline-none focus:border-[#bc944c]"></textarea>
                    </div>

                    <button type="submit" class="btn-gold w-full sm:w-auto px-8 py-3 text-xs uppercase tracking-wider font-bold shadow-md">
                        Transmit Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
