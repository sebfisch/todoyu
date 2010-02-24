--
-- Rename history to system_log
-- Rename log to system_errorlog
--
RENAME TABLE `history`  TO `system_log` ;
RENAME TABLE `log`  TO `system_errorlog` ;

--
-- Drop not yet used columns in system_log
--
ALTER TABLE `system_log`
DROP `table` ,
DROP `id_record` ,
DROP `rowdata` ;

--
--Rename working hours to workingtime in ext_calendar_holiday
--

ALTER TABLE `ext_calendar_holiday` CHANGE `workinghours` `workingtime` INT( 11 ) NOT NULL 
