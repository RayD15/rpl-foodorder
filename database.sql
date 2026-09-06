-- ============================================================
-- Pesen Dong! — KDI Deploy SQL
-- Generated: 2026-09-06
-- Import via phpMyAdmin → pilih DB → tab Import
-- ============================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

-- ----------------------------
-- categories
-- ----------------------------
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Makanan', 'makanan', NOW(), NOW()),
(2, 'Minuman', 'minuman', NOW(), NOW());

-- ----------------------------
-- admins
-- ----------------------------
CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Varo',    'varo@admin.com',    '$2y$12$842Jy0KcaxxoN6H3VQW88.P2ib2iK0abejxZQDSAubDG6rOM.OvzW', NOW(), NOW()),
(2, 'Rayhand', 'rayhand@admin.com', '$2y$12$842Jy0KcaxxoN6H3VQW88.P2ib2iK0abejxZQDSAubDG6rOM.OvzW', NOW(), NOW()),
(3, 'Zaky',    'zaky@admin.com',    '$2y$12$842Jy0KcaxxoN6H3VQW88.P2ib2iK0abejxZQDSAubDG6rOM.OvzW', NOW(), NOW());

-- ----------------------------
-- settings
-- ----------------------------
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'whatsapp_number', '6281234567890', NOW(), NOW());

-- ----------------------------
-- products (schema final setelah semua migration)
-- ----------------------------
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ready',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Churros',      'Churros crispy dengan rasa manis dan lezat. 1 porsi berisi 3 pcs.', 5000,  'churros.jpg',  'ready', NOW(), NOW()),
(2, 2, 'Matcha',       'Minuman matcha premium dengan rasa creamy dan menyegarkan.',        12000, 'matca.jpg',    'ready', NOW(), NOW()),
(3, 1, 'Basreng',      'Basreng pedas gurih, renyah dan bikin ketagihan.',                  10000, 'basreng.jpg',  'ready', NOW(), NOW()),
(4, 2, 'Caramel Latte','Latte dengan sensasi manis karamel yang lembut.',                   15000, 'caramel.jpg',  'ready', NOW(), NOW()),
(5, 1, 'Risol Mayo',   'Risol dengan isian mayo creamy yang gurih.',                        8000,  'risol.jpg',    'ready', NOW(), NOW()),
(6, 2, 'Es Teh Manis', 'Teh manis dingin yang menyegarkan.',                               4000,  'es teh.png',   'ready', NOW(), NOW());

-- ----------------------------
-- bundles
-- ----------------------------
CREATE TABLE `bundles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ready',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bundles` (`id`, `name`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Paket Ngemil',   'Churros + Basreng untuk teman ngemil.',             13000, 'paket-ngemil.jpg',   'ready', NOW(), NOW()),
(2, 'Paket Minum',    'Matcha + Es Teh Manis, segar-segar.',               14000, 'paket-minum.jpg',    'ready', NOW(), NOW()),
(3, 'Paket Lengkap',  'Risol + Churros + Es Teh, lengkap untuk kenyang.',  15000, 'paket-lengkap.jpg',  'ready', NOW(), NOW());

-- ----------------------------
-- bundle_items
-- ----------------------------
CREATE TABLE `bundle_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bundle_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `bundle_items_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bundle_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bundle_items` (`bundle_id`, `product_id`, `qty`, `created_at`, `updated_at`) VALUES
-- Paket Ngemil: Churros + Basreng
(1, 1, 1, NOW(), NOW()),
(1, 3, 1, NOW(), NOW()),
-- Paket Minum: Matcha + Es Teh Manis
(2, 2, 1, NOW(), NOW()),
(2, 6, 1, NOW(), NOW()),
-- Paket Lengkap: Risol + Churros + Es Teh Manis
(3, 5, 1, NOW(), NOW()),
(3, 1, 1, NOW(), NOW()),
(3, 6, 1, NOW(), NOW());

-- ----------------------------
-- migrations (wajib ada agar Laravel tidak re-run migration)
-- ----------------------------
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_sessions_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('0002_01_01_000000_create_categories_table', 1),
('0002_01_01_000001_create_products_table', 1),
('0002_01_01_000002_create_admins_table', 1),
('0002_01_01_000003_create_settings_table', 1),
('0002_01_01_000004_change_products_status', 1),
('0002_01_01_000005_drop_is_popular_from_products', 1),
('0002_01_01_000006_create_bundles_table', 1);

-- ----------------------------
-- sessions & cache (kosong, dibuat otomatis oleh Laravel)
-- ----------------------------
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
