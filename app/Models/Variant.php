<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Variant extends Model {
    protected static string $table = 'product_variants';

    public static function getForProduct(int $productId): array {
        return Database::fetchAll("SELECT * FROM `product_variants` WHERE `product_id` = :pid ORDER BY `price` ASC", ['pid' => $productId]);
    }
}
