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
 * Easy access to browser informations
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuBrowserInfo {

	/**
	 * Check if browser is Internet Explorer
	 *
	 * @return	Boolean
	 */
	public static function isIE() {
		return stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false;
	}



	/**
	 * Check if browser is Firefox
	 *
	 * @return	Boolean
	 */
	public static function isFirefox() {
		return stripos($_SERVER['HTTP_USER_AGENT'], 'firefox') !== false;
	}



	/**
	 * Check if browser is Safari
	 *
	 * @return	Boolean
	 */
	public static function isSafari() {
		return stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false;
	}



	/**
	 * Check if browser is Opera
	 *
	 * @return	Boolean
	 */
	public static function isOpera() {
		return stripos($_SERVER['HTTP_USER_AGENT'], 'opera') !== false;
	}



	/**
	 * Check if browser is chrome
	 *
	 * @return	Boolean
	 */
	public static function isChrome() {
		return stripos($_SERVER['HTTP_USER_AGENT'], 'chrome') !== false;
	}


	/**
	 * Get identification (name) of browser
	 *
	 * @return	String
	 */
	public static function getBrowserIdent() {
		$ident	= 'unknown';

		if( self::isFirefox() ) {
			$ident	= 'firefox';
		} elseif( self::isIE() ) {
			$ident	= 'ie';
		} elseif( self::isSafari() ) {
			$ident	= 'safari';
		} elseif( self::isOpera() ) {
			$ident	= 'opera';
		} elseif( self::isChrome() ) {
			$ident	= 'chrome';
		}

		return $ident;
	}



	/**
	 * Get the version of the browser
	 *
	 * @return	String			Version string like 5.3.21
	 */
	public static function getVersion() {
		$version	= 'unknown';
		$userAgent	= $_SERVER['HTTP_USER_AGENT'];

		if( self::isFirefox() ) {
			$pos	= strpos($userAgent, 'Firefox/');
			$parts	= explode(' ', substr($userAgent, $pos + 8));

			$version= trim($parts[0]);
		} elseif( self::isIE() ) {
			$pos	= strpos($userAgent, 'MSIE ');
			$parts	= explode(' ', substr($userAgent, $pos + 5));

			$version= trim(str_replace(';', '', $parts[0]));
		} elseif( self::isChrome() ) {
			$pos	= strpos($userAgent, 'Chrome/');
			$parts	= explode(' ', substr($userAgent, $pos + 7));

			$version= trim($parts[0]);
		} elseif( self::isSafari() ) {
			$pos	= strpos($userAgent, 'Version/');
			$parts	= explode(' ', substr($userAgent, $pos + 8));

			$version= trim($parts[0]);
		} elseif( self::isOpera() ) {
			$pos	= strpos($userAgent, 'Opera/');
			$parts	= explode(' ', substr($userAgent, $pos + 6));

			$version= trim($parts[0]);
		}

		return $version;
	}



	/**
	 * Get major version of the browser
	 *
	 * @return	Integer
	 */
	public static function getMajorVersion() {
		$version	= self::getVersion();
		$vParts		= explode('.', $version);

		return intval($vParts[0]);
	}



	/**
	 * Get browser language
	 *
	 * @return	String		Or FALSE if not found
	 */
	public static function getBrowserLanguage() {
		$language	= false;
		$accepted	= explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

		if( ! empty($accepted[0]) ) {
			$langParts	= explode('-', $accepted[0]);
			$language	= $langParts[0];
		}

		return $language;
	}

}

?>