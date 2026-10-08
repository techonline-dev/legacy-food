<?php
$gen = $settings['general'] ?? [];
$brand = $settings['branding'] ?? [];
$contact = $settings['contact'] ?? [];
$ship = $settings['shipping'] ?? [];
$tax = $settings['tax'] ?? [];
$pay = $settings['payment'] ?? [];
$seo = $settings['seo'] ?? [];
$social = $settings['social'] ?? [];
$header = $settings['header'] ?? [];
$footer = $settings['footer'] ?? [];

$gstNumber = $tax['gst_number'] ?? ($contact['gst_number'] ?? '29ABCDE1234F1Z5');
$gstPercentage = $tax['gst_percentage'] ?? '5.00';
$taxInclusive = $tax['tax_inclusive'] ?? '1';
$defaultHsn = $tax['default_hsn_code'] ?? '04059020';
$gstState = $tax['gst_state'] ?? '29 - Karnataka';

// Header settings variables
$headerAnnouncementEnabled = $header['header_announcement_enabled'] ?? '1';
$headerAnnouncementText = $header['header_announcement_text'] ?? 'Hand-Churned Farm Ghee · Lab Tested Purity · <strong class="text-white">FREE Shipping</strong> on Orders Above ₹999';
$headerLogoUrl = $header['header_logo_url'] ?? '';
$headerShowWhatsapp = $header['header_show_whatsapp'] ?? '1';
$headerWhatsappText = $header['header_whatsapp_text'] ?? 'Order on WhatsApp';

// Footer settings variables
$footerLogoUrl = $footer['footer_logo_url'] ?? '';
$footerAboutText = $footer['footer_about_text'] ?? 'Rooted in purity and craftsmanship. Hand-churned South Indian butter ghee and cold-pressed oils prepared the traditional way. 100% lab certified, zero adulteration.';
$footerCol2Title = $footer['footer_col2_title'] ?? 'Our Heritage';
$footerCol3Title = $footer['footer_col3_title'] ?? 'Company & Support';
$footerNewsletterEnabled = $footer['footer_newsletter_enabled'] ?? '1';
$footerNewsletterTitle = $footer['footer_newsletter_title'] ?? 'Stay Connected';
$footerNewsletterDesc = $footer['footer_newsletter_desc'] ?? 'Subscribe for seasonal harvest announcements, festive offers and culinary secrets.';
$footerContactPhone = $footer['footer_contact_phone'] ?? ($contact['contact_phone'] ?? '+91 81234 50509');
$footerContactEmail = $footer['footer_contact_email'] ?? ($contact['contact_email'] ?? 'Contact@legacyfood.in');
$footerCopyrightText = $footer['footer_copyright_text'] ?? '© {year} Legacy Food. All rights reserved. Crafted in South India.';
$footerBadgesText = $footer['footer_badges_text'] ?? '100% NABL Accredited Lab Verified • FSSAI Compliant';
?>

