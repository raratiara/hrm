-- =====================================================
-- SALARY SETTING MODULE - DATABASE SETUP
-- Run this SQL in phpMyAdmin on database `hrm`
-- =====================================================

-- 1. SALARY COMPONENTS TABLE
CREATE TABLE IF NOT EXISTS `salary_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `type` enum('earning','deduction') NOT NULL DEFAULT 'earning',
  `default_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_fixed` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. SALARY BPJS TABLE
CREATE TABLE IF NOT EXISTS `salary_bpjs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpjs_type` varchar(150) NOT NULL,
  `employee_percentage` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `employer_percentage` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `salary_cap` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_ded` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. TAX PTKP TABLE
CREATE TABLE IF NOT EXISTS `tax_ptkp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_code` varchar(20) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `effective_year` varchar(10) DEFAULT NULL,
  `marital_status_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. TAX BRACKETS TABLE
CREATE TABLE IF NOT EXISTS `tax_brackets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `min_income` decimal(15,2) NOT NULL DEFAULT 0.00,
  `max_income` decimal(15,2) NOT NULL DEFAULT 0.00,
  `rate` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `effective_year` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. TAX TER TABLE
CREATE TABLE IF NOT EXISTS `tax_ter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL,
  `min_bruto` decimal(15,2) NOT NULL DEFAULT 0.00,
  `max_bruto` decimal(15,2) NOT NULL DEFAULT 0.00,
  `rate` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `effective_year` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. TAX TER CATEGORY MAPPING TABLE
CREATE TABLE IF NOT EXISTS `tax_ter_category_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_code` varchar(20) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `effective_year` varchar(10) DEFAULT NULL,
  `marital_status_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =====================================================
-- MENU REGISTRATION
-- Adjust the IDs based on your existing data
-- Check MAX(id) from user_menu first!
-- =====================================================

-- Insert Parent Menu
INSERT INTO `user_menu` (`title`, `module_name`, `url`, `parent_id`, `is_parent`, `show_menu`, `um_class`) 
VALUES ('Salary Setting', 'salary_setting', '#', '0', '1', '1', 'fa-money');

-- Get the parent ID (replace @parent_id with the actual ID after insert)
SET @parent_id = LAST_INSERT_ID();

-- Insert Child Menus
INSERT INTO `user_menu` (`title`, `module_name`, `url`, `parent_id`, `is_parent`, `show_menu`, `um_class`) VALUES
('Salary Components', 'salary_components_menu', 'salary_setting/salary_components_menu', @parent_id, '0', '1', 'fa-money'),
('BPJS Configuration', 'bpjs_configuration_menu', 'salary_setting/bpjs_configuration_menu', @parent_id, '0', '1', 'fa-medkit'),
('PTKP', 'ptkp_menu', 'salary_setting/ptkp_menu', @parent_id, '0', '1', 'fa-calculator'),
('Tax Brackets', 'tax_brackets_menu', 'salary_setting/tax_brackets_menu', @parent_id, '0', '1', 'fa-percent'),
('Tax Rates', 'tax_rates_menu', 'salary_setting/tax_rates_menu', @parent_id, '0', '1', 'fa-balance-scale'),
('TER Mappings', 'ter_mappings_menu', 'salary_setting/ter_mappings_menu', @parent_id, '0', '1', 'fa-map');


-- =====================================================
-- ACCESS ROLE REGISTRATION
-- This gives full access to role_id = 1 (Super Admin)
-- Adjust role_id as needed
-- =====================================================

INSERT INTO `user_akses_role` (`role_id`, `module_name`, `view`, `add`, `edit`, `del`, `detail`, `import`, `eksport`) VALUES
(1, 'salary_components_menu', '1', '1', '1', '1', '1', '1', '1'),
(1, 'bpjs_configuration_menu', '1', '1', '1', '1', '1', '1', '1'),
(1, 'ptkp_menu', '1', '1', '1', '1', '1', '1', '1'),
(1, 'tax_brackets_menu', '1', '1', '1', '1', '1', '1', '1'),
(1, 'tax_rates_menu', '1', '1', '1', '1', '1', '1', '1'),
(1, 'ter_mappings_menu', '1', '1', '1', '1', '1', '1', '1');


-- =====================================================
-- SAMPLE DATA (Optional)
-- =====================================================

