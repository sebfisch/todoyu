-- Rename tables
RENAME TABLE `ext_comment_feedback` TO `ext_comment_mm_comment_feedback` ;
RENAME TABLE `system_errorlog` TO `system_log_error` ;

-- Remove tables
DROP TABLE `system_log`;

-- Rename worktype to activity --
RENAME TABLE `ext_project_worktype` TO `ext_project_activity` ;
ALTER TABLE `ext_project_task` CHANGE `id_worktype` `id_activity` SMALLINT( 6 ) NOT NULL DEFAULT '0';
ALTER TABLE `ext_projectbilling_rate` CHANGE `id_worktype` `id_activity` SMALLINT( 6 ) UNSIGNED NOT NULL;

-- container status --- (open)
UPDATE `ext_project_task` SET status = 2 WHERE `type` = 2;

-- locale path ---
UPDATE `ext_contact_contactinfotype` SET `title` = REPLACE(`title`, 'LLL:contact.contactinfo.', 'LLL:contact.ext.contactinfo.');


-- system_panelwidget --
DROP TABLE `system_panelwidget`;

-- Change field type to varchar
ALTER TABLE `ext_timetracking_track` CHANGE `comment` `comment` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

-- Rename preferred to is_preferred
ALTER TABLE `ext_contact_contactinfo` CHANGE `preferred` `is_preferred` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';-- Rename person.active => is_active

-- Rename active to is_active
ALTER TABLE `ext_contact_person` CHANGE `active` `is_active` TINYINT( 1 ) NOT NULL DEFAULT '0';

-- Rename course to rate
ALTER TABLE `ext_currency_exchange` CHANGE `course` `rate` FLOAT UNSIGNED NOT NULL DEFAULT '0';