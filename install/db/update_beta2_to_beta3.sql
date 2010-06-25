--
-- Remove type field from user
--
ALTER TABLE `ext_user_user` DROP `type`;

-- --------------------------------------------------------

--
-- Rename customer to company
--
RENAME TABLE `ext_user_customer` TO `ext_user_company` ;
RENAME TABLE `ext_user_mm_customer_address` TO `ext_user_mm_company_address` ;
RENAME TABLE `ext_user_mm_customer_contactinfo` TO `ext_user_mm_company_contactinfo` ;
RENAME TABLE `ext_user_mm_customer_user` TO `ext_user_mm_company_user` ;
DROP TABLE `ext_user_customerrole`;

-- --------------------------------------------------------

--
-- Rename id_customer to id_company
--
ALTER TABLE `ext_user_mm_company_address` CHANGE `id_customer` `id_company` SMALLINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_user_mm_company_contactinfo` CHANGE `id_customer` `id_company` SMALLINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_user_mm_company_user` CHANGE `id_customer` `id_company` SMALLINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `ext_project_project` CHANGE `id_customer` `id_company` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';

-- --------------------------------------------------------

--
-- Add internal flag to company
--
ALTER TABLE `ext_user_company` ADD `is_internal` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';
UPDATE `ext_user_company` SET `is_internal` = 1;

-- --------------------------------------------------------

--
-- Change eventtype to text
--
ALTER TABLE `ext_calendar_event` CHANGE `eventtype` `eventtype` VARCHAR( 20 ) NOT NULL;

-- --------------------------------------------------------

--
-- Drop id_old_version from assets
--
ALTER TABLE `ext_assets_asset` DROP `id_old_version`;

-- --------------------------------------------------------

