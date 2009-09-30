

CREATE TABLE IF NOT EXISTS __backup_customer_person_mm SELECT * FROM customer_person_mm WHERE 1

;


RENAME TABLE `customer_person_mm`  TO `ext_user_mm_customer_user`

;

ALTER TABLE	`ext_user_mm_customer_user`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `customer` `id_customer` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `person` `id_user` INT UNSIGNED NOT NULL

;






ALTER TABLE	`ext_user_mm_customer_user`
DROP `last_modified` ,
DROP `cruser` ,
DROP `crdate`,
DROP `deleted`,
DROP `is_preferred_person`,
DROP `workfunction`,
DROP `working_country`,
DROP `working_region`,
DROP `employment_start`,
DROP `employment_end`

;
