CREATE TABLE `ext_user_right` (
  `ext` smallint(5) unsigned NOT NULL default '0',
  `id_group` tinyint(3) unsigned NOT NULL default '0',
  `right` tinytext(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ext` (`ext`,`id_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8