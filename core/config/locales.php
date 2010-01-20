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
	'en'	=> array('en_US.utf8', 'en_GB.utf8', 'en_US', 'en_GB', 'en', 'English_US', 'English_United States.1252'),
	'de'	=> array('de_CH.utf8', 'de_AT.utf8', 'de_DE.utf8', 'de_CH', 'de_AT', 'de_DE', 'de', 'de_DE@euro', 'de_DE.utf8@euro', 'deu_deu', 'German_Switzerland.1252', 'German_Germany.1252'),
	'fr'	=> array('fr_CH.utf8', 'fr_FR.utf8', 'fr'),
	'it'	=> array('it_CH.utf8', 'it_IT.utf8', 'it')
);

?>