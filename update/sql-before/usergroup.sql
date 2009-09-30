RENAME TABLE `usergroup`  TO `ext_user_group`

;

ALTER TABLE	`ext_user_group`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `name` `title` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
ADD `is_fixed` BOOL NOT NULL DEFAULT '0',
ADD `key` VARCHAR( 20 ) NOT NULL


;

ALTER TABLE	`ext_user_group`
DROP `permissions` ,
DROP `groupkey` ,
DROP `accesslevel`
