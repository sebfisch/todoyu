RENAME TABLE `prepayment`  TO `ext_projectbilling_prepayment`

;

ALTER TABLE	`ext_projectbilling_prepayment`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `project` `id_project` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `date` `date_paid` INT UNSIGNED NOT NULL ,
CHANGE `is_paid` `is_paid` BOOL NOT NULL DEFAULT '0' ,
CHANGE `is_used` `is_consumed` BOOL NOT NULL DEFAULT '0'
