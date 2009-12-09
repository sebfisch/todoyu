-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5+lenny1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 31. Juli 2009 um 10:29
-- Server Version: 5.0.51
-- PHP-Version: 5.2.6-1+lenny3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `todoyu`
--

-- --------------------------------------------------------

--
-- Table structure for table `ext_assets_asset`
--

DROP TABLE IF EXISTS `ext_assets_asset`;
CREATE TABLE `ext_assets_asset` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_parent` int(10) unsigned NOT NULL default '0',
  `parenttype` tinyint(1) NOT NULL,
  `date_update` int(10) unsigned NOT NULL default '0',
  `id_user_create` smallint(5) unsigned NOT NULL default '0',
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `is_public` tinyint(1) NOT NULL default '0',
  `file_ext` varchar(10) NOT NULL,
  `file_storage` varchar(100) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `file_size` int(10) unsigned NOT NULL default '0',
  `id_old_version` mediumint(8) unsigned NOT NULL default '0',
  `file_mime` varchar(20) NOT NULL,
  `file_mime_sub` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parent` (`id_parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_billing_currency`
--

DROP TABLE IF EXISTS `ext_billing_currency`;
CREATE TABLE `ext_billing_currency` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `deleted` tinyint(2) NOT NULL,
  `short` varchar(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `course` float NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_billing_invoicereminder`
--

DROP TABLE IF EXISTS `ext_billing_invoicereminder`;
CREATE TABLE `ext_billing_invoicereminder` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `id_project` int(10) unsigned NOT NULL default '0',
  `date_from` int(11) NOT NULL,
  `date_to` int(10) unsigned NOT NULL,
  `is_approved` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_bookmark_bookmark`
--

DROP TABLE IF EXISTS `ext_bookmark_bookmark`;
CREATE TABLE `ext_bookmark_bookmark` (
  `id` int(11) NOT NULL auto_increment,
  `id_user_create` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `type` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `id_item` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_calendar_event`
--

DROP TABLE IF EXISTS `ext_calendar_event`;
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
  `is_private` tinyint(3) unsigned NOT NULL default '0',
  `is_dayevent` tinyint(3) unsigned NOT NULL default '0',
  `is_public` tinyint(1) unsigned NOT NULL default '0',
  `is_repeated` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_calendar_holiday`
--

DROP TABLE IF EXISTS `ext_calendar_holiday`;
CREATE TABLE `ext_calendar_holiday` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `title` varchar(48) NOT NULL,
  `description` varchar(256) NOT NULL,
  `workinghours` varchar(48) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_calendar_holidayset`
--

DROP TABLE IF EXISTS `ext_calendar_holidayset`;
CREATE TABLE `ext_calendar_holidayset` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL,
  `title` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_calendar_mm_event_user`
--

DROP TABLE IF EXISTS `ext_calendar_mm_event_user`;
CREATE TABLE `ext_calendar_mm_event_user` (
  `id` int(11) NOT NULL auto_increment,
  `id_event` int(10) unsigned NOT NULL default '0',
  `id_user` int(10) unsigned NOT NULL default '0',
  `is_acknowledged` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid_local` (`id_event`),
  KEY `uid_foreign` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_calendar_mm_holiday_holidayset`
--

DROP TABLE IF EXISTS `ext_calendar_mm_holiday_holidayset`;
CREATE TABLE `ext_calendar_mm_holiday_holidayset` (
  `id` int(11) NOT NULL auto_increment,
  `id_holiday` int(10) unsigned NOT NULL default '0',
  `id_holidayset` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `holiday` (`id_holiday`),
  KEY `holidayset` (`id_holidayset`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_calendar_series`
--

DROP TABLE IF EXISTS `ext_calendar_series`;
CREATE TABLE `ext_calendar_series` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL,
  `id_user_create` int(10) unsigned NOT NULL,
  `id_project` smallint(6) unsigned NOT NULL,
  `id_task` int(10) unsigned NOT NULL,
  `eventtype` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `place` varchar(255) NOT NULL,
  `date_start` int(10) unsigned NOT NULL,
  `date_end` int(10) unsigned NOT NULL,
  `is_private` tinyint(3) unsigned NOT NULL,
  `is_dayevent` tinyint(3) unsigned NOT NULL,
  `is_public` tinyint(1) unsigned NOT NULL,
  `is_repeated` tinyint(3) unsigned NOT NULL,
  `longerthanoneday` varchar(100) NOT NULL,
  `series_type` varchar(100) NOT NULL,
  `series_repetition` int(10) unsigned NOT NULL,
  `series_endtype` varchar(100) NOT NULL,
  `series_enddate` int(11) unsigned NOT NULL,
  `series_repetitiontype` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_comment_comment`
--

DROP TABLE IF EXISTS `ext_comment_comment`;
CREATE TABLE `ext_comment_comment` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL default '0',
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `id_user_create` smallint(5) unsigned NOT NULL default '0',
  `id_task` mediumint(9) unsigned NOT NULL default '0',
  `comment` text NOT NULL,
  `is_public` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `task` (`id_task`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_comment_feedback`
--

DROP TABLE IF EXISTS `ext_comment_feedback`;
CREATE TABLE `ext_comment_feedback` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date_create` int(10) unsigned NOT NULL,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` mediumint(8) unsigned NOT NULL,
  `id_user_feedback` mediumint(8) unsigned NOT NULL,
  `id_comment` int(10) unsigned NOT NULL,
  `is_seen` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_filter_condition`
--

DROP TABLE IF EXISTS `ext_filter_condition`;
CREATE TABLE `ext_filter_condition` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `id_user_create` smallint(5) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL default '0',
  `id_set` smallint(5) unsigned NOT NULL,
  `filter` varchar(64) NOT NULL,
  `value` varchar(100) NOT NULL,
  `negate` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_filter_set`
--

DROP TABLE IF EXISTS `ext_filter_set`;
CREATE TABLE `ext_filter_set` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL default '0',
  `sorting` smallint(5) unsigned NOT NULL,
  `is_hidden` tinyint(2) NOT NULL default '0',
  `id_user` smallint(5) unsigned NOT NULL,
  `usergroups` varchar(16) NOT NULL,
  `type` varchar(16) NOT NULL,
  `title` varchar(64) NOT NULL,
  `conjunction` varchar(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_fixed_project`
--

DROP TABLE IF EXISTS `ext_fixed_project`;
CREATE TABLE `ext_fixed_project` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(256) NOT NULL,
  `creation_period` int(10) unsigned NOT NULL default '0',
  `id_customer` int(10) unsigned NOT NULL default '0',
  `description` mediumtext NOT NULL,
  `add_supporttask` int(10) unsigned NOT NULL default '0',
  `status` tinyint(2) unsigned NOT NULL,
  `status_finish` tinyint(2) unsigned NOT NULL,
  `fixedcosts` int(10) unsigned NOT NULL,
  `is_fixedcosts_paid` tinyint(1) NOT NULL default '0',
  `id_customer_hoster` int(11) NOT NULL default '0',
  `domain` varchar(64) NOT NULL,
  `id_rateset` int(10) unsigned NOT NULL default '0',
  `ext_projectbilling_settlementinterval` int(10) unsigned NOT NULL,
  `id_user_manager` int(10) unsigned NOT NULL default '0',
  `id_user_supervisor` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `customer` (`id_customer`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_fixed_task`
--

DROP TABLE IF EXISTS `ext_fixed_task`;
CREATE TABLE `ext_fixed_task` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(11) unsigned NOT NULL default '0',
  `id_user_create` smallint(5) unsigned NOT NULL default '0',
  `date_create` int(11) unsigned NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `active` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(256) NOT NULL,
  `creation_period` tinyint(3) unsigned NOT NULL default '0',
  `id_usergroup` mediumint(8) unsigned NOT NULL default '0',
  `id_usergroup_secondary` mediumint(11) unsigned NOT NULL default '0',
  `assign_externals` tinyint(1) unsigned NOT NULL default '0',
  `status_finish` tinyint(2) unsigned NOT NULL default '0',
  `id_fixedproject` int(11) unsigned NOT NULL default '0',
  `id_worktype` smallint(5) unsigned NOT NULL default '0',
  `description` mediumtext NOT NULL,
  `estimated_workload_static` smallint(5) unsigned NOT NULL default '0',
  `workload_generic` smallint(5) unsigned NOT NULL default '0',
  `billingtype` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `title` (`title`(10))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `ext_projectbilling_invoice`
--

DROP TABLE IF EXISTS `ext_projectbilling_invoice`;
CREATE TABLE `ext_projectbilling_invoice` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `id_project` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `is_paid` tinyint(2) NOT NULL default '0',
  `total` double NOT NULL,
  `text` mediumtext NOT NULL,
  `date_invoice` int(10) unsigned NOT NULL,
  `number` varchar(255) NOT NULL,
  `mwst` float NOT NULL,
  `id_address` int(10) unsigned NOT NULL default '0',
  `id_user` int(10) unsigned NOT NULL default '0',
  `text_total` mediumtext NOT NULL,
  `text_reduction` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `project` (`id_project`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_invoiceapproval`
--

DROP TABLE IF EXISTS `ext_projectbilling_invoiceapproval`;
CREATE TABLE `ext_projectbilling_invoiceapproval` (
  `id` int(11) NOT NULL auto_increment,
  `date_create` int(10) unsigned NOT NULL default '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_invoicereminder` int(10) unsigned NOT NULL,
  `id_projectrole` int(10) unsigned NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`id_user`),
  KEY `reminder` (`id_invoicereminder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_invoiceitem`
--

DROP TABLE IF EXISTS `ext_projectbilling_invoiceitem`;
CREATE TABLE `ext_projectbilling_invoiceitem` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `id_invoice` int(10) unsigned NOT NULL default '0',
  `id_prepayment` int(10) unsigned NOT NULL,
  `id_task` int(10) unsigned NOT NULL,
  `id_invoiceunit` int(10) unsigned NOT NULL,
  `infotext` mediumtext NOT NULL,
  `id_rateset` tinyint(2) unsigned NOT NULL,
  `total` float NOT NULL,
  `hours` float NOT NULL,
  `total_spez` float NOT NULL,
  `price_spez` float NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_prepayment`
--

DROP TABLE IF EXISTS `ext_projectbilling_prepayment`;
CREATE TABLE `ext_projectbilling_prepayment` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `id_project` int(10) unsigned NOT NULL default '0',
  `amount` int(11) NOT NULL,
  `date_paid` int(10) unsigned NOT NULL,
  `is_paid` tinyint(1) NOT NULL default '0',
  `is_consumed` tinyint(1) NOT NULL default '0',
  `comment` mediumtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `project` (`id_project`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_rate`
--

DROP TABLE IF EXISTS `ext_projectbilling_rate`;
CREATE TABLE `ext_projectbilling_rate` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `id_worktype` int(10) unsigned NOT NULL,
  `rate` float NOT NULL,
  `id_rateset` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_rateset`
--

DROP TABLE IF EXISTS `ext_projectbilling_rateset`;
CREATE TABLE `ext_projectbilling_rateset` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `title` varchar(64) NOT NULL,
  `rate` float NOT NULL,
  `prepayment_price` float NOT NULL,
  `prepayment_info` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_reduction`
--

DROP TABLE IF EXISTS `ext_projectbilling_reduction`;
CREATE TABLE `ext_projectbilling_reduction` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL default '0',
  `title` varchar(32) NOT NULL,
  `years` int(10) unsigned NOT NULL,
  `percent` int(10) unsigned NOT NULL,
  `is_ngoreduction` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_reminder`
--

DROP TABLE IF EXISTS `ext_projectbilling_reminder`;
CREATE TABLE `ext_projectbilling_reminder` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `id_project` int(10) unsigned NOT NULL default '0',
  `date_from` int(11) NOT NULL,
  `date_to` int(10) unsigned NOT NULL,
  `is_approved` tinyint(1) NOT NULL default '0',
  `has_error` tinyint(3) NOT NULL,
  `error_code` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_settlement`
--

DROP TABLE IF EXISTS `ext_projectbilling_settlement`;
CREATE TABLE `ext_projectbilling_settlement` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL default '0',
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL default '0',
  `title` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_projectbilling_type`
--

DROP TABLE IF EXISTS `ext_projectbilling_type`;
CREATE TABLE `ext_projectbilling_type` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `deleted` tinyint(1) unsigned NOT NULL,
  `key` varchar(15) NOT NULL,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_project_mm_project_user`
--

DROP TABLE IF EXISTS `ext_project_mm_project_user`;
CREATE TABLE `ext_project_mm_project_user` (
  `id` int(11) NOT NULL auto_increment,
  `id_project` int(10) unsigned NOT NULL default '0',
  `id_user` int(10) unsigned NOT NULL default '0',
  `id_userrole` int(10) unsigned NOT NULL default '0',
  `comment` tinytext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `project` (`id_project`),
  KEY `person` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_project_project`
--

DROP TABLE IF EXISTS `ext_project_project`;
CREATE TABLE `ext_project_project` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL default '0',
  `id_user_create` smallint(5) unsigned NOT NULL default '0',
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `is_fixed` tinyint(1) unsigned NOT NULL default '0',
  `id_fixedproject` smallint(5) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '0',
  `ext_hosting_hoster` smallint(5) unsigned NOT NULL default '0',
  `id_rateset` smallint(5) unsigned NOT NULL default '0',
  `id_customer` smallint(5) unsigned NOT NULL default '0',
  `ext_hosting_domain` varchar(100) NOT NULL,
  `ext_projectbilling_settlementinterval` smallint(5) unsigned NOT NULL default '0',
  `date_start` int(10) unsigned NOT NULL default '0',
  `date_end` int(10) unsigned NOT NULL default '0',
  `date_deadline` int(10) unsigned NOT NULL default '0',
  `fixedcosts` float unsigned NOT NULL default '0',
  `is_fixedcosts_paid` tinyint(1) unsigned NOT NULL default '0',
  `date_finish` int(10) unsigned NOT NULL default '0',
  `ext_projectbilling_reduction` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `customer` (`id_customer`),
  FULLTEXT KEY `name_domain` (`title`,`ext_hosting_domain`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_project_task`
--

DROP TABLE IF EXISTS `ext_project_task`;
CREATE TABLE `ext_project_task` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(11) NOT NULL,
  `id_project` smallint(6) NOT NULL default '0',
  `date_create` int(11) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `id_user_create` smallint(6) NOT NULL default '0',
  `tasknumber` int(11) default '0',
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `estimated_workload` mediumint(8) unsigned NOT NULL default '0',
  `is_estimatedworkload_public` tinyint(1) NOT NULL,
  `date_deadline` int(10) unsigned NOT NULL default '0',
  `date_start` int(10) unsigned NOT NULL default '0',
  `date_end` int(10) unsigned NOT NULL default '0',
  `ext_fixed_isfixed` tinyint(1) unsigned NOT NULL default '0',
  `ext_fixed_idtask` smallint(5) unsigned NOT NULL default '0',
  `id_user_assigned` smallint(5) unsigned NOT NULL default '0',
  `is_acknowledged` tinyint(1) unsigned NOT NULL default '0',
  `ext_projectbilling_offeredprice` float NOT NULL default '0',
  `offered_accesslevel` tinyint(3) unsigned NOT NULL default '0',
  `is_offered` tinyint(1) unsigned NOT NULL default '0',
  `clearance_state` tinyint(3) unsigned NOT NULL default '0',
  `id_parenttask` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `id_worktype` smallint(5) unsigned NOT NULL default '0',
  `type` tinyint(3) unsigned NOT NULL default '0',
  `date_finish` int(10) unsigned NOT NULL,
  `ext_projectbilling_type` tinyint(3) unsigned NOT NULL,
  `is_private` tinyint(1) unsigned NOT NULL default '0',
  `is_public` tinyint(1) unsigned NOT NULL default '0',
  `is_onblock` tinyint(1) unsigned NOT NULL default '0',
  `id_user_owner` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `parenttask` (`id_parenttask`),
  KEY `project` (`id_project`),
  KEY `assigned_to` (`id_user_assigned`),
  KEY `cruser` (`id_user_create`),
  KEY `owner` (`id_user_owner`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_project_userrole`
--

DROP TABLE IF EXISTS `ext_project_userrole`;
CREATE TABLE `ext_project_userrole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) NOT NULL,
  `id_user_create` smallint(5) NOT NULL,
  `date_update` int(10) NOT NULL,
  `rolekey` varchar(35) NOT NULL,
  `title` varchar(60) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_project_worktype`
--

DROP TABLE IF EXISTS `ext_project_worktype`;
CREATE TABLE `ext_project_worktype` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `date_update` int(11) NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(64) NOT NULL,
  `type` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_timetracking_track`
--

DROP TABLE IF EXISTS `ext_timetracking_track`;
CREATE TABLE `ext_timetracking_track` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL default '0',
  `date_create` int(10) unsigned NOT NULL default '0',
  `id_task` int(10) unsigned NOT NULL default '0',
  `workload_tracked` int(10) unsigned NOT NULL default '0',
  `workload_chargeable` int(10) unsigned NOT NULL default '0',
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `task` (`id_task`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_address`
--

DROP TABLE IF EXISTS `ext_user_address`;
CREATE TABLE `ext_user_address` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL,
  `id_addresstype` tinyint(1) unsigned NOT NULL default '0',
  `street` varchar(128) NOT NULL,
  `postbox` varchar(32) NOT NULL,
  `city` varchar(48) NOT NULL,
  `region` varchar(32) NOT NULL,
  `zip` mediumtext NOT NULL,
  `id_country` tinyint(1) unsigned NOT NULL default '0',
  `is_preferred` tinyint(1) NOT NULL default '0',
  `comment` varchar(255) NOT NULL,
  `id_holidayset` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_contactinfo`
--

DROP TABLE IF EXISTS `ext_user_contactinfo`;
CREATE TABLE `ext_user_contactinfo` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `id_contactinfotype` tinytext NOT NULL,
  `info` tinytext NOT NULL,
  `preferred` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_contactinfotype`
--

DROP TABLE IF EXISTS `ext_user_contactinfotype`;
CREATE TABLE `ext_user_contactinfotype` (
  `id` int(11) NOT NULL auto_increment,
  `deleted` tinyint(1) NOT NULL default '0',
  `key` varchar(20) NOT NULL,
  `title` varchar(48) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_customer`
--

DROP TABLE IF EXISTS `ext_user_customer`;
CREATE TABLE `ext_user_customer` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL default '0',
  `title` tinytext NOT NULL,
  `shortname` tinytext NOT NULL,
  `id_currency` tinyint(1) unsigned NOT NULL default '0',
  `date_enter` int(10) unsigned NOT NULL default '0',
  `is_ngo` tinyint(1) NOT NULL default '0',
  `ext_projectbilling_reduction` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_customerrole`
--

DROP TABLE IF EXISTS `ext_user_customerrole`;
CREATE TABLE `ext_user_customerrole` (
  `id` int(11) NOT NULL auto_increment,
  `deleted` tinyint(1) NOT NULL default '0',
  `title` varchar(48) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_group`
--

DROP TABLE IF EXISTS `ext_user_group`;
CREATE TABLE `ext_user_group` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `title` varchar(32) NOT NULL,
  `is_active` tinyint(1) NOT NULL default '0',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_holiday`
--

DROP TABLE IF EXISTS `ext_user_holiday`;
CREATE TABLE `ext_user_holiday` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `title` varchar(48) NOT NULL,
  `description` varchar(256) NOT NULL,
  `countrywide` tinyint(2) unsigned NOT NULL default '0',
  `working_hours` varchar(48) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_jobtype`
--

DROP TABLE IF EXISTS `ext_user_jobtype`;
CREATE TABLE `ext_user_jobtype` (
  `id` int(11) NOT NULL auto_increment,
  `deleted` tinyint(1) NOT NULL default '0',
  `title` varchar(48) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_mm_customer_address`
--

DROP TABLE IF EXISTS `ext_user_mm_customer_address`;
CREATE TABLE `ext_user_mm_customer_address` (
  `id` int(11) NOT NULL auto_increment,
  `id_customer` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_mm_customer_contactinfo`
--

DROP TABLE IF EXISTS `ext_user_mm_customer_contactinfo`;
CREATE TABLE `ext_user_mm_customer_contactinfo` (
  `id` int(11) NOT NULL auto_increment,
  `id_customer` int(10) unsigned NOT NULL default '0',
  `id_contactinfo` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_mm_customer_user`
--

DROP TABLE IF EXISTS `ext_user_mm_customer_user`;
CREATE TABLE `ext_user_mm_customer_user` (
  `id` int(11) NOT NULL auto_increment,
  `id_customer` int(10) unsigned NOT NULL default '0',
  `id_user` int(10) unsigned NOT NULL,
  `id_workaddress` SMALLINT NOT NULL,
  `id_jobtype` SMALLINT NOT NULL,
  `ext_resources_efficiency` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `ext_resources_wl_mon_am` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_mon_pm` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_tue_am` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_tue_pm` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_wed_am` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_wed_pm` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_thu_am` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_thu_pm` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_fri_am` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_fri_pm` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_sat_am` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_sat_pm` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_sun_am` INT(3) NOT NULL DEFAULT '0',
  `ext_resources_wl_sun_pm` INT(3) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `customer` (`id_customer`),
  KEY `user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_mm_user_address`
--

DROP TABLE IF EXISTS `ext_user_mm_user_address`;
CREATE TABLE `ext_user_mm_user_address` (
  `id` int(11) NOT NULL auto_increment,
  `id_user` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_mm_user_contactinfo`
--

DROP TABLE IF EXISTS `ext_user_mm_user_contactinfo`;
CREATE TABLE `ext_user_mm_user_contactinfo` (
  `id` int(11) NOT NULL auto_increment,
  `id_user` int(10) unsigned NOT NULL default '0',
  `id_contactinfo` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_mm_user_group`
--

DROP TABLE IF EXISTS `ext_user_mm_user_group`;
CREATE TABLE `ext_user_mm_user_group` (
  `id` int(11) NOT NULL auto_increment,
  `id_user` int(10) unsigned NOT NULL default '0',
  `id_group` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_panelwidget`
--

DROP TABLE IF EXISTS `ext_user_panelwidget`;
CREATE TABLE `ext_user_panelwidget` (
  `id_user` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `widget` varchar(50) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `expanded` tinyint(1) NOT NULL,
  `config` text NOT NULL,
  KEY `fast` (`id_user`,`ext`,`widget`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_preference`
--

DROP TABLE IF EXISTS `ext_user_preference`;
CREATE TABLE `ext_user_preference` (
  `id_user` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `area` smallint(5) unsigned NOT NULL,
  `preference` varchar(50) NOT NULL,
  `item` mediumint(8) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  KEY `fast` (`id_user`,`ext`,`preference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_right`
--

DROP TABLE IF EXISTS `ext_user_right`;
CREATE TABLE `ext_user_right` (
  `ext` smallint(5) unsigned NOT NULL default '0',
  `right` tinytext NOT NULL,
  `id_group` tinyint(3) unsigned NOT NULL default '0',
  KEY `ext` (`ext`,`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ext_user_user`
--

DROP TABLE IF EXISTS `ext_user_user`;
CREATE TABLE `ext_user_user` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL default '0',
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL default '0',
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE `history` (
  `id` int(11) NOT NULL auto_increment,
  `date_create` int(10) unsigned NOT NULL default '0',
  `id_user_create` int(10) unsigned NOT NULL,
  `table` varchar(20) NOT NULL,
  `id_record` int(10) unsigned NOT NULL,
  `rowdata` mediumtext NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `foreignTable` (`table`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `static_country`
--

DROP TABLE IF EXISTS `static_country`;
CREATE TABLE `static_country` (
  `id` int(11) NOT NULL auto_increment,
  `iso_alpha2` char(2) NOT NULL,
  `iso_alpha3` char(3) NOT NULL,
  `iso_num` int(11) unsigned NOT NULL default '0',
  `iso_num_currency` char(3) NOT NULL,
  `phone` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `static_country_zone`
--

DROP TABLE IF EXISTS `static_country_zone`;
CREATE TABLE `static_country_zone` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `iso_alpha2_country` char(2) NOT NULL,
  `iso_alpha3_country` char(3) NOT NULL,
  `iso_num_country` int(11) unsigned NOT NULL default '0',
  `code` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `static_currency`
--

DROP TABLE IF EXISTS `static_currency`;
CREATE TABLE `static_currency` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `iso_alpha` char(3) default NULL,
  `iso_num` int(11) unsigned default '0',
  `symbol_left` varchar(12) default NULL,
  `symbol_right` varchar(12) default NULL,
  `thousands_point` char(1) default NULL,
  `decimal_point` char(1) default NULL,
  `decimal_digits` tinyint(3) unsigned default '0',
  `sub_divisor` int(11) default '1',
  `sub_symbol_left` varchar(12) default NULL,
  `sub_symbol_right` varchar(12) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `static_territory`
--

DROP TABLE IF EXISTS `static_territory`;
CREATE TABLE `static_territory` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `iso_num` int(11) unsigned NOT NULL default '0',
  `parent_iso_num` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;