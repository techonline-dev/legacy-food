<?php

namespace App\Core;

class AdminProvisioner {
    public static function fromEnv(bool $updateExisting = true): array {
        $email = strtolower(trim((string)env('ADMIN_EMAIL', 'admin@legacyfood.in')));
        $password = (string)env('ADMIN_PASSWORD', 'Admin@LegacyFood2026');
        $name = trim((string)env('ADMIN_NAME', 'Super Admin'));

        return self::provision($email, $password, $name, $updateExisting);
    }

    public static function provision(string $email, string $password, string $name = 'Super Admin', bool $updateExisting = true): array {
        $email = strtolower(trim($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Invalid admin email address.');
        }
        if (strlen($password) < 8) {
            throw new \RuntimeException('Admin password must be at least 8 characters long.');
        }

        Database::query(
            "INSERT IGNORE INTO `roles` (`id`, `name`, `slug`, `description`) VALUES (1, 'Super Admin', 'super-admin', 'Full access to all system features')"
        );
        $role = Database::fetch("SELECT `id` FROM `roles` WHERE `slug` = 'super-admin' LIMIT 1");
        if (!$role) {
            throw new \RuntimeException('Could not resolve the super-admin role.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $existing = Database::fetch("SELECT `id` FROM `admins` WHERE LOWER(`email`) = :email LIMIT 1", ['email' => $email]);

        if ($existing) {
            if (!$updateExisting) {
                return ['action' => 'exists', 'email' => $email];
            }
            Database::update('admins', [
                'name' => $name !== '' ? $name : 'Super Admin',
                'password' => $hash,
                'role_id' => (int)$role['id'],
                'status' => 'active',
            ], "`id` = :id", ['id' => $existing['id']]);
            return ['action' => 'updated', 'email' => $email];
        }

        Database::insert('admins', [
            'role_id' => (int)$role['id'],
            'name' => $name !== '' ? $name : 'Super Admin',
            'email' => $email,
            'password' => $hash,
            'status' => 'active',
        ]);

        return ['action' => 'created', 'email' => $email];
    }
}
