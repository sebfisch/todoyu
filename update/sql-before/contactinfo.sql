RENAME TABLE `contactinfo`  TO `ext_user_contactinfo`

;

ALTER TABLE	`ext_user_contactinfo`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `type` `id_contactinfotype` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `contact` `info` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `is_preferred_contactinfo` `preferred` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_user_contactinfo` 
DROP `hidden` ,
DROP `person` ,
DROP `customer`