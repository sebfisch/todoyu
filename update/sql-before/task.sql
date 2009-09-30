RENAME TABLE `task`  TO `ext_project_task`

;

ALTER TABLE	`ext_project_task`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT NOT NULL ,
CHANGE `project` `id_project` SMALLINT NOT NULL DEFAULT '0',
CHANGE `crdate` `date_create` INT NOT NULL DEFAULT '0',
CHANGE `deleted` `deleted` TINYINT(1) NOT NULL DEFAULT '0',
CHANGE `hidden` `hidden` TINYINT(1) NOT NULL DEFAULT '0',
CHANGE `cruser` `id_user_create` SMALLINT NOT NULL DEFAULT '0',
CHANGE `tasknumber` `tasknumber` INT NULL DEFAULT '0',
CHANGE `task` `description` TEXT NOT NULL ,
CHANGE `status` `status` TINYINT NOT NULL,
CHANGE `estimated_workload` `estimated_workload` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `est_workload_accesslevel` `is_estimatedworkload_visibileforcustomer` TINYINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `deadline` `date_deadline` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `starttime` `date_start` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `endtime` `date_end` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `is_fixedtask` `ext_fixed_isfixed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `fixed_task` `ext_fixed_idtask` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `assigned_to` `id_user_assigned` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `offered` `offered_price` FLOAT NOT NULL DEFAULT '0',
CHANGE `offered_accesslevel` `offered_accesslevel` TINYINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `is_offered` `is_offered` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `clearance_state` `clearance_state` TINYINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `parenttask` `id_parenttask` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `title` VARCHAR( 255 ) NOT NULL,
CHANGE `tasktype` `id_worktype` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `type` `type` TINYINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `finish_date` `date_finish` INT UNSIGNED NOT NULL ,
CHANGE `billing_type` `ext_projectbilling_type` TINYINT UNSIGNED NOT NULL ,
CHANGE `private` `is_private` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `accesslevel` `is_public` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `not_on_block` `is_onblock` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `owner` `id_user_owner` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_project_task`
ADD `is_acknowledged` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_project_task`
DROP `hidden`,
DROP `recurrance`,
DROP `was_quicktask`,
DROP `clone_parent`,
DROP `supervisor`

;

-- UPDATE `ext_project_task` SET `is_internal` = 2 WHERE `is_internal` = 1;
-- UPDATE `ext_project_task` SET `is_internal` = 1 WHERE `is_internal` = 0;
-- UPDATE `ext_project_task` SET `is_internal` = 0 WHERE `is_internal` = 2;