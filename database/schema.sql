-- ====================================================================
-- Legacy Food Database Schema
-- Production-Ready MySQL E-commerce Schema
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. ROLES
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. PERMISSIONS
CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `module` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ROLE_PERMISSIONS
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `role_id` INT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ADMINS
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT UNSIGNED NOT NULL DEFAULT 1,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(25) NULL,
    `avatar` VARCHAR(255) NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `last_login_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. USERS (Customers)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `phone` VARCHAR(25) NULL,
    `password` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255) NULL,
    `status` ENUM('active', 'blocked') DEFAULT 'active',
    `email_verified_at` DATETIME NULL,
    `last_login_at` DATETIME NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. PASSWORD RESETS
CREATE TABLE IF NOT EXISTS `password_resets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(150) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `user_type` ENUM('user', 'admin') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` DATETIME NOT NULL,
    INDEX `idx_pw_email_token` (`email`, `token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. PRODUCT CATEGORIES
CREATE TABLE IF NOT EXISTS `product_categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `parent_id` INT UNSIGNED NULL,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `image` VARCHAR(255) NULL,
    `banner` VARCHAR(255) NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `meta_title` VARCHAR(200) NULL,
    `meta_description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL,
    INDEX `idx_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. PRODUCTS
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(180) NOT NULL UNIQUE,
    `sku` VARCHAR(50) NOT NULL UNIQUE,
    `short_description` VARCHAR(500) NULL,
    `description` LONGTEXT NULL,
    `benefits` TEXT NULL,
    `ingredients` TEXT NULL,
    `specifications` JSON NULL,
    `featured_image` VARCHAR(255) NULL,
    `base_price` DECIMAL(10,2) NOT NULL,
    `sale_price` DECIMAL(10,2) NULL,
    `cost_price` DECIMAL(10,2) NULL,
    `stock_quantity` INT NOT NULL DEFAULT 0,
    `low_stock_threshold` INT NOT NULL DEFAULT 5,
    `stock_status` ENUM('in_stock', 'out_of_stock', 'backorder') DEFAULT 'in_stock',
    `is_featured` TINYINT(1) DEFAULT 0,
    `is_bestseller` TINYINT(1) DEFAULT 0,
    `status` ENUM('published', 'draft', 'archived') DEFAULT 'published',
    `rating_cache` DECIMAL(3,2) DEFAULT 5.00,
    `review_count_cache` INT UNSIGNED DEFAULT 0,
    `accent_color` VARCHAR(20) DEFAULT '#bc944c',
    `meta_title` VARCHAR(200) NULL,
    `meta_description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE RESTRICT,
    INDEX `idx_products_slug` (`slug`),
    INDEX `idx_products_sku` (`sku`),
    INDEX `idx_products_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. PRODUCT ATTRIBUTES (e.g. Size / Volume, Packaging)
CREATE TABLE IF NOT EXISTS `product_attributes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. PRODUCT ATTRIBUTE VALUES (e.g. 250 ml, 500 ml, 1 L)
CREATE TABLE IF NOT EXISTS `product_attribute_values` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `attribute_id` INT UNSIGNED NOT NULL,
    `value` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. PRODUCT VARIANTS
