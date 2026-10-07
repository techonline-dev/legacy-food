<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Mailer;

class ContactController extends Controller {
    public function index(): void {
        $this->view('pages.contact', [
            'meta_title' => 'Contact Legacy Food | Crafted in South India',
            'meta_description' => 'Reach out to Legacy Food for order inquiries, bulk corporate gifting, or WhatsApp support.',
            'phone' => '+91 81234 50509',
            'whatsapp' => '919845279936',
            'email' => 'Contact@legacyfood.in',
            'address' => '#286, 4th Cross, 8th Main, 4th Phase, Dollars Colony, Bangalore 560078'
        ]);
    }

    public function submit(): void {
        if (!$this->validateCsrf()) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => 'Security token expired. Please refresh the page.'], 419);
                return;
            }
            flash('error', 'Token expired.');
            $this->redirect('contact');
            return;
        }

        // Honeypot spam check
        if (!empty($this->request->input('website_check'))) {
            $this->redirect('contact');
            return;
        }

        $errors = $this->validate([
            'name' => 'required|min:2',
            'email' => 'required|email',
            'message' => 'required|min:10'
        ]);

        if (!empty($errors)) {
            $msg = reset($errors)[0];
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'error' => $msg], 422);
                return;
            }
            flash('error', $msg);
            $this->redirect('contact');
            return;
        }

        $name = sanitize($this->request->input('name'));
        $email = sanitize($this->request->input('email'));
        $phone = sanitize($this->request->input('phone'));
        $subject = sanitize($this->request->input('subject', 'General Inquiry'));
        $message = sanitize($this->request->input('message'));

        Database::insert('contact_messages', [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'status' => 'unread'
        ]);

        // Trigger notification email to store admin
        try {
            $adminEmail = config('mail.from.address', 'contact@legacyfood.in');
            $adminHtml = Mailer::renderEmail(
                "New Contact Message: " . $subject,
                "<p><strong>From:</strong> {$name} ({$email}, {$phone})</p><p><strong>Subject:</strong> {$subject}</p><p><strong>Message:</strong></p><p>" . nl2br($message) . "</p>"
            );
            Mailer::send($adminEmail, 'Legacy Admin', "Contact Inquiry from {$name}", $adminHtml);
        } catch (\Throwable $t) {
            // log error
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Thank you for reaching out! Our team will respond shortly.']);
            return;
        }

        flash('success', 'Thank you! Your message has been received. Our team will contact you shortly.');
        $this->redirect('contact');
    }

    public function subscribeNewsletter(): void {
        $email = strtolower(trim((string)$this->request->input('email')));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'error' => 'Please enter a valid email address.'], 422);
            return;
        }

        $exists = Database::fetch("SELECT id FROM `newsletter_subscribers` WHERE `email` = :e", ['e' => $email]);
        if (!$exists) {
            Database::insert('newsletter_subscribers', [
                'email' => $email,
                'status' => 'subscribed'
            ]);
        }

        $this->json(['success' => true, 'message' => 'Thank you for subscribing to Legacy Food updates & offers!']);
    }
}
