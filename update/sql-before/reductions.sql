RENAME TABLE `reductions`  TO `ext_projectbilling_reduction`

;

ALTER TABLE	`ext_projectbilling_reduction`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` INT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `afteryears` `years` INT UNSIGNED NOT NULL ,
CHANGE `inpercent` `percent` INT UNSIGNED NOT NULL ,
CHANGE `isngoreduction` `is_ngoreduction` BOOL NOT NULL DEFAULT '0'

