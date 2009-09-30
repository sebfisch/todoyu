RENAME TABLE `granted_holiday`  TO `ext_user_holiday`

;

ALTER TABLE	`ext_user_holiday`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `name` `title` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

;

ALTER TABLE	`ext_user_holiday` 
DROP `hidden` ,
DROP `affected_country` ,
DROP `affected_region`