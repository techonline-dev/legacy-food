<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Setting extends Model {
    protected static string $table = 'settings';
    private static array $cache = [];

    public static function get(string $key, $default = null) {
        if (empty(self::$cache)) {
            self::loadAll();
        }
        return self::$cache[$key] ?? $default;
    }

    public static function set(string $key, $value, string $group = 'general'): void {
        $existing = Database::fetch("SELECT id FROM `settings` WHERE `key_name` = :k LIMIT 1", ['k' => $key]);
        if ($existing) {
            Database::update('settings', ['value' => $value], "`key_name` = :k", ['k' => $key]);
        } else {
            Database::insert('settings', [
                'group_name' => $group,
                'key_name' => $key,
                'value' => $value
            ]);
        }
        self::$cache[$key] = $value;
    }

    public static function loadAll(): void {
        try {
            $rows = Database::fetchAll("SELECT `key_name`, `value` FROM `settings`");
            foreach ($rows as $row) {
                self::$cache[$row['key_name']] = $row['value'];
            }
        } catch (\Throwable $t) {
            // During installer or cold start before table exists
        }
    }

    public static function allGrouped(): array {
        $rows = Database::fetchAll("SELECT * FROM `settings` ORDER BY `group_name`, `key_name`");
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['group_name']][$row['key_name']] = $row['value'];
        }
        return $grouped;
    }
}
