<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Page;
use App\Models\ActivityLog;

class AdminMenuController extends Controller {
    public function index(): void {
        $group = $this->request->query('group') ?: 'header';
        
        $menuTree = MenuItem::getTree($group, false);
        $flatItems = Database::fetchAll("SELECT * FROM `menu_items` WHERE `menu_group` = :g ORDER BY `sort_order` ASC, `id` ASC", ['g' => $group]);
        
        $categories = Category::all('sort_order ASC, name ASC');
        $pages = Page::all('title ASC');

        // Predefined site links for 1-click addition
        $presetLinks = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Shop All Products', 'url' => '/shop'],
            ['title' => 'About Us (Our Story)', 'url' => '/about'],
            ['title' => 'Contact Us', 'url' => '/contact'],
            ['title' => 'FAQs & Help', 'url' => '/faq'],
            ['title' => 'Privacy Policy', 'url' => '/privacy-policy'],
            ['title' => 'Terms of Service', 'url' => '/terms-and-conditions'],
            ['title' => 'Shipping & Returns', 'url' => '/shipping-policy'],
        ];

        $this->view('admin.menu.index', [
            'meta_title' => 'Navigation Menu Builder | Legacy Food Admin',
            'group' => $group,
            'menuTree' => $menuTree,
            'flatItems' => $flatItems,
            'categories' => $categories,
            'pages' => $pages,
            'presetLinks' => $presetLinks,
        ], 'admin');
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => 'Session expired. Please refresh the page.'], 403);
                return;
            }
            flash('error', 'Token expired.');
            $this->redirect('admin/menu');
            return;
        }

        $group = sanitize($this->request->input('menu_group') ?: 'header');
        $type = $this->request->input('type') ?: 'custom';
        $parentId = $this->request->input('parent_id') ? (int)$this->request->input('parent_id') : null;

        // Calculate max sort order
        $maxOrder = (int)(Database::fetchOne("SELECT MAX(sort_order) FROM `menu_items` WHERE `menu_group` = :g", ['g' => $group]) ?? 0);

        if ($type === 'custom') {
            $title = trim(sanitize($this->request->input('title') ?? ''));
            $url = trim($this->request->input('url') ?? '');
            $target = $this->request->input('target') === '_blank' ? '_blank' : '_self';

            if (empty($title) || empty($url)) {
                if ($this->request->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Title and URL are required.'], 422);
                    return;
                }
                flash('error', 'Title and URL are required.');
                $this->redirect('admin/menu?group=' . urlencode($group));
                return;
            }

            Database::insert('menu_items', [
                'menu_group' => $group,
                'parent_id' => $parentId,
                'title' => $title,
                'url' => $url,
                'target' => $target,
                'sort_order' => $maxOrder + 1,
                'is_active' => 1,
            ]);

            ActivityLog::log('menu_created', "Added menu item '{$title}'");
        } elseif ($type === 'categories') {
            $categoryIds = (array)($this->request->input('category_ids') ?? []);
            if (!empty($categoryIds)) {
                $categories = Database::fetchAll("SELECT * FROM `product_categories` WHERE `id` IN (" . implode(',', array_map('intval', $categoryIds)) . ")");
                foreach ($categories as $cat) {
                    $maxOrder++;
                    Database::insert('menu_items', [
                        'menu_group' => $group,
                        'parent_id' => $parentId,
                        'title' => $cat['name'],
                        'url' => '/category/' . $cat['slug'],
                        'target' => '_self',
                        'sort_order' => $maxOrder,
                        'is_active' => 1,
                    ]);
                }
                ActivityLog::log('menu_created', "Added " . count($categories) . " categories to menu");
            }
        } elseif ($type === 'pages') {
            $pageSlugs = (array)($this->request->input('page_slugs') ?? []);
            if (!empty($pageSlugs)) {
                foreach ($pageSlugs as $slug) {
                    $maxOrder++;
                    $page = Database::fetch("SELECT * FROM `pages` WHERE `slug` = :s LIMIT 1", ['s' => $slug]);
                    $title = $page ? $page['title'] : ucfirst(str_replace('-', ' ', $slug));
                    $url = ($slug === 'home') ? '/' : ('/' . ltrim($slug, '/'));

                    Database::insert('menu_items', [
                        'menu_group' => $group,
                        'parent_id' => $parentId,
                        'title' => $title,
                        'url' => $url,
                        'target' => '_self',
                        'sort_order' => $maxOrder,
                        'is_active' => 1,
                    ]);
                }
                ActivityLog::log('menu_created', "Added " . count($pageSlugs) . " pages to menu");
            }
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Menu item(s) added successfully.']);
            return;
        }

        flash('success', 'Menu item(s) added successfully.');
        $this->redirect('admin/menu?group=' . urlencode($group));
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => 'Session expired.'], 403);
                return;
            }
            flash('error', 'Token expired.');
            $this->redirect('admin/menu');
            return;
        }

        $item = MenuItem::find($id);
        if (!$item) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => 'Menu item not found.'], 404);
                return;
            }
            flash('error', 'Menu item not found.');
            $this->redirect('admin/menu');
            return;
        }

        $title = trim(sanitize($this->request->input('title') ?? $item['title']));
        $url = trim($this->request->input('url') ?? $item['url']);
        $target = $this->request->input('target') === '_blank' ? '_blank' : '_self';
        $isActive = ($this->request->input('is_active') == '1' || $this->request->input('is_active') === true) ? 1 : 0;
        $parentId = $this->request->input('parent_id');
        $parentId = ($parentId !== '' && $parentId !== null) ? (int)$parentId : null;
        if ($parentId === $id) {
            $parentId = null; // Prevent self-parenting
        }

        MenuItem::update($id, [
            'title' => $title,
            'url' => $url,
            'target' => $target,
            'is_active' => $isActive,
            'parent_id' => $parentId,
        ]);

        ActivityLog::log('menu_updated', "Updated menu item #{$id} ({$title})");

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Menu item updated successfully.']);
            return;
        }

        flash('success', 'Menu item updated successfully.');
        $this->redirect('admin/menu?group=' . urlencode($item['menu_group']));
    }

    public function delete(int $id): void {
        if (!$this->validateCsrf()) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => 'Session expired.'], 403);
                return;
            }
            flash('error', 'Token expired.');
            $this->redirect('admin/menu');
            return;
        }

        $item = MenuItem::find($id);
        if (!$item) {
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => 'Menu item not found.'], 404);
                return;
            }
            flash('error', 'Menu item not found.');
            $this->redirect('admin/menu');
            return;
        }

        // Delete children or re-attach to parent
        Database::query("DELETE FROM `menu_items` WHERE `parent_id` = :id", ['id' => $id]);
        MenuItem::delete($id);

        ActivityLog::log('menu_deleted', "Deleted menu item '{$item['title']}'");

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Menu item deleted successfully.']);
            return;
        }

        flash('success', 'Menu item deleted successfully.');
        $this->redirect('admin/menu?group=' . urlencode($item['menu_group']));
    }

    public function reorder(): void {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Session expired. Please refresh the page.'], 403);
            return;
        }

        // Retrieve items payload from JSON or POST
        $items = $this->request->input('items');
        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        if (!is_array($items)) {
            $this->json(['success' => false, 'message' => 'Invalid hierarchy data received.'], 422);
            return;
        }

        $success = MenuItem::updateHierarchy($items);

        if ($success) {
            ActivityLog::log('menu_reordered', 'Updated navigation menu order and hierarchy');
            $this->json(['success' => true, 'message' => 'Menu hierarchy and order saved successfully!']);
        } else {
            $this->json(['success' => false, 'message' => 'Database error while saving hierarchy.'], 500);
        }
    }

    public function reset(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/menu');
            return;
        }

        $group = sanitize($this->request->input('menu_group') ?: 'header');
        MenuItem::seedDefaultItems($group);

        ActivityLog::log('menu_reset', "Reset {$group} menu to system defaults");

        flash('success', 'Navigation menu reset to default items.');
        $this->redirect('admin/menu?group=' . urlencode($group));
    }
}
