DROP TABLE `contactinfo_type`

;

CREATE TABLE `ext_user_contactinfotype` (
  `id` int(11) NOT NULL auto_increment,
  `deleted` tinyint(1) NOT NULL default '0',
  `key` varchar(20) NOT NULL,
  `title` varchar(48) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8

;

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

