<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Testimonial extends Model {
    protected static string $table = 'testimonials';

    public static function getActive(): array {
        return Database::fetchAll("SELECT * FROM `testimonials` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
    }
}
