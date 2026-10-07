<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Faq extends Model {
    protected static string $table = 'faqs';

    public static function getActiveGrouped(): array {
        $faqs = Database::fetchAll("SELECT * FROM `faqs` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC");
        $grouped = [];
        foreach ($faqs as $faq) {
            $cat = $faq['category'] ?: 'General';
            $grouped[$cat][] = $faq;
        }
        return $grouped;
    }
}
