-- --------------------------------------------------------

--
-- Table structure for table `system_log_error`
--

CREATE TABLE `system_log_error` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`date_create` int(10) unsigned NOT NULL,
	`requestkey` varchar(8) NOT NULL,
	`id_person` int(5) unsigned NOT NULL,
	`level` tinyint(1) unsigned NOT NULL,
	`file` varchar(100) NOT NULL,
	`line` smallint(5) unsigned NOT NULL,
	`message` varchar(255) NOT NULL,
	`data` text NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `static_country`
--

CREATE TABLE `static_country` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`iso_alpha2` char(2) NOT NULL,
	`iso_alpha3` char(3) NOT NULL,
	`iso_num` int(11) unsigned NOT NULL DEFAULT '0',
	`iso_num_currency` char(3) NOT NULL,
	`phone` int(10) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `static_country_zone`
--

CREATE TABLE `static_country_zone` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`iso_alpha2_country` char(2) NOT NULL,
	`iso_alpha3_country` char(3) NOT NULL,
	`iso_num_country` int(11) unsigned NOT NULL DEFAULT '0',
	`code` varchar(15) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `static_territory`
--

CREATE TABLE `static_territory` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`iso_num` int(11) unsigned NOT NULL DEFAULT '0',
	`parent_iso_num` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `static_language`
--

CREATE TABLE `static_language` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	`iso_alpha2` char(2) DEFAULT '' NOT NULL,
	`iso_alpha3` char(3) DEFAULT '' NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `alpha2` (`iso_alpha2`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

--
-- Table structure for table `system_role`
--

CREATE TABLE `system_role` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`date_create` int(10) unsigned NOT NULL DEFAULT '0',
	`date_update` int(10) unsigned NOT NULL,
	`id_person_create` int(10) unsigned NOT NULL,
	`deleted` tinyint(1) NOT NULL DEFAULT '0',
	`title` varchar(32) NOT NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT '0',
	`description` text NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `system_preference`
--

CREATE TABLE `system_preference` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`id_person` int(10) unsigned NOT NULL,
	`ext` smallint(5) unsigned NOT NULL,
	`area` smallint(5) unsigned NOT NULL,
	`preference` varchar(50) NOT NULL,
	`item` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`value` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `fast` (`id_person`,`ext`,`preference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_right`
--

CREATE TABLE `system_right` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ext` smallint(5) unsigned NOT NULL DEFAULT '0',
	`right` tinytext NOT NULL,
	`id_role` tinyint(3) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `ext` (`ext`,`id_role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table  `static_timezone`
--

CREATE TABLE `static_timezone` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`timezone` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_freeze`
--

CREATE TABLE IF NOT EXISTS `system_freeze` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`date_create` int(11) NOT NULL,
	`date_update` int(11) NOT NULL,
	`id_person_create` int(11) NOT NULL,
	`element_type` varchar(255) NOT NULL,
	`element_id` int(11) NOT NULL,
	`data` text NOT NULL,
	`hash` varchar(32) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `original` (`element_type`,`element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_lock`
--

CREATE TABLE `system_lock` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ext` smallint(6) NOT NULL,
	`table` varchar(60) NOT NULL,
	`id_record` int(11) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `tablerecord` (`table`,`id_record`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_log_mail`
--

CREATE TABLE `system_log_email` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`date_create` int(10) unsigned NOT NULL,
	`date_update` int(10) unsigned NOT NULL,
	`id_person_create` int(10) unsigned NOT NULL,
	`ext` smallint(5) unsigned NOT NULL,
	`record_type` smallint(5) unsigned NOT NULL,
	`id_record` int(10) unsigned NOT NULL,
	`id_person_email` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `record` (`id_record`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system_scheduler`
--
CREATE TABLE IF NOT EXISTS `system_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_execute` int(11) NOT NULL,
  `class` varchar(100) NOT NULL,
  `success` tinyint(1) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;