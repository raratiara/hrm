USE hrm_gdi;

ALTER TABLE bonus_internal
	ADD COLUMN IF NOT EXISTS status_id INT(11) NULL DEFAULT 1 AFTER total_bonus,
	ADD COLUMN IF NOT EXISTS rfu_reason TEXT NULL AFTER status_id,
	ADD COLUMN IF NOT EXISTS reject_reason TEXT NULL AFTER rfu_reason,
	ADD COLUMN IF NOT EXISTS approval_date DATETIME NULL AFTER reject_reason;

ALTER TABLE thr_internal
	ADD COLUMN IF NOT EXISTS status_id INT(11) NULL DEFAULT 1 AFTER total_thr,
	ADD COLUMN IF NOT EXISTS rfu_reason TEXT NULL AFTER status_id,
	ADD COLUMN IF NOT EXISTS reject_reason TEXT NULL AFTER rfu_reason,
	ADD COLUMN IF NOT EXISTS approval_date DATETIME NULL AFTER reject_reason;

ALTER TABLE bonus_os
	ADD COLUMN IF NOT EXISTS status_id INT(11) NULL DEFAULT 1 AFTER total_bonus,
	ADD COLUMN IF NOT EXISTS rfu_reason TEXT NULL AFTER status_id,
	ADD COLUMN IF NOT EXISTS reject_reason TEXT NULL AFTER rfu_reason,
	ADD COLUMN IF NOT EXISTS approval_date DATETIME NULL AFTER reject_reason;

ALTER TABLE thr_os
	ADD COLUMN IF NOT EXISTS status_id INT(11) NULL DEFAULT 1 AFTER total_thr,
	ADD COLUMN IF NOT EXISTS rfu_reason TEXT NULL AFTER status_id,
	ADD COLUMN IF NOT EXISTS reject_reason TEXT NULL AFTER rfu_reason,
	ADD COLUMN IF NOT EXISTS approval_date DATETIME NULL AFTER reject_reason;

INSERT INTO approval_matrix_mstype (id, name, tbl, satuan, link, tbl_employee_id)
SELECT 14, 'Payroll Bonus Internal', 'bonus_internal', 'amount', 'payroll_internal/bonus_int_menu', 'created_by'
WHERE NOT EXISTS (SELECT 1 FROM approval_matrix_mstype WHERE id = 14);

INSERT INTO approval_matrix_mstype (id, name, tbl, satuan, link, tbl_employee_id)
SELECT 15, 'Payroll THR Internal', 'thr_internal', 'amount', 'payroll_internal/thr_int_menu', 'created_by'
WHERE NOT EXISTS (SELECT 1 FROM approval_matrix_mstype WHERE id = 15);

INSERT INTO approval_matrix_mstype (id, name, tbl, satuan, link, tbl_employee_id)
SELECT 16, 'Payroll Bonus Outsource', 'bonus_os', 'amount', 'payroll_outsource/bonus_os_menu', 'created_by'
WHERE NOT EXISTS (SELECT 1 FROM approval_matrix_mstype WHERE id = 16);

INSERT INTO approval_matrix_mstype (id, name, tbl, satuan, link, tbl_employee_id)
SELECT 17, 'Payroll THR Outsource', 'thr_os', 'amount', 'payroll_outsource/thr_os_menu', 'created_by'
WHERE NOT EXISTS (SELECT 1 FROM approval_matrix_mstype WHERE id = 17);

INSERT INTO approval_matrix (approval_name, approval_type_id, work_location_id, leave_type_id, min, max, description, created_date, created_by)
SELECT REPLACE(src.approval_name, 'Cash Advance', 'Payroll Bonus Internal'), 14, src.work_location_id, src.leave_type_id, src.min, src.max, src.description, NOW(), src.created_by
FROM approval_matrix src
WHERE src.approval_type_id = 2
AND NOT EXISTS (
	SELECT 1 FROM approval_matrix dst
	WHERE dst.approval_type_id = 14
	AND dst.work_location_id = src.work_location_id
	AND IFNULL(dst.min, '') = IFNULL(src.min, '')
	AND IFNULL(dst.max, '') = IFNULL(src.max, '')
);

INSERT INTO approval_matrix (approval_name, approval_type_id, work_location_id, leave_type_id, min, max, description, created_date, created_by)
SELECT REPLACE(src.approval_name, 'Cash Advance', 'Payroll THR Internal'), 15, src.work_location_id, src.leave_type_id, src.min, src.max, src.description, NOW(), src.created_by
FROM approval_matrix src
WHERE src.approval_type_id = 2
AND NOT EXISTS (
	SELECT 1 FROM approval_matrix dst
	WHERE dst.approval_type_id = 15
	AND dst.work_location_id = src.work_location_id
	AND IFNULL(dst.min, '') = IFNULL(src.min, '')
	AND IFNULL(dst.max, '') = IFNULL(src.max, '')
);

