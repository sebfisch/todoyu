
CREATE TABLE `ext_calendar_holidayset` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `id_user_create` smallint(5) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(2) NOT NULL,
  `title` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


INSERT INTO `ext_calendar_holidayset` (`id`, `date_update`, `id_user_create`, `date_create`, `deleted`, `title`, `description`) VALUES
(1, 1244290547, 0, 0, 0, 'Feiertage Kanton Zürich', ''),
(2, 1244290548, 0, 0, 0, 'Feiertage Kanton Bern', '');






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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

INSERT INTO `ext_calendar_holiday` (`id`, `date_update`, `id_user_create`, `date_create`, `deleted`, `date`, `title`, `description`, `workinghours`) VALUES
(39, 1220364646, 381, 1220364646, 0, 1230764400, 'Neujahr', '', '0'),
(40, 1220364684, 381, 1220364684, 0, 1230850800, 'Berchtoldstag', '', '0'),
(41, 1220364734, 381, 1220364734, 0, 1239314400, 'Karfreitag', '', '0'),
(42, 1220364777, 381, 1220364777, 0, 1239573600, 'Ostermontag', '', '0'),
(43, 1220364960, 381, 1220364960, 0, 1240178400, 'Sechseläuten', 'In Zürich Mittag frei', '240'),
(44, 1220366196, 381, 1220366196, 0, 1241128800, 'Tag der Arbeit', '', '0'),
(45, 1220366245, 381, 1220366245, 0, 1243807200, 'Pfingstmontag', '', '0'),
(46, 1220366291, 381, 1220366291, 0, 1249077600, 'Nationalfeiertag', '', '0'),
(47, 1220366366, 381, 1220366366, 0, 1252879200, 'Knabenschiessen', 'In Zürich am Mittag frei', '240'),
(48, 1220366417, 381, 1220366417, 0, 1261609200, 'Heiligabend', 'ab Mittag frei', '240'),
(49, 1220366454, 381, 1220366454, 0, 1261695600, 'Weihnachten', '', '0'),
(51, 1220366523, 381, 1220366523, 0, 1261782000, 'Stephanstag', '', '0'),
(52, 1220366574, 381, 1220366574, 0, 1262214000, 'Silvester', 'Ab Mittag frei', '240'),
(54, 1229503133, 381, 1229502530, 0, 1258930800, 'Zibelemärit ', 'Mittag frei - nur Bern', '240'),
(62, 1244472469, 381, 1244472469, 0, 1242856800, 'Auffahrt', '', '0');





CREATE TABLE `ext_calendar_mm_holiday_holidayset` (
  `id` int(11) NOT NULL auto_increment,
  `id_holiday` int(10) unsigned NOT NULL default '0',
  `id_holidayset` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `holiday` (`id_holiday`),
  KEY `holidayset` (`id_holidayset`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=377 ;

INSERT INTO `ext_calendar_mm_holiday_holidayset` (`id`, `id_holiday`, `id_holidayset`) VALUES
(360, 62, 2),
(376, 62, 1),
(375, 51, 1),
(374, 44, 1),
(373, 45, 1),
(372, 46, 1),
(371, 48, 1),
(359, 51, 2),
(370, 52, 1),
(369, 42, 1),
(358, 54, 2),
(368, 41, 1),
(357, 52, 2),
(367, 49, 1),
(356, 39, 2),
(355, 41, 2),
(354, 40, 2),
(366, 53, 1),
(353, 53, 2),
(352, 42, 2),
(351, 50, 2),
(365, 39, 1),
(350, 44, 2),
(364, 40, 1),
(349, 45, 2),
(363, 47, 1),
(348, 46, 2),
(347, 48, 2),
(362, 43, 1),
(346, 49, 2),
(361, 50, 1);

