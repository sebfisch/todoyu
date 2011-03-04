-- Rename tables
RENAME TABLE `ext_timetracking_tracking`  TO `ext_timetracking_active` ;
RENAME TABLE `ext_comment_feedback` TO `ext_comment_mm_comment_feedback` ;
RENAME TABLE `system_errorlog` TO `system_log_error` ;

-- Remove tables
DROP TABLE `system_log` ;

-- Rename worktype to activity --
RENAME TABLE `ext_project_worktype` TO `ext_project_activity` ;
ALTER TABLE `ext_project_task` CHANGE `id_worktype` `id_activity` SMALLINT( 6 ) NOT NULL DEFAULT '0';
ALTER TABLE `ext_project_taskpreset` CHANGE `id_worktype` `id_activity` SMALLINT( 6 ) NOT NULL DEFAULT '0';
ALTER TABLE `ext_projectbilling_rate` CHANGE `id_worktype` `id_activity` SMALLINT( 6 ) UNSIGNED NOT NULL;

-- container status --- (open)
UPDATE ext_project_task SET status = 2 WHERE `type` = 2;

-- locale path ---
UPDATE ext_contact_contactinfotype SET title = REPLACE(title, 'LLL:contact.contactinfo.', 'LLL:contact.ext.contactinfo.');