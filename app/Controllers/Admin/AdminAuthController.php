<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;

class AdminAuthController extends Controller {
    public function showLogin(): void {
        if (Auth::adminCheck()) {
            $this->redirect('admin');
            return;
        }

        $this->view('admin.auth.login', [
            'meta_title' => 'Admin Authentication | Legacy Food Portal'
        ], 'main');
    }

    public function login(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Security token expired. Please try again.');
            $this->redirect('admin/login');
            return;
        }

        $email = strtolower(trim((string)$this->request->input('email')));
        $password = (string)$this->request->input('password');

        $rateKey = 'admin_login:' . client_ip() . ':' . $email;
        if (!\App\Core\RateLimiter::attempt($rateKey, 5, 900)) {
            flash('error', 'Too many admin login attempts. Please wait 15 minutes.');
            $this->redirect('admin/login');
            return;
        }

        if (Auth::adminAttempt($email, $password)) {
            \App\Core\RateLimiter::clear($rateKey);
            flash('success', 'Welcome to Legacy Food Admin Portal.');
            $this->redirect('admin');
            return;
        }

        flash('error', 'Invalid admin email or password.');
        $this->redirect('admin/login');
    }

    public function logout(): void {
        Auth::adminLogout();
        flash('info', 'Logged out of admin portal.');
        $this->redirect('admin/login');
    }
}
