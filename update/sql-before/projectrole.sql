DROP TABLE `projectrole`

;

CREATE TABLE `ext_project_userrole` (
  `id` int(11) NOT NULL auto_increment,
  `rolekey` varchar(35) NOT NULL,
  `title` varchar(60) NOT NULL,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8