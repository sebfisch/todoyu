CREATE TABLE `ext_projectbilling_type` (
`id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`deleted` TINYINT( 1 ) UNSIGNED NOT NULL ,
`key` VARCHAR( 15 ) NOT NULL ,
`title` VARCHAR( 50 ) NOT NULL
) ENGINE = MYISAM

;

INSERT INTO `ext_projectbilling_type` ( `id` , `deleted` , `key` , `title` ) VALUES
(1 , '0', 'normal', 'billing.type.normal'),
(2 , '0', 'notbilled', 'billing.type.notbilled'),
(3 , '0', 'bug', 'billing.type.bug'),
(4 , '0', 'feature', 'billing.type.feature'),
(5 , '0', 'unknown', 'billing.type.unknown')

;