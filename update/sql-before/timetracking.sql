RENAME TABLE `timetracking`  TO `ext_timetracking_track`

;

ALTER TABLE	`ext_timetracking_track`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `cruser` `id_user` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `date` `date` INT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `task` `id_task` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `workload` `workload_tracked` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `chargeable_workload` `workload_chargeable` INT UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_timetracking_track`
DROP `hidden`,
DROP `deleted`,
DROP `person`,
DROP `is_cleared`
