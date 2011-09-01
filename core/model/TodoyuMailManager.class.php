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

/**
 * Manage mail DB logs
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuMailManager {

	/**
	 * Default table for database requests
	 *
	 * @var	String
	 */
	const TABLE = 'system_log_email';



	/**
	 * Save log record about persons the given mail has been sent to
	 *
	 * @param	Integer		$extID			EXTID of extension the record belongs to
	 * @param	Integer		$type			Type of record (comment, event, etc.) the email refers to
	 * @param	Integer		$idRecord		ID of record the email refers to
	 * @param	Array		$personIDs		Persons the comment has been sent to
	 */
	public static function saveMailsSent($extID, $type, $idRecord, array $personIDs = array() ) {
		$extID		= intval($extID);
		$type		= intval($type);
		$idRecord	= intval($idRecord);
		$personIDs	= TodoyuArray::intval($personIDs);

		foreach($personIDs as $idPerson) {
			self::addMailSent($extID, $type, $idRecord, $idPerson);
		}
	}



	/**
	 * Log sent email of given type to given person
	 *
	 * @param	Integer		$extID			EXTID of extension the record belongs to
	 * @param	Integer		$type			Type of record (comment, event, etc.) the email refers to
	 * @param	Integer		$idRecord		ID of record the email refers to
	 * @param	Integer		$idPerson
	 */
	public static function addMailSent($extID, $type, $idRecord, $idPerson) {
		$extID		= intval($extID);
		$type		= intval($type);
		$idRecord	= intval($idRecord);
		$idPerson	= intval($idPerson);

		$data	= array(
			'date_create'		=> NOW,
			'id_person_create'	=> Todoyu::personid(),
			'ext'				=> $extID,
			'record_type'		=> $type,
			'id_record'			=> $idRecord,
			'id_person_email'	=> $idPerson,
		);

		TodoyuRecordManager::addRecord(self::TABLE, $data);
	}



	/**
	 * Get persons the given comment has been sent to by email
	 *
	 * @param	Integer		$extID			EXTID of extension the record belongs to
	 * @param	Integer		$type			Type of record (comment, event, etc.) the email refers to
	 * @param	Integer		$idRecord		ID of record the email refers to
	 * @return	Array
	 */
	public static function getEmailPersons($extID, $type, $idRecord) {
		$extID		= intval($extID);
		$type		= intval($type);
		$idRecord	= intval($idRecord);

		$fields	= '	p.id,
					p.username,
					p.email,
					p.firstname,
					p.lastname,
					e.date_create';
		$tables	= '		ext_contact_person p,' .
					'	system_log_email e';
		$where	= '		e.ext 				= ' . $extID .
				'	AND	e.record_type		= \'' . $type . '\' ' .
				'	AND	e.id_record 		= ' . $idRecord .
				  ' AND	e.id_person_email	= p.id
					AND	p.deleted			= 0';
		$group	= '	p.id';
		$order	= '	p.lastname,
					p.firstname';
		$indexField	= 'id';

		return Todoyu::db()->getArray($fields, $tables, $where, $group, $order, '', $indexField);
	}

}

?>