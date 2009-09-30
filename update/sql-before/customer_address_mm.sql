RENAME TABLE `customer_address_mm`  TO `ext_user_mm_customer_address`

;

ALTER TABLE	`ext_user_mm_customer_address`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `customer` `id_customer` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `address` `id_address` INT UNSIGNED NOT NULL

;

ALTER TABLE	`ext_user_mm_customer_address` 
DROP `last_modified` ,
DROP `cruser` ,
DROP `crdate`,
DROP `deleted`