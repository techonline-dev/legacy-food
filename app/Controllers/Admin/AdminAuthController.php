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
        $csrfValid = $this->validateCsrf();
        $isLocal = (env('APP_ENV') === 'local' || in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']));
        if (!$csrfValid && !$isLocal) {
            flash('error', 'Security token expired. Please try again.');
            $this->redirect('admin/login');
            return;
        }

        $email = trim((string)$this->request->input('email'));
        $password = (string)$this->request->input('password');

        if (Auth::adminAttempt($email, $password)) {
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
