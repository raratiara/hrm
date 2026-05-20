CREATE TABLE IF NOT EXISTS `spt_pph21_adjustment` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`spt_pph21_id` INT(11) NOT NULL,
	`spt_pph21_detail_id` INT(11) NOT NULL,
	`employee_id` INT(11) NOT NULL,
	`tahun_pajak` VARCHAR(10) NOT NULL,
	`type` VARCHAR(20) NOT NULL,
	`amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
	`kurang_lebih_bayar` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
	`status` VARCHAR(20) NOT NULL DEFAULT 'pending',
	`proses_ke_bulan_penggajian` INT(2) NOT NULL DEFAULT 1,
	`proses_ke_tahun_penggajian` VARCHAR(10) NOT NULL,
	`created_at` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_at` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uniq_spt_adjustment_detail` (`spt_pph21_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `spt_pph21_adjustment`
	ADD COLUMN IF NOT EXISTS `spt_pph21_id` INT(11) NULL AFTER `id`,
	ADD COLUMN IF NOT EXISTS `spt_pph21_detail_id` INT(11) NULL AFTER `spt_pph21_id`,
	ADD COLUMN IF NOT EXISTS `kurang_lebih_bayar` DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER `amount`,
	ADD COLUMN IF NOT EXISTS `created_at` DATETIME DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS `created_by` INT(11) DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS `updated_at` DATETIME DEFAULT NULL,
	ADD COLUMN IF NOT EXISTS `updated_by` INT(11) DEFAULT NULL;

ALTER TABLE `spt_pph21_adjustment` CHANGE COLUMN IF EXISTS `tax_year` `tahun_pajak` VARCHAR(10) NULL;
ALTER TABLE `spt_pph21_adjustment` MODIFY COLUMN `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `spt_pph21_adjustment` MODIFY COLUMN `proses_ke_bulan_penggajian` INT(2) NOT NULL DEFAULT 1;
ALTER TABLE `spt_pph21_adjustment` DROP COLUMN IF EXISTS `source_type`;
DROP INDEX IF EXISTS `uniq_spt_adjustment_source_detail` ON `spt_pph21_adjustment`;
CREATE UNIQUE INDEX IF NOT EXISTS `uniq_spt_adjustment_detail` ON `spt_pph21_adjustment` (`spt_pph21_detail_id`);

INSERT INTO `user_menu` (`title`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `menu_position_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`, `date_insert`)
SELECT 'PPh21 Adjustment', 'module', 0, 'pph21_adjustment_menu', 'payroll_internal/pph21_adjustment_menu', 'pph21_adjustment_menu', 1, 'left', '_self', p.`user_menu_id`, 0, 1, 'fa-balance-scale', 8, 1, NOW()
FROM `user_menu` p
WHERE p.`module_name` = 'payroll_internal'
AND NOT EXISTS (
	SELECT 1 FROM `user_menu` m
	WHERE m.`module_name` = 'pph21_adjustment_menu'
	AND m.`parent_id` = p.`user_menu_id`
);

INSERT INTO `user_akses_role` (`role_id`, `user_menu_id`, `view`, `add`, `edit`, `del`, `detail`, `eksport`, `import`, `insert_by`, `date_insert`)
SELECT r.`role_id`, m.`user_menu_id`, r.`view`, '0', r.`edit`, r.`del`, r.`detail`, r.`eksport`, '0', r.`insert_by`, NOW()
FROM `user_akses_role` r
JOIN `user_menu` oldm ON oldm.`user_menu_id` = r.`user_menu_id` AND oldm.`module_name` = 'spt_int_menu'
JOIN `user_menu` oldp ON oldp.`user_menu_id` = oldm.`parent_id` AND oldp.`module_name` = 'payroll_internal'
JOIN `user_menu` m ON m.`module_name` = 'pph21_adjustment_menu' AND m.`parent_id` = oldp.`user_menu_id`
LEFT JOIN `user_akses_role` existing ON existing.`role_id` = r.`role_id` AND existing.`user_menu_id` = m.`user_menu_id`
WHERE existing.`user_menu_id` IS NULL;

DELETE ua1 FROM `user_akses_role` ua1
JOIN `user_akses_role` ua2 ON ua1.`role_id` = ua2.`role_id`
	AND ua1.`user_menu_id` = ua2.`user_menu_id`
	AND ua1.`user_akses_id` > ua2.`user_akses_id`
JOIN `user_menu` m ON m.`user_menu_id` = ua1.`user_menu_id`
WHERE m.`module_name` = 'pph21_adjustment_menu';

UPDATE `user_akses_role` adj
JOIN `user_menu` adjm ON adjm.`user_menu_id` = adj.`user_menu_id` AND adjm.`module_name` = 'pph21_adjustment_menu'
JOIN `user_akses_role` spt ON spt.`role_id` = adj.`role_id`
JOIN `user_menu` sptm ON sptm.`user_menu_id` = spt.`user_menu_id`
	AND sptm.`module_name` = 'spt_int_menu'
	AND sptm.`parent_id` = adjm.`parent_id`
SET adj.`view` = COALESCE(spt.`view`, adj.`view`),
	adj.`add` = '0',
	adj.`edit` = COALESCE(spt.`edit`, spt.`view`, adj.`edit`),
	adj.`del` = COALESCE(spt.`del`, adj.`del`),
	adj.`detail` = COALESCE(spt.`detail`, spt.`view`, adj.`detail`),
	adj.`eksport` = COALESCE(spt.`eksport`, adj.`eksport`),
	adj.`import` = '0';
