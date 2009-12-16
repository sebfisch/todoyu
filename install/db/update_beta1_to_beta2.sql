--
-- Rename ext_filter_.. tables to ext_search...
--

RENAME TABLE `ext_filter_set` TO `ext_search_filterset`;
RENAME TABLE `ext_filter_condition` TO `ext_search_filtercondition`;

--
-- Rename id_user to id_user_create in filterset
--

ALTER TABLE `ext_search_filterset` CHANGE `id_user` `id_user_create` SMALLINT( 5 ) UNSIGNED NOT NULL;

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
ALTER TABLE `ext_timetracking_track` CHANGE `id_user` `id_user_create` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' ;


--
-- Add date_track to timetracking and set values of date_create
--
ALTER TABLE `ext_timetracking_track` ADD `date_track` INT( 10 ) NOT NULL DEFAULT '0' AFTER `date_create` ;
UPDATE `ext_timetracking_track` SET `date_track` = `date_create`;
