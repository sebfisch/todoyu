<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * Manager for email receiver types
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuMailReceiverManager {

	/**
	 * Register an email receiver type
	 *
	 * @param	String		$type
	 * @param	String		$callbackObject
	 */
	public static function registerType($type, $callbackObject) {
		Todoyu::$CONFIG['MailReceiver'][$type] = $callbackObject;
	}



	/**
	 * Get email receiver type configuration
	 *
	 * @param	String		$type
	 * @return	String					Object callback
	 */
	public static function getTypeConfig($type) {
		return Todoyu::$CONFIG['MailReceiver'][$type];
	}



	/**
	 * @param	String				$receiverTuple		Tuple: 'type:ID', e.g. 'contactperson:232' or just ID, which sets default type: 'contactperson'
	 * @return	TodoyuMailReceiver
	 */
	public static function getMailReceiverObject($receiverTuple) {
		$receiverTuple	= trim($receiverTuple);

		if( is_numeric($receiverTuple) ) {
				// Default type: person
			$type		= 'contactperson';
			$idRecord	= $receiverTuple;
		} else {
				// ID is prefixed with registered key of receiver type
			list($type, $idRecord)	= explode(':', $receiverTuple);
		}

		$objectClass	= self::getTypeConfig($type);
		if( !class_exists($objectClass)) {
			TodoyuLogger::logError('Undefined email Receiver type key: "' . $type . '"', $receiverTuple);
			return false;
		}

		return new $objectClass($idRecord);
	}



	/**
	 * Check whether the given type key is registered
	 *
	 * @param	String		$type
	 * @return	Boolean
	 */
	public static function isTypeRegistered($type) {
		return array_key_exists($type, Todoyu::$CONFIG['MailReceiver']);
	}

}

?>