CREATE TABLE IF NOT EXISTS `bonus_thr_os` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `periode_bulan` int(11) NOT NULL,
  `periode_tahun` varchar(4) NOT NULL,
  `component_type` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bonus_thr_os_period` (`project_id`, `periode_bulan`, `periode_tahun`, `component_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bonus_thr_os_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bonus_thr_os_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `bonus_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `thr_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bonus_thr_os_detail_employee` (`bonus_thr_os_id`, `employee_id`),
  KEY `idx_bonus_thr_os_detail_employee` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_menu`
(`title`, `type`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `menu_position_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`, `date_insert`)
SELECT 'Bonus & THR', 'outsource', 'uri', NULL, 'bonus_thr_os_menu', 'payroll_outsource/bonus_thr_os_menu', NULL, NULL, NULL, NULL, '109', '0', '1', '', '3', 'system', NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `user_menu` WHERE `module_name` = 'bonus_thr_os_menu'
);

UPDATE `user_menu`
SET `um_order` = '4'
WHERE `module_name` = 'pembayaran_gaji_os_menu' AND `parent_id` = '109';

UPDATE `user_menu`
SET `um_order` = '5'
WHERE `module_name` = 'pembayaran_lembur_os_menu' AND `parent_id` = '109';

UPDATE `user_menu`
SET `um_order` = '6'
WHERE `module_name` = 'history_bpjs_menu' AND `parent_id` = '109';

UPDATE `user_menu`
SET `um_order` = '7'
WHERE `module_name` = 'spt_os_menu' AND `parent_id` = '109';

UPDATE `user_menu`
SET `um_order` = '8'
WHERE `module_name` = 'invoice_menu' AND `parent_id` = '109';

INSERT INTO `user_akses_role`
(`role_id`, `user_menu_id`, `view`, `add`, `edit`, `del`, `detail`, `eksport`, `import`, `insert_by`, `date_insert`)
SELECT r.`role_id`, m.`user_menu_id`, r.`view`, r.`add`, r.`edit`, r.`del`, r.`detail`, r.`eksport`, r.`import`, 'system', NOW()
FROM `user_akses_role` r
JOIN `user_menu` m ON m.`module_name` = 'bonus_thr_os_menu'
WHERE r.`user_menu_id` = 111
AND NOT EXISTS (
  SELECT 1
  FROM `user_akses_role` x
  WHERE x.`role_id` = r.`role_id`
  AND x.`user_menu_id` = m.`user_menu_id`
);
