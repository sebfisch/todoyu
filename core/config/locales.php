<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Locales which match to the selected language.
 * setlocale() tries all locales in the list, uses the first which matches
 * locales are different on the systems (WIN,LINUX,MAC, etc)
 */
$CONFIG['LOCALES'] = array(
	'en_US'	=> array('en_US.utf8', 'en_US', 'en', 'English_US', 'English_United States.1252'),
	'en_GB' => array('en_US.utf8', 'en_GB', 'en', 'English_GB'),
	'de_DE'	=> array('de_DE.utf8', 'de_DE', 'de', 'de_DE@euro', 'de_DE.utf8@euro', 'German_Germany.1252', 'deu_deu'),
	'de_CH'	=> array('de_CH.utf8', 'de_CH', 'de', 'German_Switzerland.1252'),
	'de_AT'	=> array('de_AT.utf8', 'de_AT', 'de', 'de_AT@euro', 'de_AT.utf8@euro', 'German_Austria.1252'),
	'fr_FR'	=> array('fr_FR.utf8', 'fr_FR', 'fr'),
	'fr_CH' => array('fr_CH.utf8', 'fr_CH', 'fr'),
	'it_IT'	=> array('it_IT.utf8', 'it_IT', 'it'),
	'it_CH'	=> array('it_CH.utf8', 'it_CH', 'it')
);

?>