-- Sample Salary Components
INSERT INTO `salary_components` (`name`, `type`, `default_amount`, `is_fixed`, `is_active`, `sort_order`, `description`, `created_at`) VALUES
('Gaji Pokok', 'earning', 0.00, 1, 1, 1, 'Gaji pokok karyawan', NOW()),
('Tunjangan Transport', 'earning', 0.00, 1, 1, 2, 'Tunjangan transportasi', NOW()),
('Tunjangan Makan', 'earning', 0.00, 1, 1, 3, 'Tunjangan makan', NOW()),
('Lembur', 'earning', 0.00, 0, 1, 4, 'Upah lembur', NOW()),
('BPJS Kesehatan (Employee)', 'deduction', 0.00, 1, 1, 10, 'Potongan BPJS Kesehatan karyawan', NOW()),
('BPJS TK JHT (Employee)', 'deduction', 0.00, 1, 1, 11, 'Potongan BPJS TK JHT karyawan', NOW()),
('BPJS TK JP (Employee)', 'deduction', 0.00, 1, 1, 12, 'Potongan BPJS TK JP karyawan', NOW()),
('PPh 21', 'deduction', 0.00, 0, 1, 20, 'Potongan pajak penghasilan', NOW());

-- Sample BPJS Configuration
INSERT INTO `salary_bpjs` (`bpjs_type`, `employee_percentage`, `employer_percentage`, `salary_cap`, `tax_ded`, `created_at`) VALUES
('BPJS Kesehatan', 1.0000, 4.0000, 12000000.00, 0.00, NOW()),
('BPJS TK - JHT', 2.0000, 3.7000, 0.00, 0.00, NOW()),
('BPJS TK - JKK', 0.0000, 0.2400, 0.00, 0.00, NOW()),
('BPJS TK - JKM', 0.0000, 0.3000, 0.00, 0.00, NOW()),
('BPJS TK - JP', 1.0000, 2.0000, 10042300.00, 0.00, NOW());

-- Sample Tax PTKP (2024)
INSERT INTO `tax_ptkp` (`status_code`, `description`, `amount`, `effective_year`, `marital_status_id`, `created_at`) VALUES
('TK/0', 'Tidak Kawin Tanpa Tanggungan', 54000000.00, '2024', NULL, NOW()),
('TK/1', 'Tidak Kawin 1 Tanggungan', 58500000.00, '2024', NULL, NOW()),
('TK/2', 'Tidak Kawin 2 Tanggungan', 63000000.00, '2024', NULL, NOW()),
('TK/3', 'Tidak Kawin 3 Tanggungan', 67500000.00, '2024', NULL, NOW()),
('K/0', 'Kawin Tanpa Tanggungan', 58500000.00, '2024', NULL, NOW()),
('K/1', 'Kawin 1 Tanggungan', 63000000.00, '2024', NULL, NOW()),
('K/2', 'Kawin 2 Tanggungan', 67500000.00, '2024', NULL, NOW()),
('K/3', 'Kawin 3 Tanggungan', 72000000.00, '2024', NULL, NOW());

-- Sample Tax Brackets (PPh 21 - 2024)
INSERT INTO `tax_brackets` (`min_income`, `max_income`, `rate`, `effective_year`, `created_at`) VALUES
(0.00, 60000000.00, 5.0000, '2024', NOW()),
(60000000.00, 250000000.00, 15.0000, '2024', NOW()),
(250000000.00, 500000000.00, 25.0000, '2024', NOW()),
(500000000.00, 5000000000.00, 30.0000, '2024', NOW()),
(5000000000.00, 99999999999.00, 35.0000, '2024', NOW());

-- Sample Tax TER
INSERT INTO `tax_ter` (`category`, `min_bruto`, `max_bruto`, `rate`, `effective_year`, `created_at`) VALUES
('A', 0.00, 5400000.00, 0.0000, '2024', NOW()),
('A', 5400000.00, 5650000.00, 0.2500, '2024', NOW()),
('A', 5650000.00, 5950000.00, 0.5000, '2024', NOW()),
('B', 0.00, 6200000.00, 0.0000, '2024', NOW()),
('B', 6200000.00, 6500000.00, 0.2500, '2024', NOW()),
('C', 0.00, 6600000.00, 0.0000, '2024', NOW()),
('C', 6600000.00, 6950000.00, 0.2500, '2024', NOW());

-- Sample Tax TER Category Mapping
INSERT INTO `tax_ter_category_mapping` (`status_code`, `category`, `description`, `effective_year`, `marital_status_id`, `created_at`) VALUES
('TK/0', 'A', 'Tidak Kawin Tanpa Tanggungan', '2024', NULL, NOW()),
('TK/1', 'A', 'Tidak Kawin 1 Tanggungan', '2024', NULL, NOW()),
('K/0', 'B', 'Kawin Tanpa Tanggungan', '2024', NULL, NOW()),
('K/1', 'B', 'Kawin 1 Tanggungan', '2024', NULL, NOW()),
('K/2', 'B', 'Kawin 2 Tanggungan', '2024', NULL, NOW()),
('K/3', 'C', 'Kawin 3 Tanggungan', '2024', NULL, NOW());
