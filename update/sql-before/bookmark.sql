RENAME TABLE `bookmark`  TO `ext_bookmark_bookmark`

;

ALTER TABLE	`ext_bookmark_bookmark`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `cruser` `id_user_create` INT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `task` `id_item` INT UNSIGNED NOT NULL 

;

ALTER TABLE `ext_bookmark_bookmark`
ADD `type` TINYINT( 1 ) NOT NULL AFTER `date_create`

;

ALTER TABLE	`ext_bookmark_bookmark` 
DROP `last_modified` ,
DROP `title`

;