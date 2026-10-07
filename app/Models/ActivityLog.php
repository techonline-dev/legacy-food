<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Core\Auth;

class ActivityLog extends Model {
    protected static string $table = 'activity_logs';

    public static function log(string $action, string $description, ?int $adminId = null): void {
        try {
            $adminId = $adminId ?: Auth::adminId();
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

            Database::insert('activity_logs', [
                'admin_id' => $adminId,
                'action' => $action,
                'description' => $description,
                'ip_address' => $ip,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $t) {
            error_log("Activity log error: " . $t->getMessage());
        }
    }

    public static function getRecent(int $limit = 20): array {
        $sql = "SELECT al.*, a.name as admin_name 
                FROM `activity_logs` al
                LEFT JOIN `admins` a ON al.admin_id = a.id
                ORDER BY al.id DESC LIMIT {$limit}";
        return Database::fetchAll($sql);
    }
}
