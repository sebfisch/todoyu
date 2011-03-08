<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
		$locale		= Todoyu::person()->getLocale();
		$temp		= explode('_', $locale);
		$lang		= strtolower($temp[0]);

		$fileCore	= TodoyuFileManager::pathAbsolute('core/lib/js/jscalendar/lang/calendar-' . $lang . '.js');
		$fileLibUtf8= TodoyuFileManager::pathAbsolute('lib/js/jscalendar/lang/calendar-' . $lang . '-utf8.js');
		$fileLib	= TodoyuFileManager::pathAbsolute('lib/js/jscalendar/lang/calendar-' . $lang . '.js');
		$fileEn		= TodoyuFileManager::pathAbsolute('lib/js/jscalendar/lang/calendar-en.js');

			// Try to find translations file
			// 1. Check core for custom translation
			// 2. Check lib for default translation
			// 3. Use english translation if language is not available
		if( is_file($fileCore) ) {
			$file	= $fileCore;
		} elseif( is_file($fileLibUtf8) ) {
			$file	= $fileLibUtf8;
		}  elseif( is_file($fileLib) ) {
			$file	= $fileLib;
		} else {
			$file	= false;
		}

		return $file;
	}



	/**
	 * Add JSCalendar language JavaScript file to the page
	 */
	public static function addLangFile() {
		TodoyuPageAssetManager::addJavascript('lib/js/jscalendar/lang/calendar-en.js', 25);

		$file	= self::getLangFile();

		if( $file !== false ) {
			TodoyuPageAssetManager::addJavascript($file, 25);
		}
	}

}

?>