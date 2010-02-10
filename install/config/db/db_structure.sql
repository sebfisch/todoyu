-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 04. Februar 2010 um 15:08
-- Server Version: 5.1.37
-- PHP-Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `rc1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_assets_asset`
--

CREATE TABLE IF NOT EXISTS `ext_assets_asset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `id_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parenttype` tinyint(1) NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `file_ext` varchar(10) NOT NULL,
  `file_storage` varchar(100) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `file_size` int(10) unsigned NOT NULL DEFAULT '0',
  `file_mime` varchar(20) NOT NULL,
  `file_mime_sub` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`id_parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_bookmark_bookmark`
--

CREATE TABLE IF NOT EXISTS `ext_bookmark_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `id_item` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_calendar_event`
--

CREATE TABLE IF NOT EXISTS `ext_calendar_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `id_project` smallint(6) NOT NULL DEFAULT '0',
  `id_task` int(10) unsigned NOT NULL,
  `eventtype` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` text,
  `place` varchar(255) NOT NULL,
  `date_start` int(10) unsigned NOT NULL DEFAULT '0',
  `date_end` int(10) unsigned NOT NULL DEFAULT '0',
  `is_private` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_dayevent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_calendar_holiday`
--

CREATE TABLE IF NOT EXISTS `ext_calendar_holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `title` varchar(48) NOT NULL,
  `description` varchar(256) NOT NULL,
  `workingtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_calendar_holidayset`
--

CREATE TABLE IF NOT EXISTS `ext_calendar_holidayset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(2) NOT NULL,
  `title` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_calendar_mm_event_user`
--

CREATE TABLE IF NOT EXISTS `ext_calendar_mm_event_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_event` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user` int(10) unsigned NOT NULL DEFAULT '0',
  `is_acknowledged` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid_local` (`id_event`),
  KEY `uid_foreign` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_calendar_mm_holiday_holidayset`
--

CREATE TABLE IF NOT EXISTS `ext_calendar_mm_holiday_holidayset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_holiday` int(10) unsigned NOT NULL DEFAULT '0',
  `id_holidayset` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `holiday` (`id_holiday`),
  KEY `holidayset` (`id_holidayset`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=419 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_comment_comment`
--

CREATE TABLE IF NOT EXISTS `ext_comment_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `id_user_create` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id_task` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `is_public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task` (`id_task`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_comment_feedback`
--

CREATE TABLE IF NOT EXISTS `ext_comment_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` mediumint(8) unsigned NOT NULL,
  `id_user_feedback` mediumint(8) unsigned NOT NULL,
  `id_comment` int(10) unsigned NOT NULL,
  `is_seen` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_comment_mailed`
--

CREATE TABLE IF NOT EXISTS `ext_comment_mailed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL,
  `id_user_create` mediumint(8) unsigned NOT NULL,
  `id_comment` int(10) unsigned NOT NULL,
  `id_user_mailed` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_project_mm_project_user`
--

CREATE TABLE IF NOT EXISTS `ext_project_mm_project_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_project` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user` int(10) unsigned NOT NULL DEFAULT '0',
  `id_userrole` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project` (`id_project`),
  KEY `person` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_project_project`
--

CREATE TABLE IF NOT EXISTS `ext_project_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_start` int(10) unsigned NOT NULL DEFAULT '0',
  `date_end` int(10) unsigned NOT NULL DEFAULT '0',
  `date_deadline` int(10) unsigned NOT NULL DEFAULT '0',
  `date_finish` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id_company` smallint(5) unsigned NOT NULL DEFAULT '0',
  `fixedcosts` float unsigned NOT NULL DEFAULT '0',
  `is_fixedcosts_paid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_fixed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `id_fixedproject` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id_rateset` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `company2` (`id_company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_project_task`
--

CREATE TABLE IF NOT EXISTS `ext_project_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(11) NOT NULL DEFAULT '0',
  `date_update` int(11) NOT NULL,
  `id_user_create` smallint(6) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id_project` smallint(6) NOT NULL DEFAULT '0',
  `id_parenttask` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `id_user_assigned` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id_user_owner` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date_deadline` int(10) unsigned NOT NULL DEFAULT '0',
  `date_start` int(10) unsigned NOT NULL DEFAULT '0',
  `date_end` int(10) unsigned NOT NULL DEFAULT '0',
  `date_finish` int(10) unsigned NOT NULL,
  `tasknumber` int(11) DEFAULT '0',
  `status` tinyint(4) NOT NULL,
  `id_worktype` smallint(6) NOT NULL DEFAULT '0',
  `estimated_workload` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `is_estimatedworkload_public` tinyint(1) NOT NULL,
  `is_acknowledged` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `offered_accesslevel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_offered` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `clearance_state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_private` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sorting` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parenttask` (`id_parenttask`),
  KEY `project` (`id_project`),
  KEY `assigned_to` (`id_user_assigned`),
  KEY `cruser` (`id_user_create`),
  KEY `owner` (`id_user_owner`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=151 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_project_userrole`
--

CREATE TABLE IF NOT EXISTS `ext_project_userrole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) NOT NULL,
  `id_user_create` smallint(5) NOT NULL,
  `date_update` int(10) NOT NULL,
  `rolekey` varchar(35) NOT NULL,
  `title` varchar(60) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_project_worktype`
--

CREATE TABLE IF NOT EXISTS `ext_project_worktype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_update` int(11) NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL,
  `type` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_search_filtercondition`
--

CREATE TABLE IF NOT EXISTS `ext_search_filtercondition` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `date_update` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(2) NOT NULL DEFAULT '0',
  `id_set` smallint(5) unsigned NOT NULL,
  `filter` varchar(64) NOT NULL,
  `value` varchar(100) NOT NULL,
  `negate` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=319 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_search_filterset`
--

CREATE TABLE IF NOT EXISTS `ext_search_filterset` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `date_update` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(2) NOT NULL DEFAULT '0',
  `sorting` smallint(5) unsigned NOT NULL,
  `is_hidden` tinyint(2) NOT NULL DEFAULT '0',
  `usergroups` varchar(16) NOT NULL,
  `type` varchar(16) NOT NULL,
  `title` varchar(64) NOT NULL,
  `conjunction` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_sysmanager_extension`
--

CREATE TABLE IF NOT EXISTS `ext_sysmanager_extension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `version` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_timetracking_track`
--

CREATE TABLE IF NOT EXISTS `ext_timetracking_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_track` int(10) unsigned NOT NULL DEFAULT '0',
  `id_task` int(10) unsigned NOT NULL DEFAULT '0',
  `workload_tracked` int(10) unsigned NOT NULL DEFAULT '0',
  `workload_chargeable` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task` (`id_task`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_timetracking_tracking`
--

CREATE TABLE IF NOT EXISTS `ext_timetracking_tracking` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id_task` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_address`
--

CREATE TABLE IF NOT EXISTS `ext_user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(2) NOT NULL,
  `id_addresstype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `street` varchar(128) NOT NULL,
  `postbox` varchar(32) NOT NULL,
  `city` varchar(48) NOT NULL,
  `region` varchar(32) NOT NULL,
  `zip` mediumtext NOT NULL,
  `id_country` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_preferred` tinyint(1) NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL,
  `id_holidayset` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_company`
--

CREATE TABLE IF NOT EXISTS `ext_user_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(2) NOT NULL DEFAULT '0',
  `title` tinytext NOT NULL,
  `shortname` tinytext NOT NULL,
  `id_currency` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_enter` int(10) unsigned NOT NULL DEFAULT '0',
  `is_ngo` tinyint(1) NOT NULL DEFAULT '0',
  `is_internal` tinyint(1) NOT NULL DEFAULT '0',
  `ext_projectbilling_reduction` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_contactinfo`
--

CREATE TABLE IF NOT EXISTS `ext_user_contactinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `id_contactinfotype` tinytext NOT NULL,
  `info` tinytext NOT NULL,
  `preferred` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_contactinfotype`
--

CREATE TABLE IF NOT EXISTS `ext_user_contactinfotype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_create` int(11) NOT NULL,
  `date_create` int(11) NOT NULL,
  `date_update` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `category` smallint(5) unsigned NOT NULL,
  `key` varchar(20) NOT NULL,
  `title` varchar(48) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_group`
--

CREATE TABLE IF NOT EXISTS `ext_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_holiday`
--

CREATE TABLE IF NOT EXISTS `ext_user_holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `title` varchar(48) NOT NULL,
  `description` varchar(256) NOT NULL,
  `countrywide` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `working_hours` varchar(48) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_jobtype`
--

CREATE TABLE IF NOT EXISTS `ext_user_jobtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(48) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_mm_company_address`
--

CREATE TABLE IF NOT EXISTS `ext_user_mm_company_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_company` int(10) unsigned NOT NULL DEFAULT '0',
  `id_address` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_mm_company_contactinfo`
--

CREATE TABLE IF NOT EXISTS `ext_user_mm_company_contactinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_company` int(10) unsigned NOT NULL DEFAULT '0',
  `id_contactinfo` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=90 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_mm_company_user`
--

CREATE TABLE IF NOT EXISTS `ext_user_mm_company_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_company` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user` int(10) unsigned NOT NULL,
  `id_workaddress` smallint(6) NOT NULL,
  `id_jobtype` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_mm_user_address`
--

CREATE TABLE IF NOT EXISTS `ext_user_mm_user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL DEFAULT '0',
  `id_address` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_mm_user_contactinfo`
--

CREATE TABLE IF NOT EXISTS `ext_user_mm_user_contactinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL DEFAULT '0',
  `id_contactinfo` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=90 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_mm_user_group`
--

CREATE TABLE IF NOT EXISTS `ext_user_mm_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL DEFAULT '0',
  `id_group` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_panelwidget`
--

CREATE TABLE IF NOT EXISTS `ext_user_panelwidget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `widget` varchar(50) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `expanded` tinyint(1) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fast` (`id_user`,`ext`,`widget`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_preference`
--

CREATE TABLE IF NOT EXISTS `ext_user_preference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `area` smallint(5) unsigned NOT NULL,
  `preference` varchar(50) NOT NULL,
  `item` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fast` (`id_user`,`ext`,`preference`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2286 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_right`
--

CREATE TABLE IF NOT EXISTS `ext_user_right` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ext` smallint(5) unsigned NOT NULL DEFAULT '0',
  `right` tinytext NOT NULL,
  `id_group` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ext` (`ext`,`id_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=922 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ext_user_user`
--

CREATE TABLE IF NOT EXISTS `ext_user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(2) NOT NULL DEFAULT '0',
  `username` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `shortname` varchar(11) NOT NULL,
  `gender` varchar(2) NOT NULL,
  `title` varchar(64) NOT NULL,
  `birthday` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `static_country`
--

CREATE TABLE IF NOT EXISTS `static_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso_alpha2` char(2) NOT NULL,
  `iso_alpha3` char(3) NOT NULL,
  `iso_num` int(11) unsigned NOT NULL DEFAULT '0',
  `iso_num_currency` char(3) NOT NULL,
  `phone` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=242 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `static_country_zone`
--

CREATE TABLE IF NOT EXISTS `static_country_zone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha2_country` char(2) NOT NULL,
  `iso_alpha3_country` char(3) NOT NULL,
  `iso_num_country` int(11) unsigned NOT NULL DEFAULT '0',
  `code` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=483 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `static_currency`
--

CREATE TABLE IF NOT EXISTS `static_currency` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha` char(3) DEFAULT '',
  `iso_num` int(11) unsigned DEFAULT '0',
  `symbol_left` varchar(12) DEFAULT '',
  `symbol_right` varchar(12) DEFAULT '',
  `thousands_point` char(1) DEFAULT '',
  `decimal_point` char(1) DEFAULT '',
  `decimal_digits` tinyint(3) unsigned DEFAULT '0',
  `sub_divisor` int(11) DEFAULT '1',
  `sub_symbol_left` varchar(12) DEFAULT '',
  `sub_symbol_right` varchar(12) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=176 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `static_language`
--

CREATE TABLE IF NOT EXISTS `static_language` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha2` char(2) NOT NULL DEFAULT '',
  `iso_alpha3` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha2` (`iso_alpha2`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=185 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `static_territory`
--

CREATE TABLE IF NOT EXISTS `static_territory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso_num` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_iso_num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `system_errorlog`
--

CREATE TABLE IF NOT EXISTS `system_errorlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL,
  `requestkey` varchar(8) NOT NULL,
  `id_user` smallint(5) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `file` varchar(100) NOT NULL,
  `line` smallint(5) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `system_log`
--

CREATE TABLE IF NOT EXISTS `system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` int(10) unsigned NOT NULL,
  `table` varchar(20) NOT NULL,
  `id_record` int(10) unsigned NOT NULL,
  `rowdata` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
