-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 29. September 2009 um 16:23
-- Server Version: 5.1.37
-- PHP-Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `todoyu2alpha`
--

--
-- Daten für Tabelle `ext_assets_asset`
--


--
-- Daten für Tabelle `ext_billing_currency`
--


--
-- Daten für Tabelle `ext_billing_invoicereminder`
--


--
-- Daten für Tabelle `ext_bookmark_bookmark`
--

INSERT INTO `ext_bookmark_bookmark` (`id`, `id_user_create`, `date_create`, `type`, `deleted`, `id_item`) VALUES
(1, 1, 1254233035, 1, 0, 2);

--
-- Daten für Tabelle `ext_calendar_event`
--


--
-- Daten für Tabelle `ext_calendar_holiday`
--


--
-- Daten für Tabelle `ext_calendar_holidayset`
--


--
-- Daten für Tabelle `ext_calendar_mm_event_user`
--


--
-- Daten für Tabelle `ext_calendar_mm_holiday_holidayset`
--


--
-- Daten für Tabelle `ext_calendar_series`
--


--
-- Daten für Tabelle `ext_comment_comment`
--

INSERT INTO `ext_comment_comment` (`id`, `date_update`, `date_create`, `deleted`, `id_user_create`, `id_task`, `comment`, `is_public`) VALUES
(1, 1254233694, 1254233694, 0, 1, 7, '<p>Hallo, schau dir das bitte mal an, wenn moeglich noch Heute</p>', 0);

--
-- Daten für Tabelle `ext_comment_feedback`
--

INSERT INTO `ext_comment_feedback` (`id`, `date_create`, `date_update`, `id_user_create`, `id_user_feedback`, `id_comment`, `is_seen`) VALUES
(1, 1254233694, 0, 1, 2, 1, 0);

--
-- Daten für Tabelle `ext_filter_condition`
--

INSERT INTO `ext_filter_condition` (`id`, `date_update`, `date_create`, `id_user_create`, `deleted`, `id_set`, `filter`, `value`, `negate`) VALUES
(7, 0, 0, 0, 0, 1, 'currentUserIsAssigned', '1', 0),
(8, 0, 0, 0, 0, 1, 'status', '2,3', 0),
(10, 0, 0, 0, 0, 2, 'currentUserIsOwner', '1', 0),
(11, 0, 0, 0, 0, 2, 'status', '4', 0),
(257, 1246637647, 1246637647, 474, 0, 3, 'unseenFeedbackCurrentUser', '', 0),
(259, 1254233948, 1254233948, 1, 0, 4, 'status', '2', 0),
(260, 1254233948, 1254233948, 1, 0, 4, 'currentUserIsAssigned', '', 0);

--
-- Daten für Tabelle `ext_filter_set`
--

INSERT INTO `ext_filter_set` (`id`, `date_update`, `date_create`, `deleted`, `sorting`, `is_hidden`, `id_user`, `usergroups`, `type`, `title`, `conjunction`) VALUES
(1, 0, 0, 0, 0, 1, 0, '', 'task', 'Meine Tasks: offen/ in Bearbeitung', 'AND'),
(2, 0, 0, 0, 0, 1, 0, '0', 'task', 'Meine Todos', 'AND'),
(3, 1246637647, 1246547545, 0, 0, 1, 474, '', 'task', 'Feedback erwartet', 'AND'),
(4, 1254233948, 1254233928, 0, 0, 0, 1, '', 'task', 'Noch offene Tasks', 'AND');

--
-- Daten für Tabelle `ext_fixed_project`
--


--
-- Daten für Tabelle `ext_fixed_task`
--


--
-- Daten für Tabelle `ext_portal_mm_tab_filterset`
--

INSERT INTO `ext_portal_mm_tab_filterset` (`id`, `id_tab`, `id_filterset`) VALUES
(1, 1, 1),
(2, 10, 2),
(3, 2, 3);

--
-- Daten für Tabelle `ext_portal_tab`
--

INSERT INTO `ext_portal_tab` (`id`, `deleted`, `type`, `id_user`, `usergroups`, `class`, `title`, `is_or`, `sorting`) VALUES
(1, 0, 'task', 0, '0', 'todos', 'LLL:portal.tab.todos', 1, 0),
(2, 0, 'task', 0, '0', 'feedback', 'LLL:portal.tab.feedbacks', 0, 0),
(3, 0, 'calendar', 0, '0', 'appointments', 'LLL:portal.tab.appointments', 0, 0);

--
-- Daten für Tabelle `ext_projectbilling_invoice`
--


--
-- Daten für Tabelle `ext_projectbilling_invoiceapproval`
--


--
-- Daten für Tabelle `ext_projectbilling_invoiceitem`
--


--
-- Daten für Tabelle `ext_projectbilling_prepayment`
--


--
-- Daten für Tabelle `ext_projectbilling_rate`
--


--
-- Daten für Tabelle `ext_projectbilling_rateset`
--


--
-- Daten für Tabelle `ext_projectbilling_reduction`
--


--
-- Daten für Tabelle `ext_projectbilling_reminder`
--


--
-- Daten für Tabelle `ext_projectbilling_settlement`
--


--
-- Daten für Tabelle `ext_projectbilling_type`
--


--
-- Daten für Tabelle `ext_project_mm_project_user`
--

INSERT INTO `ext_project_mm_project_user` (`id`, `id_project`, `id_user`, `id_userrole`, `comment`) VALUES
(5, 2, 2, 2, ''),
(4, 2, 3, 1, ''),
(6, 2, 1, 1, '');

--
-- Daten für Tabelle `ext_project_project`
--

INSERT INTO `ext_project_project` (`id`, `date_update`, `id_user_create`, `date_create`, `deleted`, `title`, `description`, `is_fixed`, `id_fixedproject`, `status`, `ext_hosting_hoster`, `id_rateset`, `id_customer`, `ext_hosting_domain`, `ext_projectbilling_settlementinterval`, `date_start`, `date_end`, `date_deadline`, `fixedcosts`, `is_fixedcosts_paid`, `date_finish`, `ext_projectbilling_reduction`) VALUES
(1, 1246982959, 1, 1246982959, 0, 'My First Project', '<p>This is the first todoyu project.</p>', 0, 0, 3, 0, 0, 4, '', 0, 1246917600, 1264719600, 1264719600, 0, 0, 0, 0),
(2, 1254233593, 1, 1254213566, 0, 'Example Project', '<p>This is the project description. This text should contain the relevant data for your project.</p>', 0, 0, 3, 0, 0, 5, '', 0, 1254175200, 1262214000, 1262905200, 0, 0, 0, 0);

--
-- Daten für Tabelle `ext_project_task`
--

INSERT INTO `ext_project_task` (`id`, `date_update`, `id_project`, `date_create`, `deleted`, `id_user_create`, `tasknumber`, `description`, `status`, `estimated_workload`, `is_estimatedworkload_public`, `date_deadline`, `date_start`, `date_end`, `ext_fixed_isfixed`, `ext_fixed_idtask`, `id_user_assigned`, `is_acknowledged`, `ext_projectbilling_offeredprice`, `offered_accesslevel`, `is_offered`, `clearance_state`, `id_parenttask`, `title`, `id_worktype`, `type`, `date_finish`, `ext_projectbilling_type`, `is_private`, `is_public`, `is_onblock`, `id_user_owner`) VALUES
(1, 1246985220, 1, 1246983025, 0, 1, 1, '<p>Projectmanagement</p>', 2, 3600, 0, 1264719600, 1246917600, 1264719600, 0, 0, 1, 1, 0, 0, 0, 0, 0, 'Projectmanagement', 0, 1, 0, 0, 0, 0, 0, 1),
(2, 1254233016, 2, 1254232560, 0, 1, 1, '<p>Some random description in the task. Here you should write useful informations about this project</p>', 2, 3600, 0, 1256306040, 1254175200, 1256133240, 0, 0, 3, 1, 0, 0, 0, 0, 0, 'Sampletask', 1, 1, 0, 0, 0, 0, 0, 1),
(3, 1254233504, 2, 1254233504, 0, 1, 2, '<ul><li>Welche Module müssen fehlerfrei laufen</li><li>Welche Module kommen als Debugversion in den Release</li><li>Updatezyklen für Beta Bugfixes festlegen</li></ul>', 2, 10800, 0, 1254233040, 1254175200, 1254233040, 0, 0, 3, 0, 0, 0, 0, 0, 0, 'Betarelease planen', 1, 2, 0, 0, 0, 0, 0, 3),
(4, 1254233729, 2, 1254233534, 0, 1, 3, '<p>askfdl asldfjalsdfjalksdjfasdf adfa sdf asdfasdf</p>', 3, 3600, 0, 1256220660, 1254175200, 1254233460, 0, 0, 3, 1, 0, 0, 0, 0, 0, 'Random Task', 1, 1, 0, 0, 0, 0, 0, 1),
(5, 1254233577, 2, 1254233577, 0, 1, 4, '<p>Das ist ein Subtask im Container</p><p>Verschachtelung beliebig tief möglich</p>', 2, 3600, 0, 1254233520, 1254175200, 1254233520, 0, 0, 3, 0, 0, 0, 0, 0, 3, 'Subtask', 1, 1, 0, 0, 0, 0, 0, 1),
(6, 1254233620, 2, 1254233620, 0, 1, 5, '<p>salkdfj adlsdjfalksdjfasjdfasdf asdfa sdfa sf</p>', 2, 3600, 0, 1257001980, 1254175200, 1256742780, 0, 0, 1, 0, 0, 0, 0, 0, 0, 'Noch ein Task', 1, 1, 0, 0, 0, 0, 0, 3),
(7, 1254233877, 2, 1254233652, 0, 1, 6, '<p>Das ist sehr wichtig</p>', 3, 19200, 0, 1254233580, 1254175200, 1254233580, 0, 0, 2, 0, 0, 0, 0, 0, 3, 'Unbedingt schnell erledigen', 1, 1, 0, 0, 0, 0, 0, 1);