CREATE TABLE IF NOT EXISTS `product_variants` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `sku` VARCHAR(60) NOT NULL UNIQUE,
    `price` DECIMAL(10,2) NOT NULL,
    `sale_price` DECIMAL(10,2) NULL,
    `stock_quantity` INT NOT NULL DEFAULT 50,
    `image` VARCHAR(255) NULL,
    `weight_grams` INT NULL,
    `is_default` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    INDEX `idx_variants_sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. PRODUCT IMAGES (Gallery)
CREATE TABLE IF NOT EXISTS `product_images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `variant_id` INT UNSIGNED NULL,
    `image_url` VARCHAR(255) NOT NULL,
    `alt_text` VARCHAR(150) NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_featured` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. COUPONS
CREATE TABLE IF NOT EXISTS `coupons` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `type` ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    `value` DECIMAL(10,2) NOT NULL,
    `description` VARCHAR(255) NULL,
    `min_order_amount` DECIMAL(10,2) DEFAULT 0.00,
    `max_discount_amount` DECIMAL(10,2) NULL,
    `usage_limit_total` INT NULL,
    `usage_limit_per_user` INT DEFAULT 1,
    `first_order_only` TINYINT(1) DEFAULT 0,
    `category_id` INT UNSIGNED NULL,
    `min_quantity` INT UNSIGNED DEFAULT 0,
    `usage_count` INT NOT NULL DEFAULT 0,
    `start_date` DATETIME NULL,
    `end_date` DATETIME NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_coupons_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. COUPON USAGES
CREATE TABLE IF NOT EXISTS `coupon_usages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `coupon_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `order_id` INT UNSIGNED NOT NULL,
    `discount_amount` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. SHIPPING ZONES
CREATE TABLE IF NOT EXISTS `shipping_zones` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `states` TEXT NULL, -- comma separated or JSON
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. SHIPPING METHODS
CREATE TABLE IF NOT EXISTS `shipping_methods` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `free_threshold` DECIMAL(10,2) DEFAULT 999.00,
    `estimated_days` VARCHAR(50) DEFAULT '3-5 business days',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. ORDERS
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED NULL,
    `guest_email` VARCHAR(150) NULL,
    `guest_phone` VARCHAR(25) NULL,
    `status` ENUM('pending', 'payment_confirmed', 'processing', 'packed', 'shipped', 'delivered', 'cancelled', 'refunded') NOT NULL DEFAULT 'pending',
    `payment_status` ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    `payment_method` ENUM('razorpay', 'cashfree', 'upi', 'cod', 'card', 'netbanking') NOT NULL DEFAULT 'cod',
    `transaction_id` VARCHAR(100) NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
    `coupon_code` VARCHAR(50) NULL,
    `shipping_amount` DECIMAL(10,2) DEFAULT 0.00,
    `tax_amount` DECIMAL(10,2) DEFAULT 0.00,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `shipping_courier` VARCHAR(100) NULL,
    `tracking_number` VARCHAR(100) NULL,
    `tracking_url` VARCHAR(255) NULL,
    `customer_notes` TEXT NULL,
    `admin_notes` TEXT NULL,
    `placed_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    INDEX `idx_orders_number` (`order_number`),
    INDEX `idx_orders_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. ORDER ITEMS
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `variant_id` INT UNSIGNED NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `variant_name` VARCHAR(100) NULL,
    `sku` VARCHAR(60) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `quantity` INT UNSIGNED NOT NULL,
    `total` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. ORDER ADDRESSES (Shipping & Billing)
CREATE TABLE IF NOT EXISTS `order_addresses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `type` ENUM('shipping', 'billing') NOT NULL DEFAULT 'shipping',
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(25) NOT NULL,
    `email` VARCHAR(150) NULL,
    `address_line1` VARCHAR(255) NOT NULL,
    `address_line2` VARCHAR(255) NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `postal_code` VARCHAR(20) NOT NULL,
    `country` VARCHAR(50) DEFAULT 'India',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. PAYMENTS
CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `payment_method` VARCHAR(50) NOT NULL,
    `gateway_order_id` VARCHAR(100) NULL,
    `gateway_payment_id` VARCHAR(100) NULL,
    `gateway_signature` VARCHAR(255) NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `currency` VARCHAR(10) DEFAULT 'INR',
    `status` ENUM('initiated', 'captured', 'failed', 'refunded') NOT NULL DEFAULT 'initiated',
    `response_payload` JSON NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. INVOICES
