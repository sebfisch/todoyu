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

	// Include mailer library
require_once( PATH_LIB . '/php/phpmailer/class.phpmailer-lite.php' );

/**
 * Manage mail DB logs
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuMailManager {

	/**
	 * @var	String		Default table for database requests
	 */
	const TABLE = 'system_log_email';



	/**
	 * Send email using phpMailerLite
	 *
	 * @param	String	$subject
	 * @param	String	$fromAddress
	 * @param	String	$fromName
	 * @param	String	$toAddress
	 * @param	String	$toName
	 * @param	String	$htmlBody
	 * @param	String	$textBody
	 * @param	String	$baseURL		URL to base HTML paths (e.g. images) on
	 * @param	Boolean	$noReplyTo
	 * @return	Boolean
	 */
	public static function sendMail($subject, $fromAddress, $fromName, $toAddress, $toName, $htmlBody, $textBody, $baseURL, $noReplyTo = false) {
		$mailer	= self::getPHPMailerLite(true);

			// Set subject
		$mailer->Subject	= $subject;

			// Set "from" and "to" email addresses and names
		$mailer->SetFrom($fromAddress, $fromName);
		$mailer->AddAddress($toAddress, $toName);

			// Add message body as HTML and plain text
		$mailer->MsgHTML($htmlBody, $baseURL);
		$mailer->AltBody	= $textBody;

			// Set "replyTo"
		if( ! $noReplyTo ) {
			$mailer->AddReplyTo(Todoyu::person()->getEmail(), Todoyu::person()->getFullName());
		}

		try {
			$sendStatus	= $mailer->Send();
		} catch(phpmailerException $e) {
			Todoyu::log($e->getMessage(), TodoyuLogger::LEVEL_ERROR);
		} catch(Exception $e) {
			Todoyu::log($e->getMessage(), TodoyuLogger::LEVEL_ERROR);
		}

		return $sendStatus;
	}



	/**
	 * Get PHPMailerLite object
	 *
	 * @param	Boolean		$exceptions
	 * @param	String		$mailer
	 * @param	String		$charSet
	 * @return	PHPMailerLite
	 */
	private static function getPHPMailerLite($exceptions = false, $mailer = 'mail', $charSet = 'uft-8') {
		$phpMailerLite	= new PHPMailerLite($exceptions);

			// Config
		$phpMailerLite->Mailer	= $mailer;
		$phpMailerLite->CharSet	= $charSet;

//			// Change mail program
//		if( PHP_OS !== 'Linux' ) {
//				// Windows Server: use 'mail' instead of 'sendmail'
//			$mail->Mailer	= 'mail';
//		}

		return $phpMailerLite;
	}



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
			'id_person_create'	=> personid(),
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