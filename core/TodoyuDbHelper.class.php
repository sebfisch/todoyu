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
 * Various database helper functions
 * Usefull database operations used in several extensions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuDbHelper {

	/**
	 * Save MM relations from 1 record to n others
	 *
	 * @param	String		$mmTable			Link table
	 * @param	String		$localField			Locale field name (for the one record linked to the others)
	 * @param	String		$foreignField		Foreign field name for the other records
	 * @param	Integer		$idRecord			The linking record
	 * @param	Array		$foreignRecordIDs	The other linked records
	 * @param	Boolean		$removeCurrent		Remove all current links of the record
	 */
	public static function saveMMrelations($mmTable, $localField, $foreignField, $idRecord, array $foreignRecordIDs, $removeCurrent = true) {
		$idRecord			= intval($idRecord);
		$foreignRecordIDs	= TodoyuArray::intval($foreignRecordIDs, true, true);

		if( $removeCurrent ) {
			self::removeMMrelations($mmTable, $localField, $idRecord);
		}

		foreach($foreignRecordIDs as $idForeignRecord) {
			self::addMMrelation($mmTable, $localField, $foreignField, $idRecord, $idForeignRecord);
		}
	}



	/**
	 * Save MM relation with extended (more than the commonly two) data columns
	 *
	 * @param	String		$mmTable
	 * @param	String		$localField
	 * @param	String		$foreignField
	 * @param	Integer		$idLocalRecord
	 * @param	Integer		$idForeignRecord
	 * @param	Array		$data
	 * @return	Integer		New ID of the record
	 */
	public static function saveExtendedMMrelation($mmTable, $localField, $foreignField, $idLocalRecord, $idForeignRecord, array $data) {
		$idLocalRecord	= intval($idLocalRecord);
		$idForeignRecord= intval($idLocalRecord);

		self::removeMMrelation($mmTable, $localField, $foreignField, $idLocalRecord, $idForeignRecord);

		return Todoyu::db()->addRecord($mmTable, $data);
	}



	/**
	 * Add a single MM relation
	 *
	 * @param	String		$mmTable			Link table
	 * @param	String		$localField			Locale field name (for the one record linked to the others)
	 * @param	String		$foreignField		Foreign field name for the other records
	 * @param	Integer		$idLocalRecord
	 * @param	Integer		$idForeignRecord
	 * @return	Integer		id of new record
	 */
	public static function addMMrelation($mmTable, $localField, $foreignField, $idLocalRecord, $idForeignRecord) {
		$data	= array(
			$localField		=> intval($idLocalRecord),
			$foreignField	=> intval($idForeignRecord)
		);

		return Todoyu::db()->addRecord($mmTable, $data);
	}



	/**
	 * Delete given MM relation
	 *
	 * @param	String	$mmTable
	 * @param	String	$localField
	 * @param	String	$foreignField
	 * @param	Integer	$idLocalRecord
	 * @param	Integer	$idForeignRecord
	 * @return	Integer		Num affected (deleted) rows
	 */
	public static function removeMMrelation($mmTable, $localField, $foreignField, $idLocalRecord, $idForeignRecord) {
		$idLocalRecord	= intval($idLocalRecord);
		$idForeignRecord= intval($idForeignRecord);

		$where	= 	Todoyu::db()->backtick($localField) . ' = ' . $idLocalRecord . ' AND ' .
					Todoyu::db()->backtick($foreignField) . ' = ' . $idForeignRecord;
		$limit	= 1;

		return Todoyu::db()->doDelete($mmTable, $where, $limit);
	}



	/**
	 * Remove given MM relation records
	 *
	 * @param	String		$mmTable
	 * @param	String		$field
	 * @param	Integer		$idRecord
	 * @return	Integer		Num affected (deleted) rows
	 */
	public static function removeMMrelations($mmTable, $field, $idRecord) {
		$idRecord	= intval($idRecord);
		$where		= Todoyu::db()->backtick($field) . ' = ' . $idRecord;

		return Todoyu::db()->doDelete($mmTable, $where);
	}

}
?>