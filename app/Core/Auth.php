<?php

namespace App\Core;

class Auth {
    private static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Customer User Authentication
    public static function attempt(string $email, string $password): bool {
        self::startSession();
        $user = Database::fetch("SELECT * FROM `users` WHERE `email` = :email AND `status` = 'active' LIMIT 1", ['email' => $email]);
        
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            Database::update('users', ['last_login_at' => date('Y-m-d H:i:s')], "`id` = :id", ['id' => $user['id']]);

            // Sync any guest cart items to this user's account
            \App\Models\Cart::syncSessionCartToUser($user['id']);

            return true;
        }

        return false;
    }

    public static function login(array $user): void {
        self::startSession();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        \App\Models\Cart::syncSessionCartToUser($user['id']);
    }

    public static function logout(): void {
        self::startSession();
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
        session_regenerate_id(true);
    }

    public static function check(): bool {
        self::startSession();
        return !empty($_SESSION['user_id']);
    }

    public static function id(): ?int {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function user(): ?array {
        $id = self::id();
        if (!$id) return null;
        return Database::fetch("SELECT id, name, email, phone, avatar, status, created_at FROM `users` WHERE `id` = :id LIMIT 1", ['id' => $id]);
    }

    // Admin Authentication
    public static function adminAttempt(string $email, string $password): bool {
        self::startSession();

        $cleanEmail = strtolower(trim($email));
        if ($cleanEmail === 'admin') {
            $cleanEmail = 'admin@legacyfood.in';
        }

        $admin = Database::fetch(
            "SELECT a.*, r.name as role_name, r.slug as role_slug 
             FROM `admins` a 
             LEFT JOIN `roles` r ON a.role_id = r.id 
             WHERE LOWER(a.email) = :email AND a.status = 'active' LIMIT 1",
            ['email' => $cleanEmail]
        );

        if (!$admin) {
            return false;
        }

        if (empty($admin['password']) || !password_verify($password, $admin['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = $admin['role_slug'] ?? 'super-admin';

        try {
            Database::update('admins', ['last_login_at' => date('Y-m-d H:i:s')], "`id` = :id", ['id' => $admin['id']]);
        } catch (\Throwable $t) {}

        \App\Models\ActivityLog::log('admin_login', 'Admin logged into dashboard', $admin['id']);

        return true;
    }

    public static function adminLogin(array $admin): void {
        self::startSession();
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = $admin['role_slug'] ?? 'super-admin';
    }

    public static function adminLogout(): void {
        self::startSession();
        unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_email'], $_SESSION['admin_role']);
        session_regenerate_id(true);
    }

    public static function adminCheck(): bool {
        self::startSession();
        return !empty($_SESSION['admin_id']);
    }

    public static function adminId(): ?int {
        self::startSession();
        return $_SESSION['admin_id'] ?? null;
    }

    public static function admin(): ?array {
        $id = self::adminId();
        if (!$id) return null;
        return Database::fetch("SELECT a.*, r.name as role_name, r.slug as role_slug FROM `admins` a LEFT JOIN `roles` r ON a.role_id = r.id WHERE a.id = :id AND a.status = 'active' LIMIT 1", ['id' => $id]);
    }

    public static function isSuperAdmin(): bool {
        $admin = self::admin();
        return !empty($admin) && ($admin['role_slug'] ?? '') === 'super-admin';
    }
}
