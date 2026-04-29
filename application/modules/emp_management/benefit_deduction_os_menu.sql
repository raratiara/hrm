CREATE TABLE IF NOT EXISTS salary_components_os LIKE salary_components;

INSERT INTO salary_components_os
SELECT *
FROM salary_components src
WHERE NOT EXISTS (
	SELECT 1
	FROM salary_components_os dst
	WHERE dst.id = src.id
);

CREATE TABLE IF NOT EXISTS employee_benefit_deduction_os (
	id INT(11) NOT NULL AUTO_INCREMENT,
	employee_id VARCHAR(45) DEFAULT NULL,
	salary_components_id INT(11) DEFAULT NULL,
	amount VARCHAR(45) DEFAULT NULL,
	PRIMARY KEY (id),
	KEY idx_employee_component (employee_id, salary_components_id)
);

SET @parent_id := (
	SELECT user_menu_id
	FROM user_menu
	WHERE module_name = 'emp_management'
		OR url = 'emp_management'
		OR title = 'Employee Management'
	ORDER BY user_menu_id
	LIMIT 1
);

INSERT INTO user_menu
	(`title`, `link_type`, `module_name`, `url`, `parent_id`, `is_parent`, `show_menu`, `um_class`, `um_order`, `insert_by`)
SELECT
	'Benefit/Deduction OS',
	'page',
	'benefit_deduction_os_menu',
	'emp_management/benefit_deduction_os_menu',
	@parent_id,
	0,
	1,
	'fa fa-money',
	3,
	'system'
WHERE @parent_id IS NOT NULL
	AND NOT EXISTS (
		SELECT 1
		FROM user_menu
		WHERE module_name = 'benefit_deduction_os_menu'
	);

UPDATE user_menu
SET um_order = '3'
WHERE module_name = 'benefit_deduction_os_menu';

SET @menu_id := (
	SELECT user_menu_id
	FROM user_menu
	WHERE module_name = 'benefit_deduction_os_menu'
	LIMIT 1
);

INSERT INTO user_akses_role
	(`role_id`, `user_menu_id`, `view`, `add`, `edit`, `del`, `detail`, `import`, `eksport`, `insert_by`)
SELECT
	id,
	@menu_id,
	1,
	0,
	1,
	0,
	0,
	0,
	0,
	'system'
FROM user_group
WHERE @menu_id IS NOT NULL
	AND id IN (1, 4)
	AND NOT EXISTS (
		SELECT 1
		FROM user_akses_role
		WHERE role_id = user_group.id
			AND user_menu_id = @menu_id
	);
