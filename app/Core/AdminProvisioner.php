<?php

namespace App\Core;

class AdminProvisioner {
    public static function fromEnv(bool $updateExisting = true): array {
        $email = strtolower(trim((string)env('ADMIN_EMAIL', '')));
        $password = (string)env('ADMIN_PASSWORD', '');
        $name = trim((string)env('ADMIN_NAME', 'Administrator'));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('ADMIN_EMAIL must be a valid email address.');
        }
        if (strlen($password) < 12) {
            throw new \RuntimeException('ADMIN_PASSWORD must be at least 12 characters long.');
        }

        Database::query(
            "INSERT IGNORE INTO `roles` (`name`, `slug`, `description`) VALUES ('Super Admin', 'super-admin', 'Full access to all system features')"
        );
        $role = Database::fetch("SELECT `id` FROM `roles` WHERE `slug` = 'super-admin' LIMIT 1");
        if (!$role) {
            throw new \RuntimeException('Could not resolve the super-admin role.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Remove the legacy seeded administrator if a different account is being provisioned.
        Database::query(
            "DELETE FROM `admins` WHERE LOWER(`email`) = 'admin@legacyfood.in' AND LOWER(`email`) <> :email",
            ['email' => $email]
        );

        $existing = Database::fetch("SELECT `id` FROM `admins` WHERE LOWER(`email`) = :email LIMIT 1", ['email' => $email]);

        if ($existing) {
            if (!$updateExisting) {
                return ['action' => 'exists', 'email' => $email];
            }
            Database::update('admins', [
                'name' => $name !== '' ? $name : 'Administrator',
                'password' => $hash,
                'role_id' => (int)$role['id'],
                'status' => 'active',
            ], "`id` = :id", ['id' => $existing['id']]);
            return ['action' => 'updated', 'email' => $email];
        }

        Database::insert('admins', [
            'role_id' => (int)$role['id'],
            'name' => $name !== '' ? $name : 'Administrator',
            'email' => $email,
            'password' => $hash,
            'status' => 'active',
        ]);

        return ['action' => 'created', 'email' => $email];
    }
}
