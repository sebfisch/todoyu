RENAME TABLE `person_usergroup_mm`  TO `ext_user_mm_user_group`

;

ALTER TABLE	`ext_user_mm_user_group`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `person` `id_user` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `usergroup` `id_group` INT UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_user_mm_user_group` 
DROP `last_modified` ,
DROP `cruser` ,
DROP `crdate`,
DROP `deleted`