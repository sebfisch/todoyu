RENAME TABLE `settlement_type`  TO `ext_projectbilling_settlement`

;

ALTER TABLE	`ext_projectbilling_settlement`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `name` `title` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

;

ALTER TABLE	`ext_projectbilling_settlement`
DROP `payment_interval`


