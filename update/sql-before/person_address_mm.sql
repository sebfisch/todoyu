RENAME TABLE `person_address_mm`  TO `ext_user_mm_user_address`

;

ALTER TABLE	`ext_user_mm_user_address`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `person` `id_user` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `address` `id_address` INT UNSIGNED NOT NULL

;

ALTER TABLE	`ext_user_mm_user_address` 
DROP `last_modified` ,
DROP `cruser` ,
DROP `crdate`,
DROP `deleted`