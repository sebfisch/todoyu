CREATE TABLE `ext_user_panelwidget` (
  `id_user` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `widget` varchar(50) NOT NULL,  
  `position` tinyint(4) NOT NULL,
  `expanded` tinyint(1) NOT NULL,
  `config` text NOT NULL,
  KEY `fast` (`id_user`,`ext`,`widget`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;