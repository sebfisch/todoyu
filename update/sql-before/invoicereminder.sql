RENAME TABLE `invoicereminder`  TO `ext_projectbilling_reminder`

;

ALTER TABLE	`ext_projectbilling_reminder`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `project` `id_project` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `date_until` `date_to` INT UNSIGNED NOT NULL ,
CHANGE `isapproved` `is_approved` BOOL NOT NULL DEFAULT '0',
ADD `has_error` TINYINT( 3 ) NOT NULL DEFAULT '0' ,

;

ALTER TABLE	`ext_projectbilling_reminder`
DROP `hidden` ,
DROP `only_type` ,
DROP `label`