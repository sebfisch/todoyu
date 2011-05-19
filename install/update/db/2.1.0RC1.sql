UPDATE `ext_project_task` SET `status` = 2 WHERE `type` = 2 AND `status` = 0;
ALTER TABLE `ext_project_role` DROP `rolekey`;