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
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

//require_once( PATH_LIB . '/php/dwoo/Dwoo/IDataProvider.php');

/**
 * Add basic and lot used access functions for internal member vars
 *
 * @package		Todoyu
 * @subpackage	Core
 */
abstract class TodoyuBaseObject implements ArrayAccess, Dwoo_IDataProvider {

	/**
	 * Record data (database row)
	 *
	 * @var	Array
	 */
	protected $data = array();

	/**
	 * Cache for extra data, so they have to be fetched only once
	 *
	 * @var	Array
	 */
	protected $cache = array();




	/**
	 * Initialize object.
	 * Only load data from database, when $idRecord is not zero
	 *
	 * @param	Integer		$idRecordOrData		Record ID
	 * @param	String		$table				Tablename
	 */
	public function __construct($idRecord, $table) {
		$idRecord	= intval($idRecord);

		if( $idRecord > 0 ) {
			$record		= Todoyu::db()->getRecord($table, $idRecord);
			if( $record !== false ) {
				$this->data = $record;
			} else {
				Todoyu::log('Record not found! ID: "' . $idRecord . '", TABLE: "' . $table . '"', TodoyuLogger::LEVEL_ERROR);
			}
		} else {
			//Todoyu::log('Record with ID 0 created (new object or missing data?) Table: ' . $table, TodoyuLogger::LEVEL_NOTICE);
		}
	}



	/**
	 * Fallback for not defined getters. If a getter for a member variable is not defined,
	 * this function will be called and try to get the value from $this->data
	 * This is only for getters, so parameters are ignored
	 *
	 * @param	String		$methodName
	 * @param	Array		$params
	 * @return	String
	 */
	public function __call($methodName, $params) {
		$methodName	= strtolower($methodName);
		$dataKey	= str_replace('get', '', $methodName);

		if( substr($methodName, 0, 3) === 'get' && array_key_exists($dataKey, $this->data) ) {
			return $this->get($dataKey);
		} else {
			Todoyu::log('Data "' . $dataKey . '" not found in ' . get_class($this) . ' (ID:' . $this->data['id'] . ')', TodoyuLogger::LEVEL_NOTICE, $this->data);
			return '';
		}
	}



	/**
	 * Fallback for direct member access.
	 * First it checks for a getter function, if not available try to find the data in $this->data
	 *
	 * @param	String		$memberName
	 * @return 	String
	 */
	public function __get($memberName) {
		$dataKey	= strtolower($memberName);
		$methodName	= 'get' . $memberName;

		if( method_exists($this, $methodName) ) {
			return call_user_func(array($this, $methodName));
		} elseif( array_key_exists($dataKey, $this->data) ) {
			return $this->get($dataKey);
		} else {
			Todoyu::log('Data [' . $dataKey . '] not found in object [' . get_class($this) . ']', TodoyuLogger::LEVEL_NOTICE);
			return '';
		}
	}



	/**
	 * Get record ID
	 *
	 * @return	Integer
	 */
	public function getID() {
		return intval($this->data['id']);
	}



	/**
	 * Get data from internal record storage
	 *
	 * @param	String		$key
	 * @return	Mixed
	 */
	public function get($key) {
		return $this->data[$key];
	}



	/**
	 * Check whether a property is set
	 *
	 * @param	String		$key
	 * @return	Boolean
	 */
	public function has($key) {
		return isset($this->data[$key]);
	}



	/**
	 * Check whether a property is not empty
	 *
	 * @param	String		$key
	 * @return	Boolean
	 */
	public function notEmpty($key) {
		return empty($this->data[$key]) === false;
	}



	/**
	 * Inject data.
	 * Usefull if user initialized without an ID to avoid an extra request
	 *
	 * @param	Array	$data
	 */
	public function injectData(array $data = array()) {
		$this->data = $data;
	}



	/**
	 * Check if current user is creator of the record
	 *
	 * @return	Boolean
	 */
	public function isCurrentPersonCreator() {
		return intval($this->get('id_person_create')) === personid();
	}



