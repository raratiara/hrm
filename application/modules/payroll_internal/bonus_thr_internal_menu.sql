CREATE TABLE IF NOT EXISTS `bonus_internal` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`periode_bulan` int(11) NOT NULL,
	`periode_tahun` varchar(4) NOT NULL,
	`total_bonus` decimal(18,2) NOT NULL DEFAULT 0.00,
	`notes` text DEFAULT NULL,
	`created_at` datetime DEFAULT NULL,
	`created_by` int(11) DEFAULT NULL,
	`updated_at` datetime DEFAULT NULL,
	`updated_by` int(11) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_bonus_internal_period` (`periode_bulan`, `periode_tahun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `bonus_internal` ADD COLUMN IF NOT EXISTS `total_bonus` decimal(18,2) NOT NULL DEFAULT 0.00 AFTER `periode_tahun`;

CREATE TABLE IF NOT EXISTS `bonus_internal_detail` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`bonus_internal_id` int(11) NOT NULL,
	`employee_id` int(11) NOT NULL,
	`bonus_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
	`note` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_bonus_internal_detail_employee` (`bonus_internal_id`, `employee_id`),
	KEY `idx_bonus_internal_detail_employee` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `thr_internal` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`periode_bulan` int(11) NOT NULL,
	`periode_tahun` varchar(4) NOT NULL,
	`total_thr` decimal(18,2) NOT NULL DEFAULT 0.00,
	`notes` text DEFAULT NULL,
	`created_at` datetime DEFAULT NULL,
	`created_by` int(11) DEFAULT NULL,
	`updated_at` datetime DEFAULT NULL,
	`updated_by` int(11) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_thr_internal_period` (`periode_bulan`, `periode_tahun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `thr_internal` ADD COLUMN IF NOT EXISTS `total_thr` decimal(18,2) NOT NULL DEFAULT 0.00 AFTER `periode_tahun`;

UPDATE `bonus_internal` h
SET h.`total_bonus` = (
	SELECT COALESCE(SUM(d.`bonus_amount`), 0)
	FROM `bonus_internal_detail` d
	WHERE d.`bonus_internal_id` = h.`id`
);

UPDATE `thr_internal` h
SET h.`total_thr` = (
	SELECT COALESCE(SUM(d.`thr_amount`), 0)
	FROM `thr_internal_detail` d
	WHERE d.`thr_internal_id` = h.`id`
);

CREATE TABLE IF NOT EXISTS `thr_internal_detail` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`thr_internal_id` int(11) NOT NULL,
	`employee_id` int(11) NOT NULL,
	`thr_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
	`note` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_thr_internal_detail_employee` (`thr_internal_id`, `employee_id`),
	KEY `idx_thr_internal_detail_employee` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `user_menu` (`title`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `menu_position_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`, `date_insert`)
SELECT 'Bonus', 'uri', NULL, 'bonus_int_menu', 'payroll_internal/bonus_int_menu', NULL, NULL, NULL, NULL, '118', '0', '1', '', '3', 'system', NOW()
WHERE NOT EXISTS (
	SELECT 1 FROM `user_menu` WHERE `module_name` = 'bonus_int_menu'
);

INSERT INTO `user_menu` (`title`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `menu_position_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`, `date_insert`)
SELECT 'THR', 'uri', NULL, 'thr_int_menu', 'payroll_internal/thr_int_menu', NULL, NULL, NULL, NULL, '118', '0', '1', '', '4', 'system', NOW()
WHERE NOT EXISTS (
	SELECT 1 FROM `user_menu` WHERE `module_name` = 'thr_int_menu'
);

UPDATE `user_menu` SET `um_order` = '5' WHERE `module_name` = 'pembayaran_gaji_int_menu' AND `parent_id` = '118';
UPDATE `user_menu` SET `um_order` = '6' WHERE `module_name` = 'history_bpjs_int_menu' AND `parent_id` = '118';
UPDATE `user_menu` SET `um_order` = '7' WHERE `module_name` = 'spt_int_menu' AND `parent_id` = '118';

INSERT INTO `user_akses_role` (`role_id`, `user_menu_id`, `view`, `add`, `edit`, `del`, `detail`, `eksport`, `import`, `insert_by`, `date_insert`)
SELECT r.`role_id`, m.`user_menu_id`, r.`view`, r.`add`, r.`edit`, r.`del`, r.`detail`, r.`eksport`, r.`import`, 'system', NOW()
FROM `user_akses_role` r
JOIN `user_menu` oldm ON oldm.`user_menu_id` = r.`user_menu_id` AND oldm.`module_name` = 'hitung_gaji_int_menu'
JOIN `user_menu` m ON m.`module_name` IN ('bonus_int_menu', 'thr_int_menu')
LEFT JOIN `user_akses_role` existing ON existing.`role_id` = r.`role_id` AND existing.`user_menu_id` = m.`user_menu_id`
WHERE existing.`user_akses_id` IS NULL;

DELETE a
FROM `user_akses_role` a
JOIN `user_akses_role` b ON a.`user_menu_id` = b.`user_menu_id`
	AND a.`role_id` = b.`role_id`
	AND a.`user_akses_id` > b.`user_akses_id`
WHERE a.`user_menu_id` IN (
	SELECT `user_menu_id` FROM `user_menu` WHERE `module_name` IN ('bonus_int_menu', 'thr_int_menu')
);
