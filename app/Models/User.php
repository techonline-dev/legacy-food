<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class User extends Model {
    protected static string $table = 'users';

    public static function getCustomerStats(): array {
        $totalCustomers = Database::fetch("SELECT COUNT(*) as total FROM `users` WHERE `status` = 'active'");
        return [
            'total' => (int)($totalCustomers['total'] ?? 0)
        ];
    }
}
