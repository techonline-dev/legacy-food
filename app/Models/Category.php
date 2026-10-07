<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Category extends Model {
    protected static string $table = 'product_categories';

    public static function getActive(): array {
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM `product_categories` c
                LEFT JOIN `products` p ON c.id = p.category_id AND p.status = 'published'
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY c.sort_order ASC";
        return Database::fetchAll($sql);
    }

    public static function getBySlug(string $slug): ?array {
        return self::findBy('slug', $slug);
    }
}
