
ALTER TABLE	`history`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `cruser` `id_user_create` INT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `src_table` `table` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `changed_fields` `rowdata` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `record_uid` `id_record` INT UNSIGNED NOT NULL 

;

ALTER TABLE	`history` 
DROP `last_modified` ,
DROP `deleted`


