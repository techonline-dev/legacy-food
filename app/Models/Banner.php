<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Banner extends Model {
    protected static string $table = 'banners';

    public static function getHero(): array {
        return Database::fetchAll("SELECT * FROM `banners` WHERE `type` = 'hero' AND `status` = 'active' ORDER BY `sort_order` ASC");
    }

    public static function getPromotional(): ?array {
        return Database::fetch("SELECT * FROM `banners` WHERE `type` = 'promotional' AND `status` = 'active' ORDER BY `sort_order` ASC LIMIT 1");
    }
}
