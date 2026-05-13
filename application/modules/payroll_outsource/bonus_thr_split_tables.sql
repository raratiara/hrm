CREATE TABLE IF NOT EXISTS `bonus_os` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`project_id` int(11) NOT NULL,
	`periode_bulan` int(11) NOT NULL,
	`periode_tahun` varchar(4) NOT NULL,
	`total_bonus` decimal(18,2) NOT NULL DEFAULT 0.00,
	`notes` text DEFAULT NULL,
	`created_at` datetime DEFAULT NULL,
	`created_by` int(11) DEFAULT NULL,
	`updated_at` datetime DEFAULT NULL,
	`updated_by` int(11) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_bonus_os_period` (`project_id`, `periode_bulan`, `periode_tahun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `bonus_os` ADD COLUMN IF NOT EXISTS `total_bonus` decimal(18,2) NOT NULL DEFAULT 0.00 AFTER `periode_tahun`;

CREATE TABLE IF NOT EXISTS `bonus_os_detail` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`bonus_os_id` int(11) NOT NULL,
	`employee_id` int(11) NOT NULL,
	`bonus_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
	`note` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_bonus_os_detail_employee` (`bonus_os_id`, `employee_id`),
	KEY `idx_bonus_os_detail_employee` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `thr_os` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`project_id` int(11) NOT NULL,
	`periode_bulan` int(11) NOT NULL,
	`periode_tahun` varchar(4) NOT NULL,
	`total_thr` decimal(18,2) NOT NULL DEFAULT 0.00,
	`notes` text DEFAULT NULL,
	`created_at` datetime DEFAULT NULL,
	`created_by` int(11) DEFAULT NULL,
	`updated_at` datetime DEFAULT NULL,
	`updated_by` int(11) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_thr_os_period` (`project_id`, `periode_bulan`, `periode_tahun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `thr_os` ADD COLUMN IF NOT EXISTS `total_thr` decimal(18,2) NOT NULL DEFAULT 0.00 AFTER `periode_tahun`;

CREATE TABLE IF NOT EXISTS `thr_os_detail` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`thr_os_id` int(11) NOT NULL,
	`employee_id` int(11) NOT NULL,
	`thr_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
	`note` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_thr_os_detail_employee` (`thr_os_id`, `employee_id`),
	KEY `idx_thr_os_detail_employee` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `bonus_os` DROP COLUMN IF EXISTS `component_type`;
ALTER TABLE `thr_os` DROP COLUMN IF EXISTS `component_type`;

INSERT IGNORE INTO `bonus_os` (`id`, `project_id`, `periode_bulan`, `periode_tahun`, `notes`, `created_at`, `created_by`, `updated_at`, `updated_by`)
SELECT `id`, `project_id`, `periode_bulan`, `periode_tahun`, `notes`, `created_at`, `created_by`, `updated_at`, `updated_by`
FROM `bonus_thr_os`
WHERE `component_type` = 'Bonus';

INSERT IGNORE INTO `bonus_os_detail` (`id`, `bonus_os_id`, `employee_id`, `bonus_amount`, `note`)
SELECT d.`id`, d.`bonus_thr_os_id`, d.`employee_id`, d.`bonus_amount`, d.`note`
FROM `bonus_thr_os_detail` d
JOIN `bonus_thr_os` h ON h.`id` = d.`bonus_thr_os_id`
WHERE h.`component_type` = 'Bonus';

UPDATE `bonus_os` h
SET h.`total_bonus` = (
	SELECT COALESCE(SUM(d.`bonus_amount`), 0)
	FROM `bonus_os_detail` d
	WHERE d.`bonus_os_id` = h.`id`
);

INSERT IGNORE INTO `thr_os` (`id`, `project_id`, `periode_bulan`, `periode_tahun`, `notes`, `created_at`, `created_by`, `updated_at`, `updated_by`)
SELECT `id`, `project_id`, `periode_bulan`, `periode_tahun`, `notes`, `created_at`, `created_by`, `updated_at`, `updated_by`
FROM `bonus_thr_os`
WHERE `component_type` = 'THR';

INSERT IGNORE INTO `thr_os_detail` (`id`, `thr_os_id`, `employee_id`, `thr_amount`, `note`)
SELECT d.`id`, d.`bonus_thr_os_id`, d.`employee_id`, d.`thr_amount`, d.`note`
FROM `bonus_thr_os_detail` d
JOIN `bonus_thr_os` h ON h.`id` = d.`bonus_thr_os_id`
WHERE h.`component_type` = 'THR';

UPDATE `thr_os` h
SET h.`total_thr` = (
	SELECT COALESCE(SUM(d.`thr_amount`), 0)
	FROM `thr_os_detail` d
	WHERE d.`thr_os_id` = h.`id`
);
