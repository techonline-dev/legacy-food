<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

class AuthController extends Controller {
    public function showLogin(): void {
        if (Auth::check()) {
            $this->redirect('account');
            return;
        }

        $this->view('auth.login', [
            'meta_title' => 'Sign In to Your Account | Legacy Food',
            'redirect' => $this->request->input('redirect', 'account'),
            'prefill_email' => $this->request->input('email', '')
        ], 'main');
    }

    public function login(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Security token expired. Please try again.');
            $this->redirect('login');
            return;
        }

        $email = trim((string)$this->request->input('email'));
        $password = (string)$this->request->input('password');

        if (empty($email) || empty($password)) {
            flash('error', 'Please enter both email and password.');
            $this->redirect('login');
            return;
        }

        if (Auth::attempt($email, $password)) {
            flash('success', 'Welcome back to Legacy Food!');
            $redirect = trim((string)$this->request->input('redirect', ''));
            if (empty($redirect) || $redirect === 'account') {
                $cart = \App\Models\Cart::getSummary();
                $redirect = (!empty($cart['items'])) ? 'checkout' : 'account';
            }
            $this->redirect($redirect);
            return;
        }

        flash('error', 'Invalid email or password. Please check your credentials.');
        $redirectParam = $this->request->input('redirect') ? '?redirect=' . urlencode($this->request->input('redirect')) : '';
        $this->redirect('login' . $redirectParam);
    }

    public function showRegister(): void {
        if (Auth::check()) {
            $this->redirect('account');
            return;
        }

        $redirect = $this->request->input('redirect', '');

        $this->view('auth.register', [
            'meta_title' => 'Create an Account | Legacy Food',
            'redirect' => $redirect,
            'prefill_email' => $this->request->input('email', ''),
        ], 'main');
    }

    public function register(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Security token expired. Please try again.');
            $this->redirect('register');
            return;
        }

        $errors = $this->validate([
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'phone' => 'required|min:10'
        ]);

        if (!empty($errors)) {
            flash('error', reset($errors)[0]);
            $redirectParam = $this->request->input('redirect') ? '?redirect=' . urlencode($this->request->input('redirect')) : '';
            $this->redirect('register' . $redirectParam);
            return;
        }

        $email = strtolower(trim((string)$this->request->input('email')));
        $existing = Database::fetch("SELECT id FROM `users` WHERE `email` = :email", ['email' => $email]);
        if ($existing) {
            flash('error', 'An account with this email already exists. Please log in.');
            $redirectParam = $this->request->input('redirect') ? '?redirect=' . urlencode($this->request->input('redirect')) : '';
            $this->redirect('login' . $redirectParam);
            return;
        }

        $userId = Database::insert('users', [
            'name' => sanitize($this->request->input('name')),
            'email' => $email,
            'phone' => sanitize($this->request->input('phone')),
            'password' => password_hash((string)$this->request->input('password'), PASSWORD_BCRYPT),
            'status' => 'active'
        ]);

        $user = Database::fetch("SELECT * FROM `users` WHERE `id` = :id", ['id' => $userId]);
        Auth::login($user);

        flash('success', 'Your account has been created successfully! Welcome to Legacy Food.');
        $redirect = trim((string)$this->request->input('redirect', ''));
        if (empty($redirect) || $redirect === 'account') {
            $cart = \App\Models\Cart::getSummary();
            $redirect = (!empty($cart['items'])) ? 'checkout' : 'account';
        }
        $this->redirect($redirect);
    }

    public function logout(): void {
        Auth::logout();
        flash('info', 'You have been logged out.');
        $this->redirect('login');
    }

    public function showForgotPassword(): void {
        $this->view('auth.forgot_password', [
            'meta_title' => 'Forgot Password | Legacy Food'
        ], 'main');
    }

    public function forgotPassword(): void {
        $email = trim((string)$this->request->input('email'));
        flash('success', 'If an account exists with that email, a password reset link has been dispatched.');
        $this->redirect('forgot-password');
    }
}
