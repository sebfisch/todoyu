RENAME TABLE `person_contactinfo_mm`  TO `ext_user_mm_user_contactinfo`

;

ALTER TABLE	`ext_user_mm_user_contactinfo`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `person` `id_user` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `contactinfo` `id_contactinfo` INT UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_user_mm_user_contactinfo` 
DROP `last_modified` ,
DROP `cruser` ,
DROP `crdate`,
DROP `deleted`