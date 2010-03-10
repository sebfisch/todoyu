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
 * Cache for various data
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuCache {

	private static $enabled = true;

	/**
	 * Cache
	 *
	 * @var	Array
	 */
	public static $cache = array();



	/**
	 * Get element from cache
	 *
	 * @param	String		$key		Unique key
	 * @return	Mixed		Whatever is stored in the cache under the key
	 */
	public static function get($key) {
		return self::$enabled ? self::$cache[$key] : null;
	}



	/**
	 * Store data in cache identified by key
	 *
	 * @param	String		$key
	 * @param	Mixed		$data
	 */
	public static function set($key, $data) {
		if( self::$enabled !== false ) {
			self::$cache[$key] = $data;
		}
	}



	/**
	 * Remove element from cache
	 *
	 * @param	String		$key
	 */
	public static function remove($key) {
		unset(self::$cache[$key]);
	}



	/**
	 * Check if something is stored under $key
	 *
	 * @param	String		$key
	 * @return	Boolean
	 */
	public static function isIn($key) {
		return self::$enabled ? array_key_exists($key, self::$cache) : false;
	}



	/**
	 * Disable caching
	 *
	 */
	public static function disable() {
		self::$enabled = false;
	}



	/**
	 * Enable caching
	 *
	 */
	public static function enable() {
		self::$enabled = true;
	}



	/**
	 * Flush the cache
	 */
	public static function flush() {
		self::$cache = array();
	}

}

?>