--
-- Daten für Tabelle `ext_project_userrole`
--

INSERT INTO `ext_project_userrole` (`id`, `rolekey`, `title`, `deleted`) VALUES
(1, 'projectleader', 'Projektleiter', 0),
(2, 'developer', 'Entwickler', 0),
(3, 'designer', 'Designer', 0),
(4, 'external_projectleader', 'Externer Projektleiter', 0),
(5, 'customer_contact', 'Ansprechpartner Kunde', 0);

--
-- Daten für Tabelle `ext_project_worktype`
--

INSERT INTO `ext_project_worktype` (`id`, `date_update`, `id_user_create`, `date_create`, `deleted`, `title`, `type`) VALUES
(1, 1254232852, 1, 1254232852, 0, 'Consulting', 0),
(2, 1254232863, 1, 1254232863, 0, 'Entwicklung', 0),
(3, 1254232867, 1, 1254232867, 0, 'Design', 0),
(4, 1254232876, 1, 1254232876, 0, 'Testing', 0);

--
-- Daten für Tabelle `ext_timetracking_track`
--

INSERT INTO `ext_timetracking_track` (`id`, `date_update`, `id_user`, `date_create`, `id_task`, `workload_tracked`, `workload_chargeable`, `comment`) VALUES
(1, 1254233739, 1, 1254233726, 4, 16, 0, ''),
(2, 1254233883, 1, 1254233883, 7, 6, 0, '');

--
-- Daten für Tabelle `ext_user_address`
--


--
-- Daten für Tabelle `ext_user_contactinfo`
--


--
-- Daten für Tabelle `ext_user_contactinfotype`
--

INSERT INTO `ext_user_contactinfotype` (`id`, `deleted`, `key`, `title`) VALUES
(1, 0, 'email_business', 'user.contactinfo.email_business'),
(2, 0, 'tel_private', 'user.contactinfo.tel_private'),
(3, 0, 'tel_exchange', 'user.contactinfo.tel_exchange'),
(4, 0, 'tel_business', 'user.contactinfo.tel_business'),
(5, 0, 'email_private', 'user.contactinfo.email_private'),
(6, 0, 'mobile_business', 'user.contactinfo.mobile_business'),
(7, 0, 'fax_private', 'user.contactinfo.fax_private'),
(8, 0, 'fax_business', 'user.contactinfo.fax_business'),
(9, 0, 'mobile_private', 'user.contactinfo.mobile_private'),
(10, 0, 'fax_exchange', 'user.contactinfo.fax_exchange'),
(11, 0, 'website', 'user.contactinfo.website'),
(12, 0, 'skype', 'user.contactinfo.skype');

--
-- Daten für Tabelle `ext_user_customer`
--

INSERT INTO `ext_user_customer` (`id`, `date_update`, `id_user_create`, `date_create`, `deleted`, `title`, `shortname`, `id_currency`, `date_enter`, `is_ngo`, `ext_projectbilling_reduction`) VALUES
(1, 1246888595, 1, 1246886240, 0, 'Demo Customer', 'Demo', 0, 101, 0, 0),
(3, 1246888856, 1, 1246888856, 0, 'Random Company', 'Rand', 0, 607, 0, 0),
(5, 1254213233, 1, 1254213151, 0, 'Snowflake Productions', 'Snowflake', 0, 1136156400, 0, 0),
(6, 1254213723, 1, 1254213723, 0, 'OneStepLeft Movie Factory', 'Movie Factory', 0, 1189548000, 0, 0);

--
-- Daten für Tabelle `ext_user_customerrole`
--

INSERT INTO `ext_user_customerrole` (`id`, `deleted`, `title`) VALUES
(1, 0, 'Client');

--
-- Daten für Tabelle `ext_user_group`
--


--
-- Daten für Tabelle `ext_user_holiday`
--


--
-- Daten für Tabelle `ext_user_jobtype`
--


--
-- Daten für Tabelle `ext_user_mm_customer_address`
--


--
-- Daten für Tabelle `ext_user_mm_customer_contactinfo`
--


--
-- Daten für Tabelle `ext_user_mm_customer_user`
--

INSERT INTO `ext_user_mm_customer_user` (`id`, `id_customer`, `id_user`, `id_workaddress`, `id_jobtype`) VALUES
(1, 5, 1, 0, 0),
(2, 6, 2, 0, 0),
(3, 5, 3, 0, 0);

--
-- Daten für Tabelle `ext_user_mm_user_address`
--


--
-- Daten für Tabelle `ext_user_mm_user_contactinfo`
--


--
-- Daten für Tabelle `ext_user_mm_user_group`
--


--
-- Daten für Tabelle `ext_user_panelwidget`
--


--
-- Daten für Tabelle `ext_user_preference`
--

INSERT INTO `ext_user_preference` (`id_user`, `ext`, `area`, `preference`, `item`, `value`) VALUES
(1, 0, 0, 'tab', 0, 'portal'),
(1, 112, 0, 'projecttabs', 0, '2'),
(1, 112, 0, 'project', 0, '2'),
(1, 112, 0, 'tasktree-subtasks', 0, '3'),
(1, 111, 0, 'task-exp', 0, '2'),
(1, 112, 112, 'tasktree-subtasks', 0, '3'),
(1, 112, 0, 'tasktree-task-exp', 0, '7'),
(1, 112, 112, 'task-tab', 7, 'timetracking'),
(1, 111, 0, 'task-exp', 0, '4'),
(1, 112, 0, 'tasktree-task-exp', 0, '4'),
(1, 115, 0, 'filterset-task', 0, '4'),
(1, 111, 0, 'filtersets', 0, '4'),
(1, 111, 0, 'tab', 0, '0');

--
-- Daten für Tabelle `ext_user_right`
--


--
-- Daten für Tabelle `ext_user_user`
--

