RENAME TABLE `static_countries`  TO `country`

;

ALTER TABLE	`country`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `cn_iso_2` `short` CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `cn_currency_iso_3` `id_currency` CHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `cn_phone` `phone` INT UNSIGNED NOT NULL DEFAULT '0' ,
 CHANGE `cn_short_en` `name_en` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
  CHANGE `cn_short_de` `name_de` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

;

ALTER TABLE	`country` 
DROP `last_modified` ,
DROP `crdate` ,
DROP `cruser` ,
DROP `pid` ,
DROP `cn_iso_3` ,
DROP `cn_iso_nr` ,
DROP `cn_official_name_local` ,
DROP `cn_official_name_en` ,
DROP `cn_capital` ,
DROP `cn_tldomain` ,
DROP `cn_currency_iso_nr` ,
DROP `cn_eu_member` ,
DROP `cn_address_format` ,
DROP `cn_zone_flag` ,
DROP `cn_short_local` ,
DROP `cn_parent_tr_iso_nr` ,
DROP `deleted`
