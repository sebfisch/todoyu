--
-- Tabellenstruktur für Tabelle `history`
--

CREATE TABLE `system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `id_person_create` int(10) unsigned NOT NULL,
  `table` varchar(20) NOT NULL,
  `id_record` int(10) unsigned NOT NULL,
  `rowdata` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

--
-- Tabellenstruktur für Tabelle `log`
--

CREATE TABLE `system_errorlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL,
  `requestkey` varchar(8) NOT NULL,
  `id_person` smallint(5) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `file` varchar(100) NOT NULL,
  `line` smallint(5) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

--
-- Tabellenstruktur für Tabelle `static_country`
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

--
-- Tabellenstruktur für Tabelle `static_country_zone`
--

CREATE TABLE `static_country_zone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha2_country` char(2) NOT NULL,
  `iso_alpha3_country` char(3) NOT NULL,
  `iso_num_country` int(11) unsigned NOT NULL DEFAULT '0',
  `code` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- Tabellenstruktur für Tabelle `static_currency`
--

CREATE TABLE `static_currency` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- Tabellenstruktur für Tabelle `static_territory`
--

CREATE TABLE `static_territory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso_num` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_iso_num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- Tabellenstruktur für Tabelle `static_language`
--

CREATE TABLE `static_language` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha2` char(2) DEFAULT '' NOT NULL,
  `iso_alpha3` char(3) DEFAULT '' NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha2` (`iso_alpha2`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


--
-- Table structure for table `system_role`
--

CREATE TABLE `system_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL,
  `id_person_create` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `system_panelwidget`
--

CREATE TABLE `system_panelwidget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_person` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `widget` varchar(50) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `expanded` tinyint(1) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `system_preference`
--

CREATE TABLE `system_preference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_person` smallint(5) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `area` smallint(5) unsigned NOT NULL,
  `preference` varchar(50) NOT NULL,
  `item` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fast` (`id_person`,`ext`,`preference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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