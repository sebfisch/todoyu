
CREATE TABLE `ext_portal_tab` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `deleted` tinyint(2) NOT NULL default '0',
  `key` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `id_user` smallint(5) unsigned NOT NULL,
  `usergroups` varchar(16) NOT NULL,
  `class` varchar(32) NOT NULL,
  `title` varchar(64) NOT NULL,
  `is_or` tinyint(1) NOT NULL default '1',
  `sorting` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;
