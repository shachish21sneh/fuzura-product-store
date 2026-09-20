-- Fuzura Product Store & Warranty Management System
-- Database Dump for glittl3q_warranty
-- Generated: 2026-09-20 14:07:00

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admins` VALUES 
(1, 'Fuzura Super Admin', 'admin@fuzura.com', '$2y$12$4mxFFyR1OIWvBzhS9kmwl.cTU2S30ZDPzzol.tn/yuZ6w94jcKYTK', 'admin', '+1 (800) 555-0199', NULL, NULL, '2026-09-20 13:43:38', '2026-09-20 13:57:21');

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`),
  KEY `customers_mobile_index` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customers` VALUES 
(1, 'John Doe', 'john.doe@example.com', '+1-555-0144', '742 Evergreen Terrace, Springfield, IL', '$2y$12$keP1JJKNYuj/rZ3az.EIL.E44oyJkphx.u9pbIiQG0CwF3raoxT0G', 'active', NULL, NULL, '2026-09-20 13:43:38', '2026-09-20 13:57:22'),
(2, 'Jane Smith', 'jane.smith@example.com', '+1-555-0288', '1007 Mountain View Way, Austin, TX', '$2y$12$86XlMgCIP3xeWvqQJ.sKzOW.JCOi9UhY5.BhvCBc2DuqlV65bY9fS', 'active', NULL, NULL, '2026-09-20 13:43:38', '2026-09-20 13:57:22'),
(3, 'Michael Brown', 'michael.brown@example.com', '+1-555-0377', '42 Ocean Drive, Miami, FL', '$2y$12$bvxWXW91d4VXgY6vdpQ9VOnoVxvBf86wnhH5O6dghDasctpRGpgyO', 'active', NULL, NULL, '2026-09-20 13:43:39', '2026-09-20 13:57:22');

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_number` varchar(255) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `manufacturing_date` date NOT NULL,
  `warranty_period_years` tinyint unsigned NOT NULL DEFAULT '1',
  `spare_details` text DEFAULT NULL,
  `spare_vendor` varchar(255) DEFAULT NULL,
  `status` enum('available','sold','replaced','scrapped') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_serial_number_unique` (`serial_number`),
  KEY `products_model_number_index` (`model_number`),
  KEY `products_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` VALUES 
(1, 'FZ-PRO-500', 'FZ-SN-1001', '2025-01-20 00:00:00', 3, 'Thermal sensor, Copper heatsink, 12V Fan', 'Precision Tech Ltd', 'replaced', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(2, 'FZ-PRO-500', 'FZ-SN-1002', '2025-07-20 00:00:00', 3, 'Thermal sensor, Copper heatsink, 12V Fan', 'Precision Tech Ltd', 'replaced', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(3, 'FZ-PRO-500', 'FZ-SN-1003', '2026-01-20 00:00:00', 3, 'Thermal sensor, Copper heatsink, 12V Fan', 'Precision Tech Ltd', 'replaced', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(4, 'FZ-PRO-500', 'FZ-SN-1004', '2026-06-20 00:00:00', 3, 'Thermal sensor, Copper heatsink, 12V Fan', 'Precision Tech Ltd', 'sold', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(5, 'FZ-PRO-500', 'FZ-SN-1005', '2026-07-20 00:00:00', 3, 'Thermal sensor, Copper heatsink, 12V Fan', 'Precision Tech Ltd', 'available', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(6, 'FZ-PRO-500', 'FZ-SN-1006', '2026-08-20 00:00:00', 3, 'Thermal sensor, Copper heatsink, 12V Fan', 'Precision Tech Ltd', 'available', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(7, 'FZ-ECO-200', 'FZ-SN-2001', '2026-02-20 00:00:00', 2, 'Micro controller, Power relay 5V', 'Acro Micro Systems', 'sold', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(8, 'FZ-ECO-200', 'FZ-SN-2002', '2026-05-20 00:00:00', 2, 'Micro controller, Power relay 5V', 'Acro Micro Systems', 'sold', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(9, 'FZ-ECO-200', 'FZ-SN-2003', '2026-07-20 00:00:00', 2, 'Micro controller, Power relay 5V', 'Acro Micro Systems', 'available', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(10, 'FZ-ECO-200', 'FZ-SN-2004', '2026-08-20 00:00:00', 2, 'Micro controller, Power relay 5V', 'Acro Micro Systems', 'available', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(11, 'FZ-MAX-900', 'FZ-SN-3001', '2020-09-20 00:00:00', 5, 'Heavy duty transformer, Dual MOSFET', 'Titan Industrial Corp', 'sold', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(12, 'FZ-MAX-900', 'FZ-SN-3002', '2026-08-31 00:00:00', 5, 'Heavy duty transformer, Dual MOSFET', 'Titan Industrial Corp', 'sold', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(13, 'FZ-MAX-900', 'FZ-SN-3003', '2026-09-05 00:00:00', 5, 'Heavy duty transformer, Dual MOSFET', 'Titan Industrial Corp', 'available', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(14, 'FZ-MAX-900', 'FZ-SN-3004', '2026-09-10 00:00:00', 5, 'Heavy duty transformer, Dual MOSFET', 'Titan Industrial Corp', 'available', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(15, 'FZ-MAX-900', 'FZ-SN-3005', '2026-09-15 00:00:00', 5, 'Heavy duty transformer, Dual MOSFET', 'Titan Industrial Corp', 'available', '2026-09-20 13:43:39', '2026-09-20 13:43:39');

DROP TABLE IF EXISTS `product_sales`;
CREATE TABLE IF NOT EXISTS `product_sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `product_model` varchar(255) NOT NULL,
  `product_serial_number` varchar(255) NOT NULL,
  `bill_date` date NOT NULL,
  `dealer_name` varchar(255) NOT NULL,
  `dealer_address` text DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_mobile` varchar(255) NOT NULL,
  `customer_address` text DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_sales_product_serial_number_unique` (`product_serial_number`),
  KEY `product_sales_customer_mobile_index` (`customer_mobile`),
  CONSTRAINT `product_sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `product_sales` VALUES 
(1, 1, 'FZ-PRO-500', 'FZ-SN-1001', '2025-03-20 00:00:00', 'Apex Electronics Tech', 'Suite 400, Silicon Galleria, Chicago, IL', 'John Doe', '+1-555-0144', '742 Evergreen Terrace, Springfield, IL', 'INV-2025-8810', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(2, 7, 'FZ-ECO-200', 'FZ-SN-2001', '2026-03-20 00:00:00', 'Metro Digital Mart', '420 Commercial Ave, Austin, TX', 'Jane Smith', '+1-555-0288', '1007 Mountain View Way, Austin, TX', 'INV-2026-1044', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(3, 8, 'FZ-ECO-200', 'FZ-SN-2002', '2026-07-20 00:00:00', 'Global Supplies Inc', '88 Biscayne Blvd, Miami, FL', 'Michael Brown', '+1-555-0377', '42 Ocean Drive, Miami, FL', 'INV-2026-3021', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(4, 11, 'FZ-MAX-900', 'FZ-SN-3001', '2020-09-20 00:00:00', 'Summit Hardware Hub', '12 Central Plaza, Denver, CO', 'John Doe', '+1-555-0144', '742 Evergreen Terrace, Springfield, IL', 'INV-2020-0092', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(5, 12, 'FZ-MAX-900', 'FZ-SN-3002', '2026-09-10 00:00:00', 'Nexus Retailers', '500 Tech Parkway, Dallas, TX', 'Jane Smith', '+1-555-0288', '1007 Mountain View Way, Austin, TX', 'INV-2026-9901', '2026-09-20 13:43:39', '2026-09-20 13:43:39');

DROP TABLE IF EXISTS `product_replacements`;
CREATE TABLE IF NOT EXISTS `product_replacements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `old_product_id` bigint unsigned NOT NULL,
  `old_serial_number` varchar(255) NOT NULL,
  `new_product_id` bigint unsigned NOT NULL,
  `new_serial_number` varchar(255) NOT NULL,
  `dealer_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `replacement_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_replacements_old_serial_number_index` (`old_serial_number`),
  KEY `product_replacements_new_serial_number_index` (`new_serial_number`),
  CONSTRAINT `product_replacements_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `product_sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_replacements_old_product_id_foreign` FOREIGN KEY (`old_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_replacements_new_product_id_foreign` FOREIGN KEY (`new_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `product_replacements` VALUES 
(1, 1, 1, 'FZ-SN-1001', 2, 'FZ-SN-1002', 'Apex Electronics Tech', 'John Doe', '2025-09-20 00:00:00', 'Internal PCB diode failure during power spike. Replaced under standard warranty.', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(2, 1, 2, 'FZ-SN-1002', 3, 'FZ-SN-1003', 'Apex Electronics Tech', 'John Doe', '2026-03-20 00:00:00', 'Control unit display backlight failed. Replacement approved.', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(3, 1, 3, 'FZ-SN-1003', 4, 'FZ-SN-1004', 'Apex Electronics Tech', 'John Doe', '2026-08-20 00:00:00', 'Customer reported connector loose pin. Upgraded to latest batch FZ-SN-1004 unit.', '2026-09-20 13:43:39', '2026-09-20 13:43:39');

DROP TABLE IF EXISTS `warranty_registrations`;
CREATE TABLE IF NOT EXISTS `warranty_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_mobile` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_address` text DEFAULT NULL,
  `product_model` varchar(255) NOT NULL,
  `product_serial_number` varchar(255) NOT NULL,
  `dealer_name` varchar(255) NOT NULL,
  `dealer_address` text DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `bill_path` varchar(255) DEFAULT NULL,
  `warranty_start_date` date NOT NULL,
  `warranty_end_date` date NOT NULL,
  `status` enum('approved','pending','rejected') NOT NULL DEFAULT 'approved',
  `admin_remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warranty_registrations_product_serial_number_unique` (`product_serial_number`),
  KEY `warranty_registrations_customer_mobile_index` (`customer_mobile`),
  KEY `warranty_registrations_warranty_end_date_index` (`warranty_end_date`),
  KEY `warranty_registrations_status_index` (`status`),
  CONSTRAINT `warranty_registrations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `warranty_registrations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `warranty_registrations` VALUES 
(1, 1, 1, 'John Doe', '+1-555-0144', 'john.doe@example.com', '742 Evergreen Terrace, Springfield, IL', 'FZ-PRO-500', 'FZ-SN-1001', 'Apex Electronics Tech', 'Suite 400, Silicon Galleria, Chicago, IL', '2025-03-20 00:00:00', 'bills/sample_bill.png', '2025-03-20 00:00:00', '2028-03-20 00:00:00', 'approved', 'Verified and active. Includes replacements up to current active unit FZ-SN-1004.', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(2, 2, 7, 'Jane Smith', '+1-555-0288', 'jane.smith@example.com', '1007 Mountain View Way, Austin, TX', 'FZ-ECO-200', 'FZ-SN-2001', 'Metro Digital Mart', '420 Commercial Ave, Austin, TX', '2026-03-20 00:00:00', 'bills/sample_bill.png', '2026-03-20 00:00:00', '2028-03-20 00:00:00', 'approved', 'Invoice verified against Metro Digital Mart sales record.', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(3, 3, 8, 'Michael Brown', '+1-555-0377', 'michael.brown@example.com', '42 Ocean Drive, Miami, FL', 'FZ-ECO-200', 'FZ-SN-2002', 'Global Supplies Inc', '88 Biscayne Blvd, Miami, FL', '2026-07-20 00:00:00', 'bills/sample_bill.png', '2026-07-20 00:00:00', '2028-07-20 00:00:00', 'approved', 'Approved automatically upon submission.', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(4, 1, 11, 'John Doe', '+1-555-0144', 'john.doe@example.com', '742 Evergreen Terrace, Springfield, IL', 'FZ-MAX-900', 'FZ-SN-3001', 'Summit Hardware Hub', '12 Central Plaza, Denver, CO', '2020-09-20 00:00:00', 'bills/sample_bill.png', '2020-09-20 00:00:00', '2025-09-20 00:00:00', 'approved', 'Warranty expired on schedule.', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(5, 2, 12, 'Jane Smith', '+1-555-0288', 'jane.smith@example.com', '1007 Mountain View Way, Austin, TX', 'FZ-MAX-900', 'FZ-SN-3002', 'Nexus Retailers', '500 Tech Parkway, Dallas, TX', '2026-09-10 00:00:00', 'bills/sample_bill.png', '2026-09-10 00:00:00', '2031-09-10 00:00:00', 'pending', 'Awaiting manual invoice stamp verification.', '2026-09-20 13:43:39', '2026-09-20 13:43:39');

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `properties` json DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_logs` VALUES 
(1, 'App\\Models\\Admin', 1, 'SYSTEM_INIT', 'System database seeded with demo catalog and initial multi-level replacement timeline.', NULL, '127.0.0.1', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(2, 'App\\Models\\Admin', 1, 'RECORD_SALE', 'Sale recorded for FZ-SN-1001 to John Doe', NULL, '127.0.0.1', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(3, 'App\\Models\\Admin', 1, 'PRODUCT_REPLACED', 'Replaced FZ-SN-1003 with FZ-SN-1004 for sale #1', NULL, '127.0.0.1', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(4, 'App\\Models\\Customer', 1, 'WARRANTY_REGISTERED', 'Warranty registered for serial FZ-SN-1001 by john.doe@example.com', NULL, '127.0.0.1', '2026-09-20 13:43:39', '2026-09-20 13:43:39'),
(5, 'App\\Models\\Admin', 1, 'SYSTEM_INIT', 'System database seeded with demo catalog and initial multi-level replacement timeline.', NULL, '127.0.0.1', '2026-09-20 13:57:22', '2026-09-20 13:57:22'),
(6, 'App\\Models\\Admin', 1, 'RECORD_SALE', 'Sale recorded for FZ-SN-1001 to John Doe', NULL, '127.0.0.1', '2026-09-20 13:57:22', '2026-09-20 13:57:22'),
(7, 'App\\Models\\Admin', 1, 'PRODUCT_REPLACED', 'Replaced FZ-SN-1003 with FZ-SN-1004 for sale #1', NULL, '127.0.0.1', '2026-09-20 13:57:22', '2026-09-20 13:57:22'),
(8, 'App\\Models\\Customer', 1, 'WARRANTY_REGISTERED', 'Warranty registered for serial FZ-SN-1001 by john.doe@example.com', NULL, '127.0.0.1', '2026-09-20 13:57:22', '2026-09-20 13:57:22');

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES 
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_20_000001_create_admins_table', 1),
(5, '2026_09_20_000002_create_customers_table', 1),
(6, '2026_09_20_000003_create_products_table', 1),
(7, '2026_09_20_000004_create_product_sales_table', 1),
(8, '2026_09_20_000005_create_product_replacements_table', 1),
(9, '2026_09_20_000006_create_warranty_registrations_table', 1),
(10, '2026_09_20_000007_create_activity_logs_table', 1);

SET FOREIGN_KEY_CHECKS=1;
