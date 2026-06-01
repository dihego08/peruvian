-- Menú del nuevo frontend (React). Ejecutar si no usa artisan migrate.
-- Compatible MySQL / MariaDB.

CREATE TABLE IF NOT EXISTS `app_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned NOT NULL DEFAULT 0,
  `label` varchar(120) NOT NULL,
  `route` varchar(255) DEFAULT NULL COMMENT 'Ruta React; NULL = agrupador',
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT 0,
  `module_key` varchar(80) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_menus_module_key_unique` (`module_key`),
  KEY `app_menus_parent_sort_idx` (`parent_id`,`sort_order`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_menu_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `app_menu_id` bigint unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_menu_user_unique` (`app_menu_id`,`user_id`),
  KEY `app_menu_user_user_idx` (`user_id`),
  CONSTRAINT `app_menu_user_menu_fk` FOREIGN KEY (`app_menu_id`) REFERENCES `app_menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tras crear tablas, ejecutar en el servidor:
--   php artisan db:seed --class=AppMenuSeeder
