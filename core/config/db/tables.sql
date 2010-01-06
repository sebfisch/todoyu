--
-- Tabellenstruktur f�r Tabelle `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user_create` int(10) unsigned NOT NULL,
  `table` varchar(20) NOT NULL,
  `id_record` int(10) unsigned NOT NULL,
  `rowdata` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `foreignTable` (`table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Tabellenstruktur f�r Tabelle `log`
--

CREATE TABLE `log` (
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

--
-- Tabellenstruktur f�r Tabelle `static_country`
--

CREATE TABLE `static_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso_alpha2` char(2) NOT NULL,
  `iso_alpha3` char(3) NOT NULL,
  `iso_num` int(11) unsigned NOT NULL DEFAULT '0',
  `iso_num_currency` char(3) NOT NULL,
  `phone` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Tabellenstruktur f�r Tabelle `static_country_zone`
--

CREATE TABLE `static_country_zone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha2_country` char(2) NOT NULL,
  `iso_alpha3_country` char(3) NOT NULL,
  `iso_num_country` int(11) unsigned NOT NULL DEFAULT '0',
  `code` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Tabellenstruktur f�r Tabelle `static_currency`
--

CREATE TABLE `static_currency` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha` char(3) DEFAULT '' NULL,
  `iso_num` int(11) unsigned DEFAULT '0',
  `symbol_left` varchar(12) DEFAULT '' NULL,
  `symbol_right` varchar(12) DEFAULT '' NULL,
  `thousands_point` char(1) DEFAULT '' NULL,
  `decimal_point` char(1) DEFAULT '' NULL,
  `decimal_digits` tinyint(3) unsigned DEFAULT '0',
  `sub_divisor` int(11) DEFAULT '1',
  `sub_symbol_left` varchar(12) DEFAULT '' NULL,
  `sub_symbol_right` varchar(12) DEFAULT '' NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Tabellenstruktur für Tabelle `static_territory`
--

CREATE TABLE `static_territory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iso_num` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_iso_num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Tabellenstruktur für Tabelle `static_language`
--

CREATE TABLE `static_language` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha2` char(2) CHARACTER SET utf8 NOT NULL,
  `iso_alpha3` char(3) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha2` (`iso_alpha2`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;