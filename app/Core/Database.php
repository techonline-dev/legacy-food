<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;
    private static bool $schemaChecked = false;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $host = config('database.host', '127.0.0.1');
            $port = config('database.port', '3306');
            $dbName = config('database.database', 'legacyfood');
            $user = config('database.username', 'root');
            $pass = config('database.password', '');
            $charset = config('database.charset', 'utf8mb4');
            $target = config('database.target', 'local');

            try {
                self::$instance = self::connectPdo($host, $port, $dbName, $user, $pass, $charset);
            } catch (PDOException $e) {
                // If the primary connection failed and it was attempting production while on a local/dev machine,
                // seamlessly fall back to the local database connection!
                $connections = config('database.connections', []);
                $altKey = ($target === 'production') ? 'local' : null;

                if ($altKey && isset($connections[$altKey])) {
                    $alt = $connections[$altKey];
                    try {
                        self::$instance = self::connectPdo(
                            $alt['host'],
                            $alt['port'],
                            $alt['database'],
                            $alt['username'],
                            $alt['password'],
                            $alt['charset']
                        );
                        error_log("Database notice: Production connection unavailable. Seamlessly fell back to {$altKey} database '{$alt['database']}'.");
                    } catch (\Throwable $altEx) {
                        throw $e;
                    }
                } else {
                    throw $e;
                }
            }

            // Check if tables are initialized
            if (!self::$schemaChecked) {
                self::ensureTablesExist();
                self::$schemaChecked = true;
            }
        }

        return self::$instance;
    }

    private static function connectPdo(string $host, string|int $port, string $dbName, string $user, string $pass, string $charset): PDO {
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            // If database does not exist (error 1049), auto-create it!
            if ($e->getCode() == 1049 || str_contains($e->getMessage(), 'Unknown database')) {
                $initPdo = new PDO("mysql:host={$host};port={$port};charset={$charset}", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                $initPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                unset($initPdo);

                $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
                $pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);

                // Run initial schema and seeds automatically
                self::autoMigrate($pdo);
                return $pdo;
            }
            throw $e;
        }
    }

    public static function ensureTablesExist(): void {
        try {
            $stmt = self::$instance->query("SHOW TABLES LIKE 'products'");
            if ($stmt->rowCount() === 0) {
                self::autoMigrate();
            } else {
                // Ensure gst_rate and hsn_code exist on products
                try {
                    $hasCols = self::$instance->query("SHOW COLUMNS FROM `products` LIKE 'gst_rate'")->rowCount();
                    if ($hasCols === 0) {
                        self::$instance->exec("ALTER TABLE `products` ADD COLUMN `gst_rate` DECIMAL(5,2) NULL DEFAULT NULL AFTER `sale_price`");
                    }
                    $hasHsn = self::$instance->query("SHOW COLUMNS FROM `products` LIKE 'hsn_code'")->rowCount();
                    if ($hasHsn === 0) {
                        self::$instance->exec("ALTER TABLE `products` ADD COLUMN `hsn_code` VARCHAR(30) NULL DEFAULT NULL AFTER `sku`");
                    }

                    // Ensure coupons conditional rule columns exist
                    $colsCoupon = self::$instance->query("SHOW COLUMNS FROM `coupons` LIKE 'first_order_only'")->rowCount();
                    if ($colsCoupon === 0) {
                        self::$instance->exec("ALTER TABLE `coupons` ADD COLUMN `first_order_only` TINYINT(1) DEFAULT 0 AFTER `usage_limit_per_user`");
                    }
                    $colsCouponCat = self::$instance->query("SHOW COLUMNS FROM `coupons` LIKE 'category_id'")->rowCount();
                    if ($colsCouponCat === 0) {
                        self::$instance->exec("ALTER TABLE `coupons` ADD COLUMN `category_id` INT UNSIGNED NULL DEFAULT NULL AFTER `first_order_only`");
                    }
                    $colsCouponQty = self::$instance->query("SHOW COLUMNS FROM `coupons` LIKE 'min_quantity'")->rowCount();
                    if ($colsCouponQty === 0) {
                        self::$instance->exec("ALTER TABLE `coupons` ADD COLUMN `min_quantity` INT UNSIGNED DEFAULT 0 AFTER `category_id`");
                    }
                    $colsCouponDesc = self::$instance->query("SHOW COLUMNS FROM `coupons` LIKE 'description'")->rowCount();
                    if ($colsCouponDesc === 0) {
                        self::$instance->exec("ALTER TABLE `coupons` ADD COLUMN `description` VARCHAR(255) NULL DEFAULT NULL AFTER `value`");
                    }

                    // Ensure category SEO columns exist
                    $colsCatMeta = self::$instance->query("SHOW COLUMNS FROM `product_categories` LIKE 'meta_title'")->rowCount();
                    if ($colsCatMeta === 0) {
                        self::$instance->exec("ALTER TABLE `product_categories` ADD COLUMN `meta_title` VARCHAR(200) NULL DEFAULT NULL AFTER `is_active`");
                    }
                    $colsCatDesc = self::$instance->query("SHOW COLUMNS FROM `product_categories` LIKE 'meta_description'")->rowCount();
                    if ($colsCatDesc === 0) {
                        self::$instance->exec("ALTER TABLE `product_categories` ADD COLUMN `meta_description` TEXT NULL DEFAULT NULL AFTER `meta_title`");
                    }

                    // Ensure menu_items table exists
                    $hasMenuItems = self::$instance->query("SHOW TABLES LIKE 'menu_items'")->rowCount();
                    if ($hasMenuItems === 0) {
                        self::$instance->exec("
                            CREATE TABLE IF NOT EXISTS `menu_items` (
                                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                `menu_group` VARCHAR(50) NOT NULL DEFAULT 'header',
                                `parent_id` INT UNSIGNED NULL DEFAULT NULL,
                                `title` VARCHAR(150) NOT NULL,
                                `url` VARCHAR(255) NOT NULL,
                                `target` ENUM('_self', '_blank') DEFAULT '_self',
                                `sort_order` INT NOT NULL DEFAULT 0,
                                `is_active` TINYINT(1) DEFAULT 1,
                                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                INDEX `idx_menu_group` (`menu_group`, `sort_order`),
                                INDEX `idx_parent_id` (`parent_id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                        ");

                        \App\Models\MenuItem::seedDefaultItems();
                    }
                } catch (\Throwable $ex) {
                    // Ignore if migration fails
                }
            }
        } catch (\Throwable $t) {
            // Ignore during setup
        }
    }

    public static function autoMigrate(?PDO $pdo = null): void {
        $db = $pdo ?? self::$instance;
        if (!$db) return;
        
        $schemaFile = __DIR__ . '/../../database/schema.sql';
        $seedsFile = __DIR__ . '/../../database/seeds.sql';

        if (file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            $db->exec($sql);
        }

        if (file_exists($seedsFile)) {
            $seeds = file_get_contents($seedsFile);
            $db->exec($seeds);
        }
    }

    public static function query(string $sql, array $params = []): \PDOStatement {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array {
        $res = self::query($sql, $params)->fetch();
        return $res ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        return self::query($sql, $params)->fetchAll();
    }

    public static function insert(string $table, array $data): int {
        $keys = array_keys($data);
        $fields = implode('`, `', $keys);
        $placeholders = ':' . implode(', :', $keys);
        $sql = "INSERT INTO `{$table}` (`{$fields}`) VALUES ({$placeholders})";
        self::query($sql, $data);
        return (int)self::getInstance()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int {
        $set = [];
        $params = [];
        foreach ($data as $key => $val) {
            $set[] = "`{$key}` = :set_{$key}";
            $params["set_{$key}"] = $val;
        }
        $setString = implode(', ', $set);
        $sql = "UPDATE `{$table}` SET {$setString} WHERE {$where}";
        $merged = array_merge($params, $whereParams);
        $stmt = self::query($sql, $merged);
        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $whereParams = []): int {
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        return self::query($sql, $whereParams)->rowCount();
    }

    public static function beginTransaction(): bool {
        return self::getInstance()->beginTransaction();
    }

    public static function commit(): bool {
        return self::getInstance()->commit();
    }

    public static function rollBack(): bool {
        return self::getInstance()->rollBack();
    }
}