--
-- Add static_language
--
CREATE TABLE `static_language` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `iso_alpha2` char(2) CHARACTER SET utf8 NOT NULL,
  `iso_alpha3` char(3) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha2` (`iso_alpha2`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Add data for static_language
--
INSERT INTO `static_language` (`id`, `iso_alpha2`, `iso_alpha3`) VALUES
(1, 'aa', 'aar'),
(2, 'ab', 'abk'),
(3, 'ae', 'ave'),
(4, 'af', 'afr'),
(5, 'ak', 'aka'),
(6, 'am', 'amh'),
(7, 'an', 'arg'),
(8, 'ar', 'ara'),
(9, 'as', 'asm'),
(10, 'av', 'ava'),
(11, 'ay', 'aym'),
(12, 'az', 'aze'),
(13, 'ba', 'bak'),
(14, 'be', 'bel'),
(15, 'bg', 'bul'),
(16, 'bh', 'bih'),
(17, 'bi', 'bis'),
(18, 'bm', 'bam'),
(19, 'bn', 'ben'),
(20, 'bo', 'tib'),
(21, 'br', 'bre'),
(22, 'bs', 'bos'),
(23, 'ca', 'cat'),
(24, 'ce', 'che'),
(25, 'ch', 'cha'),
(26, 'co', 'cos'),
(27, 'cr', 'cre'),
(28, 'cs', 'cze'),
(29, 'cu', 'chu'),
(30, 'cv', 'chv'),
(31, 'cy', 'wel'),
(32, 'da', 'dan'),
(33, 'de', 'ger'),
(34, 'dv', 'div'),
(35, 'dz', 'dzo'),
(36, 'ee', 'ewe'),
(37, 'el', 'gre'),
(38, 'en', 'eng'),
(39, 'eo', 'epo'),
(40, 'es', 'spa'),
(41, 'et', 'est'),
(42, 'eu', 'baq'),
(43, 'fa', 'per'),
(44, 'ff', 'ful'),
(45, 'fi', 'fin'),
(46, 'fj', 'fij'),
(47, 'fo', 'fao'),
(48, 'fr', 'fre'),
(49, 'fy', 'fry'),
(50, 'ga', 'gle'),
(51, 'gd', 'gla'),
(52, 'gl', 'glg'),
(53, 'gn', 'grn'),
(54, 'gu', 'guj'),
(55, 'gv', 'glv'),
(56, 'ha', 'hau'),
(57, 'he', 'heb'),
(58, 'hi', 'hin'),
(59, 'ho', 'hmo'),
(60, 'hr', 'hrv'),
(61, 'ht', 'hat'),
(62, 'hu', 'hun'),
(63, 'hy', 'arm'),
(64, 'hz', 'her'),
(65, 'ia', 'ina'),
(66, 'id', 'ind'),
(67, 'ie', 'ile'),
(68, 'ig', 'ibo'),
(69, 'ii', 'iii'),
(70, 'ik', 'ipk'),
(71, 'io', 'ido'),
(72, 'is', 'ice'),
(73, 'it', 'ita'),
(74, 'iu', 'iku'),
(75, 'ja', 'jpn'),
(76, 'jv', 'jav'),
(77, 'ka', 'geo'),
(78, 'kg', 'kon'),
(79, 'ki', 'kik'),
(80, 'kj', 'kua'),
(81, 'kk', 'kaz'),
(82, 'kl', 'kal'),
(83, 'km', 'khm'),
(84, 'kn', 'kan'),
(85, 'ko', 'kor'),
(86, 'kr', 'kau'),
(87, 'ks', 'kas'),
(88, 'ku', 'kur'),
(89, 'kv', 'kom'),
(90, 'kw', 'cor'),
(91, 'ky', 'kir'),
(92, 'la', 'lat'),
(93, 'lb', 'ltz'),
(94, 'lg', 'lug'),
(95, 'li', 'lim'),
(96, 'ln', 'lin'),
(97, 'lo', 'lao'),
(98, 'lt', 'lit'),
(99, 'lu', 'lub'),
(100, 'lv', 'lav'),
(101, 'mg', 'mlg'),
(102, 'mh', 'mah'),
(103, 'mi', 'mao'),
(104, 'mk', 'mac'),
(105, 'ml', 'mal'),
(106, 'mn', 'mon'),
(107, 'mr', 'mar'),
(108, 'ms', 'may'),
(109, 'mt', 'mlt'),
(110, 'my', 'bur'),
(111, 'na', 'nau'),
(112, 'nb', 'nob'),
(113, 'nd', 'nde'),
(114, 'ne', 'nep'),
(115, 'ng', 'ndo'),
(116, 'nl', 'dut'),
(117, 'nn', 'nno'),
(118, 'no', 'nor'),
(119, 'nr', 'nbl'),
(120, 'nv', 'nav'),
(121, 'ny', 'nya'),
(122, 'oc', 'oci'),
(123, 'oj', 'oji'),
(124, 'om', 'orm'),
(125, 'or', 'ori'),
(126, 'os', 'oss'),
(127, 'pa', 'pan'),
(128, 'pi', 'pli'),
(129, 'pl', 'pol'),
(130, 'ps', 'pus'),
(131, 'pt', 'por'),
(132, 'qu', 'que'),
(133, 'rm', 'roh'),
(134, 'rn', 'run'),
(135, 'ro', 'rum'),
(136, 'ru', 'rus'),
(137, 'rw', 'kin'),
(138, 'sa', 'san'),
(139, 'sc', 'srd'),
(140, 'sd', 'snd'),
(141, 'se', 'sme'),
(142, 'sg', 'sag'),
(143, 'si', 'sin'),
(144, 'sk', 'slo'),
(145, 'sl', 'slv'),
(146, 'sm', 'smo'),
(147, 'sn', 'sna'),
(148, 'so', 'som'),
(149, 'sq', 'alb'),
(150, 'sr', 'srp'),
(151, 'ss', 'ssw'),
(152, 'st', 'sot'),
(153, 'su', 'sun'),
(154, 'sv', 'swe'),
(155, 'sw', 'swa'),
(156, 'ta', 'tam'),
(157, 'te', 'tel'),
(158, 'tg', 'tgk'),
(159, 'th', 'tha'),
(160, 'ti', 'tir'),
(161, 'tk', 'tuk'),
(162, 'tl', 'tgl'),
(163, 'tn', 'tsn'),
(164, 'to', 'ton'),
(165, 'tr', 'tur'),
(166, 'ts', 'tso'),
(167, 'tt', 'tat'),
(168, 'tw', 'twi'),
(169, 'ty', 'tah'),
(170, 'ug', 'uig'),
(171, 'uk', 'ukr'),
(172, 'ur', 'urd'),
(173, 'uz', 'uzb'),
(174, 've', 'ven'),
(175, 'vi', 'vie'),
(176, 'vo', 'vol'),
(177, 'wa', 'wln'),
(178, 'wo', 'wol'),
(179, 'xh', 'xho'),
(180, 'yi', 'yid'),
(181, 'yo', 'yor'),
(182, 'za', 'zha'),
(183, 'zh', 'chi'),
(184, 'zu', 'zul');