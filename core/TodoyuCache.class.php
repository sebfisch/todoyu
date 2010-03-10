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



	/**
	 * Get a record object of a specific class
	 * If the object is not in the cache yet, create it
	 *
	 * @param	String		$class		Classname
	 * @param	Integer		$idRecord	ID of the record in the database
	 * @return	BaseObject		Object of type $class
	 */
	public static function getRecord($className, $idRecord) {
		$idRecord	= intval($idRecord);
		$idCache	= self::makeClassKey($className, $idRecord);

		if( TodoyuCache::isIn($idCache) ) {
			$object = TodoyuCache::get($idCache);
		} else {
			$object = new $className($idRecord);
			TodoyuCache::set($idCache, $object);
		}

		return $object;
	}



	/**
	 * Remove a record from cache
	 *
	 * @param	String		$className
	 * @param	Integer		$idRecord
	 */
	public static function removeRecord($className, $idRecord) {
		$idRecord	= intval($idRecord);
		$idCache	= self::makeClassKey($className, $idRecord);

		self::remove($idCache);
	}



	/**
	 * Add a record to cache
	 * The record has to be of the type TodoyuBaseObject (or extended)
	 *
	 * @param	TodoyuBaselObject 		$record
	 */
	public static function addRecord(TodoyuBaseObject $record) {
		$className	= get_class($record);
		$idRecord	= $record->getID();

		$idCache	= self::makeClassKey($className, $idRecord);

		TodoyuCache::set($idCache, $record);
	}



	/**
	 * Remove a record query from the cache. This is necessary to force
	 * a new created object to load the data again from the database
	 *
	 * @param	String		$table
	 * @param	 $idRecord
	 */
	public static function removeRecordQuery($table, $idRecord) {
		$idRecord	= intval($idRecord);
		$idCache	= self::makeRecordQueryKey($table, $idRecord);

		self::remove($idCache);
	}



	/**
	 * Make a cache key for a record class based on the classname and the record ID
	 *
	 * @param	String		$className
	 * @param	Integer		$idRecord
	 * @return	String		Cache key
	 */
	public static function makeClassKey($className, $idRecord) {
		return $className . ':' . intval($idRecord);
	}



	/**
	 * Make a cache key for a record query based on the table and the record ID
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 * @return	String
	 */
	public static function makeRecordQueryKey($table, $idRecord) {
		return $table . ':' . intval($idRecord);
	}

}

?>