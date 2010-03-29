<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * JSCalendar related helper methods
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuJSCalendar {

	/**
	 * Get JSCalendar lang file suiting to system language, default: english
	 *
	 * @return	String
	 */
	public static function getLangFile() {
		$path		= PATH_LIB . '/js/jscalendar/lang/';
		$lang		= TodoyuPreferenceManager::getPreference(EXTID_CONTACT, 'language');
		$file		= $path . 'calendar-' . $lang . '.js';

		if ( ! TodoyuFileManager::isFile($file) ) {
			$file	=	$path . 'calendar-en.js';
		}

		return $file;
	}



	/**
	 * Add JSCalendar language JavaScript file to the page
	 */
	public static function addLangFile() {
		$file	= self::getLangFile();

		TodoyuPageAssetManager::addJavascript($file, 23);
	}

}

?>