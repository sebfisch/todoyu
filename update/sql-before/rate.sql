RENAME TABLE `rate`  TO `ext_projectbilling_rate`

;

ALTER TABLE	`ext_projectbilling_rate`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `tasktype` `id_worktype` INT UNSIGNED NOT NULL ,
CHANGE `hourly_rate` `rate` FLOAT NOT NULL ,
CHANGE `rateset` `id_rateset` INT UNSIGNED NOT NULL
