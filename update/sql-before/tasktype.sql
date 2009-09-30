RENAME TABLE `tasktype`  TO `ext_project_worktype`

;

ALTER TABLE `ext_project_worktype`
CHANGE `uid` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `deleted` `deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `title` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `type` `type` TINYINT(2) UNSIGNED NOT NULL

;

ALTER TABLE	`ext_project_worktype`
DROP `hidden`