INSERT INTO approval_matrix (approval_name, approval_type_id, work_location_id, leave_type_id, min, max, description, created_date, created_by)
SELECT REPLACE(src.approval_name, 'Cash Advance', 'Payroll Bonus Outsource'), 16, src.work_location_id, src.leave_type_id, src.min, src.max, src.description, NOW(), src.created_by
FROM approval_matrix src
WHERE src.approval_type_id = 2
AND NOT EXISTS (
	SELECT 1 FROM approval_matrix dst
	WHERE dst.approval_type_id = 16
	AND dst.work_location_id = src.work_location_id
	AND IFNULL(dst.min, '') = IFNULL(src.min, '')
	AND IFNULL(dst.max, '') = IFNULL(src.max, '')
);

INSERT INTO approval_matrix (approval_name, approval_type_id, work_location_id, leave_type_id, min, max, description, created_date, created_by)
SELECT REPLACE(src.approval_name, 'Cash Advance', 'Payroll THR Outsource'), 17, src.work_location_id, src.leave_type_id, src.min, src.max, src.description, NOW(), src.created_by
FROM approval_matrix src
WHERE src.approval_type_id = 2
AND NOT EXISTS (
	SELECT 1 FROM approval_matrix dst
	WHERE dst.approval_type_id = 17
	AND dst.work_location_id = src.work_location_id
	AND IFNULL(dst.min, '') = IFNULL(src.min, '')
	AND IFNULL(dst.max, '') = IFNULL(src.max, '')
);

INSERT INTO approval_matrix_detail (approval_matrix_id, approval_level, role_id, approval_max_duration)
SELECT dst.id, d.approval_level, d.role_id, d.approval_max_duration
FROM approval_matrix src
JOIN approval_matrix dst ON dst.approval_type_id = 14
	AND dst.work_location_id = src.work_location_id
	AND IFNULL(dst.min, '') = IFNULL(src.min, '')
	AND IFNULL(dst.max, '') = IFNULL(src.max, '')
JOIN approval_matrix_detail d ON d.approval_matrix_id = src.id
WHERE src.approval_type_id = 2
AND NOT EXISTS (
	SELECT 1 FROM approval_matrix_detail x
	WHERE x.approval_matrix_id = dst.id
	AND x.approval_level = d.approval_level
);

INSERT INTO approval_matrix_detail (approval_matrix_id, approval_level, role_id, approval_max_duration)
SELECT dst.id, d.approval_level, d.role_id, d.approval_max_duration
FROM approval_matrix src
JOIN approval_matrix dst ON dst.approval_type_id = 15
	AND dst.work_location_id = src.work_location_id
	AND IFNULL(dst.min, '') = IFNULL(src.min, '')
	AND IFNULL(dst.max, '') = IFNULL(src.max, '')
JOIN approval_matrix_detail d ON d.approval_matrix_id = src.id
WHERE src.approval_type_id = 2
AND NOT EXISTS (
	SELECT 1 FROM approval_matrix_detail x
	WHERE x.approval_matrix_id = dst.id
	AND x.approval_level = d.approval_level
);

INSERT INTO approval_matrix_detail (approval_matrix_id, approval_level, role_id, approval_max_duration)
SELECT dst.id, d.approval_level, d.role_id, d.approval_max_duration
FROM approval_matrix src
JOIN approval_matrix dst ON dst.approval_type_id = 16
	AND dst.work_location_id = src.work_location_id
	AND IFNULL(dst.min, '') = IFNULL(src.min, '')
	AND IFNULL(dst.max, '') = IFNULL(src.max, '')
JOIN approval_matrix_detail d ON d.approval_matrix_id = src.id
WHERE src.approval_type_id = 2
AND NOT EXISTS (
	SELECT 1 FROM approval_matrix_detail x
	WHERE x.approval_matrix_id = dst.id
	AND x.approval_level = d.approval_level
);

INSERT INTO approval_matrix_detail (approval_matrix_id, approval_level, role_id, approval_max_duration)
SELECT dst.id, d.approval_level, d.role_id, d.approval_max_duration
FROM approval_matrix src
JOIN approval_matrix dst ON dst.approval_type_id = 17
	AND dst.work_location_id = src.work_location_id
	AND IFNULL(dst.min, '') = IFNULL(src.min, '')
	AND IFNULL(dst.max, '') = IFNULL(src.max, '')
JOIN approval_matrix_detail d ON d.approval_matrix_id = src.id
WHERE src.approval_type_id = 2
AND NOT EXISTS (
	SELECT 1 FROM approval_matrix_detail x
	WHERE x.approval_matrix_id = dst.id
	AND x.approval_level = d.approval_level
);
