-- Select user data

SELECT `users`.`username`,
	`groups`.`name` AS `groupname`,
	`parents`.`name` AS `parentname`,
	`widgets`.`name` AS `widgetname`
FROM `sys_user_group_xref` AS `xref` 
	INNER JOIN `sys_users` AS `users` ON `xref`.`user_id` = `users`.`id`
	INNER JOIN `sys_user_groups` AS `groups` ON `xref`.`group_id` = `groups`.`id`
	INNER JOIN `sys_user_groups` AS `parents` ON  `groups`.`left`  >= `parents`.`left` AND `groups`.`right` <= `parents`.`right`
	INNER JOIN `app_widget_access` AS `access` ON `access`.`group_id` = `parents`.`id`
	INNER JOIN `app_widgets` AS `widgets` ON `access`.`widget_id` = `widgets`.`id`