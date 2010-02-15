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