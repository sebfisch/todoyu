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


--
-- Rename id_user to id_user_create in timetracking
--
ALTER TABLE `ext_timetracking_track` CHANGE `id_user` `id_user_create` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0'