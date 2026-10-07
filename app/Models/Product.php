<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Product extends Model {
    protected static string $table = 'products';

    public static function getPublished(string $orderBy = 'p.is_featured DESC, p.id ASC'): array {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM `products` p
                LEFT JOIN `product_categories` c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY {$orderBy}";
        return Database::fetchAll($sql);
    }

    public static function getFeatured(int $limit = 6): array {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM `products` p
                LEFT JOIN `product_categories` c ON p.category_id = c.id
                WHERE p.status = 'published' AND p.is_featured = 1
                ORDER BY p.id ASC LIMIT {$limit}";
        return Database::fetchAll($sql);
    }

    public static function getBestsellers(int $limit = 6): array {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM `products` p
                LEFT JOIN `product_categories` c ON p.category_id = c.id
                WHERE p.status = 'published' AND p.is_bestseller = 1
                ORDER BY p.id ASC LIMIT {$limit}";
        return Database::fetchAll($sql);
    }

    public static function getBySlug(string $slug): ?array {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM `products` p
                LEFT JOIN `product_categories` c ON p.category_id = c.id
                WHERE p.slug = :slug AND p.status = 'published'
                LIMIT 1";
        $product = Database::fetch($sql, ['slug' => $slug]);
        if (!$product) return null;

        $product['variants'] = self::getVariants($product['id']);
        $product['images'] = self::getImages($product['id']);
        $product['reviews'] = self::getReviews($product['id']);
        return $product;
    }

    public static function getVariants(int $productId): array {
        return Database::fetchAll("SELECT * FROM `product_variants` WHERE `product_id` = :pid ORDER BY `price` ASC", ['pid' => $productId]);
    }

    public static function getImages(int $productId): array {
        return Database::fetchAll("SELECT * FROM `product_images` WHERE `product_id` = :pid ORDER BY `sort_order` ASC", ['pid' => $productId]);
    }

    public static function getReviews(int $productId): array {
        return Database::fetchAll("SELECT * FROM `reviews` WHERE `product_id` = :pid AND `status` = 'approved' ORDER BY `created_at` DESC", ['pid' => $productId]);
    }

    public static function filter(array $filters = []): array {
        $where = ["p.status = 'published'"];
        $params = [];

        if (!empty($filters['category'])) {
            $where[] = "c.slug = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(p.name LIKE :search OR p.short_description LIKE :search OR p.sku LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['min_price'])) {
            $where[] = "p.base_price >= :min_price";
            $params['min_price'] = (float)$filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $where[] = "p.base_price <= :max_price";
            $params['max_price'] = (float)$filters['max_price'];
        }

        $orderBy = "p.is_featured DESC, p.id ASC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_low':
                    $orderBy = "p.base_price ASC";
                    break;
                case 'price_high':
                    $orderBy = "p.base_price DESC";
                    break;
                case 'newest':
                    $orderBy = "p.created_at DESC";
                    break;
                case 'bestseller':
                    $orderBy = "p.is_bestseller DESC, p.rating_cache DESC";
                    break;
                case 'rating':
                    $orderBy = "p.rating_cache DESC";
                    break;
            }
        }

        $whereClause = implode(' AND ', $where);
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM `products` p
                LEFT JOIN `product_categories` c ON p.category_id = c.id
                WHERE {$whereClause}
                ORDER BY {$orderBy}";

        return Database::fetchAll($sql, $params);
    }
}
