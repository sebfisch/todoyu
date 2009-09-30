RENAME TABLE `customer_contactinfo_mm`  TO `ext_user_mm_customer_contactinfo`

;

ALTER TABLE	`ext_user_mm_customer_contactinfo`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `customer` `id_customer` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `contactinfo` `id_contactinfo` INT UNSIGNED NOT NULL

;

ALTER TABLE	`ext_user_mm_customer_contactinfo` 
DROP `last_modified` ,
DROP `cruser` ,
DROP `crdate`,
DROP `deleted`