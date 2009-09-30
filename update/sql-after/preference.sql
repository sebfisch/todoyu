CREATE TABLE `ext_user_preference` (
  `id_user` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `area` smallint(5) unsigned NOT NULL,
  `preference` varchar(50) NOT NULL,
  `item` mediumint(8) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  KEY `fast` (`id_user`,`ext`,`preference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

;