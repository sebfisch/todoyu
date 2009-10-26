<?php

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

		Todoyu::db()->deleteRecord($table, $idRecord);
	}


	public static function isRecord($table, $idRecord) {
		$idRecord	= intval($idRecord);

	}

}


?>