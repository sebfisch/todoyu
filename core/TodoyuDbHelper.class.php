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
	 * @deprecated
	 *
	 * @param	String		$mmTable			Link table
	 * @param	String		$fieldLocal			Locale field name (for the one record linked to the others)
	 * @param	String		$fieldForeign		Foreign field name for the other records
	 * @param	Integer		$idLocalRecord		The linking record
	 * @param	Array		$foreignRecordIDs	The other linked records
	 */
	public static function addMMLinks($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord, array $foreignRecordIDs) {
		$idLocalRecord		= intval($idLocalRecord);
		$foreignRecordIDs	= TodoyuArray::intval($foreignRecordIDs, true, true);

			// Get existing record IDs in link table
		$currentForeignIDs	= self::getForeignIDs($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord);

			// If record not allready linked, add link
		foreach($foreignRecordIDs as $idForeignRecord) {
			if( ! in_array($idForeignRecord, $currentForeignIDs) ) {
				self::addMMLink($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord, $idForeignRecord);
			}
		}
	}



	/**
	 * Get foreign record IDs of a mm table
	 *
	 * @param	String		$mmTable
	 * @param	String		$fieldLocal
	 * @param	String		$fieldForeign
	 * @param	Integer		$idLocalRecord
	 * @return	Array
	 */
	public static function getForeignIDs($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord) {
		$where	= $fieldLocal . ' = ' . intval($idLocalRecord);

		return Todoyu::db()->getColumn($fieldForeign, $mmTable, $where);
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
	public static function saveExtendedMMLinks($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord, array $linksData) {
		$idLocalRecord		= intval($idLocalRecord);
		$foreignRecordIDs	= self::getForeignIDs($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord);

		foreach($linksData as $linkData) {
			if( in_array($linkData[$fieldForeign], $foreignRecordIDs) ) {
				self::updateMMLink($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord, $linkData[$fieldForeign], $linkData);
			} else {
				self::addMMLink($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord, $linkData[$fieldForeign], $linkData);
			}

		}
	}



	/**
	 * Update a mm link with extra data
	 *
	 * @param	String		$mmTable
	 * @param	String		$fieldLocal
	 * @param	String		$fieldForeign
	 * @param	Integer		$idLocalRecord
	 * @param	Integer		$idForeignRecord
	 * @param	Array		$data
	 */
	public static function updateMMLink($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord, $idForeignRecord, array $data) {
		$where	= 	$fieldLocal . ' = ' . $idLocalRecord . ' AND ' .
					$fieldForeign . '	= ' . $idForeignRecord;

		unset($data['id']);
		unset($data[$fieldLocal]);
		unset($data[$fieldForeign]);

		Todoyu::db()->doUpdate($mmTable, $where, $data);
	}



	/**
	 * Add a single MM relation
	 *
	 * @param	String		$mmTable			Link table
	 * @param	String		$localField			Locale field name (for the one record linked to the others)
	 * @param	String		$foreignField		Foreign field name for the other records
	 * @param	Integer		$idLocalRecord
	 * @param	Integer		$idForeignRecord
	 * @return	Integer		ID of new record
	 */
	public static function addMMLink($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord, $idForeignRecord, array $extraData = array()) {
		$data	= array(
			$fieldLocal		=> intval($idLocalRecord),
			$fieldForeign	=> intval($idForeignRecord)
		);

		unset($extraData['id']);
		unset($extraData[$fieldLocal]);
		unset($extraData[$fieldForeign]);

		$data	= array_merge($extraData, $data);

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



	/**
	 * Delete all records which are linked with another record and not in the ignore list
	 * Inactive records will be marked as deleted, but stay in the link table for restore
	 *
	 * @param	String		$mmTable			Link table
	 * @param	String		$recordTable		Record table
	 * @param	Integer		$idLinkRecord		ID of the base record which is linked with the other records
	 * @param	Array		$ignoreRecordIDs	IDs of records which shoudn't get deleted
	 * @param	String		$fieldRecord		Field with the base record ID
	 * @param	String		$fieldLink			Field with the linked record IDs
	 * @return	Integer		Number of delete records
	 */
	public static function deleteOtherMmRecords($mmTable, $recordTable, $idLinkRecord, array $ignoreRecordIDs, $fieldRecord, $fieldLink) {
		$idLinkRecord	= intval($idLinkRecord);
		$ignoreRecordIDs= TodoyuArray::intval($ignoreRecordIDs, true, true);

		$tables		= 	$recordTable . ' r,' .
						$mmTable . ' mm';
		$where		= '	mm.' . $fieldRecord . ' = ' . $idLinkRecord . ' AND
						mm.' . $fieldLink . '	= r.id';

		if( sizeof($ignoreRecordIDs) > 0 ) {
			$where .= ' AND	r.id NOT IN(' . implode(',', $ignoreRecordIDs) . ')';
		}


		$update		= array(
			'r.deleted'	=> 1
		);
		$noQuotes	= array(
			'r.deleted'
		);

		return Todoyu::db()->doUpdate($tables, $where, $update, $noQuotes);
	}



	/**
	 * Delete all links whichs foreign field ID is not in the ignore list
	 * Deletes all inactive links. The row is deleted from the mm table
	 *
	 * @param	String		$mmTable
	 * @param	String		$fieldLocal
	 * @param	String		$fieldForeign
	 * @param	Integer		$idLocalRecord
	 * @param	Array		$ignoreForeignIDs
	 */
	public static function deleteOtherMmLinks($mmTable, $fieldLocal, $fieldForeign, $idLocalRecord, array $ignoreForeignIDs = array()) {
		$ignoreForeignIDs	= TodoyuArray::intval($ignoreForeignIDs, true, true);
		$idForeignRecord	= intval($idForeignRecord);
		$idLocalRecord		= intval($idLocalRecord);

		$where	= $fieldLocal . ' = ' . $idLocalRecord;

			// If a list of IDs to ignore is given, add ignore clause
		if( sizeof($ignoreForeignIDs) > 0 ) {
			$where .= ' AND	' . $fieldForeign . ' NOT IN(' . implode(',', $ignoreForeignIDs) . ')';
		}

		Todoyu::db()->doDelete($mmTable, $where);
	}

}
?>