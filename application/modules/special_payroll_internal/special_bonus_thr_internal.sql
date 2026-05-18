CREATE TABLE IF NOT EXISTS `special_bonus_internal` LIKE `bonus_internal`;
CREATE TABLE IF NOT EXISTS `special_bonus_internal_detail` LIKE `bonus_internal_detail`;

ALTER TABLE `special_bonus_internal_detail`
	CHANGE COLUMN `bonus_internal_id` `special_bonus_internal_id` INT(11) NOT NULL;

CREATE TABLE IF NOT EXISTS `special_thr_internal` LIKE `thr_internal`;
CREATE TABLE IF NOT EXISTS `special_thr_internal_detail` LIKE `thr_internal_detail`;

ALTER TABLE `special_thr_internal_detail`
	CHANGE COLUMN `thr_internal_id` `special_thr_internal_id` INT(11) NOT NULL;

INSERT INTO `approval_matrix_mstype` (`id`, `name`, `tbl`, `satuan`, `link`, `tbl_employee_id`)
SELECT 22, 'Special Payroll Bonus Internal', 'special_bonus_internal', 'amount', 'special_payroll_internal/bonus_int_menu', 'created_by'
WHERE NOT EXISTS (SELECT 1 FROM `approval_matrix_mstype` WHERE `id` = 22);

INSERT INTO `approval_matrix_mstype` (`id`, `name`, `tbl`, `satuan`, `link`, `tbl_employee_id`)
SELECT 23, 'Special Payroll THR Internal', 'special_thr_internal', 'amount', 'special_payroll_internal/thr_int_menu', 'created_by'
WHERE NOT EXISTS (SELECT 1 FROM `approval_matrix_mstype` WHERE `id` = 23);

INSERT INTO `approval_matrix` (`approval_name`, `approval_type_id`, `work_location_id`, `leave_type_id`, `min`, `max`, `description`, `created_date`, `created_by`)
SELECT REPLACE(src.`approval_name`, 'Payroll Bonus Internal', 'Special Payroll Bonus Internal'), 22, src.`work_location_id`, src.`leave_type_id`, src.`min`, src.`max`, src.`description`, NOW(), src.`created_by`
FROM `approval_matrix` src
WHERE src.`approval_type_id` = 14
AND NOT EXISTS (
	SELECT 1 FROM `approval_matrix` dst
	WHERE dst.`approval_type_id` = 22
	AND dst.`work_location_id` = src.`work_location_id`
	AND IFNULL(dst.`min`, '') = IFNULL(src.`min`, '')
	AND IFNULL(dst.`max`, '') = IFNULL(src.`max`, '')
);

INSERT INTO `approval_matrix` (`approval_name`, `approval_type_id`, `work_location_id`, `leave_type_id`, `min`, `max`, `description`, `created_date`, `created_by`)
SELECT REPLACE(src.`approval_name`, 'Payroll THR Internal', 'Special Payroll THR Internal'), 23, src.`work_location_id`, src.`leave_type_id`, src.`min`, src.`max`, src.`description`, NOW(), src.`created_by`
FROM `approval_matrix` src
WHERE src.`approval_type_id` = 15
AND NOT EXISTS (
	SELECT 1 FROM `approval_matrix` dst
	WHERE dst.`approval_type_id` = 23
	AND dst.`work_location_id` = src.`work_location_id`
	AND IFNULL(dst.`min`, '') = IFNULL(src.`min`, '')
	AND IFNULL(dst.`max`, '') = IFNULL(src.`max`, '')
);

INSERT INTO `approval_matrix_detail` (`approval_matrix_id`, `approval_level`, `role_id`, `approval_max_duration`)
SELECT dst.`id`, d.`approval_level`, d.`role_id`, d.`approval_max_duration`
FROM `approval_matrix` src
JOIN `approval_matrix` dst ON dst.`approval_type_id` = 22
	AND dst.`work_location_id` = src.`work_location_id`
	AND IFNULL(dst.`min`, '') = IFNULL(src.`min`, '')
	AND IFNULL(dst.`max`, '') = IFNULL(src.`max`, '')
