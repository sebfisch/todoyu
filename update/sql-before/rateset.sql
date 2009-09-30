RENAME TABLE `rateset`  TO `ext_projectbilling_rateset`

;

ALTER TABLE	`ext_projectbilling_rateset`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `name` `title` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `default_rate` `rate` FLOAT NOT NULL ,
CHANGE `autocreateprepayment_amount` `prepayment_price` FLOAT NOT NULL ,
CHANGE `autocreateprepayment_text` `prepayment_info` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL