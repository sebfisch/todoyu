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
 * Manage user preferences (current application status)
 * and store them in the database
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuPreferenceManager {

	/**
	 * Working table
	 */
	const TABLE = 'system_preference';



	/**
	 * Save preference. Unique replaces an existing preference
	 *
	 * @param	Integer		$extID
	 * @param	Integer		$preference
	 * @param	String		$value
	 * @param	Integer		$idItem
	 * @param	Boolean		$unique
	 * @param	Integer		$idArea
	 * @param	Integer		$idPerson
	 */
	public static function savePreference($extID, $preference, $value, $idItem = 0, $unique = false, $idArea = 0, $idPerson = 0) {
		$extID		= intval($extID);
		$preference	= strtolower($preference);
		$idItem		= intval($idItem);
		$idArea		= intval($idArea);
		$idPerson		= personid($idPerson);

			// Delete existing preferences
		if( $unique ) {
			self::deletePreference($extID, $preference, null, $idItem, $idArea, $idPerson);
		} else {
			self::deletePreference($extID, $preference, $value, $idItem, $idArea, $idPerson);
		}

		$table	= self::TABLE;
		$data	= array('id_person'	=> $idPerson,
						'ext'		=> $extID,
						'area'		=> $idArea,
						'preference'=> $preference,
						'item'		=> $idItem,
						'value'		=> $value);

		Todoyu::db()->doInsert($table, $data);
	}



	/**
	 * Delete preference(s) of a preference
	 *
	 * @param	Integer		$extID			Extension ID
	 * @param	Integer		$preference		Preference preference
	 * @param	String		$value
	 * @param	Integer		$idItem
	 * @param	Integer		$idArea
	 * @param	Integer		$idPerson
	 * @return	Boolean
	 */
	public static function deletePreference($extID, $preference, $value = null, $idItem = 0, $idArea = 0, $idPerson = 0) {
		$extID		= intval($extID);
		$preference	= strtolower($preference);
		$idPerson	= personid($idPerson);
		$idItem		= intval($idItem);
		$idArea		= intval($idArea);

		$table	= self::TABLE;
		$where	= '		id_person	= ' . $idPerson .
				  ' AND	ext 		= ' . $extID .
				  ' AND	area 		= ' . $idArea .
				  ' AND	preference	= ' . Todoyu::db()->quote($preference, true) .
				  ' AND	item		= ' . $idItem;;

		if( ! is_null($value) ) {
			$where .= ' AND value = ' . Todoyu::db()->quote($value, true);
		}

		return Todoyu::db()->doDelete($table, $where);
	}



	/**
	 * Get the value of a single (unique) preference
	 *
	 * @param	Integer		$extID
	 * @param	String		$preference
	 * @param	Integer		$idItem
	 * @param	Integer		$idArea
	 * @param	Boolean		$unserialize
	 * @param	Integer		$idPerson
	 * @return	String
	 */
	public static function getPreference($extID, $preference, $idItem = 0, $idArea = 0, $unserialize = false, $idPerson = 0) {
		$extID		= intval($extID);
		$preference	= strtolower($preference);
		$idItem		= intval($idItem);
		$idArea		= intval($idArea);
		$idPerson		= personid($idPerson);

			// Don't check database if the user ID is 0
		if( $idPerson === 0 ) {
			return false;
		}

		$field	= 'value';
		$table	= self::TABLE;
		$where	= '		id_person	= ' . $idPerson .
				  ' AND	ext 		= ' . $extID .
				  ' AND	area		= ' . $idArea .
				  ' AND	preference	= ' . Todoyu::db()->quote($preference, true) .
				  ' AND	item		= ' . $idItem;

		$value	= Todoyu::db()->getFieldValue($field, $table, $where);

		if( $unserialize && $value !== false ) {
			$value	= unserialize($value);
		}

		return $value;
	}



	/**
	 * Get all preference values of a multiple values preference
	 * (A preference can have multiple entries)
	 *
	 * @param	Integer		$extID
	 * @param	String		$preference
	 * @param	Integer		$idItem
	 * @param	Integer		$idArea
	 * @param	Integer		$idPerson
	 * @return	Array
	 */
	public static function getPreferences($extID, $preference, $idItem = 0, $idArea = 0, $idPerson = 0) {
		$extID		= intval($extID);
		$preference	= strtolower($preference);
		$idPerson		= personid($idPerson);
		$idItem		= intval($idItem);
		$idArea		= intval($idArea);

		$field	= 'value';
		$table	= self::TABLE;
		$where	= '		id_person	= ' . $idPerson .
				  ' AND	ext 		= ' . $extID .
				  ' AND	area		= ' . $idArea .
				  ' AND	preference	= ' . Todoyu::db()->quote($preference, true);

		if( $idItem !== 0 ) {
			$where .= ' AND item = ' . $idItem;
		}

		return Todoyu::db()->getColumn($field, $table, $where);
	}



	/**
	 * Delete all preferences of a user
	 *
	 * @param	Integer		$idPerson
	 */
	public static function deleteUserPreferences($idPerson = 0) {
		$idPerson	= personid($idPerson);

		$table	= self::TABLE;
		$where	= 'id_person	= ' . $idPerson;

		Todoyu::db()->doDelete($table, $where);
	}



	/**
	 * Check if preference is set
	 * Without $value, it checks if a preference is stored, else it checks if
	 * a preference with exactly this value is stored
	 *
	 * @param	Integer		$extID			Extension ID
	 * @param	String		$preference		Preference name
	 * @param	Integer		$idItem			ID of the item
	 * @param	String		$value			Stored value
	 * @param	Integer		$idArea			ID of the area
	 * @param	Integer		$idPerson			ID of the user
	 * @return	Boolean
	 */
	public static function isPreferenceSet($extID, $preference, $idItem = 0, $value = null, $idArea = 0, $idPerson = 0) {
		$extID		= intval($extID);
		$preference	= strtolower($preference);
		$idPerson		= personid($idPerson);
		$idArea		= intval($idArea);

		$field	= 'id_person';
		$table	= self::TABLE;
		$where	= '		id_person	= ' . $idPerson .
				  ' AND	ext 		= ' . $extID .
				  ' AND	area 		= ' . $idArea .
				  ' AND	preference	= ' . Todoyu::db()->quote($preference, true);

		if( ! is_null($value) ) {
			$where .= ' AND value = ' . Todoyu::db()->quote($value, true);
		}

		return Todoyu::db()->hasResult($field, $table, $where);
	}



	/**
	 * Get last requested extension for a page request (or default, if none is stored)
	 *
	 * @return	String
	 */
	public static function getLastExt() {
		$pref = self::getPreference(0, 'ext');

		if( $pref === false ) {
			$pref = Todoyu::$CONFIG['FE']['DEFAULT']['ext'];
		}

		return $pref;
	}



	/**
	 * Save extension for the page request
	 *
	 * @param	String		$ext
	 */
	public static function setLastExt($ext) {
		self::savePreference(0, 'ext', $ext, true);
	}



	/**
	 * Save controller for the page request
	 *
	 * @param	String		$controller
	 */
	public static function setLastController($controller) {
		self::savePreference(0, 'controller', $controller, true);
	}

}

?>