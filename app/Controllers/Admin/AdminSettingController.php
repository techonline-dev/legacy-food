<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;

class AdminSettingController extends Controller {
    public function index(): void {
        $settings = Setting::allGrouped();

        $this->view('admin.settings.index', [
            'meta_title' => 'Store & Business Settings | Legacy Food Admin',
            'settings' => $settings
        ], 'admin');
    }

    public function update(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/settings');
            return;
        }

        $posted = $this->request->all();
        unset($posted['_csrf_token']);

        // Checkbox defaults for settings that might be unticked
        $checkboxes = ['razorpay_enabled', 'cod_enabled', 'show_cgst_sgst'];
        foreach ($checkboxes as $cb) {
            if (!isset($posted[$cb])) {
                $posted[$cb] = '0';
            }
        }

        // Group mapping for setting keys
        $groupMap = [
            'gst_percentage' => 'tax',
            'tax_inclusive' => 'tax',
            'default_hsn_code' => 'tax',
            'show_cgst_sgst' => 'tax',
            'gst_state' => 'tax',
            'gst_number' => 'contact',
            'free_shipping_min' => 'shipping',
            'standard_shipping_charge' => 'shipping',
            'razorpay_enabled' => 'payment',
            'razorpay_key_id' => 'payment',
            'razorpay_key_secret' => 'payment',
            'cod_enabled' => 'payment',
            'cod_extra_charge' => 'payment',
            'site_name' => 'general',
            'site_tagline' => 'general',
            'logo_light' => 'branding',
            'currency_symbol' => 'general',
            'contact_email' => 'contact',
            'contact_phone' => 'contact',
            'whatsapp_number' => 'contact',
            'address' => 'contact',
            'meta_title_default' => 'seo',
            'meta_description_default' => 'seo',
            'meta_keywords_default' => 'seo',
            'og_image_default' => 'seo',
            'meta_robots_default' => 'seo',
            'google_analytics_id' => 'seo',
            'google_tag_manager_id' => 'seo',
            'canonical_url_base' => 'seo',
            'twitter_handle' => 'social',
            'instagram_url' => 'social',
            'facebook_url' => 'social',
        ];

        foreach ($posted as $key => $value) {
            $group = $groupMap[$key] ?? 'general';
            Setting::set($key, (string)$value, $group);
        }

        ActivityLog::log('settings_updated', 'Updated store configuration and settings');
        flash('success', 'Store settings updated successfully.');
        $this->redirect('admin/settings');
    }
}
