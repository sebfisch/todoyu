RENAME TABLE `currency`  TO `ext_billing_currency`;


ALTER TABLE	`ext_billing_currency`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` INT UNSIGNED NOT NULL ,
CHANGE `title_short` `short` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `currencyrate` `course` FLOAT NOT NULL

;