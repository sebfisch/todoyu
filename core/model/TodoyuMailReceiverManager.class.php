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
	 * @param	String		$typeKey
	 * @param	String		$callbackObject
	 */
	public static function registerType($typeKey, $callbackObject) {
		Todoyu::$CONFIG['MailReceiver'][$typeKey] = $callbackObject;
	}



	/**
	 * Get email receiver type configuration
	 *
	 * @param	String		$typeKey
	 * @return	String						Object callback
	 */
	public static function getTypeConfig($typeKey) {
		return Todoyu::$CONFIG['MailReceiver'][$typeKey];
	}



	/**
	 * @param	String				$itemID		Can be numeric (= person ID) or prefixed with a registered type key
	 * @return	TodoyuMailReceiver
	 */
	public static function getMailReceiverObject($itemID) {
		$itemID	= trim($itemID);

		if( is_numeric($itemID) ) {
				// Default type: person
			$typeKey	= 'contactperson';
			$idRecord	= $itemID;
		} else {
				// ID is prefixed with registered key of receiver type
			list($typeKey, $idRecord)	= explode(':', $itemID);
		}

		$objectClass	= self::getTypeConfig($typeKey);
		if( !class_exists($objectClass)) {
			TodoyuLogger::logError('Undefined email Receiver type key: "' . $typeKey . '"', $itemID);
			return false;
		}

		return new $objectClass($idRecord);
	}



	/**
	 * Check whether the given type key is registered
	 *
	 * @param	String		$typeKey
	 * @return	Boolean
	 */
	public static function isTypeRegistered($typeKey) {
		return array_key_exists($typeKey, Todoyu::$CONFIG['MailReceiver']);
	}

}

?>