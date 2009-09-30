RENAME TABLE `fixed_project`  TO `ext_fixed_project`

;

ALTER TABLE `ext_fixed_project`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `deleted` `deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `title` `title` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `creation_period` `creation_period` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `customer` `id_customer` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `description` `description` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `support_auto_disclosure` `add_supporttask` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `status` `status` TINYINT(2) UNSIGNED NOT NULL,
CHANGE `autostatus_on_expiry` `status_finish` TINYINT(2) UNSIGNED NOT NULL,
CHANGE `fixed_amount` `fixedcosts` INT UNSIGNED NOT NULL,
CHANGE `fixed_amount_is_paid` `is_fixedcosts_paid` BOOL NOT NULL DEFAULT '0' ,
CHANGE `hoster` `id_customer_hoster` INT NOT NULL DEFAULT '0' ,
CHANGE `domain` `domain` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `rateset` `id_rateset` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `settlement_interval` `ext_projectbilling_settlementinterval` INT UNSIGNED NOT NULL,
CHANGE `project_manager` `id_user_manager` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `project_supervisor` `id_user_supervisor` INT UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_fixed_project`
DROP `hidden`