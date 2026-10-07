<?php

namespace App\Models;

use App\Core\Model;

class Page extends Model {
    protected static string $table = 'pages';

    public static function getBySlug(string $slug): ?array {
        return self::findBy('slug', $slug);
    }
}