JOIN `approval_matrix_detail` d ON d.`approval_matrix_id` = src.`id`
WHERE src.`approval_type_id` = 14
AND NOT EXISTS (
	SELECT 1 FROM `approval_matrix_detail` x
	WHERE x.`approval_matrix_id` = dst.`id`
	AND x.`approval_level` = d.`approval_level`
);

INSERT INTO `user_menu` (`title`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `menu_position_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`, `date_insert`)
SELECT 'Bonus', 'module', 0, 'bonus_int_menu', 'special_payroll_internal/bonus_int_menu', 'bonus_int_menu', 1, 'left', '_self', p.`user_menu_id`, 0, 1, 'fa-gift', 3, 1, NOW()
FROM `user_menu` p
WHERE p.`module_name` = 'special_payroll_internal'
AND NOT EXISTS (
	SELECT 1 FROM `user_menu` m
	WHERE m.`url` = 'special_payroll_internal/bonus_int_menu'
	AND m.`parent_id` = p.`user_menu_id`
);

INSERT INTO `user_menu` (`title`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `menu_position_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`, `date_insert`)
SELECT 'THR', 'module', 0, 'thr_int_menu', 'special_payroll_internal/thr_int_menu', 'thr_int_menu', 1, 'left', '_self', p.`user_menu_id`, 0, 1, 'fa-gift', 4, 1, NOW()
FROM `user_menu` p
WHERE p.`module_name` = 'special_payroll_internal'
AND NOT EXISTS (
	SELECT 1 FROM `user_menu` m
	WHERE m.`url` = 'special_payroll_internal/thr_int_menu'
	AND m.`parent_id` = p.`user_menu_id`
);

UPDATE `user_menu` SET `um_order` = 5 WHERE `url` = 'special_payroll_internal/pembayaran_gaji_int_menu';
UPDATE `user_menu` SET `um_order` = 6 WHERE `url` = 'special_payroll_internal/history_bpjs_int_menu';
UPDATE `user_menu` SET `um_order` = 7 WHERE `url` = 'special_payroll_internal/spt_int_menu';

INSERT INTO `user_akses_role` (`role_id`, `user_menu_id`, `view`, `add`, `edit`, `del`, `detail`, `eksport`, `import`, `insert_by`, `date_insert`)
SELECT r.`role_id`, m.`user_menu_id`, r.`view`, r.`add`, r.`edit`, r.`del`, r.`detail`, r.`eksport`, r.`import`, r.`insert_by`, NOW()
FROM `user_akses_role` r
JOIN `user_menu` oldm ON oldm.`user_menu_id` = r.`user_menu_id`
	AND oldm.`url` = 'special_payroll_internal/hitung_gaji_int_menu'
JOIN `user_menu` m ON m.`url` IN ('special_payroll_internal/bonus_int_menu', 'special_payroll_internal/thr_int_menu')
LEFT JOIN `user_akses_role` existing ON existing.`role_id` = r.`role_id`
	AND existing.`user_menu_id` = m.`user_menu_id`
WHERE existing.`user_akses_id` IS NULL;

INSERT INTO `approval_matrix_detail` (`approval_matrix_id`, `approval_level`, `role_id`, `approval_max_duration`)
SELECT dst.`id`, d.`approval_level`, d.`role_id`, d.`approval_max_duration`
FROM `approval_matrix` src
JOIN `approval_matrix` dst ON dst.`approval_type_id` = 23
	AND dst.`work_location_id` = src.`work_location_id`
	AND IFNULL(dst.`min`, '') = IFNULL(src.`min`, '')
	AND IFNULL(dst.`max`, '') = IFNULL(src.`max`, '')
JOIN `approval_matrix_detail` d ON d.`approval_matrix_id` = src.`id`
WHERE src.`approval_type_id` = 15
AND NOT EXISTS (
	SELECT 1 FROM `approval_matrix_detail` x
	WHERE x.`approval_matrix_id` = dst.`id`
	AND x.`approval_level` = d.`approval_level`
);
