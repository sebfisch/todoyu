RENAME TABLE `ext_timetracking_tracking`  TO `ext_timetracking_active` ;
RENAME TABLE `ext_comment_feedback` TO `ext_comment_mm_comment_feedback` ;
RENAME TABLE `system_errorlog` TO `system_log_error` ;
DROP TABLE `system_log` ;