RENAME TABLE `customerrole`  TO `ext_user_customerrole`

;

ALTER TABLE	`ext_user_customerrole`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `name` `title` VARCHAR( 48 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 

;

ALTER TABLE	`ext_user_customerrole` 
DROP `last_modified` ,
DROP `cruser` ,
DROP `crdate`