RENAME TABLE `invoiceitem`  TO `ext_projectbilling_invoiceitem`

;

ALTER TABLE	`ext_projectbilling_invoiceitem`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `invoice` `id_invoice` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `prepayment` `id_prepayment` INT UNSIGNED NOT NULL ,
CHANGE `task` `id_task` INT UNSIGNED NOT NULL ,
CHANGE `unit` `id_invoiceunit` INT UNSIGNED NOT NULL ,
CHANGE `descr` `infotext` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `price` `id_rateset` TINYINT( 2 ) UNSIGNED NOT NULL ,
CHANGE `quantity` `hours` FLOAT NOT NULL

;

ALTER TABLE	`ext_projectbilling_invoiceitem`
DROP `date`
