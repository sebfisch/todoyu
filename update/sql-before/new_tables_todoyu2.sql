
CREATE TABLE `log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date_create` int(10) unsigned NOT NULL,
  `requestkey` varchar(8) NOT NULL,
  `id_user` smallint(5) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `file` varchar(100) NOT NULL,
  `line` smallint(5) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 

;

CREATE TABLE `preference` (
  `id_user` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `preference` varchar(30) NOT NULL,
  `value` varchar(150) NOT NULL,
  KEY `fast` (`id_user`,`ext`,`preference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

;


CREATE TABLE `right` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ext` smallint(5) unsigned NOT NULL default '0',
  `right` tinyint(3) unsigned NOT NULL default '0',
  `id_group` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ext` (`ext`,`id_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 

