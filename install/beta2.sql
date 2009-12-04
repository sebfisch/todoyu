--
-- Add sorting column to task
--

ALTER TABLE `ext_project_task` ADD `sorting` SMALLINT UNSIGNED NOT NULL;
UPDATE `ext_project_task` SET `sorting` = `tasknumber`;

--
-- Drop portal tables
--
DROP TABLE `ext_portal_mm_tab_filterset`;
DROP TABLE `ext_portal_tab`;