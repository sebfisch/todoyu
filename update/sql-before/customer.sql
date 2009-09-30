RENAME TABLE `customer`  TO `ext_user_customer`

;

ALTER TABLE	`ext_user_customer`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `company_name` `title` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `initials` `shortname` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `currency` `id_currency` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `enteringdate` `date_enter` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `isngo` `is_ngo` BOOL NOT NULL DEFAULT '0' ,
CHANGE `reduction` `ext_projectbilling_reduction` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_user_customer`
DROP `hidden`,
DROP `user`
