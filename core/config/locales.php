<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Locales which match the selected language.
 * setlocale() tries all locales in the list, uses the first that matches.
 * Locales are different on the systems (WIN,LINUX,MAC, etc).
 *
 * Make sure the locale to be used is installed on your web server:
 * Use the locale program to list available locales; locale -a prints the locales your system supports.
 * Linux and Solaris systems: you can find locale at /usr/bin/locale.
 * Windows: locales are listed in the Regional Options section of the Control Panel. For using complex script and right-to-left languages or East Asian languages (e.g. japanese) install them in the "Languages" section of "Regional and Language Options" of the Control Panel.
 */
Todoyu::$CONFIG['LOCALE']['SYSTEMLOCALES'] = array(
	'en_GB' => array('en_GB.utf8', 'en_GB', 'en', 'English_GB'), // English (United Kingdom)
	//'en_US'	=> array('en_US.utf8', 'en_US', 'en', 'English_US', 'English_United States.1252'),
	'de_DE'	=> array('de_DE.utf8', 'de_DE', 'de', 'de_DE@euro', 'de_DE.utf8@euro', 'German_Germany.1252', 'deu_deu'), // German (Germany)
	//'de_CH'	=> array('de_CH.utf8', 'de_CH', 'de', 'German_Switzerland.1252'),
	//'de_AT'	=> array('de_AT.utf8', 'de_AT', 'de', 'de_AT@euro', 'de_AT.utf8@euro', 'German_Austria.1252'),
	'fr_FR'	=> array('fr_FR.utf8', 'fr_FR', 'fr'), // French
	'pt_BR' => array('pt_BR.utf8', 'pt_BR', 'pt'), // Portuguese (Brasilia)
	'ru_RU' => array('ru_RU.utf8', 'ru_RU', 'ru'), // Russian
	'nl_NL' => array('nl_NL.utf8', 'nl_NL', 'nl'), // Dutch
	'cs_CZ' => array('cs_CZ.utf8', 'cs_CZ', 'cs'), // Czech
	'hr_HR' => array('hr_HR.utf8', 'hr_HR', 'hr'), // Croatian
	'sr_ME' => array('sr_ME.utf8', 'sr_ME', 'sr'), // Serbian (Montenegro)
	'sr_RS' => array('sr_RS.utf8', 'sr_RS', 'sr'), // Serbian (Serbia)
	'ja_JP' => array('ja_JP.utf8', 'ja_JP', 'ja') // Japanese
);
Todoyu::$CONFIG['LOCALE']['default']	= 'en_GB';
Todoyu::$CONFIG['LOCALE']['available']	= array('en_GB','de_DE','pt_BR','ru_RU','fr_FR','nl_NL','cs_CZ','sr_RS','ja_JP'/*,'hr_HR','sr_ME'*/);
?>