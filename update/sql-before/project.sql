RENAME TABLE `project`  TO `ext_project_project`

;

ALTER TABLE `ext_project_project`
CHANGE `uid` `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `deleted` `deleted` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `title` VARCHAR( 255 ) NOT NULL ,
CHANGE `description` `description` MEDIUMTEXT NOT NULL ,
CHANGE `is_fixedproject` `is_fixed` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `fixed_project` `id_fixedproject` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `status` `status` TINYINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `hoster` `ext_hosting_hoster` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `rateset` `id_rateset` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `customer` `id_customer` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `domain` `ext_hosting_domain` VARCHAR( 100 ) NOT NULL ,
CHANGE `settlement_interval` `ext_projectbilling_settlementinterval` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `starttime` `date_start` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `endtime` `date_end` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `deadline` `date_deadline` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `fixed_amount` `ext_projectbilling_fixedcosts` FLOAT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `fixed_amount_is_paid` `ext_projectbilling_fixedcosts_paid` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `finish_date` `date_finish` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `reduction` `ext_projectbilling_reduction` SMALLINT UNSIGNED NOT NULL DEFAULT '0'

;


ALTER TABLE	`ext_project_project`
DROP `hidden`,
DROP `project_manager`,
DROP `reduction_text`

;