<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#07160d]">Store Configuration & Settings</h1>
            <p class="text-xs text-stone-500 mt-1">Configure business identity, header, footer, GST tax slabs, payment gateways, and shipping rates.</p>
        </div>
    </div>

    <form action="<?= url('admin/settings/update') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Settings Tabs Navigation -->
        <div class="flex items-center gap-2 overflow-x-auto border-b border-[#e7dec8] pb-2 text-xs font-semibold" id="settingsTabs">
            <button type="button" class="tab-btn active px-4 py-2.5 rounded-xl bg-[#bc944c] text-[#07160d] font-bold shadow-xs transition-colors" data-target="tab-tax">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                    Taxes & GST (Settings)
                </span>
            </button>
            <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#07160d] transition-colors" data-target="tab-header-footer">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    Header & Footer
                </span>
            </button>
            <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#07160d] transition-colors" data-target="tab-general">General & Contact</button>
            <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#07160d] transition-colors" data-target="tab-payment">Payments & Gateway</button>
            <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#07160d] transition-colors" data-target="tab-shipping">Shipping & Delivery</button>
            <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#07160d] transition-colors" data-target="tab-seo">SEO & Social Links</button>
            <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#07160d] transition-colors" data-target="tab-database">Database & Env</button>
        </div>

        <!-- TAB: Taxes & GST (First / Focused) -->
        <div class="tab-pane space-y-6" id="tab-tax">
            <!-- GST Master Setup Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-6">
                <div class="flex items-start justify-between border-b border-[#e7dec8] pb-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider mb-1.5">
                            Compliance & Taxation
                        </div>
                        <h2 class="font-display text-lg font-bold text-[#07160d]">Goods and Services Tax (GST) Configuration</h2>
                        <p class="text-xs text-stone-500 mt-0.5">Define your GSTIN, standard tax rate percentage, pricing calculation method, and HSN code.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- GSTIN -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">GST Identification Number (GSTIN)</label>
                        <input type="text" name="gst_number" id="gst_number_input" value="<?= e($gstNumber) ?>"
                               placeholder="e.g. 29ABCDE1234F1Z5"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm font-mono uppercase text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        <p class="text-[11px] text-stone-500 mt-1">15-digit GSTIN printed on tax invoices, delivery slips, and customer receipts.</p>
                    </div>

                    <!-- Registered State -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">GST Registered State / Jurisdiction</label>
                        <input type="text" name="gst_state" value="<?= e($gstState) ?>"
                               placeholder="e.g. 29 - Karnataka"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        <p class="text-[11px] text-stone-500 mt-1">Used to automatically split intra-state sales into CGST + SGST or inter-state IGST.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-[#e7dec8]">
                    <!-- GST Rate Percentage -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider">Default GST Rate Percentage (%)</label>
                            <span class="text-[11px] font-bold text-[#bc944c]" id="gst_current_pill"><?= e($gstPercentage) ?>%</span>
                        </div>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" max="100" name="gst_percentage" id="gst_percentage_input" value="<?= e($gstPercentage) ?>"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-base font-bold text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                            <span class="absolute right-4 top-3 text-xs font-bold text-stone-400">%</span>
                        </div>

                        <!-- Quick Slab Pills -->
                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                            <span class="text-[10px] text-stone-500 uppercase tracking-wider font-semibold mr-1">Quick Slabs:</span>
                            <button type="button" onclick="setGstRate(0.00)" class="px-2.5 py-1 text-[11px] rounded-lg border border-stone-200 bg-stone-50 text-stone-700 hover:bg-stone-100 hover:border-stone-300 font-medium transition-colors">0% (Exempt)</button>
                            <button type="button" onclick="setGstRate(5.00)" class="px-2.5 py-1 text-[11px] rounded-lg border border-[#bc944c]/40 bg-[#bc944c]/10 text-[#07160d] hover:bg-[#bc944c]/20 font-bold transition-colors">5% (Ghee & Oils)</button>
                            <button type="button" onclick="setGstRate(12.00)" class="px-2.5 py-1 text-[11px] rounded-lg border border-stone-200 bg-stone-50 text-stone-700 hover:bg-stone-100 hover:border-stone-300 font-medium transition-colors">12% (Food Items)</button>
                            <button type="button" onclick="setGstRate(18.00)" class="px-2.5 py-1 text-[11px] rounded-lg border border-stone-200 bg-stone-50 text-stone-700 hover:bg-stone-100 hover:border-stone-300 font-medium transition-colors">18% (Standard)</button>
                        </div>
                        <p class="text-[11px] text-stone-500 mt-2">Indian Food products (Pure Desi Ghee, Butter & Cold Pressed Oils) generally attract 5% or 12% GST.</p>
                    </div>

                    <!-- Tax Calculation Mode -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Tax Calculation & Pricing Mode *</label>
                        <select name="tax_inclusive" id="tax_inclusive_select" class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                            <option value="1" <?= $taxInclusive === '1' ? 'selected' : '' ?>>Prices are Inclusive of GST (Recommended for Direct-to-Consumer / Retail)</option>
                            <option value="0" <?= $taxInclusive === '0' ? 'selected' : '' ?>>Prices are Exclusive of GST (Tax is added extra at checkout)</option>
                        </select>
                        <p class="text-[11px] text-stone-500 mt-2" id="tax_mode_helper">
                            <?= $taxInclusive === '1' ? '✓ Recommended: Customer pays listed product price. The GST amount is calculated from the price and shown on invoices.' : 'Tax will be added on top of the cart subtotal at checkout.' ?>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-[#e7dec8]">
                    <!-- Default HSN Code -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Default HSN / SAC Code</label>
                        <input type="text" name="default_hsn_code" value="<?= e($defaultHsn) ?>"
                               placeholder="e.g. 04059020"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        <p class="text-[11px] text-stone-500 mt-1">HSN <strong>04059020</strong> applies to Pure Cow Ghee and Butter Oil. (You can also set individual HSN codes per product).</p>
                    </div>

                    <!-- Invoice Breakdown Toggle -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Invoice Breakdown Option</label>
                        <div class="p-3.5 rounded-xl bg-[#faf8f5] border border-[#e7dec8] flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold text-[#07160d]">Show CGST + SGST Split</div>
                                <div class="text-[11px] text-stone-500">Split GST into equal Central and State GST portions on invoice</div>
                            </div>
                            <input type="checkbox" name="show_cgst_sgst" value="1" <?= ($tax['show_cgst_sgst'] ?? '1') == '1' ? 'checked' : '' ?> class="h-4 w-4 rounded border-stone-300 text-[#bc944c] focus:ring-[#bc944c]">
                        </div>
                    </div>
                </div>

                <!-- Live Tax Simulator Card -->
                <div class="p-4 rounded-xl bg-[#faf8f5] border border-[#d6c7af] space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-[#07160d] flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#bc944c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Live Tax Calculation Simulator (for ₹1,000.00 Order)
                        </span>
                        <span class="text-[11px] text-stone-500" id="sim_mode_badge">Mode: Tax Inclusive</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1 text-xs">
                        <div class="p-2.5 rounded-lg bg-white border border-[#e7dec8]">
                            <span class="text-[10px] uppercase text-stone-500 font-semibold block">Product Price</span>
                            <span class="font-bold text-sm text-[#07160d]" id="sim_price">₹1,000.00</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-white border border-[#e7dec8]">
                            <span class="text-[10px] uppercase text-stone-500 font-semibold block">Base (Excl. Tax)</span>
                            <span class="font-bold text-sm text-[#07160d]" id="sim_base">₹952.38</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-white border border-[#e7dec8]">
                            <span class="text-[10px] uppercase text-stone-500 font-semibold block" id="sim_tax_label">GST Total (5%)</span>
                            <span class="font-bold text-sm text-[#bc944c]" id="sim_tax">₹47.62</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-white border border-[#e7dec8]">
                            <span class="text-[10px] uppercase text-stone-500 font-semibold block">Customer Pays</span>
                            <span class="font-bold text-sm text-emerald-700" id="sim_total">₹1,000.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: General & Contact -->
        <div class="tab-pane hidden space-y-6" id="tab-general">
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <h2 class="font-display text-base font-bold text-[#07160d]">Store Identity & Branding</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Store / Brand Name</label>
                        <input type="text" name="site_name" value="<?= e($gen['site_name'] ?? 'Legacy Food') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Tagline</label>
                        <input type="text" name="site_tagline" value="<?= e($gen['site_tagline'] ?? '') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Light Logo Path / URL</label>
                        <input type="text" name="logo_light" value="<?= e($brand['logo_light'] ?? '/assets/images/branding/header-logo-light.svg') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="<?= e($gen['currency_symbol'] ?? '₹') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <h2 class="font-display text-base font-bold text-[#07160d]">Official Contact & Customer Support</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Customer Support Email</label>
                        <input type="email" name="contact_email" value="<?= e($contact['contact_email'] ?? 'Contact@legacyfood.in') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Support Phone Number</label>
                        <input type="text" name="contact_phone" value="<?= e($contact['contact_phone'] ?? '+91 81234 50509') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">WhatsApp Orders Number (Country code + number)</label>
                        <input type="text" name="whatsapp_number" value="<?= e($contact['whatsapp_number'] ?? '919845279936') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] font-mono focus:border-[#bc944c] focus:outline-none">
                        <p class="text-[11px] text-stone-500 mt-1">Used for the floating WhatsApp button and customer inquiries.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Company GSTIN (Synced)</label>
                        <input type="text" value="<?= e($gstNumber) ?>" readonly
                               class="w-full py-2.5 px-3.5 rounded-xl bg-stone-100 border border-[#d6c7af] text-sm text-stone-600 font-mono uppercase cursor-not-allowed">
                        <p class="text-[11px] text-stone-500 mt-1">Editable in the Taxes & GST tab.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Registered Office Address</label>
                    <textarea name="address" rows="2"
                              class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($contact['address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- TAB: Payments & Gateways -->
        <div class="tab-pane hidden space-y-6" id="tab-payment">
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-base font-bold text-[#07160d]">Razorpay Payment Gateway</h2>
                        <p class="text-xs text-stone-500">Accept UPI (Google Pay, PhonePe, Paytm), Credit/Debit Cards, & Net Banking.</p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="razorpay_enabled" value="1" <?= !empty($pay['razorpay_enabled']) ? 'checked' : '' ?> class="rounded border-stone-300 text-[#bc944c] focus:ring-[#bc944c]">
                        <span class="text-xs font-semibold text-emerald-700">Enable Razorpay</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Razorpay Key ID</label>
                        <input type="text" name="razorpay_key_id" value="<?= e($pay['razorpay_key_id'] ?? '') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Razorpay Key Secret</label>
                        <input type="password" name="razorpay_key_secret" value="<?= e($pay['razorpay_key_secret'] ?? '') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-base font-bold text-[#07160d]">Cash on Delivery (COD)</h2>
                        <p class="text-xs text-stone-500">Allow customers to pay upon package receipt.</p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="cod_enabled" value="1" <?= !empty($pay['cod_enabled']) ? 'checked' : '' ?> class="rounded border-stone-300 text-[#bc944c] focus:ring-[#bc944c]">
                        <span class="text-xs font-semibold text-emerald-700">Enable COD</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">COD Handling Surcharge (₹)</label>
                    <input type="number" step="0.01" name="cod_extra_charge" value="<?= e($pay['cod_extra_charge'] ?? '0.00') ?>"
                           class="w-48 py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>
            </div>
        </div>

        <!-- TAB: Shipping & Delivery -->
        <div class="tab-pane hidden space-y-6" id="tab-shipping">
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <h2 class="font-display text-base font-bold text-[#07160d]">Delivery Fee Matrix</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Free Shipping Order Threshold (₹)</label>
                        <input type="number" step="0.01" name="free_shipping_min" value="<?= e($ship['free_shipping_min'] ?? '999.00') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <p class="text-[11px] text-stone-500 mt-1">Orders at or above this amount automatically receive ₹0 shipping.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Standard Shipping Fee (₹)</label>
                        <input type="number" step="0.01" name="standard_shipping_charge" value="<?= e($ship['standard_shipping_charge'] ?? '60.00') ?>"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <p class="text-[11px] text-stone-500 mt-1">Applied when cart total is below the free shipping threshold.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: SEO & Social -->
        <div class="tab-pane hidden space-y-6" id="tab-seo">
            <!-- Global Meta Tags -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-[#e7dec8] pb-3">
                    <div>
                        <h2 class="font-display text-base font-bold text-[#07160d]">Default Search Engine Meta Tags</h2>
                        <p class="text-xs text-stone-500">Global fallback SEO meta tags used across your storefront when specific page tags are not set.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Default Title Tag</label>
                    <input type="text" name="meta_title_default" value="<?= e($seo['meta_title_default'] ?? 'Legacy Food | Authentic South Indian Traditional Food & Cold Pressed Oils') ?>"
                           class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Default Meta Description</label>
                    <textarea name="meta_description_default" rows="2"
                              class="w-full py-2 px-3 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none"><?= e($seo['meta_description_default'] ?? 'Discover pure, hand-churned A2 desi cow ghee, cold-pressed wood oils, and heritage staples prepared according to traditional culinary wisdom.') ?></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Default Meta Keywords</label>
                        <input type="text" name="meta_keywords_default" value="<?= e($seo['meta_keywords_default'] ?? 'a2 ghee, cold pressed oils, wood pressed oil, traditional ghee, legacy food, organic ghee india') ?>"
                               placeholder="ghee, wood pressed oils, heritage food"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <p class="text-[11px] text-stone-500 mt-1">Comma-separated target keywords.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Search Engine Robots</label>
                        <select name="meta_robots_default" class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                            <option value="index, follow" <?= ($seo['meta_robots_default'] ?? 'index, follow') === 'index, follow' ? 'selected' : '' ?>>index, follow (Allow full indexing - Recommended)</option>
                            <option value="noindex, follow" <?= ($seo['meta_robots_default'] ?? '') === 'noindex, follow' ? 'selected' : '' ?>>noindex, follow (Hide from search, follow links)</option>
                            <option value="noindex, nofollow" <?= ($seo['meta_robots_default'] ?? '') === 'noindex, nofollow' ? 'selected' : '' ?>>noindex, nofollow (Block all indexing)</option>
                        </select>
                        <p class="text-[11px] text-stone-500 mt-1">Instructs Google and search bots how to crawl the store.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Social Share (OG) Image URL</label>
                        <input type="url" name="og_image_default" value="<?= e($seo['og_image_default'] ?? '') ?>"
                               placeholder="https://example.com/assets/images/og-share.jpg"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <p class="text-[11px] text-stone-500 mt-1">Default 1200x630 banner for WhatsApp, Facebook & Twitter cards.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Canonical Base URL (Optional)</label>
                        <input type="url" name="canonical_url_base" value="<?= e($seo['canonical_url_base'] ?? '') ?>"
                               placeholder="https://legacyfood.in"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <p class="text-[11px] text-stone-500 mt-1">Leave empty to use automatic host domain.</p>
                    </div>
                </div>
            </div>

            <!-- Web Analytics & Tracking Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <h2 class="font-display text-base font-bold text-[#07160d]">Analytics & Conversion Tracking</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Google Analytics 4 (GA4) ID</label>
                        <input type="text" name="google_analytics_id" value="<?= e($seo['google_analytics_id'] ?? '') ?>"
                               placeholder="G-XXXXXXXXXX"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <p class="text-[11px] text-stone-500 mt-1">Automatically injects the Google Analytics gtag.js snippet.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Google Tag Manager (GTM) ID</label>
                        <input type="text" name="google_tag_manager_id" value="<?= e($seo['google_tag_manager_id'] ?? '') ?>"
                               placeholder="GTM-XXXXXXX"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                        <p class="text-[11px] text-stone-500 mt-1">Optional container ID for advanced tag management.</p>
                    </div>
                </div>
            </div>

            <!-- XML Sitemap Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-display text-base font-bold text-[#07160d] flex items-center gap-2">
                        <span>XML Sitemap</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Live Auto-Generated</span>
                    </h2>
                    <p class="text-xs text-stone-500 mt-1">Includes all published products, active categories, and CMS editorial pages automatically.</p>
                    <div class="text-xs font-mono text-stone-600 mt-2 bg-stone-50 py-1.5 px-3 rounded-lg border border-[#e7dec8] inline-block">
                        <?= url('sitemap.xml') ?>
                    </div>
                </div>
                <div>
                    <a href="<?= url('sitemap.xml') ?>" target="_blank" class="btn-ghost text-xs py-2.5 px-4 border border-[#d6c7af] text-[#07160d] hover:bg-stone-50 inline-flex items-center gap-1.5">
                        <span>View Sitemap XML</span> ↗
                    </a>
                </div>
            </div>

            <!-- Social Media Profiles -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <h2 class="font-display text-base font-bold text-[#07160d]">Social Media & Brand Identity</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Instagram URL</label>
                        <input type="url" name="instagram_url" value="<?= e($social['instagram_url'] ?? '') ?>"
                               placeholder="https://instagram.com/legacyfood"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Facebook URL</label>
                        <input type="url" name="facebook_url" value="<?= e($social['facebook_url'] ?? '') ?>"
                               placeholder="https://facebook.com/legacyfood"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Twitter / X Handle</label>
                        <input type="text" name="twitter_handle" value="<?= e($social['twitter_handle'] ?? '') ?>"
                               placeholder="@legacyfood"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: Header & Footer -->
        <div class="tab-pane hidden space-y-6" id="tab-header-footer">
            <!-- Header Configuration Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-6">
                <div class="border-b border-[#e7dec8] pb-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider mb-1.5">
                        Header Controls
                    </div>
                    <h2 class="font-display text-lg font-bold text-[#07160d]">Header & Announcement Bar</h2>
                    <p class="text-xs text-stone-500 mt-0.5">Customize top announcement notification bar, header logo, and WhatsApp quick action button.</p>
                </div>

                <!-- Quick Link to Drag-Drop Menu Builder -->
                <div class="p-3.5 rounded-xl bg-amber-50/70 border border-[#bc944c]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-[#bc944c] text-[#07160d] flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                            ☰
                        </span>
                        <div>
                            <h4 class="font-bold text-xs text-[#07160d]">Store Navigation Menu & Dropdown Submenus</h4>
                            <p class="text-[11px] text-stone-600">Reorder links, create nested submenus, and manage custom URLs.</p>
                        </div>
                    </div>
                    <a href="<?= url('admin/menu') ?>" class="btn-primary text-xs py-1.5 px-3.5 font-bold whitespace-nowrap inline-flex items-center gap-1 shadow-xs self-start sm:self-auto">
                        <span>Open Menu Builder</span>
                        <span>↗</span>
                    </a>
                </div>

                <div class="space-y-5">
                    <!-- Announcement Bar Toggle -->
                    <div class="flex items-center justify-between p-4 rounded-xl bg-stone-50 border border-[#e7dec8]">
                        <div>
                            <label for="header_announcement_enabled" class="text-xs font-bold text-[#07160d] block cursor-pointer">Enable Top Announcement Bar</label>
                            <p class="text-[11px] text-stone-500 mt-0.5">Show or hide the promo banner strip displayed above the navigation header.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="header_announcement_enabled" name="header_announcement_enabled" value="1" <?= ($headerAnnouncementEnabled == '1' || $headerAnnouncementEnabled === true) ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#bc944c]"></div>
                        </label>
                    </div>

                    <!-- Announcement Bar Text -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Announcement Bar Content (Supports HTML)</label>
                        <input type="text" name="header_announcement_text" value="<?= e($headerAnnouncementText) ?>"
                               placeholder="Hand-Churned Farm Ghee · Lab Tested Purity · <strong class=&quot;text-white&quot;>FREE Shipping</strong> on Orders Above ₹999"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        <p class="text-[11px] text-stone-500 mt-1">HTML tags like <code>&lt;strong class="text-white"&gt;</code> and <code>&lt;span&gt;</code> are supported for highlighted text.</p>
                    </div>

                    <!-- Header Logo URL -->
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Header Brand Logo URL</label>
                        <input type="text" name="header_logo_url" value="<?= e($headerLogoUrl) ?>"
                               placeholder="e.g. /assets/images/branding/header-logo-dark.svg or https://example.com/logo.png"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        <p class="text-[11px] text-stone-500 mt-1">Leave empty to use the default dark brand logo (<code>assets/images/branding/header-logo-dark.svg</code>).</p>
                    </div>

                    <!-- WhatsApp Quick Order Button in Header -->
                    <div class="pt-4 border-t border-[#e7dec8] space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-stone-50 border border-[#e7dec8]">
                            <div>
                                <label for="header_show_whatsapp" class="text-xs font-bold text-[#07160d] block cursor-pointer">Show WhatsApp Order Button in Header</label>
                                <p class="text-[11px] text-stone-500 mt-0.5">Displays a quick-order button in desktop navigation and mobile drawer.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="header_show_whatsapp" name="header_show_whatsapp" value="1" <?= ($headerShowWhatsapp == '1' || $headerShowWhatsapp === true) ? 'checked' : '' ?> class="sr-only peer">
                                <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#bc944c]"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">WhatsApp Button Text</label>
                                <input type="text" name="header_whatsapp_text" value="<?= e($headerWhatsappText) ?>"
                                       placeholder="Order on WhatsApp"
                                       class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">WhatsApp Number (with Country Code)</label>
                                <input type="text" name="whatsapp_number" value="<?= e($contact['whatsapp_number'] ?? '919845279936') ?>"
                                       placeholder="919845279936"
                                       class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm font-mono text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                                <p class="text-[11px] text-stone-500 mt-1">Country code without plus sign (e.g., 919845279936 for India).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Branding & Bio Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-6">
                <div class="border-b border-[#e7dec8] pb-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider mb-1.5">
                        Footer Branding
                    </div>
                    <h2 class="font-display text-lg font-bold text-[#07160d]">Footer Identity & About Text</h2>
                    <p class="text-xs text-stone-500 mt-0.5">Customize the brand logo, story snippet, and column titles shown in the dark footer.</p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Footer Brand Logo URL (Light / Gold Variant)</label>
                        <input type="text" name="footer_logo_url" value="<?= e($footerLogoUrl) ?>"
                               placeholder="e.g. /assets/images/branding/header-logo-light.svg"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        <p class="text-[11px] text-stone-500 mt-1">Leave empty to use the light logo (<code>assets/images/branding/header-logo-light.svg</code>) suitable for the dark background.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Footer About / Bio Paragraph</label>
                        <textarea name="footer_about_text" rows="3"
                                  class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]"><?= e($footerAboutText) ?></textarea>
                        <p class="text-[11px] text-stone-500 mt-1">Short bio shown directly below the logo in the first column of the footer.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Column 2 Heading</label>
                            <input type="text" name="footer_col2_title" value="<?= e($footerCol2Title) ?>"
                                   placeholder="Our Heritage"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                            <p class="text-[11px] text-stone-500 mt-1">Default: "Our Heritage" (Products navigation list).</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Column 3 Heading</label>
                            <input type="text" name="footer_col3_title" value="<?= e($footerCol3Title) ?>"
                                   placeholder="Company & Support"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                            <p class="text-[11px] text-stone-500 mt-1">Default: "Company & Support" (Information & policy links).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Newsletter & Contact Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-6">
                <div class="border-b border-[#e7dec8] pb-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider mb-1.5">
                        Stay Connected & Support
                    </div>
                    <h2 class="font-display text-lg font-bold text-[#07160d]">Footer Newsletter & Direct Contact</h2>
                    <p class="text-xs text-stone-500 mt-0.5">Configure the email subscription block and support phone / email displayed in the footer.</p>
                </div>

                <div class="space-y-5">
                    <!-- Newsletter Toggle -->
                    <div class="flex items-center justify-between p-4 rounded-xl bg-stone-50 border border-[#e7dec8]">
                        <div>
                            <label for="footer_newsletter_enabled" class="text-xs font-bold text-[#07160d] block cursor-pointer">Enable Newsletter Subscription Form</label>
                            <p class="text-[11px] text-stone-500 mt-0.5">Show or hide the email subscription form in column 4.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="footer_newsletter_enabled" name="footer_newsletter_enabled" value="1" <?= ($footerNewsletterEnabled == '1' || $footerNewsletterEnabled === true) ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#bc944c]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Newsletter Title</label>
                            <input type="text" name="footer_newsletter_title" value="<?= e($footerNewsletterTitle) ?>"
                                   placeholder="Stay Connected"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Newsletter Description</label>
                            <input type="text" name="footer_newsletter_desc" value="<?= e($footerNewsletterDesc) ?>"
                                   placeholder="Subscribe for seasonal harvest announcements..."
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Footer Support Phone</label>
                            <input type="text" name="footer_contact_phone" value="<?= e($footerContactPhone) ?>"
                                   placeholder="+91 81234 50509"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Footer Support Email</label>
                            <input type="email" name="footer_contact_email" value="<?= e($footerContactEmail) ?>"
                                   placeholder="Contact@legacyfood.in"
                                   class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Social Links Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <div class="border-b border-[#e7dec8] pb-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider mb-1.5">
                        Social Handles
                    </div>
                    <h2 class="font-display text-lg font-bold text-[#07160d]">Social Media Channels (Shown in Footer)</h2>
                    <p class="text-xs text-stone-500 mt-0.5">Icons will be rendered for channels with a populated URL.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Instagram Profile URL</label>
                        <input type="url" name="instagram_url" value="<?= e($social['instagram_url'] ?? 'https://www.instagram.com/legacy.ghee?utm_source=qr') ?>"
                               placeholder="https://instagram.com/legacy.ghee"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Facebook Page URL</label>
                        <input type="url" name="facebook_url" value="<?= e($social['facebook_url'] ?? 'https://www.facebook.com/share/1E2eLKSKph/?mibextid=wwXIfr') ?>"
                               placeholder="https://facebook.com/..."
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">YouTube Channel URL</label>
                        <input type="url" name="youtube_url" value="<?= e($social['youtube_url'] ?? '') ?>"
                               placeholder="https://youtube.com/@legacyfood"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-xs text-[#1c1917] focus:border-[#bc944c] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Footer Bottom Copyright & Badges Card -->
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-5">
                <div class="border-b border-[#e7dec8] pb-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider mb-1.5">
                        Bottom Bar
                    </div>
                    <h2 class="font-display text-lg font-bold text-[#07160d]">Copyright Notice & Trust Badges</h2>
                    <p class="text-xs text-stone-500 mt-0.5">Customize the bottom-most legal strip and accreditation badges.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Copyright Statement</label>
                        <input type="text" name="footer_copyright_text" value="<?= e($footerCopyrightText) ?>"
                               placeholder="© {year} Legacy Food. All rights reserved. Crafted in South India."
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        <p class="text-[11px] text-stone-500 mt-1">Use <code>{year}</code> to automatically insert current calendar year.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#07160d] uppercase tracking-wider mb-1.5">Trust & Accreditation Badges</label>
                        <input type="text" name="footer_badges_text" value="<?= e($footerBadgesText) ?>"
                               placeholder="100% NABL Accredited Lab Verified • FSSAI Compliant"
                               class="w-full py-2.5 px-3.5 rounded-xl bg-white border border-[#d6c7af] text-sm text-[#1c1917] focus:border-[#bc944c] focus:outline-none focus:ring-1 focus:ring-[#bc944c]">
                        <p class="text-[11px] text-stone-500 mt-1">Displayed in the bottom footer row alongside FAQ and Shop links.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: Database & Environment -->
        <div class="tab-pane hidden space-y-6" id="tab-database">
            <div class="p-6 rounded-2xl bg-white border border-[#e7dec8] shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#e7dec8] pb-4">
                    <div>
                        <h2 class="font-display text-base font-bold text-[#07160d]">Database Environment & Configuration</h2>
                        <p class="text-xs text-stone-500">Live connection monitor and seamless switching between local development (Laragon) and production databases.</p>
                    </div>
                    <?php 
                    $target = config('database.target', 'local');
                    $isLocal = config('database.is_local', false);
                    ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shrink-0 <?= $target === 'local' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' ?>">
                        <span class="w-2 h-2 rounded-full <?= $target === 'local' ? 'bg-amber-500' : 'bg-emerald-500' ?>"></span>
                        Active: <?= strtoupper($target) ?> DATABASE
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-stone-50 border border-[#e7dec8]">
                        <span class="text-[10px] text-stone-500 uppercase tracking-wider font-bold block mb-1">Host & Port</span>
                        <span class="font-mono font-bold text-sm text-[#07160d]"><?= e(config('database.host')) ?>:<?= e(config('database.port')) ?></span>
                    </div>
                    <div class="p-4 rounded-xl bg-stone-50 border border-[#e7dec8]">
                        <span class="text-[10px] text-stone-500 uppercase tracking-wider font-bold block mb-1">Database Name</span>
                        <span class="font-mono font-bold text-sm text-[#bc944c]"><?= e(config('database.database')) ?></span>
                    </div>
                    <div class="p-4 rounded-xl bg-stone-50 border border-[#e7dec8]">
                        <span class="text-[10px] text-stone-500 uppercase tracking-wider font-bold block mb-1">Database User</span>
                        <span class="font-mono font-bold text-sm text-[#07160d]"><?= e(config('database.username')) ?></span>
                    </div>
                </div>
            </div>

            <!-- Profile Overview Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Local Config Card -->
                <?php $local = config('database.connections.local', []); ?>
                <div class="p-6 rounded-2xl bg-white border <?= $target === 'local' ? 'border-[#bc944c] ring-1 ring-[#bc944c]/30' : 'border-[#e7dec8]' ?> shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-display text-sm font-bold text-[#07160d]">Local Profile (Laragon / XAMPP)</h3>
                        <?php if ($target === 'local'): ?>
                            <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full">ACTIVE</span>
                        <?php endif; ?>
                    </div>
                    <div class="space-y-1.5 text-xs text-stone-600 font-mono">
                        <div>Host: <strong><?= e($local['host'] ?? '127.0.0.1') ?>:<?= e($local['port'] ?? 3306) ?></strong></div>
                        <div>Database: <strong class="text-[#07160d]"><?= e($local['database'] ?? 'legacyfood') ?></strong></div>
                        <div>User: <strong><?= e($local['username'] ?? 'root') ?></strong></div>
                        <div>Password: <em><?= empty($local['password']) ? '(none / empty)' : '••••••••' ?></em></div>
                    </div>
                    <p class="text-[11px] text-stone-500 pt-2 border-t border-stone-100">
                        Automatically connects when browsing from <code>localhost</code> or <code>.test</code> domains. If database doesn't exist, it is auto-created with initial schema.
                    </p>
                </div>

                <!-- Production Config Card -->
                <?php $prod = config('database.connections.production', []); ?>
                <div class="p-6 rounded-2xl bg-white border <?= $target === 'production' ? 'border-[#bc944c] ring-1 ring-[#bc944c]/30' : 'border-[#e7dec8]' ?> shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-display text-sm font-bold text-[#07160d]">Production Profile (Live Server)</h3>
                        <?php if ($target === 'production'): ?>
                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full">ACTIVE</span>
                        <?php endif; ?>
                    </div>
                    <div class="space-y-1.5 text-xs text-stone-600 font-mono">
                        <div>Host: <strong><?= e($prod['host'] ?? 'localhost') ?>:<?= e($prod['port'] ?? 3306) ?></strong></div>
                        <div>Database: <strong class="text-[#07160d]"><?= e($prod['database'] ?? 'u291611210_legacyfood') ?></strong></div>
                        <div>User: <strong><?= e($prod['username'] ?? 'u291611210_legacyfood') ?></strong></div>
                        <div>Password: <em>••••••••</em></div>
                    </div>
                    <p class="text-[11px] text-stone-500 pt-2 border-t border-stone-100">
                        Kept ready for cloud deployment. When deployed to your live server, production connects automatically.
                    </p>
                </div>
            </div>

            <!-- Switching Instructions Card -->
            <div class="p-6 rounded-2xl bg-[#faf8f5] border border-[#d6c7af] text-xs space-y-2.5">
                <h3 class="font-bold text-[#07160d]">How to Switch Database Target:</h3>
                <p class="text-stone-600">You can control which database is active in your <code>.env</code> file via the <code>DB_TARGET</code> setting:</p>
                <div class="p-3.5 rounded-xl bg-white border border-[#e7dec8] font-mono text-[11px] space-y-1.5">
                    <div><strong class="text-[#bc944c]">DB_TARGET=auto</strong> <span class="text-stone-500">// Auto-detects: uses Local on localhost / .test, uses Prod on live server (Recommended)</span></div>
                    <div><strong class="text-[#bc944c]">DB_TARGET=local</strong> <span class="text-stone-500">// Always forces Local MySQL database (Laragon)</span></div>
                    <div><strong class="text-[#bc944c]">DB_TARGET=production</strong> <span class="text-stone-500">// Always forces Production MySQL database (Hostinger)</span></div>
                </div>
            </div>
        </div>

        <!-- Sticky Save Button -->
        <div class="flex items-center justify-end pt-4 border-t border-[#e7dec8]">
            <button type="submit" class="btn-primary text-xs py-3 px-8 font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save All Settings</span>
            </button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('#settingsTabs .tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Toggle buttons
        document.querySelectorAll('#settingsTabs .tab-btn').forEach(b => {
            b.classList.remove('bg-[#bc944c]', 'text-[#07160d]', 'active', 'shadow-xs');
            b.classList.add('text-stone-600', 'hover:bg-stone-100');
        });
        this.classList.add('bg-[#bc944c]', 'text-[#07160d]', 'active', 'shadow-xs');
        this.classList.remove('text-stone-600', 'hover:bg-stone-100');

        // Toggle panes
        const targetId = this.getAttribute('data-target');
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
        document.getElementById(targetId)?.classList.remove('hidden');
    });
});

// Quick set GST rate helper
function setGstRate(rate) {
    const input = document.getElementById('gst_percentage_input');
    if (input) {
        input.value = Number(rate).toFixed(2);
        updateSim();
    }
}

// Interactive Tax Simulator
function updateSim() {
    const rateInput = document.getElementById('gst_percentage_input');
    const modeSelect = document.getElementById('tax_inclusive_select');
    const pill = document.getElementById('gst_current_pill');
    const helper = document.getElementById('tax_mode_helper');
    
    const rate = parseFloat(rateInput ? rateInput.value : 5) || 0;
    const isInclusive = modeSelect ? (modeSelect.value === '1') : true;

    if (pill) {
        pill.textContent = rate + '%';
    }

    if (helper) {
        helper.textContent = isInclusive
            ? '✓ Recommended: Customer pays listed product price. The GST amount (' + rate + '%) is extracted from the price and displayed on tax invoices.'
            : 'Tax (' + rate + '%) will be added on top of the cart subtotal at checkout.';
    }

    const sampleAmount = 1000.00;
    let base = 0;
    let tax = 0;
    let total = 0;

    if (isInclusive) {
        // Tax = total - (total / (1 + rate/100))
        tax = sampleAmount - (sampleAmount / (1 + (rate / 100)));
        base = sampleAmount - tax;
        total = sampleAmount;
        document.getElementById('sim_mode_badge').textContent = 'Mode: Tax Inclusive (MRP includes GST)';
    } else {
        // Tax = total * rate/100
        base = sampleAmount;
        tax = sampleAmount * (rate / 100);
        total = sampleAmount + tax;
        document.getElementById('sim_mode_badge').textContent = 'Mode: Tax Exclusive (GST added at checkout)';
    }

    document.getElementById('sim_price').textContent = '₹' + sampleAmount.toFixed(2);
    document.getElementById('sim_base').textContent = '₹' + base.toFixed(2);
    document.getElementById('sim_tax_label').textContent = 'GST Total (' + rate + '%)';
    document.getElementById('sim_tax').textContent = '₹' + tax.toFixed(2);
    document.getElementById('sim_total').textContent = '₹' + total.toFixed(2);
}

document.getElementById('gst_percentage_input')?.addEventListener('input', updateSim);
document.getElementById('tax_inclusive_select')?.addEventListener('change', updateSim);
updateSim();
</script>
