UPDATE `user_menu`
SET `show_menu` = '0',
	`date_update` = NOW(),
	`update_by` = 'system'
WHERE `module_name` = 'bonus_thr_os_menu';

INSERT INTO `user_menu` (`title`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `menu_position_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`, `date_insert`)
SELECT 'Bonus', 'uri', NULL, 'bonus_os_menu', 'payroll_outsource/bonus_os_menu', NULL, NULL, NULL, NULL, '109', '0', '1', '', '3', 'system', NOW()
WHERE NOT EXISTS (
	SELECT 1 FROM `user_menu` WHERE `module_name` = 'bonus_os_menu'
);

INSERT INTO `user_menu` (`title`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `menu_position_id`, `position`, `target`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`, `date_insert`)
SELECT 'THR', 'uri', NULL, 'thr_os_menu', 'payroll_outsource/thr_os_menu', NULL, NULL, NULL, NULL, '109', '0', '1', '', '4', 'system', NOW()
WHERE NOT EXISTS (
	SELECT 1 FROM `user_menu` WHERE `module_name` = 'thr_os_menu'
);

UPDATE `user_menu` SET `um_order` = '5' WHERE `module_name` = 'pembayaran_gaji_os_menu' AND `parent_id` = '109';
UPDATE `user_menu` SET `um_order` = '6' WHERE `module_name` = 'pembayaran_lembur_os_menu' AND `parent_id` = '109';
UPDATE `user_menu` SET `um_order` = '7' WHERE `module_name` = 'history_bpjs_menu' AND `parent_id` = '109';
UPDATE `user_menu` SET `um_order` = '8' WHERE `module_name` = 'spt_os_menu' AND `parent_id` = '109';
UPDATE `user_menu` SET `um_order` = '9' WHERE `module_name` = 'invoice_menu' AND `parent_id` = '109';

INSERT INTO `user_akses_role` (`role_id`, `user_menu_id`, `view`, `add`, `edit`, `del`, `detail`, `eksport`, `import`, `insert_by`, `date_insert`)
SELECT r.`role_id`, m.`user_menu_id`, r.`view`, r.`add`, r.`edit`, r.`del`, r.`detail`, r.`eksport`, r.`import`, 'system', NOW()
FROM `user_akses_role` r
JOIN `user_menu` oldm ON oldm.`user_menu_id` = r.`user_menu_id` AND oldm.`module_name` = 'bonus_thr_os_menu'
JOIN `user_menu` m ON m.`module_name` IN ('bonus_os_menu', 'thr_os_menu')
LEFT JOIN `user_akses_role` existing ON existing.`role_id` = r.`role_id` AND existing.`user_menu_id` = m.`user_menu_id`
WHERE existing.`user_akses_id` IS NULL;
