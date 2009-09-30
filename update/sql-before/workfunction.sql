RENAME TABLE `workfunction`  TO `ext_user_jobtype`

;

ALTER TABLE	`ext_user_jobtype`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `workfunction` `title` VARCHAR( 48 ) NOT NULL

;

ALTER TABLE	`ext_user_jobtype`
DROP `hidden` ,
DROP `last_modified` ,
DROP `cruser` ,
DROP `crdate`

;