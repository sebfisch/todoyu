RENAME TABLE `taskcomment`  TO `ext_comment_comment`

;

ALTER TABLE `ext_comment_comment`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `deleted` `deleted` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `cruser` `id_user_create` SMALLINT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `task` `id_task` MEDIUMINT( 9 ) UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `comment` `comment` TEXT NOT NULL ,
CHANGE `visible_for_customers` `is_public` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0'

;

ALTER TABLE	`ext_comment_comment` 
DROP `hidden`,
DROP `feedback_requested_from`

;