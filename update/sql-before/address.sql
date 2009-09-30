RENAME TABLE `address`  TO `ext_user_address`

;

ALTER TABLE	`ext_user_address`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `type` `addresstype` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `country` `id_country` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `is_preferred_address` `is_preferred` BOOL NOT NULL DEFAULT '0' ,
CHANGE `description` `comment` VARCHAR(255) NOT NULL

;

ALTER TABLE `ext_user_address`
ADD `id_holidayset` int(11) NOT NULL default '0'

;


ALTER TABLE	`ext_user_address`
DROP `hidden`,
DROP `person`
