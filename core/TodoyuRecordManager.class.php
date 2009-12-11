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
 * Records manager
 * Helper functions to handle database records and prevent double code in all the manager classes
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuRecordManager {

	/**
	 * Get a record object
	 *
	 * @param	String		$className
	 * @param	Integer		$idRecord
	 * @return	BaseObject
	 */
	public static function getRecord($className, $idRecord) {
		$idRecord	= intval($idRecord);

		if( class_exists($className, true) ) {
			return TodoyuCache::getRecord($className, $idRecord);
		} else {
			Todoyu::log('Record class not found: ' . $className, LOG_LEVEL_ERROR);
			return false;
		}
	}



	/**
	 * Get record data as array
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 * @return	Array
	 */
	public static function getRecordData($table, $idRecord) {
		$idRecord	= intval($idRecord);

		return Todoyu::db()->getRecord($table, $idRecord);
	}



	/**
	 * Add a record to database
	 * Set date_create and id_user_create
	 *
	 * @param	String		$table
	 * @param	Array		$data
	 * @return	Integer
	 */
	public static function addRecord($table, array $data) {
		unset($data['id']);

		$data['date_create']	= NOW;
		$data['date_update']	= NOW;
		$data['id_user_create']	= userid();

		return Todoyu::db()->addRecord($table, $data);
	}



	/**
	 * Update a record in the database
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 * @param	Array		$data
	 * @return	Bool
	 */
	public static function updateRecord($table, $idRecord, array $data) {
		$idRecord	= intval($idRecord);
		unset($data['id']);

		$data['date_update'] = NOW;

		return Todoyu::db()->updateRecord($table, $idRecord, $data);
	}



	/**
	 * Delete a record (set deleted flag)
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 */
	public static function deleteRecord($table, $idRecord) {
		$idRecord	= intval($idRecord);
		$data		= array(
			'deleted'	=> 1
		);

		self::updateRecord($table, $idRecord, $data);
	}



	/**
	 * Check if a record exists
	 *
	 * @param	String		$table
	 * @param	Integer		$idRecord
	 * @return	Bool
	 */
	public static function isRecord($table, $idRecord) {
		$idRecord	= intval($idRecord);

		return Todoyu::db()->isRecord($table, $idRecord);
	}

}


?>