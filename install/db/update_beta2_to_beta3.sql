--
-- Remove type field from user
--
ALTER TABLE `ext_user_user` DROP `type`;

--
-- Rename customer to company
--
RENAME TABLE `ext_user_customer` TO `ext_user_company` ;
RENAME TABLE `ext_user_mm_customer_address` TO `ext_user_mm_company_address` ;
RENAME TABLE `ext_user_mm_customer_contactinfo` TO `ext_user_mm_company_contactinfo` ;
RENAME TABLE `ext_user_mm_customer_user` TO `ext_user_mm_company_user` ;
DROP TABLE `ext_user_customerrole`;

--
-- Rename id_customer to id_company
--
ALTER TABLE `ext_user_mm_company_address` CHANGE `id_customer` `id_company` SMALLINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_user_mm_company_contactinfo` CHANGE `id_customer` `id_company` SMALLINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_user_mm_company_user` CHANGE `id_customer` `id_company` SMALLINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_project_project` CHANGE `id_customer` `id_company` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';

--
-- Add internal flag to company
--
ALTER TABLE `ext_user_company` ADD `is_internal` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

--
-- Change eventtype to text
--
ALTER TABLE `ext_calendar_event` CHANGE `eventtype` `eventtype` VARCHAR( 20 ) NOT NULL