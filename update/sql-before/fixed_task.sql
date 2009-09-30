RENAME TABLE `fixed_task`  TO `ext_fixed_task`

;

ALTER TABLE `ext_fixed_task`
CHANGE `uid` `id` INT(11) NOT NULL AUTO_INCREMENT,
CHANGE `last_modified` `date_update` INT(11) UNSIGNED NOT NULL DEFAULT '0', 
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `crdate` `date_create` INT(11) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `deleted` `deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `hidden` `hidden` TINYINT(1) NOT NULL DEFAULT '0',
CHANGE `active` `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `title` `title` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `creation_period` `creation_period` TINYINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `consignee_usergroup` `id_usergroup` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `consignee_usergroup_secondary` `id_usergroup_secondary` MEDIUMINT(11) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `assign_ext_users` `assign_externals` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `auto_statuschange_afterendtime` `status_finish` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `fixed_project` `id_fixedproject` INT(11) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `tasktype` `id_worktype` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `description` `description` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `estimated_workload_static` `estimated_workload_static` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `workload_generic` `workload_generic` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `billing_type` `billingtype` TINYINT UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_fixed_task` 
DROP `hidden` ,
DROP `offered` ,
DROP `accesslevel` ,
DROP `est_workload_accesslevel` ,
DROP `fixed_bookmark` 





;