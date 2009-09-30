RENAME TABLE `project_person_mm`  TO `ext_project_mm_project_user`

;

ALTER TABLE	`ext_project_mm_project_user`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `project` `id_project` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `person` `id_user` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `projectrole` `id_userrole` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `comment` `comment` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

;

ALTER TABLE	`ext_project_mm_project_user` 
DROP `last_modified`,
DROP `crdate`,
DROP `cruser`,
DROP `deleted`

