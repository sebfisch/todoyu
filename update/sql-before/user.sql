
RENAME TABLE `user`  TO `user_backup`

;

CREATE TABLE `ext_user_user` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL default '0',
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `username` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL default '0',
  `is_admin` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `shortname` varchar(11) NOT NULL,
  `gender` varchar(2) NOT NULL,
  `title` varchar(64) NOT NULL,
  `birthday` date NOT NULL,
  `id_workaddress` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
  `id_jobtype` tinyint(1) NOT NULL default '0',
  `ext_resources_efficiency` int(10) unsigned NOT NULL default '0',
  `ext_resources_wl_mon_am` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_mon_pm` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_tue_am` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_tue_pm` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_wed_am` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_wed_pm` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_thu_am` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_thu_pm` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_fri_am` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_fri_pm` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_sat_am` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_sat_pm` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_sun_am` tinyint(3) NOT NULL default '0',
  `ext_resources_wl_sun_pm` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8

;
