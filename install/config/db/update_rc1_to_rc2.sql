ALTER TABLE `ext_user_company` DROP `is_ngo`;

UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.email_business' WHERE `title` = 'user.contactinfo.email_business' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.tel_private' WHERE `title` = 'user.contactinfo.tel_private' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.tel_exchange' WHERE `title` = 'user.contactinfo.tel_exchange' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.tel_business' WHERE `title` = 'user.contactinfo.tel_business' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.email_private' WHERE `title` = 'user.contactinfo.email_private' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.mobile_business' WHERE `title` = 'user.contactinfo.mobile_business' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.fax_private' WHERE `title` = 'user.contactinfo.fax_private' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.fax_business' WHERE `title` = 'user.contactinfo.fax_business' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.mobile_private' WHERE `title` = 'user.contactinfo.mobile_private' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.fax_exchange' WHERE `title` = 'user.contactinfo.fax_exchange' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.website' WHERE `title` = 'user.contactinfo.website' ;
UPDATE `ext_user_contactinfotype` SET `title` = 'LLL:user.contactinfo.skype' WHERE `title` = 'user.contactinfo.skype' ;

ALTER TABLE `ext_user_contactinfotype` ADD `category` smallint(5) unsigned NOT NULL ;

UPDATE `ext_user_contactinfotype` SET `category` = '1' WHERE `title` = 'LLL:user.contactinfo.email_business' ;
UPDATE `ext_user_contactinfotype` SET `category` = '2' WHERE `title` = 'LLL:user.contactinfo.tel_private' ;
UPDATE `ext_user_contactinfotype` SET `category` = '2' WHERE `title` = 'LLL:user.contactinfo.tel_exchange' ;
UPDATE `ext_user_contactinfotype` SET `category` = '2' WHERE `title` = 'LLL:user.contactinfo.tel_business' ;
UPDATE `ext_user_contactinfotype` SET `category` = '1' WHERE `title` = 'LLL:user.contactinfo.email_private' ;
UPDATE `ext_user_contactinfotype` SET `category` = '2' WHERE `title` = 'LLL:user.contactinfo.mobile_business' ;
UPDATE `ext_user_contactinfotype` SET `category` = '2' WHERE `title` = 'LLL:user.contactinfo.fax_private' ;
UPDATE `ext_user_contactinfotype` SET `category` = '2' WHERE `title` = 'LLL:user.contactinfo.fax_business' ;
UPDATE `ext_user_contactinfotype` SET `category` = '2' WHERE `title` = 'LLL:user.contactinfo.mobile_private' ;
UPDATE `ext_user_contactinfotype` SET `category` = '2' WHERE `title` = 'LLL:user.contactinfo.fax_exchange' ;
UPDATE `ext_user_contactinfotype` SET `category` = '3' WHERE `title` = 'LLL:user.contactinfo.website' ;
UPDATE `ext_user_contactinfotype` SET `category` = '3' WHERE `title` = 'LLL:user.contactinfo.skype' ;

--
-- Rename user extension tables
--
RENAME TABLE `ext_user_address`  TO `ext_contact_address` ;
RENAME TABLE `ext_user_company`  TO `ext_contact_company` ;
RENAME TABLE `ext_user_contactinfo`  TO `ext_contact_contactinfo` ;
RENAME TABLE `ext_user_contactinfotype`  TO `ext_contact_contactinfotype` ;
RENAME TABLE `ext_user_jobtype`  TO `ext_contact_jobtype` ;
RENAME TABLE `ext_user_group`  TO `system_role` ;
RENAME TABLE `ext_user_mm_company_address`  TO `ext_contact_mm_company_address` ;
RENAME TABLE `ext_user_mm_company_contactinfo`  TO `ext_contact_mm_company_contactinfo` ;
RENAME TABLE `ext_user_mm_company_user`  TO `ext_contact_mm_company_person` ;
RENAME TABLE `ext_user_mm_user_address`  TO `ext_contact_mm_person_address` ;
RENAME TABLE `ext_user_panelwidget`  TO `system_panelwidget` ;
RENAME TABLE `ext_user_mm_user_contactinfo`  TO `ext_contact_mm_person_contactinfo` ;
RENAME TABLE `ext_user_mm_user_group`  TO `ext_contact_mm_person_role` ;
RENAME TABLE `ext_user_preference`  TO `system_preference` ;
RENAME TABLE `ext_user_right`  TO `system_right` ;
RENAME TABLE `ext_user_user`  TO `ext_contact_person` ;

--
-- Rename all id_user_create
--
ALTER TABLE `ext_assets_asset` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_bookmark_bookmark` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_calendar_event` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_calendar_holiday` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_calendar_holidayset` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_comment_comment` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_comment_feedback` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_comment_mailed` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_contact_address` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_contact_company` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_contact_contactinfo` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_contact_contactinfotype` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_contact_person` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_project_project` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_project_task` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_project_userrole` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_project_worktype` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_search_filtercondition` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_search_filterset` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_timetracking_track` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_timetracking_tracking` CHANGE `id_user_create` `id_person_create` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';


