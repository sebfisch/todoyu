RENAME TABLE `taskasset`  TO `ext_assets_asset`

;

ALTER TABLE `ext_assets_asset`
CHANGE `uid` `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `task` `id_parent` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0' ,
CHANGE `downloadable_for_customer` `is_public` BOOL NOT NULL DEFAULT '0' ,
CHANGE `file_type` `file_ext` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `file_name` `file_storage` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `file_dl_name` `file_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `file_size` `file_size` INT UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE `ext_assets_asset`
ADD `id_old_version` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
ADD `file_mime` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
ADD `file_mime_sub` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
ADD `parenttype` TINYINT (1) UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_assets_asset` 
DROP `hidden`,
DROP `upload_failure`,
DROP `project`