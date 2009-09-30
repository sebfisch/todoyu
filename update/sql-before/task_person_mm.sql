RENAME TABLE `task_person_mm`  TO `ext_agenda_mm_task_user`

;

ALTER TABLE	`ext_agenda_mm_task_user`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `task` `id_task` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `person` `id_user` INT UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_agenda_mm_task_user` 
DROP `last_modified`,
DROP `crdate`,
DROP `cruser`,
DROP `deleted`

