ALTER TABLE `payroll_slip_internal`
	ADD COLUMN IF NOT EXISTS `status_id` INT(11) NULL DEFAULT 1 AFTER `status`,
	ADD COLUMN IF NOT EXISTS `rfu_reason` TEXT NULL AFTER `status_id`,
	ADD COLUMN IF NOT EXISTS `reject_reason` TEXT NULL AFTER `rfu_reason`,
	ADD COLUMN IF NOT EXISTS `approval_date` DATETIME NULL AFTER `reject_reason`,
	ADD COLUMN IF NOT EXISTS `created_at` DATETIME NULL AFTER `approval_date`,
	ADD COLUMN IF NOT EXISTS `created_by` INT(11) NULL AFTER `created_at`,
	ADD COLUMN IF NOT EXISTS `updated_at` DATETIME NULL AFTER `created_by`,
	ADD COLUMN IF NOT EXISTS `updated_by` INT(11) NULL AFTER `updated_at`;

ALTER TABLE `payroll_slip`
	ADD COLUMN IF NOT EXISTS `status_id` INT(11) NULL DEFAULT 1 AFTER `status`,
	ADD COLUMN IF NOT EXISTS `rfu_reason` TEXT NULL AFTER `status_id`,
	ADD COLUMN IF NOT EXISTS `reject_reason` TEXT NULL AFTER `rfu_reason`,
	ADD COLUMN IF NOT EXISTS `approval_date` DATETIME NULL AFTER `reject_reason`,
	ADD COLUMN IF NOT EXISTS `created_at` DATETIME NULL AFTER `approval_date`,
	ADD COLUMN IF NOT EXISTS `created_by` INT(11) NULL AFTER `created_at`,
	ADD COLUMN IF NOT EXISTS `updated_at` DATETIME NULL AFTER `created_by`,
	ADD COLUMN IF NOT EXISTS `updated_by` INT(11) NULL AFTER `updated_at`;

ALTER TABLE `payroll_slip_internal`
	MODIFY COLUMN `status_id` INT(11) NULL DEFAULT 0;

ALTER TABLE `payroll_slip`
	MODIFY COLUMN `status_id` INT(11) NULL DEFAULT 0;

UPDATE `payroll_slip_internal`
SET `status_id` = 2
WHERE `status` IS NOT NULL AND IFNULL(`status_id`, 1) = 1;

UPDATE `payroll_slip`
SET `status_id` = 2
WHERE `status` IS NOT NULL AND IFNULL(`status_id`, 1) = 1;

INSERT INTO `approval_matrix_mstype` (`id`, `name`, `tbl`, `satuan`, `link`, `tbl_employee_id`)
SELECT 18, 'Payroll Gaji Internal', 'payroll_slip_internal', 'amount', 'payroll_internal/hitung_gaji_int_menu', 'created_by'
WHERE NOT EXISTS (SELECT 1 FROM `approval_matrix_mstype` WHERE `id` = 18);

INSERT INTO `approval_matrix_mstype` (`id`, `name`, `tbl`, `satuan`, `link`, `tbl_employee_id`)
SELECT 19, 'Payroll Gaji Outsource', 'payroll_slip', 'amount', 'payroll_outsource/hitung_gaji_os_menu', 'created_by'
WHERE NOT EXISTS (SELECT 1 FROM `approval_matrix_mstype` WHERE `id` = 19);

INSERT INTO `approval_matrix` (`approval_name`, `approval_type_id`, `work_location_id`, `leave_type_id`, `min`, `max`, `description`, `created_date`, `created_by`)
SELECT REPLACE(src.`approval_name`, 'Cash Advance', 'Payroll Gaji Internal'), 18, src.`work_location_id`, src.`leave_type_id`, src.`min`, src.`max`, src.`description`, NOW(), src.`created_by`
FROM `approval_matrix` src
WHERE src.`approval_type_id` = 2
AND NOT EXISTS (
	SELECT 1 FROM `approval_matrix` dst
	WHERE dst.`approval_type_id` = 18
	AND dst.`work_location_id` = src.`work_location_id`
	AND IFNULL(dst.`min`, '') = IFNULL(src.`min`, '')
	AND IFNULL(dst.`max`, '') = IFNULL(src.`max`, '')
);

INSERT INTO `approval_matrix` (`approval_name`, `approval_type_id`, `work_location_id`, `leave_type_id`, `min`, `max`, `description`, `created_date`, `created_by`)
SELECT REPLACE(src.`approval_name`, 'Cash Advance', 'Payroll Gaji Outsource'), 19, src.`work_location_id`, src.`leave_type_id`, src.`min`, src.`max`, src.`description`, NOW(), src.`created_by`
FROM `approval_matrix` src
WHERE src.`approval_type_id` = 2
AND NOT EXISTS (
	SELECT 1 FROM `approval_matrix` dst
	WHERE dst.`approval_type_id` = 19
	AND dst.`work_location_id` = src.`work_location_id`
	AND IFNULL(dst.`min`, '') = IFNULL(src.`min`, '')
	AND IFNULL(dst.`max`, '') = IFNULL(src.`max`, '')
);

INSERT INTO `approval_matrix_detail` (`approval_matrix_id`, `approval_level`, `role_id`, `approval_max_duration`)
SELECT dst.`id`, d.`approval_level`, d.`role_id`, d.`approval_max_duration`
FROM `approval_matrix` src
JOIN `approval_matrix` dst ON dst.`approval_type_id` = 18
	AND dst.`work_location_id` = src.`work_location_id`
	AND IFNULL(dst.`min`, '') = IFNULL(src.`min`, '')
	AND IFNULL(dst.`max`, '') = IFNULL(src.`max`, '')
JOIN `approval_matrix_detail` d ON d.`approval_matrix_id` = src.`id`
WHERE src.`approval_type_id` = 2
AND NOT EXISTS (
	SELECT 1 FROM `approval_matrix_detail` x
	WHERE x.`approval_matrix_id` = dst.`id`
	AND x.`approval_level` = d.`approval_level`
);

INSERT INTO `approval_matrix_detail` (`approval_matrix_id`, `approval_level`, `role_id`, `approval_max_duration`)
SELECT dst.`id`, d.`approval_level`, d.`role_id`, d.`approval_max_duration`
FROM `approval_matrix` src
JOIN `approval_matrix` dst ON dst.`approval_type_id` = 19
	AND dst.`work_location_id` = src.`work_location_id`
	AND IFNULL(dst.`min`, '') = IFNULL(src.`min`, '')
	AND IFNULL(dst.`max`, '') = IFNULL(src.`max`, '')
JOIN `approval_matrix_detail` d ON d.`approval_matrix_id` = src.`id`
WHERE src.`approval_type_id` = 2
AND NOT EXISTS (
	SELECT 1 FROM `approval_matrix_detail` x
	WHERE x.`approval_matrix_id` = dst.`id`
	AND x.`approval_level` = d.`approval_level`
);
