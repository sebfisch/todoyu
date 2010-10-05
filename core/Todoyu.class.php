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
 * Superglobal object to access important data and objects
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class Todoyu {

	/**
	 * Current system locale
	 *
	 * @var	String
	 */
	private static $locale;


	/**
	 * Todoyu configuration
	 * All configuration of todoyu and all extensions gets into this static variable
	 *
	 * @var	Array
	 */
	public static $CONFIG	= array();


	/**
	 * Database object instance
	 *
	 * @var	Database
	 */
	private static $db;


	/**
	 * Templating engine. Currenty Dwoo
	 *
	 * @var	Dwoo
	 */
	private static $template;

	/**
	 * Log object instance
	 *
	 * @var	TodoyuLogger
	 */
	private static $logger;

	/**
	 * Currently logged in person
	 *
	 * @var	TodoyuPerson
	 */
	private static $person;


	/**
	 * Currently used timezone
	 *
	 * @var	String
	 */
	private static $timezone;



	/**
	 * Initialize static Todoyu class
	 */
	public static function init() {
			// Set system locale with setlocale
		self::setLocale();

			// Set system timezone
		self::setTimezone();
	}



	/**
	 * Set system timezone
	 *
	 * @param	String|Boolean	$forceTimezone		Set new timezone
	 */
	public static function setTimezone($forceTimezone = false) {
		if( $forceTimezone === false ) {
			$timezone	= self::getTimezone();
		} else {
			$timezone		= $forceTimezone;
			self::$timezone	= $forceTimezone;
		}

			// Set default timezone
		date_default_timezone_set($timezone);
	}



	/**
	 * Get active timezone
	 *
	 * @return	String		Timezone string
	 */
	public static function getTimezone() {
		if( is_null(self::$timezone) ) {
			if( self::db()->isConnected() ) {
				$timezone	= self::person()->getTimezone();
			} else {
				$timezone	= false;
			}

			if( $timezone === false ) {
				$timezone = self::$CONFIG['SYSTEM']['timezone'];
			}

			self::$timezone = $timezone;
		}

		return self::$timezone;
	}



	/**
	 * Return database object
	 *
	 * @return	TodoyuDatabase
	 */
	public static function db() {
		if( is_null(self::$db) ) {
			self::$db = TodoyuDatabase::getInstance(self::$CONFIG['DB']);
		}

		return self::$db;
	}



	/**
	 * Return template engine
	 *
	 * @return	Dwoo
	 */
	public static function tmpl() {
		if( is_null(self::$template) ) {
			$config	= TodoyuArray::assure(self::$CONFIG['TEMPLATE']);

				// Create needed directories
			TodoyuFileManager::makeDirDeep($config['compile']);
			TodoyuFileManager::makeDirDeep($config['cache']);

				// Initialize Dwoo
			try {
				self::$template = new Dwoo($config['compile'], $config['cache']);
			} catch(Dwoo_Exception $e) {
				$msg	= 'Can\'t initialize tempalate engine: ' . $e->getMessage();
				Todoyu::log($msg, TodoyuLogger::LEVEL_FATAL);
				die($msg);
			}
		}

		return self::$template;
	}



	/**
	 * Add directory for plugins to dwoo
	 *
	 * @param	String		$directory
	 */
	public static function addDwooPluginDir($directory) {
		$directory	= TodoyuFileManager::pathAbsolute($directory);

		self::$template->getLoader()->addDirectory($directory);
	}



	/**
	 * Save log message (can be stored in multiple systems)
	 *
	 * @param	String		$message		Log message
	 * @param	Integer		$level			Log level (use constants!)
	 * @param	Mixed		$data			Additional data to save with the log message
	 */
	public static function log($message, $level = 0, $data = null) {
		self::logger()->log($message, $level, $data);
	}


	/**
	 * Get logger instance
	 *
	 * @return		TodoyuLogger
	 */
	public static function logger() {
		if( is_null(self::$logger) ) {
			self::$logger = TodoyuLogger::getInstance(self::$CONFIG['LOG_LEVEL']);
		}

		return self::$logger;
	}



	/**
	 * Return current person object
	 *
	 * @return	TodoyuPerson
	 */
	public static function person() {
		if( is_null(self::$person) ) {
			self::$person = TodoyuAuth::getPerson();
		}

		return self::$person;
	}



	/**
	 * Reset person object if a new person is logged in
	 */
	public static function reset() {
		self::$person = TodoyuAuth::getPerson(true);
		self::$locale = null;
	}



	/**
	 * Get locale: if set get from person profile pref, otherwise from system config
	 *
	 * @return	String
	 */
	public static function getLocale() {
		if( is_null(self::$locale) ) {
			self::$locale = self::$CONFIG['SYSTEM']['locale'];

			$cookieLocale	= TodoyuLocaleManager::getCookieLocale();
			$browserLocale 	= TodoyuBrowserInfo::getBrowserLocale();

			if( TodoyuAuth::isLoggedIn() && self::db()->isConnected() ) {
				$personLocale	= self::person()->getLocale();
				if( $personLocale !== false ) {
					self::$locale = $personLocale;
				}
			} elseif( $cookieLocale !== false ) {
				self::$locale	= $cookieLocale;
			} elseif( $browserLocale !== false ) {
				self::$locale = $browserLocale;
			}
		}

			// Check if locale exists
		if( ! TodoyuLocaleManager::hasLocale(self::$locale) ) {
			self::$locale	= TodoyuLocaleManager::getDefaultLocale();
		}

		return self::$locale;
	}



	/**
	 * Set system locale with setlocale() based on the currently selected locale
	 *
	 * @param	String		$locale			Force locale. If not set try to find the correct locale
	 */
	public static function setLocale($locale = false) {
		if( $locale === false ) {
			$locale	= self::getLocale();
		}

			// Set internal locale
		self::$locale = $locale;

			// Set locale for locallang files
		TodoyuLabelManager::setLocale($locale);

			// Set locale for system
		$status	= TodoyuLocaleManager::setSystemLocale($locale);

			// Log if operation fails
		if( $status === false ) {
			self::log('Can\'t set locale "' . $locale . '"', TodoyuLogger::LEVEL_ERROR);
		}
	}



	/**
	 * Get (EXTID value of) current ext area
	 *
	 * @param	String	$area
	 * @return	Integer
	 */
	public static function getArea($area = null) {
		if( is_null($area) ) {
			$area = EXT;
		}

		return TodoyuExtensions::getExtID($area);
	}



	/**
	 * Get area key (string version)
	 *
	 * @return	String
	 */
	public static function getAreaKey() {
		return TodoyuRequest::getArea();
	}



	/**
	 * Add a path to the global include path for autoloading classes
	 *
	 * @param	String		$includePath
	 */
	public static function addIncludePath($includePath) {
		$includePath	= TodoyuFileManager::pathAbsolute($includePath);

		if( ! in_array($includePath, self::$CONFIG['AUTOLOAD']) ) {
			self::$CONFIG['AUTOLOAD'][] = $includePath;
		}
	}



	/**
	 * Autoload classes. Check all configured directories
	 *
	 * @param	String		$className
	 */
	public static function autoloader($className) {
		$classFile = $className . '.class.php';

		foreach(self::$CONFIG['AUTOLOAD'] as $includePath) {
			if( is_file($includePath . DIR_SEP . $classFile) ) {
				include_once($includePath . DIR_SEP . $classFile);
				break;
			}
		}
	}

}

?>