	/**
	 * Get data array
	 *
	 * @return	Array
	 */
	public function getObjectData() {
		return $this->data;
	}



	/**
	 * Get user ID of a specific type (create, update, assigned, etc)
	 *
	 * @param	String		$type
	 * @return	Integer
	 */
	public function getPersonID($type) {
		$dataKey = 'id_person_' . strtolower($type);

		if( array_key_exists($dataKey, $this->data) ) {
			return intval($this->data[$dataKey]);
		} else {
			return false;
		}
	}



	/**
	 * Get user array of a specific type (create, update, assigned, etc)
	 *
	 * @param	String		$type
	 * @return	Array
	 */
	public function getPersonData($type) {
		$idPerson = $this->getPersonID($type);

		if( $idPerson !== false ) {
			return TodoyuRecordManager::getRecordData('ext_contact_person', $idPerson);
		} else {
			return false;
		}
	}



	/**
	 * Get user of a specific type (create, update, assigned, etc)
	 *
	 * @param	String		$type
	 * @return	TodoyuPerson
	 */
	public function getPerson($type) {
		$idPerson = $this->getPersonID($type);

		if( $idPerson !== false ) {
			return new TodoyuPerson($idPerson);
		} else {
			return false;
		}
	}



	/**
	 *
	 *
	 * @param	String	$key
	 * @return	Boolean
	 */
	protected function isInCache($key) {
		return isset($this->cache[$key]);
	}



	/**
	 * Get item from cache
	 *
	 * @param	String	$key
	 * @return	Mixed
	 */
	protected function getCacheItem($key) {
		return $this->cache[$key];
	}



	/**
	 * Add item to cache
	 *
	 * @param	String	$key
	 * @param	Mixed	$item
	 */
	protected function addToCache($key, $item) {
		$this->cache[$key] = $item;
	}



	/**
	 * Get data array for template rendering
	 *
	 * @return	Array
	 */

	public function getTemplateData() {
		return $this->data;
	}



	/**
	 * Checks if the record is deleted
	 *
	 * @return	Boolean
	 */
	public function isDeleted()	{
		return intval($this->get('deleted')) === 1;
	}



	### MAGIC FUNCTIONS (DON'T CALL THEM DIRECTLY!) ###


	/**
	 * Called by empty() and isset() on member variables
	 *
	 * @magic
	 * @param	String		$memberName
	 * @return	Boolean
	 */
	public function __isset($memberName) {
		return isset($this->data[$memberName]);
	}



	/**
	 * Array access function to check if an attribute
	 * is set in the internal record storage
	 *
	 * Usage: $obj = new Obj(); isset($obj['id_person'])
	 *
	 * @magic
	 * @param	String		$name
	 * @return	Boolean
	 */
	public function offsetExists($name) {
		return isset($this->data[$name]);
	}



	/**
	 * Array access function to delete an attribute
	 * in the internal record storage
	 *
	 * Usage: $obj = new Obj(); unset($obj['id_person'])
	 *
	 * @magic
	 * @param	String		$name
	 */
	public function offsetUnset($name) {
		unset($this->data[$name]);
	}



	/**
	 * Array access function to set an attribute
	 * in the internal record storage
	 *
	 * Usage: $obj = new Obj(); $obj['id_person'] = 53;
	 *
	 * @magic
	 * @param	String		$name
	 * @param	String		$value
	 */
	public function offsetSet($name, $value) {
		$this->data[$name] = $value;
	}



	/**
	 * Array access function to get an attribute
	 * from the internal record storage
	 *
	 * Usage: $obj = new Obj(); echo $obj['id_person'];
	 *
	 * @magic
	 * @param	String		$name
	 * @return	String
	 */
	public function offsetGet($name) {
		return $this->get($name);
	}



	/**
	 * Alias for getTemplateData to implement Dwoo_IDataProvider
	 *
	 * @return	Array
	 */
	public function getData() {
		return $this->getTemplateData();
	}
}

?>