CREATE TABLE IF NOT EXISTS `invoices` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL UNIQUE,
    `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
    `invoice_date` DATE NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `discount` DECIMAL(10,2) DEFAULT 0.00,
    `shipping` DECIMAL(10,2) DEFAULT 0.00,
    `tax` DECIMAL(10,2) DEFAULT 0.00,
    `total` DECIMAL(10,2) NOT NULL,
    `pdf_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    INDEX `idx_invoices_number` (`invoice_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. CART ITEMS (For logged-in users / session sync)
CREATE TABLE IF NOT EXISTS `cart_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `session_id` VARCHAR(100) NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `variant_id` INT UNSIGNED NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
    INDEX `idx_cart_session` (`session_id`),
    INDEX `idx_cart_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 23. WISHLISTS
CREATE TABLE IF NOT EXISTS `wishlists` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 24. WISHLIST ITEMS
CREATE TABLE IF NOT EXISTS `wishlist_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `wishlist_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_wishlist_product` (`wishlist_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 25. REVIEWS
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(150) NULL,
    `rating` TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `title` VARCHAR(150) NULL,
    `review_text` TEXT NOT NULL,
    `is_verified_purchase` TINYINT(1) DEFAULT 0,
    `status` ENUM('approved', 'pending', 'rejected') DEFAULT 'approved',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 26. BANNERS
CREATE TABLE IF NOT EXISTS `banners` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `subtitle` VARCHAR(255) NULL,
    `image` VARCHAR(255) NOT NULL,
    `mobile_image` VARCHAR(255) NULL,
    `cta_text` VARCHAR(50) NULL,
    `cta_url` VARCHAR(255) NULL,
    `type` ENUM('hero', 'promotional', 'category', 'popup') DEFAULT 'hero',
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 27. PAGES (CMS)
CREATE TABLE IF NOT EXISTS `pages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `content` LONGTEXT NOT NULL,
    `meta_title` VARCHAR(200) NULL,
    `meta_description` TEXT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 28. FAQS
CREATE TABLE IF NOT EXISTS `faqs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(50) DEFAULT 'General',
    `question` VARCHAR(255) NOT NULL,
    `answer` TEXT NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 29. SETTINGS
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `group_name` VARCHAR(50) NOT NULL DEFAULT 'general',
    `key_name` VARCHAR(100) NOT NULL UNIQUE,
    `value` LONGTEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_settings_key` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 30. EMAIL TEMPLATES
CREATE TABLE IF NOT EXISTS `email_templates` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `body` LONGTEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 31. INVENTORY & TRANSACTIONS
CREATE TABLE IF NOT EXISTS `inventory` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `variant_id` INT UNSIGNED NULL,
    `quantity` INT NOT NULL DEFAULT 0,
    `low_stock_alert` INT NOT NULL DEFAULT 5,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `inventory_transactions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `variant_id` INT UNSIGNED NULL,
    `order_id` INT UNSIGNED NULL,
    `type` ENUM('purchase', 'sale', 'adjustment', 'return') NOT NULL,
    `quantity_change` INT NOT NULL,
    `quantity_before` INT NOT NULL,
    `quantity_after` INT NOT NULL,
    `reason` VARCHAR(255) NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 32. NOTIFICATIONS
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `admin_id` INT UNSIGNED NULL,
    `title` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `type` VARCHAR(50) DEFAULT 'info',
    `link` VARCHAR(255) NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 33. CONTACT MESSAGES
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(25) NULL,
    `subject` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 34. ACTIVITY LOGS
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `admin_id` INT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 35. USER ADDRESSES
CREATE TABLE IF NOT EXISTS `user_addresses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `type` ENUM('shipping', 'billing', 'both') DEFAULT 'both',
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(25) NOT NULL,
    `address_line1` VARCHAR(255) NOT NULL,
    `address_line2` VARCHAR(255) NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `postal_code` VARCHAR(20) NOT NULL,
    `country` VARCHAR(50) DEFAULT 'India',
    `is_default` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 36. TESTIMONIALS
CREATE TABLE IF NOT EXISTS `testimonials` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(100) NOT NULL,
    `location` VARCHAR(100) NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `review` TEXT NOT NULL,
    `avatar` VARCHAR(255) NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 37. NEWSLETTER SUBSCRIBERS
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `status` ENUM('subscribed', 'unsubscribed') DEFAULT 'subscribed',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