INSERT INTO `ext_user_user` (`id`, `date_update`, `id_user_create`, `date_create`, `deleted`, `username`, `password`, `email`, `type`, `is_admin`, `active`, `firstname`, `lastname`, `shortname`, `gender`, `title`, `birthday`, `id_jobtype`, `ext_resources_efficiency`, `ext_resources_wl_mon_am`, `ext_resources_wl_mon_pm`, `ext_resources_wl_tue_am`, `ext_resources_wl_tue_pm`, `ext_resources_wl_wed_am`, `ext_resources_wl_wed_pm`, `ext_resources_wl_thu_am`, `ext_resources_wl_thu_pm`, `ext_resources_wl_fri_am`, `ext_resources_wl_fri_pm`, `ext_resources_wl_sat_am`, `ext_resources_wl_sat_pm`, `ext_resources_wl_sun_am`, `ext_resources_wl_sun_pm`, `id_workaddress`) VALUES
(1, 1246615200, 0, 1246615200, 0, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'demo@todoyu.com', 1, 1, 1, 'Bob', 'Thingummy', 'BOTH', 'm', '', '1966-10-05', 14, 100, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 0, 0, 0, 0, 0),
(2, 0, 1, 1254213351, 0, 'alfred', 'a55e28b5514b1b1292a9018549edc271', 'alfred345KJHFSD', 2, 1, 1, 'Alfred', 'Hitchcock', 'ALHI', 'm', '', '1899-08-13', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 0, 1, 1254213483, 0, 'kurt', '3b9ef5add002b05aa3a2fc7bc83dc017', 'kurt456SDF', 1, 0, 1, 'Kurt', 'Cobain', 'KUCO', 'm', '', '1967-02-20', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

--
-- Daten für Tabelle `history`
--


--
-- Daten für Tabelle `log`
--


--
-- Daten für Tabelle `static_country`
--

INSERT INTO `static_country` (`id`, `iso_alpha2`, `iso_alpha3`, `iso_num`, `iso_num_currency`, `phone`) VALUES
(1, 'AD', 'AND', 20, '978', 376),
(2, 'AE', 'ARE', 784, '784', 971),
(3, 'AF', 'AFG', 4, 'AFA', 93),
(4, 'AG', 'ATG', 28, '951', 1809),
(5, 'AI', 'AIA', 660, '951', 1264),
(6, 'AL', 'ALB', 8, '8', 355),
(7, 'AM', 'ARM', 51, '51', 374),
(8, 'AN', 'ANT', 530, '532', 599),
(9, 'AO', 'AGO', 24, '973', 244),
(10, 'AQ', 'ATA', 0, '0', 67212),
(11, 'AR', 'ARG', 32, '32', 54),
(12, 'AS', 'ASM', 16, '840', 685),
(13, 'AT', 'AUT', 40, '978', 43),
(14, 'AU', 'AUS', 36, '36', 61),
(15, 'AW', 'ABW', 533, '533', 297),
(16, 'AZ', 'AZE', 31, 'AZM', 994),
(17, 'BA', 'BIH', 70, '977', 387),
(18, 'BB', 'BRB', 52, '52', 1246),
(19, 'BD', 'BGD', 50, '50', 880),
(20, 'BE', 'BEL', 56, '978', 32),
(21, 'BF', 'BFA', 854, '952', 226),
(22, 'BG', 'BGR', 100, 'BGL', 359),
(23, 'BH', 'BHR', 48, '48', 973),
(24, 'BI', 'BDI', 108, '108', 257),
(25, 'BJ', 'BEN', 204, '952', 229),
(26, 'BM', 'BMU', 60, '60', 1809),
(27, 'BN', 'BRN', 96, '96', 673),
(28, 'BO', 'BOL', 68, '68', 591),
(29, 'BR', 'BRA', 76, '986', 55),
(30, 'BS', 'BHS', 44, '44', 1242),
(31, 'BT', 'BTN', 64, '64', 975),
(32, 'BV', 'BVT', 74, '578', 0),
(33, 'BW', 'BWA', 72, '72', 267),
(34, 'BY', 'BLR', 112, '974', 375),
(35, 'BZ', 'BLZ', 84, '84', 501),
(36, 'CA', 'CAN', 124, '124', 1),
(37, 'CC', 'CCK', 166, '36', 6722),
(38, 'CD', 'COD', 180, '976', 0),
(39, 'CF', 'CAF', 140, '950', 236),
(40, 'CG', 'COG', 178, '950', 242),
(41, 'CH', 'CHE', 756, '756', 41),
(42, 'CI', 'CIV', 384, '952', 225),
(43, 'CK', 'COK', 184, '554', 682),
(44, 'CL', 'CHL', 152, '152', 56),
(45, 'CM', 'CMR', 120, '950', 237),
(46, 'CN', 'CHN', 156, '156', 86),
(47, 'CO', 'COL', 170, '170', 57),
(48, 'CR', 'CRI', 188, '188', 506),
(49, 'CU', 'CUB', 192, '192', 53),
(50, 'CV', 'CPV', 132, '132', 238),
(51, 'CX', 'CXR', 162, '36', 6724),
(52, 'CY', 'CYP', 196, '196', 357),
(53, 'CZ', 'CZE', 203, '203', 420),
(54, 'DE', 'DEU', 276, '978', 49),
(55, 'DJ', 'DJI', 262, '262', 253),
(56, 'DK', 'DNK', 208, '208', 45),
(57, 'DM', 'DMA', 212, '951', 1809),
(58, 'DO', 'DOM', 214, '214', 1809),
(59, 'DZ', 'DZA', 12, '12', 213),
(60, 'EC', 'ECU', 218, '840', 593),
(61, 'EE', 'EST', 233, '233', 372),
(62, 'EG', 'EGY', 818, '818', 20),
(63, 'EH', 'ESH', 732, '504', 0),
(64, 'ER', 'ERI', 232, '232', 291),
(65, 'ES', 'ESP', 724, '978', 34),
(66, 'ET', 'ETH', 231, '230', 251),
(67, 'FI', 'FIN', 246, '978', 358),
(68, 'FJ', 'FJI', 242, '242', 679),
(69, 'FK', 'FLK', 238, '238', 500),
(70, 'FM', 'FSM', 583, '840', 691),
(71, 'FO', 'FRO', 234, '208', 298),
(72, 'FR', 'FRA', 250, '978', 33),
(73, 'GA', 'GAB', 266, '950', 241),
(74, 'GB', 'GBR', 826, '826', 44),
(75, 'GD', 'GRD', 308, '951', 1809),
(76, 'GE', 'GEO', 268, '981', 995),
(77, 'GF', 'GUF', 254, '978', 594),
(78, 'GH', 'GHA', 288, '288', 233),
(79, 'GI', 'GIB', 292, '292', 350),
(80, 'GL', 'GRL', 304, '208', 299),
(81, 'GM', 'GMB', 270, '270', 220),
(82, 'GN', 'GIN', 324, '324', 224),
(83, 'GP', 'GLP', 312, '978', 590),
(84, 'GQ', 'GNQ', 226, '950', 240),
(85, 'GR', 'GRC', 300, '978', 30),
(86, 'GS', 'SGS', 239, '0', 0),
(87, 'GT', 'GTM', 320, '320', 502),
(88, 'GU', 'GUM', 316, '840', 671),
(89, 'GW', 'GNB', 624, '952', 245),
(90, 'GY', 'GUY', 328, '328', 592),
(91, 'HK', 'HKG', 344, '344', 852),
(92, 'HN', 'HND', 340, '340', 504),
(93, 'HR', 'HRV', 191, '191', 385),
(94, 'HT', 'HTI', 332, '332', 509),
(95, 'HU', 'HUN', 348, '348', 36),
(96, 'ID', 'IDN', 360, '360', 62),
(97, 'IE', 'IRL', 372, '978', 353),
(98, 'IL', 'ISR', 376, '376', 972),
(99, 'IN', 'IND', 356, '356', 91),
(100, 'IO', 'IOT', 86, '0', 0),
(101, 'IQ', 'IRQ', 368, '368', 964),
(102, 'IR', 'IRN', 364, '364', 98),
(103, 'IS', 'ISL', 352, '352', 354),
(104, 'IT', 'ITA', 380, '978', 39),
(105, 'JM', 'JAM', 388, '388', 1809),
(106, 'JO', 'JOR', 400, '400', 962),
(107, 'JP', 'JPN', 392, '392', 81),
(108, 'KE', 'KEN', 404, '404', 254),
(109, 'KG', 'KGZ', 417, '417', 7),
(110, 'KH', 'KHM', 116, '116', 855),
(111, 'KI', 'KIR', 296, '36', 686),
(112, 'KM', 'COM', 174, '174', 269),
(113, 'KN', 'KNA', 659, '951', 1809),
(114, 'KP', 'PRK', 408, '408', 850),
(115, 'KR', 'KOR', 410, '410', 82),
(116, 'KW', 'KWT', 414, '414', 965),
(117, 'KY', 'CYM', 136, '136', 1809),
(118, 'KZ', 'KAZ', 398, '398', 7),
(119, 'LA', 'LAO', 418, '418', 856),
(120, 'LB', 'LBN', 422, '422', 961),
(121, 'LC', 'LCA', 662, '951', 1809),
(122, 'LI', 'LIE', 438, '756', 41),
(123, 'LK', 'LKA', 144, '144', 94),
(124, 'LR', 'LBR', 430, '430', 231),
(125, 'LS', 'LSO', 426, '426', 266),
(126, 'LT', 'LTU', 440, '440', 370),
(127, 'LU', 'LUX', 442, '978', 352),
(128, 'LV', 'LVA', 428, '428', 371),
(129, 'LY', 'LBY', 434, '434', 218),
(130, 'MA', 'MAR', 504, '504', 212),
(131, 'MC', 'MCO', 492, '978', 377),
(132, 'MD', 'MDA', 498, '498', 373),
(133, 'MG', 'MDG', 450, 'MGF', 261),
(134, 'MH', 'MHL', 584, '840', 692),
(135, 'MK', 'MKD', 807, '807', 389),
(136, 'ML', 'MLI', 466, '952', 223),
(137, 'MM', 'MMR', 104, '104', 95),
(138, 'MN', 'MNG', 496, '496', 976),
(139, 'MO', 'MAC', 446, '446', 853),
(140, 'MP', 'MNP', 580, '840', 0),
(141, 'MQ', 'MTQ', 474, '978', 596),
(142, 'MR', 'MRT', 478, '478', 222),
(143, 'MS', 'MSR', 500, '951', 1809),
(144, 'MT', 'MLT', 470, '470', 356),
(145, 'MU', 'MUS', 480, '480', 230),
(146, 'MV', 'MDV', 462, '462', 960),
(147, 'MW', 'MWI', 454, '454', 265),
(148, 'MX', 'MEX', 484, '484', 52),
(149, 'MY', 'MYS', 458, '458', 60),
(150, 'MZ', 'MOZ', 508, '508', 258),
(151, 'NA', 'NAM', 516, '516', 264),
(152, 'NC', 'NCL', 540, '953', 687),
(153, 'NE', 'NER', 562, '952', 227),
(154, 'NF', 'NFK', 574, '36', 6723),
(155, 'NG', 'NGA', 566, '566', 234),
(156, 'NI', 'NIC', 558, '558', 505),
(157, 'NL', 'NLD', 528, '978', 31),
(158, 'NO', 'NOR', 578, '578', 47),
(159, 'NP', 'NPL', 524, '524', 977),
(160, 'NR', 'NRU', 520, '36', 674),
(161, 'NU', 'NIU', 570, '554', 683),
(162, 'NZ', 'NZL', 554, '554', 64),
(163, 'OM', 'OMN', 512, '512', 968),
(164, 'PA', 'PAN', 591, '590', 507),
(165, 'PE', 'PER', 604, '604', 51),
(166, 'PF', 'PYF', 258, '953', 689),
(167, 'PG', 'PNG', 598, '598', 675),
(168, 'PH', 'PHL', 608, '608', 63),
(169, 'PK', 'PAK', 586, '586', 92),
(170, 'PL', 'POL', 616, '985', 48),
(171, 'PM', 'SPM', 666, '978', 508),
(172, 'PN', 'PCN', 612, '554', 0),
(173, 'PR', 'PRI', 630, '840', 1809),
(174, 'PT', 'PRT', 620, '978', 351),
(175, 'PW', 'PLW', 585, '840', 680),
(176, 'PY', 'PRY', 600, '600', 595),
(177, 'QA', 'QAT', 634, '634', 974),
(178, 'RE', 'REU', 638, '978', 262),
(179, 'RO', 'ROM', 642, '642', 40),
(180, 'RU', 'RUS', 643, '643', 7),
(181, 'RW', 'RWA', 646, '646', 250),
(182, 'SA', 'SAU', 682, '682', 966),
(183, 'SB', 'SLB', 90, '90', 677),
(184, 'SC', 'SYC', 690, '690', 248),
(185, 'SD', 'SDN', 736, '736', 249),
(186, 'SE', 'SWE', 752, '752', 46),
(187, 'SG', 'SGP', 702, '702', 65),
(188, 'SH', 'SHN', 654, '654', 290),
(189, 'SI', 'SVN', 705, '705', 386),
(190, 'SJ', 'SJM', 744, '578', 0),
(191, 'SK', 'SVK', 703, '703', 421),
(192, 'SL', 'SLE', 694, '694', 232),
(193, 'SM', 'SMR', 674, '978', 378),
(194, 'SN', 'SEN', 686, '952', 221),
(195, 'SO', 'SOM', 706, '706', 252),
(196, 'SR', 'SUR', 740, 'SRG', 597),
(197, 'ST', 'STP', 678, '678', 2391),
(198, 'SV', 'SLV', 222, '222', 503),
(199, 'SY', 'SYR', 760, '760', 963),
(200, 'SZ', 'SWZ', 748, '748', 268),
(201, 'TC', 'TCA', 796, '840', 1809),
(202, 'TD', 'TCD', 148, '950', 235),
(203, 'TF', 'ATF', 260, '0', 0),
(204, 'TG', 'TGO', 768, '952', 228),
(205, 'TH', 'THA', 764, '764', 66),
(206, 'TJ', 'TJK', 762, '972', 7),
(207, 'TK', 'TKL', 772, '554', 0),
(208, 'TM', 'TKM', 795, '795', 7),
(209, 'TN', 'TUN', 788, '788', 216),
(210, 'TO', 'TON', 776, '776', 676),
(211, 'TL', 'TLS', 626, 'TPE', 0),
(212, 'TR', 'TUR', 792, 'TRL', 90),
(213, 'TT', 'TTO', 780, '780', 1809),
(214, 'TV', 'TUV', 798, '36', 688),
(215, 'TW', 'TWN', 158, '901', 886),
(216, 'TZ', 'TZA', 834, '834', 255),
(217, 'UA', 'UKR', 804, '980', 380),
(218, 'UG', 'UGA', 800, '800', 256),
(220, 'US', 'USA', 840, '840', 1),
(221, 'UY', 'URY', 858, '858', 598),
(222, 'UZ', 'UZB', 860, '860', 7),
(223, 'VA', 'VAT', 336, '978', 396),
(224, 'VC', 'VCT', 670, '951', 1809),
(225, 'VE', 'VEN', 862, '862', 58),
(226, 'VG', 'VGB', 92, '840', 1809),
(227, 'VI', 'VIR', 850, '840', 1350),
(228, 'VN', 'VNM', 704, '704', 84),
(229, 'VU', 'VUT', 548, '548', 678),
(230, 'WF', 'WLF', 876, '953', 0),
(231, 'WS', 'WSM', 882, '882', 685),
(232, 'YE', 'YEM', 887, '886', 967),
(233, 'YT', 'MYT', 175, '978', 269),
(235, 'ZA', 'ZAF', 710, '710', 27),
(236, 'ZM', 'ZMB', 894, '894', 260),
(237, 'ZW', 'ZWE', 716, '716', 263),
(238, 'PS', 'PSE', 275, '0', 0),
(239, 'CS', 'SCG', 891, '891', 0),
(241, 'HM', 'HMD', 334, '0', 0);

--
-- Daten für Tabelle `static_country_zone`
--

INSERT INTO `static_country_zone` (`id`, `iso_alpha2_country`, `iso_alpha3_country`, `iso_num_country`, `code`) VALUES
(1, 'US', 'USA', 840, 'AL'),
(2, 'US', 'USA', 840, 'AK'),
(3, 'US', 'USA', 840, 'AS'),
(4, 'US', 'USA', 840, 'AZ'),
(5, 'US', 'USA', 840, 'AR'),
(6, 'US', 'USA', 840, 'AF'),
(7, 'US', 'USA', 840, 'AA'),
(8, 'US', 'USA', 840, 'AC'),
(9, 'US', 'USA', 840, 'AE'),
(10, 'US', 'USA', 840, 'AM'),
(11, 'US', 'USA', 840, 'AP'),
(12, 'US', 'USA', 840, 'CA'),
(13, 'US', 'USA', 840, 'CO'),
(14, 'US', 'USA', 840, 'CT'),
(15, 'US', 'USA', 840, 'DE'),
(16, 'US', 'USA', 840, 'DC'),
(17, 'US', 'USA', 840, 'FM'),
(18, 'US', 'USA', 840, 'FL'),
(19, 'US', 'USA', 840, 'GA'),
(20, 'US', 'USA', 840, 'GU'),
(21, 'US', 'USA', 840, 'HI'),
(22, 'US', 'USA', 840, 'ID'),
(23, 'US', 'USA', 840, 'IL'),
(24, 'US', 'USA', 840, 'IN'),
(25, 'US', 'USA', 840, 'IA'),
(26, 'US', 'USA', 840, 'KS'),
(27, 'US', 'USA', 840, 'KY'),
(28, 'US', 'USA', 840, 'LA'),
(29, 'US', 'USA', 840, 'ME'),
(30, 'US', 'USA', 840, 'MH'),
(31, 'US', 'USA', 840, 'MD'),
(32, 'US', 'USA', 840, 'MA'),
(33, 'US', 'USA', 840, 'MI'),
(34, 'US', 'USA', 840, 'MN'),
(35, 'US', 'USA', 840, 'MS'),
(36, 'US', 'USA', 840, 'MO'),
(37, 'US', 'USA', 840, 'MT'),
(38, 'US', 'USA', 840, 'NE'),
(39, 'US', 'USA', 840, 'NV'),
(40, 'US', 'USA', 840, 'NH'),
(41, 'US', 'USA', 840, 'NJ'),
(42, 'US', 'USA', 840, 'NM'),
(43, 'US', 'USA', 840, 'NY'),
(44, 'US', 'USA', 840, 'NC'),
(45, 'US', 'USA', 840, 'ND'),
(46, 'US', 'USA', 840, 'MP'),
(47, 'US', 'USA', 840, 'OH'),
(48, 'US', 'USA', 840, 'OK'),
(49, 'US', 'USA', 840, 'OR'),
(50, 'US', 'USA', 840, 'PW'),
(51, 'US', 'USA', 840, 'PA'),
(52, 'US', 'USA', 840, 'PR'),
(53, 'US', 'USA', 840, 'RI'),
(54, 'US', 'USA', 840, 'SC'),
(55, 'US', 'USA', 840, 'SD'),
(56, 'US', 'USA', 840, 'TN'),
(57, 'US', 'USA', 840, 'TX'),
(58, 'US', 'USA', 840, 'UT'),
(59, 'US', 'USA', 840, 'VT'),
(60, 'US', 'USA', 840, 'VI'),
(61, 'US', 'USA', 840, 'VA'),
(62, 'US', 'USA', 840, 'WA'),
(63, 'US', 'USA', 840, 'WV'),
(64, 'US', 'USA', 840, 'WI'),
(65, 'US', 'USA', 840, 'WY'),
(66, 'CA', 'CAN', 142, 'AB'),
(67, 'CA', 'CAN', 142, 'BC'),
(68, 'CA', 'CAN', 142, 'MB'),
(69, 'CA', 'CAN', 142, 'NF'),
(70, 'CA', 'CAN', 142, 'NB'),
(71, 'CA', 'CAN', 142, 'NS'),
(72, 'CA', 'CAN', 142, 'NT'),
(73, 'CA', 'CAN', 142, 'NU'),
(74, 'CA', 'CAN', 142, 'ON'),
(75, 'CA', 'CAN', 142, 'PE'),
(76, 'CA', 'CAN', 142, 'QC'),
(77, 'CA', 'CAN', 142, 'SK'),
(78, 'CA', 'CAN', 142, 'YT'),
(79, 'DE', 'DEU', 276, 'NDS'),
(80, 'DE', 'DEU', 276, 'BAW'),
(81, 'DE', 'DEU', 276, 'BAY'),
(82, 'DE', 'DEU', 276, 'BER'),
(83, 'DE', 'DEU', 276, 'BRG'),
(84, 'DE', 'DEU', 276, 'BRE'),
(85, 'DE', 'DEU', 276, 'HAM'),
(86, 'DE', 'DEU', 276, 'HES'),
(87, 'DE', 'DEU', 276, 'MEC'),
(88, 'DE', 'DEU', 276, 'NRW'),
(89, 'DE', 'DEU', 276, 'RHE'),
(90, 'DE', 'DEU', 276, 'SAR'),
(91, 'DE', 'DEU', 276, 'SAS'),
(92, 'DE', 'DEU', 276, 'SAC'),
(93, 'DE', 'DEU', 276, 'SCN'),
(94, 'DE', 'DEU', 276, 'THE'),
(95, 'AT', 'AUT', 40, 'WI'),
(96, 'AT', 'AUT', 40, 'NO'),
(97, 'AT', 'AUT', 40, 'OO'),
(98, 'AT', 'AUT', 40, 'SB'),
(99, 'AT', 'AUT', 40, 'KN'),
(100, 'AT', 'AUT', 40, 'ST'),
(101, 'AT', 'AUT', 40, 'TI'),
(102, 'AT', 'AUT', 40, 'BL'),
(103, 'AT', 'AUT', 40, 'VB'),
(104, 'CH', 'CHE', 756, 'AG'),
(105, 'CH', 'CHE', 756, 'AI'),
(106, 'CH', 'CHE', 756, 'AR'),
(107, 'CH', 'CHE', 756, 'BE'),
(108, 'CH', 'CHE', 756, 'BL'),
(109, 'CH', 'CHE', 756, 'BS'),
(110, 'CH', 'CHE', 756, 'FR'),
(111, 'CH', 'CHE', 756, 'GE'),
(112, 'CH', 'CHE', 756, 'GL'),
(113, 'CH', 'CHE', 756, 'GR'),
(114, 'CH', 'CHE', 756, 'JU'),
(115, 'CH', 'CHE', 756, 'LU'),
(116, 'CH', 'CHE', 756, 'NE'),
(117, 'CH', 'CHE', 756, 'NW'),
(118, 'CH', 'CHE', 756, 'OW'),
(119, 'CH', 'CHE', 756, 'SG'),
(120, 'CH', 'CHE', 756, 'SH'),
(121, 'CH', 'CHE', 756, 'SO'),
(122, 'CH', 'CHE', 756, 'SZ'),
(123, 'CH', 'CHE', 756, 'TG'),
(124, 'CH', 'CHE', 756, 'TI'),
(125, 'CH', 'CHE', 756, 'UR'),
(126, 'CH', 'CHE', 756, 'VD'),
(127, 'CH', 'CHE', 756, 'VS'),
(128, 'CH', 'CHE', 756, 'ZG'),
(129, 'CH', 'CHE', 756, 'ZH'),
(130, 'ES', 'ESP', 724, 'Alava'),
(131, 'ES', 'ESP', 724, 'Malaga'),
(132, 'ES', 'ESP', 724, 'Segovia'),
(133, 'ES', 'ESP', 724, 'Granada'),
(134, 'ES', 'ESP', 724, 'Jaen'),
(135, 'ES', 'ESP', 724, 'Sevilla'),
(136, 'ES', 'ESP', 724, 'Barcelona'),
(137, 'ES', 'ESP', 724, 'Valencia'),
(138, 'ES', 'ESP', 724, 'Alicante'),
(139, 'ES', 'ESP', 724, 'Almeria'),
(140, 'ES', 'ESP', 724, 'Asturias'),
(141, 'ES', 'ESP', 724, 'Avila'),
(142, 'ES', 'ESP', 724, 'Badajoz'),
(143, 'ES', 'ESP', 724, 'Burgos'),
(144, 'ES', 'ESP', 724, 'Caceres'),
(145, 'ES', 'ESP', 724, 'Cadiz'),
(146, 'ES', 'ESP', 724, 'Cantabria'),
(147, 'ES', 'ESP', 724, 'Castellon'),
(148, 'ES', 'ESP', 724, 'Ceuta'),
(149, 'ES', 'ESP', 724, 'Ciudad Real'),
(150, 'ES', 'ESP', 724, 'Cordoba'),
(151, 'ES', 'ESP', 724, 'Cuenca'),
(152, 'ES', 'ESP', 724, 'Girona'),
(153, 'ES', 'ESP', 724, 'Las Palmas'),
(154, 'ES', 'ESP', 724, 'Guadalajara'),
(155, 'ES', 'ESP', 724, 'Guipuzcoa'),
(156, 'ES', 'ESP', 724, 'Huelva'),
(157, 'ES', 'ESP', 724, 'Huesca'),
(158, 'ES', 'ESP', 724, 'A Coru'),
(159, 'ES', 'ESP', 724, 'La Rioja'),
(160, 'ES', 'ESP', 724, 'Leon'),
(161, 'ES', 'ESP', 724, 'Lugo'),
(162, 'ES', 'ESP', 724, 'Lleida'),
(163, 'ES', 'ESP', 724, 'Madrid'),
(164, 'ES', 'ESP', 724, 'Baleares'),
(166, 'ES', 'ESP', 724, 'Murcia'),
(167, 'ES', 'ESP', 724, 'Navarra'),
(168, 'ES', 'ESP', 724, 'Ourense'),
(169, 'ES', 'ESP', 724, 'Palencia'),
(170, 'ES', 'ESP', 724, 'Pontevedra'),
(171, 'ES', 'ESP', 724, 'Salamanca'),
(172, 'ES', 'ESP', 724, 'Soria'),
(173, 'ES', 'ESP', 724, 'Tarragona'),
(174, 'ES', 'ESP', 724, 'Tenerife'),
(175, 'ES', 'ESP', 724, 'Teruel'),
(176, 'ES', 'ESP', 724, 'Toledo'),
(177, 'ES', 'ESP', 724, 'Valladolid'),
(178, 'ES', 'ESP', 724, 'Vizcaya'),
(179, 'ES', 'ESP', 724, 'Zamora'),
(180, 'ES', 'ESP', 724, 'Zaragoza'),
(181, 'ES', 'ESP', 724, 'Melilla'),
(182, 'MX', 'MEX', 484, 'AGS'),
(183, 'MX', 'MEX', 484, 'BCS'),
(184, 'MX', 'MEX', 484, 'BC'),
(185, 'MX', 'MEX', 484, 'CAM'),
(186, 'MX', 'MEX', 484, 'CHIS'),
(187, 'MX', 'MEX', 484, 'CHIH'),
(188, 'MX', 'MEX', 484, 'COAH'),
(189, 'MX', 'MEX', 484, 'COL'),
(190, 'MX', 'MEX', 484, 'DIF'),
(191, 'MX', 'MEX', 484, 'DGO'),
(192, 'MX', 'MEX', 484, 'GTO'),
(193, 'MX', 'MEX', 484, 'GRO'),
(194, 'MX', 'MEX', 484, 'HGO'),
(195, 'MX', 'MEX', 484, 'JAL'),
(196, 'MX', 'MEX', 484, 'MEX'),
(197, 'MX', 'MEX', 484, 'MICH'),
(198, 'MX', 'MEX', 484, 'MOR'),
(199, 'MX', 'MEX', 484, 'NAY'),
(200, 'MX', 'MEX', 484, 'NL'),
(201, 'MX', 'MEX', 484, 'OAX'),
(202, 'MX', 'MEX', 484, 'PUE'),
(203, 'MX', 'MEX', 484, 'QRO'),
(204, 'MX', 'MEX', 484, 'QROO'),
(205, 'MX', 'MEX', 484, 'SLP'),
(206, 'MX', 'MEX', 484, 'SIN'),
(207, 'MX', 'MEX', 484, 'SON'),
(208, 'MX', 'MEX', 484, 'TAB'),
(209, 'MX', 'MEX', 484, 'TAMPS'),
(210, 'MX', 'MEX', 484, 'TLAX'),
(211, 'MX', 'MEX', 484, 'VER'),
(212, 'MX', 'MEX', 484, 'YUC'),
(213, 'MX', 'MEX', 484, 'ZAC'),
(214, 'AU', 'AUS', 36, 'ACT'),
(215, 'AU', 'AUS', 36, 'NSW'),
(216, 'AU', 'AUS', 36, 'NT'),
(217, 'AU', 'AUS', 36, 'QLD'),
(218, 'AU', 'AUS', 36, 'SA'),
(219, 'AU', 'AUS', 36, 'TAS'),
(220, 'AU', 'AUS', 36, 'VIC'),
(221, 'AU', 'AUS', 36, 'WA'),
(222, 'IT', 'ITA', 380, 'AG'),
(223, 'IT', 'ITA', 380, 'AL'),
(224, 'IT', 'ITA', 380, 'AN'),
(225, 'IT', 'ITA', 380, 'AO'),
(226, 'IT', 'ITA', 380, 'AP'),
(227, 'IT', 'ITA', 380, 'AQ'),
(228, 'IT', 'ITA', 380, 'AR'),
(229, 'IT', 'ITA', 380, 'AT'),
(230, 'IT', 'ITA', 380, 'AV'),
(231, 'IT', 'ITA', 380, 'BA'),
(232, 'IT', 'ITA', 380, 'BG'),
(233, 'IT', 'ITA', 380, 'BI'),
(234, 'IT', 'ITA', 380, 'BL'),
(235, 'IT', 'ITA', 380, 'BN'),
(236, 'IT', 'ITA', 380, 'BO'),
(237, 'IT', 'ITA', 380, 'BR'),
(238, 'IT', 'ITA', 380, 'BS'),
(239, 'IT', 'ITA', 380, 'BZ'),
(240, 'IT', 'ITA', 380, 'CA'),
(241, 'IT', 'ITA', 380, 'CB'),
(242, 'IT', 'ITA', 380, 'CE'),
(243, 'IT', 'ITA', 380, 'CH'),
(244, 'IT', 'ITA', 380, 'CL'),
(245, 'IT', 'ITA', 380, 'CN'),
(246, 'IT', 'ITA', 380, 'CO'),
(247, 'IT', 'ITA', 380, 'CR'),
(248, 'IT', 'ITA', 380, 'CS'),
(249, 'IT', 'ITA', 380, 'CT'),
(250, 'IT', 'ITA', 380, 'CZ'),
(251, 'IT', 'ITA', 380, 'EN'),
(252, 'IT', 'ITA', 380, 'FE'),
(253, 'IT', 'ITA', 380, 'FG'),
(254, 'IT', 'ITA', 380, 'FI'),
(255, 'IT', 'ITA', 380, 'FO'),
(256, 'IT', 'ITA', 380, 'FR'),
(257, 'IT', 'ITA', 380, 'GE'),
(258, 'IT', 'ITA', 380, 'GO'),
(259, 'IT', 'ITA', 380, 'GR'),
(260, 'IT', 'ITA', 380, 'IM'),
(261, 'IT', 'ITA', 380, 'IS'),
(262, 'IT', 'ITA', 380, 'KR'),
(263, 'IT', 'ITA', 380, 'LC'),
(264, 'IT', 'ITA', 380, 'LE'),
(265, 'IT', 'ITA', 380, 'LI'),
(266, 'IT', 'ITA', 380, 'LO'),
(267, 'IT', 'ITA', 380, 'LT'),
(268, 'IT', 'ITA', 380, 'LU'),
(269, 'IT', 'ITA', 380, 'MC'),
(270, 'IT', 'ITA', 380, 'ME'),
(271, 'IT', 'ITA', 380, 'MI'),
(272, 'IT', 'ITA', 380, 'MN'),
(273, 'IT', 'ITA', 380, 'MO'),
(274, 'IT', 'ITA', 380, 'MS'),
(275, 'IT', 'ITA', 380, 'MT'),
(276, 'IT', 'ITA', 380, 'NA'),
(277, 'IT', 'ITA', 380, 'NO'),
(278, 'IT', 'ITA', 380, 'NU'),
(279, 'IT', 'ITA', 380, 'OR'),
(280, 'IT', 'ITA', 380, 'PA'),
(281, 'IT', 'ITA', 380, 'PC'),
(282, 'IT', 'ITA', 380, 'PD'),
(283, 'IT', 'ITA', 380, 'PE'),
(284, 'IT', 'ITA', 380, 'PG'),
(285, 'IT', 'ITA', 380, 'PI'),
(286, 'IT', 'ITA', 380, 'PN'),
(287, 'IT', 'ITA', 380, 'PR'),
(288, 'IT', 'ITA', 380, 'PS'),
(289, 'IT', 'ITA', 380, 'PT'),
(290, 'IT', 'ITA', 380, 'PV'),
(291, 'IT', 'ITA', 380, 'PO'),
(292, 'IT', 'ITA', 380, 'PZ'),
(293, 'IT', 'ITA', 380, 'RA'),
(294, 'IT', 'ITA', 380, 'RC'),
(295, 'IT', 'ITA', 380, 'RE'),
(296, 'IT', 'ITA', 380, 'RG'),
(297, 'IT', 'ITA', 380, 'RI'),
(298, 'IT', 'ITA', 380, 'RM'),
(299, 'IT', 'ITA', 380, 'RN'),
(300, 'IT', 'ITA', 380, 'RO'),
(301, 'IT', 'ITA', 380, 'SA'),
(302, 'IT', 'ITA', 380, 'SI'),
(303, 'IT', 'ITA', 380, 'SO'),
(304, 'IT', 'ITA', 380, 'SP'),
(305, 'IT', 'ITA', 380, 'SR'),
(306, 'IT', 'ITA', 380, 'SS'),
(307, 'IT', 'ITA', 380, 'SV'),
(308, 'IT', 'ITA', 380, 'TA'),
(309, 'IT', 'ITA', 380, 'TE'),
(310, 'IT', 'ITA', 380, 'TN'),
(311, 'IT', 'ITA', 380, 'TO'),
(312, 'IT', 'ITA', 380, 'TP'),
(313, 'IT', 'ITA', 380, 'TR'),
(314, 'IT', 'ITA', 380, 'TS'),
(315, 'IT', 'ITA', 380, 'TV'),
(316, 'IT', 'ITA', 380, 'UD'),
(317, 'IT', 'ITA', 380, 'VA'),
(318, 'IT', 'ITA', 380, 'VC'),
(319, 'IT', 'ITA', 380, 'VE'),
(320, 'IT', 'ITA', 380, 'VI'),
(321, 'IT', 'ITA', 380, 'VP'),
(322, 'IT', 'ITA', 380, 'VR'),
(323, 'IT', 'ITA', 380, 'VT'),
(324, 'IT', 'ITA', 380, 'VV'),
(325, 'GB', 'GBR', 826, 'ALD'),
(326, 'GB', 'GBR', 826, 'ARM'),
(327, 'GB', 'GBR', 826, 'ATM'),
(328, 'GB', 'GBR', 826, 'BDS'),
(329, 'GB', 'GBR', 826, 'BFD'),
(330, 'GB', 'GBR', 826, 'BIR'),
(331, 'GB', 'GBR', 826, 'BLG'),
(332, 'GB', 'GBR', 826, 'BRI'),
(333, 'GB', 'GBR', 826, 'BRK'),
(334, 'GB', 'GBR', 826, 'BRS'),
(335, 'GB', 'GBR', 826, 'BUX'),
(336, 'GB', 'GBR', 826, 'CAP'),
(337, 'GB', 'GBR', 826, 'CAR'),
(338, 'GB', 'GBR', 826, 'CAS'),
(339, 'GB', 'GBR', 826, 'CBA'),
(340, 'GB', 'GBR', 826, 'CBE'),
(341, 'GB', 'GBR', 826, 'CER'),
(342, 'GB', 'GBR', 826, 'CHI'),
(343, 'GB', 'GBR', 826, 'CHS'),
(344, 'GB', 'GBR', 826, 'CLD'),
(345, 'GB', 'GBR', 826, 'CNL'),
(346, 'GB', 'GBR', 826, 'CON'),
(347, 'GB', 'GBR', 826, 'CTR'),
(348, 'GB', 'GBR', 826, 'CVE'),
(349, 'GB', 'GBR', 826, 'DEN'),
(350, 'GB', 'GBR', 826, 'DFD'),
(351, 'GB', 'GBR', 826, 'DGL'),
(352, 'GB', 'GBR', 826, 'DHM'),
(353, 'GB', 'GBR', 826, 'DOR'),
(354, 'GB', 'GBR', 826, 'DVN'),
(355, 'GB', 'GBR', 826, 'DWN'),
(356, 'GB', 'GBR', 826, 'DYS'),
(357, 'GB', 'GBR', 826, 'ESX'),
(358, 'GB', 'GBR', 826, 'FER'),
(359, 'GB', 'GBR', 826, 'FFE'),
(360, 'GB', 'GBR', 826, 'FLI'),
(361, 'GB', 'GBR', 826, 'FMH'),
(362, 'GB', 'GBR', 826, 'GDD'),
(363, 'GB', 'GBR', 826, 'GLO'),
(364, 'GB', 'GBR', 826, 'GLR'),
(365, 'GB', 'GBR', 826, 'GNM'),
(366, 'GB', 'GBR', 826, 'GNS'),
(367, 'GB', 'GBR', 826, 'GNW'),
(368, 'GB', 'GBR', 826, 'GRN'),
(369, 'GB', 'GBR', 826, 'GUR'),
(370, 'GB', 'GBR', 826, 'GWT'),
(371, 'GB', 'GBR', 826, 'HBS'),
(372, 'GB', 'GBR', 826, 'HFD'),
(373, 'GB', 'GBR', 826, 'HLD'),
(374, 'GB', 'GBR', 826, 'HPH'),
(375, 'GB', 'GBR', 826, 'HWR'),
(376, 'GB', 'GBR', 826, 'IOM'),
(377, 'GB', 'GBR', 826, 'IOW'),
(378, 'GB', 'GBR', 826, 'ISL'),
(379, 'GB', 'GBR', 826, 'JER'),
(380, 'GB', 'GBR', 826, 'KNT'),
(381, 'GB', 'GBR', 826, 'LCN'),
(382, 'GB', 'GBR', 826, 'LDN'),
(383, 'GB', 'GBR', 826, 'LDR'),
(384, 'GB', 'GBR', 826, 'LEC'),
(385, 'GB', 'GBR', 826, 'LNH'),
(386, 'GB', 'GBR', 826, 'LON'),
(387, 'GB', 'GBR', 826, 'LTE'),
(388, 'GB', 'GBR', 826, 'LTM'),
(389, 'GB', 'GBR', 826, 'LTW'),
(390, 'GB', 'GBR', 826, 'MCH'),
(391, 'GB', 'GBR', 826, 'MER'),
(392, 'GB', 'GBR', 826, 'MON'),
(393, 'GB', 'GBR', 826, 'MSY'),
(394, 'GB', 'GBR', 826, 'NET'),
(395, 'GB', 'GBR', 826, 'NEW'),
(396, 'GB', 'GBR', 826, 'NHM'),
(397, 'GB', 'GBR', 826, 'NLD'),
(398, 'GB', 'GBR', 826, 'NOR'),
(399, 'GB', 'GBR', 826, 'NOT'),
(400, 'GB', 'GBR', 826, 'NWH'),
(401, 'GB', 'GBR', 826, 'OFE'),
(402, 'GB', 'GBR', 826, 'ORK'),
(403, 'GB', 'GBR', 826, 'PEM'),
(404, 'GB', 'GBR', 826, 'PWS'),
(405, 'GB', 'GBR', 826, 'SCD'),
(406, 'GB', 'GBR', 826, 'SFD'),
(407, 'GB', 'GBR', 826, 'SFK'),
(408, 'GB', 'GBR', 826, 'SLD'),
(409, 'GB', 'GBR', 826, 'SOM'),
(410, 'GB', 'GBR', 826, 'SPE'),
(411, 'GB', 'GBR', 826, 'SRK'),
(412, 'GB', 'GBR', 826, 'SRY'),
(413, 'GB', 'GBR', 826, 'SWA'),
(414, 'GB', 'GBR', 826, 'SXE'),
(415, 'GB', 'GBR', 826, 'SXW'),
(416, 'GB', 'GBR', 826, 'TAF'),
(417, 'GB', 'GBR', 826, 'TOR'),
(418, 'GB', 'GBR', 826, 'TWR'),
(419, 'GB', 'GBR', 826, 'TYR'),
(420, 'GB', 'GBR', 826, 'TYS'),
(421, 'GB', 'GBR', 826, 'VAL'),
(422, 'GB', 'GBR', 826, 'WIL'),
(423, 'GB', 'GBR', 826, 'WKS'),
(424, 'GB', 'GBR', 826, 'WLT'),
(425, 'GB', 'GBR', 826, 'WMD'),
(426, 'GB', 'GBR', 826, 'WRE'),
(427, 'GB', 'GBR', 826, 'YSN'),
(428, 'GB', 'GBR', 826, 'YSS'),
(429, 'GB', 'GBR', 826, 'YSW'),
(430, 'IE', 'IRL', 372, 'CAR'),
(431, 'IE', 'IRL', 372, 'CAV'),
(432, 'IE', 'IRL', 372, 'CLA'),
(433, 'IE', 'IRL', 372, 'COR'),
(434, 'IE', 'IRL', 372, 'DON'),
(435, 'IE', 'IRL', 372, 'DUB'),
(436, 'IE', 'IRL', 372, 'GAL'),
(437, 'IE', 'IRL', 372, 'KER'),
(438, 'IE', 'IRL', 372, 'KIL'),
(439, 'IE', 'IRL', 372, 'KLK'),
(440, 'IE', 'IRL', 372, 'LAO'),
(441, 'IE', 'IRL', 372, 'LEI'),
(442, 'IE', 'IRL', 372, 'LIM'),
(443, 'IE', 'IRL', 372, 'LON'),
(444, 'IE', 'IRL', 372, 'LOU'),
(445, 'IE', 'IRL', 372, 'MAY'),
(446, 'IE', 'IRL', 372, 'MEA'),
(447, 'IE', 'IRL', 372, 'MON'),
(448, 'IE', 'IRL', 372, 'OFF'),
(449, 'IE', 'IRL', 372, 'ROS'),
(450, 'IE', 'IRL', 372, 'SLI'),
(451, 'IE', 'IRL', 372, 'TIP'),
(452, 'IE', 'IRL', 372, 'WAT'),
(453, 'IE', 'IRL', 372, 'WES'),
(454, 'IE', 'IRL', 372, 'WEX'),
(455, 'IE', 'IRL', 372, 'WIC'),
(456, 'BR', 'BRA', 76, 'AC'),
(457, 'BR', 'BRA', 76, 'AP'),
(458, 'BR', 'BRA', 76, 'AL'),
(459, 'BR', 'BRA', 76, 'AM'),
(460, 'BR', 'BRA', 76, 'BA'),
(461, 'BR', 'BRA', 76, 'CE'),
(462, 'BR', 'BRA', 76, 'DF'),
(463, 'BR', 'BRA', 76, 'ES'),
(464, 'BR', 'BRA', 76, 'GO'),
(465, 'BR', 'BRA', 76, 'MA'),
(466, 'BR', 'BRA', 76, 'MG'),
(467, 'BR', 'BRA', 76, 'MS'),
(468, 'BR', 'BRA', 76, 'MT'),
(469, 'BR', 'BRA', 76, 'PA'),
(470, 'BR', 'BRA', 76, 'PB'),
(471, 'BR', 'BRA', 76, 'PE'),
(472, 'BR', 'BRA', 76, 'PI'),
(473, 'BR', 'BRA', 76, 'PR'),
(474, 'BR', 'BRA', 76, 'RJ'),
(475, 'BR', 'BRA', 76, 'RN'),
(476, 'BR', 'BRA', 76, 'RO'),
(477, 'BR', 'BRA', 76, 'RR'),
(478, 'BR', 'BRA', 76, 'RS'),
(479, 'BR', 'BRA', 76, 'SC'),
(480, 'BR', 'BRA', 76, 'SE'),
(481, 'BR', 'BRA', 76, 'SP'),
(482, 'BR', 'BRA', 76, 'TO');

--
-- Daten für Tabelle `static_currency`
--

INSERT INTO `static_currency` (`id`, `iso_alpha`, `iso_num`, `symbol_left`, `symbol_right`, `thousands_point`, `decimal_point`, `decimal_digits`, `sub_divisor`, `sub_symbol_left`, `sub_symbol_right`) VALUES
(2, 'AED', 784, 'Dhs.', '', '.', ',', 2, 100, '', ''),
(4, 'ALL', 8, 'L', '', '.', ',', 2, 100, '', ''),
(5, 'AMD', 51, 'Dram', '', '.', ',', 2, 100, '', ''),
(6, 'ANG', 532, 'NAƒ', '', '.', ',', 2, 100, '', ''),
(7, 'AOA', 973, 'Kz', '', '.', ',', 2, 100, '', ''),
(8, 'ARS', 32, '$', '', '.', ',', 2, 100, '', ''),
(9, 'AUD', 36, '$A', '', '.', ',', 2, 100, '', ''),
(10, 'AWG', 533, 'Af.', '', '.', ',', 2, 100, '', ''),
(11, 'AZN', 944, '', '', '.', ',', 2, 100, '', ''),
(12, 'BAM', 977, 'KM', '', '.', ',', 2, 100, '', ''),
(13, 'BBD', 52, 'Bds$', '', '.', ',', 2, 100, '', ''),
(14, 'BDT', 50, 'Tk', '', '.', ',', 2, 100, '', ''),
(16, 'BGN', 975, 'lv', '', '.', ',', 2, 100, '', ''),
(17, 'BHD', 48, 'BD', '', '.', ',', 3, 1000, '', ''),
(18, 'BIF', 108, 'FBu', '', '.', '', 2, 100, '', ''),
(19, 'BMD', 60, 'BM$', '', '.', ',', 2, 100, '', ''),
(20, 'BND', 96, 'B$', '', '.', ',', 2, 100, '', ''),
(21, 'BOB', 68, 'Bs', '', '.', ',', 2, 100, '', ''),
(23, 'BRL', 986, 'R$', '', '.', ',', 2, 100, '', ''),
(24, 'BSD', 44, '$', '', '.', ',', 2, 100, '', ''),
(25, 'BTN', 64, 'Nu', '', '.', ',', 2, 100, '', ''),
(26, 'BWP', 72, 'P', '', '.', ',', 2, 100, '', ''),
(27, 'BYR', 974, 'Br', '', '.', ',', 2, 100, '', ''),
(28, 'BZD', 84, 'BZ', '', '.', ',', 2, 100, '', ''),
(29, 'CAD', 124, '$', '', '.', ',', 2, 100, '', '¢'),
(30, 'CDF', 976, 'FC', '', '.', ',', 2, 100, '', ''),
(31, 'CHF', 756, 'SFr.', '', '.', ',', 2, 100, '', ''),
(33, 'CLP', 152, '$', '', '.', '', 0, 1, '', ''),
(34, 'CNY', 156, 'Ұ', '', '.', ',', 2, 100, '', ''),
(35, 'COP', 170, '$', '', '.', ',', 2, 100, '', ''),
(36, 'CRC', 188, '₡', '', '.', ',', 2, 100, '', ''),
(37, 'CUP', 192, 'Cub$', '', '.', ',', 2, 100, '', ''),
(38, 'CVE', 132, 'CVEsc.', '', '.', ',', 2, 100, '', ''),
(39, 'CYP', 196, 'C£', '', '.', ',', 2, 100, '', ''),
(40, 'CZK', 203, '', 'Kč', '.', ',', 2, 100, '', ''),
(41, 'DJF', 262, 'FD', '', '.', '', 0, 1, '', ''),
(42, 'DKK', 208, 'kr.', '', '.', ',', 2, 100, '', ''),
(43, 'DOP', 214, 'RD$', '', '.', ',', 2, 100, '', ''),
(44, 'DZD', 12, 'DA', '', '.', ',', 2, 100, '', ''),
(45, 'EEK', 233, '', 'ekr', '.', ',', 2, 100, '', ''),
(46, 'EGP', 818, 'LE', '', '.', ',', 2, 100, '', ''),
(47, 'ERN', 232, 'Nfa', '', '.', ',', 2, 100, '', ''),
(48, 'ETB', 230, 'Br', '', '.', ',', 2, 100, '', ''),
(49, 'EUR', 978, '€', '', '.', ',', 2, 100, '¢', ''),
(50, 'FJD', 242, 'FJ$', '', '.', ',', 2, 100, '', ''),
(51, 'FKP', 238, 'Fl£', '', '.', ',', 2, 100, '', ''),
(52, 'GBP', 826, '£', '', ',', '.', 2, 100, '', 'p'),
(53, 'GEL', 981, '', 'lari', '.', ',', 2, 100, '', ''),
(54, 'GHC', 288, '', '', '.', ',', 2, 100, '', ''),
(55, 'GIP', 292, '£', '', '.', ',', 2, 100, '', ''),
(56, 'GMD', 270, 'D', '', '.', ',', 2, 100, '', ''),
(57, 'GNF', 324, 'GF', '', '.', '', 0, 1, '', ''),
(58, 'GTQ', 320, 'Q.', '', '.', ',', 2, 100, '', ''),
(59, 'GWP', 624, '', '', '.', ',', 2, 100, '', ''),
(60, 'GYD', 328, 'G$', '', '.', ',', 2, 100, '', ''),
(61, 'HKD', 344, 'HK$', '', '.', ',', 2, 100, '', ''),
(62, 'HNL', 340, 'L', '', '.', ',', 2, 100, '', ''),
(63, 'HRK', 191, 'kn', '', '.', ',', 2, 100, '', ''),
(64, 'HTG', 332, 'Gde.', '', '.', ',', 2, 100, '', ''),
(65, 'HUF', 348, '', 'Ft', '.', ',', 2, 100, '', ''),
(66, 'IDR', 360, 'Rp.', '', '.', ',', 2, 100, '', ''),
(67, 'ILS', 376, '', 'NIS', '.', ',', 2, 100, '', ''),
(68, 'INR', 356, 'Rs', '', '.', ',', 2, 100, '', ''),
(69, 'IQD', 368, 'ID', '', '.', ',', 3, 1000, '', ''),
(70, 'IRR', 364, 'Rls', '', '.', ',', 2, 100, '', ''),
(71, 'ISK', 352, '', 'ikr', '.', ',', 2, 100, '', ''),
(72, 'JMD', 388, 'J$', '', '.', ',', 2, 100, '', ''),
(73, 'JOD', 400, 'JD', '', '.', ',', 2, 100, '', ''),
(74, 'JPY', 392, '¥', '', '.', '', 2, 100, '', ''),
(75, 'KES', 404, 'Kshs.', '', '.', ',', 2, 100, '', ''),
(76, 'KGS', 417, 'K.S.', '', '.', ',', 2, 100, '', ''),
(77, 'KHR', 116, 'CR', '', '.', ',', 2, 100, '', ''),
(78, 'KMF', 174, 'CF', '', '.', '', 0, 1, '', ''),
(79, 'KPW', 408, '₩n', '', '.', ',', 2, 100, '', ''),
(80, 'KRW', 410, '￦', '', '.', '', 2, 100, '', ''),
(81, 'KWD', 414, 'KD', '', '.', ',', 3, 1000, '', ''),
(82, 'KYD', 136, '$', '', '.', ',', 2, 100, '', ''),
(83, 'KZT', 398, 'T', '', '.', ',', 2, 100, '', ''),
(84, 'LAK', 418, '₭', '', '.', ',', 2, 100, '', ''),
(85, 'LBP', 422, '', 'LL', '.', ',', 2, 100, '', ''),
(86, 'LKR', 144, 'Re', '', '.', ',', 2, 100, '', ''),
(87, 'LRD', 430, 'Lib$', '', '.', ',', 2, 100, '', ''),
(88, 'LSL', 426, 'M', '', '.', ',', 2, 100, '', ''),
(89, 'LTL', 440, '', 'Lt', '.', ',', 2, 100, '', ''),
(90, 'LVL', 428, 'Ls', '', '.', ',', 2, 100, '', ''),
(91, 'LYD', 434, 'LD.', '', '.', ',', 3, 1000, '', ''),
(92, 'MAD', 504, 'Dh', '', '.', ',', 2, 100, '', ''),
(93, 'MDL', 498, '', '', '.', ',', 2, 100, '', ''),
(95, 'MKD', 807, 'Den', '', '.', ',', 2, 100, '', ''),
(96, 'MMK', 104, 'K', '', '.', ',', 2, 100, '', ''),
(97, 'MNT', 496, '₮', '', '.', ',', 2, 100, '', ''),
(98, 'MOP', 446, 'Pat.', '', '.', ',', 2, 100, '', ''),
(99, 'MRO', 478, 'UM', '', '.', ',', 2, 100, '', ''),
(100, 'MTL', 470, 'Lm', '', '.', ',', 2, 100, '', ''),
(101, 'MUR', 480, 'Rs', '', '.', ',', 2, 100, '', ''),
(102, 'MVR', 462, 'Rf', '', '.', ',', 2, 100, '', ''),
(103, 'MWK', 454, 'MK', '', '.', ',', 2, 100, '', ''),
(104, 'MXN', 484, '$', '', '.', ',', 2, 100, '', ''),
(106, 'MYR', 458, 'RM', '', '.', ',', 2, 100, '', ''),
(107, 'MZM', 508, '', 'Mt', '.', ',', 2, 100, '', ''),
(108, 'NAD', 516, 'N$', '', '.', ',', 2, 100, '', ''),
(109, 'NGN', 566, '₦', '', '.', ',', 2, 100, '', ''),
(110, 'NIO', 558, 'C$', '', '.', ',', 2, 100, '', ''),
(111, 'NOK', 578, 'kr', '', '.', ',', 2, 100, '', ''),
(112, 'NPR', 524, 'Rs.', '', '.', ',', 2, 100, '', ''),
(113, 'NZD', 554, '$', '', '.', ',', 2, 100, '', ''),
(114, 'OMR', 512, 'OR', '', '.', ',', 3, 1000, '', ''),
(115, 'PAB', 590, 'B', '', '.', ',', 2, 100, '', ''),
(116, 'PEN', 604, 'Sl.', '', '.', ',', 2, 100, '', ''),
(117, 'PGK', 598, 'K', '', '.', ',', 2, 100, '', ''),
(118, 'PHP', 608, 'P', '', '.', ',', 2, 100, '', ''),
(119, 'PKR', 586, 'Rs.', '', '.', ',', 2, 100, '', ''),
(120, 'PLN', 985, '', 'zł', '.', ',', 2, 100, '', ''),
(121, 'PYG', 600, 'G', '', '.', '', 2, 100, '', ''),
(122, 'QAR', 634, 'QR', '', '.', ',', 2, 100, '', ''),
(123, 'ROL', 642, '', 'l', '.', ',', 2, 100, '', ''),
(124, 'RUB', 643, '', 'R', '.', ',', 2, 100, '', ''),
(126, 'RWF', 646, 'frw', '', '.', '', 0, 1, '', ''),
(127, 'SAR', 682, 'SR', '', '.', ',', 2, 100, '', ''),
(128, 'SBD', 90, 'SI$', '', '.', ',', 2, 100, '', ''),
(129, 'SCR', 690, 'SR', '', '.', ',', 2, 100, '', ''),
(130, 'SDD', 736, 'sD', '', '.', ',', 0, 1, '', ''),
(131, 'SEK', 752, '', 'kr', '.', ',', 2, 100, '', ''),
(132, 'SGD', 702, '$', '', '.', ',', 2, 100, '', ''),
(133, 'SHP', 654, '£', '', '.', ',', 2, 100, '', ''),
(134, 'SIT', 705, 'SIT', '', '.', ',', 2, 100, '', ''),
(135, 'SKK', 703, '', 'Sk', '.', ',', 2, 100, '', 'h'),
(136, 'SLL', 694, 'Le', '', '.', ',', 2, 100, '', ''),
(137, 'SOS', 706, 'So.', '', '.', ',', 2, 100, '', ''),
(139, 'STD', 678, 'Db', '', '.', ',', 2, 100, '', ''),
(140, 'SVC', 222, '₡', '', '.', ',', 2, 100, '', ''),
(141, 'SYP', 760, '£S', '', '.', ',', 2, 100, '', ''),
(142, 'SZL', 748, '', '', '.', ',', 2, 100, '', ''),
(143, 'THB', 764, '', 'Bt', '.', ',', 2, 100, '', ''),
(144, 'TJS', 972, '', '', '.', ',', 2, 100, '', ''),
(145, 'TMM', 795, '', '', '.', ',', 2, 100, '', ''),
(146, 'TND', 788, 'TD', '', '.', ',', 3, 1000, '', ''),
(147, 'TOP', 776, 'T$', '', '.', ',', 2, 100, '', ''),
(150, 'TTD', 780, 'TT$', '', '.', ',', 2, 100, '', ''),
(151, 'TWD', 901, 'NT$', '', '.', ',', 2, 100, '', ''),
(152, 'TZS', 834, 'TSh', '', '.', ',', 2, 100, '', ''),
(153, 'UAH', 980, 'hrn', '', '.', ',', 2, 100, '', ''),
(154, 'UGX', 800, 'USh', '', '.', ',', 2, 100, '', ''),
(155, 'USD', 840, '$', '', ',', '.', 2, 100, '', '¢'),
(156, 'UYU', 858, 'UR$', '', '.', ',', 2, 100, '', ''),
(157, 'UZS', 860, 'U.S.', '', '.', ',', 2, 100, '', ''),
(158, 'VEB', 862, 'Bs.', '', '.', ',', 2, 100, '', ''),
(159, 'VND', 704, '', '₫', '.', ',', 2, 100, '', ''),
(160, 'VUV', 548, '', 'VT', '.', '', 0, 1, '', ''),
(161, 'WST', 882, 'WS$', '', '.', ',', 2, 100, '', ''),
(162, 'XAF', 950, 'CFAF', '', '.', '', 0, 1, '', ''),
(163, 'XCD', 951, 'EC$', '', '.', ',', 2, 100, '', ''),
(164, 'XOF', 952, 'CFAF', '', '.', '', 0, 1, '', ''),
(165, 'XPF', 953, 'CFPF', '', '.', '', 0, 1, '', ''),
(166, 'YER', 886, 'RI', '', '.', ',', 2, 100, '', ''),
(168, 'ZAR', 710, 'R', '', '.', ',', 2, 100, '', ''),
(169, 'ZMK', 894, 'K', '', '.', ',', 2, 100, '', ''),
(170, 'ZWD', 716, '$', '', '.', ',', 2, 100, '', ''),
(171, 'AFN', 971, 'Af', '', '.', ',', 2, 100, '', ''),
(172, 'CSD', 891, '', '', '.', ',', 2, 100, '', ''),
(173, 'MGA', 969, '', '', '.', ',', 1, 5, '', ''),
(174, 'SRD', 968, '$', '', '.', ',', 2, 100, '', ''),
(175, 'TRY', 949, 'YTL', '', '.', ',', 2, 100, '', '');

--
-- Daten für Tabelle `static_territory`
--

INSERT INTO `static_territory` (`id`, `iso_num`, `parent_iso_num`) VALUES
(1, 2, 0),
(2, 9, 0),
(3, 19, 0),
(4, 142, 0),
(5, 150, 0),
(6, 30, 142),
(7, 35, 142),
(8, 62, 142),
(9, 145, 142),
(10, 39, 150),
(11, 151, 150),
(12, 154, 150),
(13, 155, 150),
(14, 830, 154),
(15, 833, 154),
(16, 5, 419),
(17, 13, 419),
(18, 21, 3),
(19, 29, 419),
(20, 11, 2),
(21, 14, 2),
(22, 15, 2),
(23, 17, 2),
(24, 18, 2),
(25, 53, 9),
(26, 54, 9),
(27, 57, 9),
(28, 61, 9);
