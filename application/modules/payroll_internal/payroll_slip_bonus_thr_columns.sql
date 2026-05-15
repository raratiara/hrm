ALTER TABLE `payroll_slip_detail`
	ADD COLUMN `bonus` DECIMAL(18,2) NOT NULL DEFAULT 0.00 AFTER `total_nominal_lembur`,
	ADD COLUMN `thr` DECIMAL(18,2) NOT NULL DEFAULT 0.00 AFTER `bonus`;

ALTER TABLE `payroll_slip_detail_internal`
	ADD COLUMN `bonus` DECIMAL(18,2) NOT NULL DEFAULT 0.00 AFTER `total_nominal_lembur`,
	ADD COLUMN `thr` DECIMAL(18,2) NOT NULL DEFAULT 0.00 AFTER `bonus`;
