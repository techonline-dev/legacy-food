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

        $email = strtolower(trim((string)$this->request->input('email')));
        $password = (string)$this->request->input('password');

        if (empty($email) || empty($password)) {
            flash('error', 'Please enter both email and password.');
            $this->redirect('login');
            return;
        }

        $rateKey = 'login:' . client_ip() . ':' . $email;
        if (!\App\Core\RateLimiter::attempt($rateKey, 8, 900)) {
            flash('error', 'Too many login attempts. Please wait 15 minutes and try again.');
            $this->redirect('login');
            return;
        }

        if (Auth::attempt($email, $password)) {
            \App\Core\RateLimiter::clear($rateKey);
            flash('success', 'Welcome back to Legacy Food!');
            $redirect = safe_redirect_target((string)$this->request->input('redirect', ''));
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

        if (!\App\Core\RateLimiter::attempt('register:' . client_ip(), 5, 3600)) {
            flash('error', 'Too many registration attempts. Please try again later.');
            $this->redirect('register');
            return;
        }

        $errors = $this->validate([
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'required|min:10'
        ]);

        if (empty($errors)) {
            $passwordError = password_policy_error((string)$this->request->input('password'));
            if ($passwordError !== null) {
                $errors['password'][] = $passwordError;
            }
        }

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
        $redirect = safe_redirect_target((string)$this->request->input('redirect', ''));
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
        if (!$this->validateCsrf()) {
            flash('error', 'Security token expired. Please try again.');
            $this->redirect('forgot-password');
            return;
        }

        $email = strtolower(trim((string)$this->request->input('email')));
        $generic = 'If an account exists with that email, a password reset link has been dispatched.';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)
            || !\App\Core\RateLimiter::attempt('forgot:' . client_ip(), 5, 3600)
            || !\App\Core\RateLimiter::attempt('forgot_email:' . $email, 3, 3600)) {
            flash('success', $generic);
            $this->redirect('forgot-password');
            return;
        }

        $user = Database::fetch("SELECT id, name, email FROM `users` WHERE LOWER(`email`) = :email AND `status` = 'active' LIMIT 1", ['email' => $email]);

        if ($user) {
            $rawToken = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $rawToken);

            Database::delete('password_resets', '`email` = :email AND `user_type` = :type', ['email' => $email, 'type' => 'user']);
            Database::delete('password_resets', '`expires_at` < NOW()');
            Database::insert('password_resets', [
                'email' => $email,
                'token' => $tokenHash,
                'user_type' => 'user',
                'expires_at' => date('Y-m-d H:i:s', time() + 3600),
            ]);

            $resetUrl = url('reset-password/' . $rawToken);
            $body = '<p>Hello ' . e($user['name']) . ',</p>'
                . '<p>We received a request to reset your Legacy Food account password. This link is valid for one hour and can be used only once.</p>'
                . '<p>If you did not request a password reset, you can safely ignore this email.</p>';
            \App\Core\Mailer::send(
                $user['email'],
                $user['name'],
                'Reset Your Legacy Food Password',
                \App\Core\Mailer::renderEmail('Reset Your Password', $body, 'Reset Password', $resetUrl)
            );
        }

        flash('success', $generic);
        $this->redirect('forgot-password');
    }

    public function showResetPassword(string $token): void {
        $reset = $this->findValidReset($token);
        if (!$reset) {
            flash('error', 'This password reset link is invalid or has expired. Please request a new one.');
            $this->redirect('forgot-password');
            return;
        }

        $this->view('auth.reset_password', [
            'meta_title' => 'Choose a New Password | Legacy Food',
            'token' => $token,
        ], 'main');
    }

    public function resetPassword(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Security token expired. Please try again.');
            $this->redirect('forgot-password');
            return;
        }

        if (!\App\Core\RateLimiter::attempt('reset:' . client_ip(), 10, 3600)) {
            flash('error', 'Too many reset attempts. Please try again later.');
            $this->redirect('forgot-password');
            return;
        }

        $token = trim((string)$this->request->input('token'));
        $password = (string)$this->request->input('password');
        $confirm = (string)$this->request->input('password_confirmation');

        $reset = $this->findValidReset($token);
        if (!$reset) {
            flash('error', 'This password reset link is invalid or has expired. Please request a new one.');
            $this->redirect('forgot-password');
            return;
        }

        $passwordError = password_policy_error($password);
        if ($passwordError !== null) {
            flash('error', $passwordError);
            $this->redirect('reset-password/' . $token);
            return;
        }

        if (!hash_equals($password, $confirm)) {
            flash('error', 'The password confirmation does not match.');
            $this->redirect('reset-password/' . $token);
            return;
        }

        Database::update('users', ['password' => password_hash($password, PASSWORD_DEFAULT)], "`id` = :id", ['id' => $reset['user_id']]);
        Database::delete('password_resets', '`email` = :email AND `user_type` = :type', ['email' => $reset['email'], 'type' => 'user']);

        \App\Models\ActivityLog::log('password_reset', 'Customer completed a password reset via email link', null);
        flash('success', 'Your password has been reset. Please sign in with your new password.');
        $this->redirect('login');
    }

    private function findValidReset(string $token): ?array {
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }

        return Database::fetch(
            "SELECT pr.email, u.id AS user_id
             FROM `password_resets` pr
             INNER JOIN `users` u ON LOWER(u.email) = pr.email AND u.status = 'active'
             WHERE pr.token = :token AND pr.user_type = 'user' AND pr.expires_at > NOW()
             LIMIT 1",
            ['token' => hash('sha256', $token)]
        );
    }
}
