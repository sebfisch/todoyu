RENAME TABLE `invoice`  TO `ext_projectbilling_invoice`

;

ALTER TABLE	`ext_projectbilling_invoice`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `project` `id_project` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `name` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `date` `date_invoice` INT UNSIGNED NOT NULL ,
CHANGE `address` `id_address` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `person` `id_user` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `total_amount_text` `text_total` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `reduction_text` `text_reduction` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
