
CREATE TABLE `ext_calendar_event` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_series` int(10) unsigned NOT NULL,
  `date_update` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `id_user_create` int(10) unsigned NOT NULL default '0',
  `id_project` smallint(6) NOT NULL default '0',
  `id_task` int(10) unsigned NOT NULL,
  `eventtype` tinyint(3) unsigned NOT NULL default '1',
  `title` varchar(255) NOT NULL,
  `description` text,
  `place` varchar(255) NOT NULL,
  `date_start` int(10) unsigned NOT NULL default '0',
  `date_end` int(10) unsigned NOT NULL default '0',
  `is_private` tinyint(1) unsigned NOT NULL default '0',
  `is_dayevent` tinyint(1) unsigned NOT NULL default '0',
  `is_public` tinyint(1) unsigned NOT NULL default '0',
  `is_repeated` tinyint(1) unsigned NOT NULL default '0'
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=172 ;


CREATE TABLE `ext_calendar_mm_event_user` (
  `id` int(11) NOT NULL auto_increment,
  `id_event` int(10) unsigned NOT NULL default '0',
  `id_user` int(10) unsigned NOT NULL default '0',
  `is_acknowledged` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid_local` (`id_event`),
  KEY `uid_foreign` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

