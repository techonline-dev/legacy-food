<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class MenuItem extends Model {
    protected static string $table = 'menu_items';

    /**
     * Get menu tree (nested parents and children)
     */
    public static function getTree(string $group = 'header', bool $onlyActive = true): array {
        $where = ["`menu_group` = :group"];
        $params = ['group' => $group];

        if ($onlyActive) {
            $where[] = "`is_active` = 1";
        }

        $whereSql = implode(' AND ', $where);
        $items = Database::fetchAll("SELECT * FROM `menu_items` WHERE {$whereSql} ORDER BY `sort_order` ASC, `id` ASC", $params);

        if (empty($items)) {
            return [];
        }

        $indexed = [];
        foreach ($items as $item) {
            $item['children'] = [];
            $indexed[$item['id']] = $item;
        }

        $tree = [];
        foreach ($indexed as $id => &$item) {
            $parentId = $item['parent_id'] ? (int)$item['parent_id'] : null;
            if ($parentId && isset($indexed[$parentId])) {
                $indexed[$parentId]['children'][] = &$item;
            } else {
                $tree[] = &$item;
            }
        }
        unset($item);

        return $tree;
    }

    /**
     * Seed default menu items for the header navigation
     */
    public static function seedDefaultItems(string $group = 'header'): void {
        // Delete existing items in this group
        Database::query("DELETE FROM `menu_items` WHERE `menu_group` = :g", ['g' => $group]);

        // 1. Home
        Database::insert('menu_items', [
            'menu_group' => $group,
            'parent_id' => null,
            'title' => 'Home',
            'url' => '/',
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => 1,
        ]);

        // 2. Categories (Parent)
        $catParentId = Database::insert('menu_items', [
            'menu_group' => $group,
            'parent_id' => null,
            'title' => 'Categories',
            'url' => '/shop',
            'target' => '_self',
            'sort_order' => 2,
            'is_active' => 1,
        ]);

        // Submenus under Categories
        Database::insert('menu_items', [
            'menu_group' => $group,
            'parent_id' => $catParentId,
            'title' => 'Pure Cow Ghee',
            'url' => '/category/ghee',
            'target' => '_self',
            'sort_order' => 1,
            'is_active' => 1,
        ]);

        Database::insert('menu_items', [
            'menu_group' => $group,
            'parent_id' => $catParentId,
            'title' => 'Buffalo Ghee',
            'url' => '/product/buffalo-ghee',
            'target' => '_self',
            'sort_order' => 2,
            'is_active' => 1,
        ]);

        Database::insert('menu_items', [
            'menu_group' => $group,
            'parent_id' => $catParentId,
            'title' => 'Cold-Pressed Oils',
            'url' => '/category/cold-pressed-oils',
            'target' => '_self',
            'sort_order' => 3,
            'is_active' => 1,
        ]);

        // 3. Shop All
        Database::insert('menu_items', [
            'menu_group' => $group,
            'parent_id' => null,
            'title' => 'Shop All',
            'url' => '/shop',
            'target' => '_self',
            'sort_order' => 3,
            'is_active' => 1,
        ]);

        // 4. About Us
        Database::insert('menu_items', [
            'menu_group' => $group,
            'parent_id' => null,
            'title' => 'About Us',
            'url' => '/about',
            'target' => '_self',
            'sort_order' => 4,
            'is_active' => 1,
        ]);

        // 5. Contact
        Database::insert('menu_items', [
            'menu_group' => $group,
            'parent_id' => null,
            'title' => 'Contact',
            'url' => '/contact',
            'target' => '_self',
            'sort_order' => 5,
            'is_active' => 1,
        ]);
    }

    /**
     * Batch update menu hierarchy from drag-and-drop tree
     */
    public static function updateHierarchy(array $items): bool {
        $pdo = Database::getInstance();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("UPDATE `menu_items` SET `parent_id` = :parent_id, `sort_order` = :sort_order WHERE `id` = :id");

            foreach ($items as $item) {
                $id = (int)($item['id'] ?? 0);
                $parentId = !empty($item['parent_id']) ? (int)$item['parent_id'] : null;
                $sortOrder = (int)($item['sort_order'] ?? 0);

                if ($id > 0) {
                    // Prevent circular parenting (an item cannot be parent of itself)
                    if ($parentId === $id) {
                        $parentId = null;
                    }

                    $stmt->execute([
                        'parent_id' => $parentId,
                        'sort_order' => $sortOrder,
                        'id' => $id,
                    ]);
                }
            }

            $pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            error_log("Failed to update menu hierarchy: " . $e->getMessage());
            return false;
        }
    }
}
