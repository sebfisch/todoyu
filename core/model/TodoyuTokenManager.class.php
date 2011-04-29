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
 * Token Manager
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuTokenManager {

	const TABLE = 'system_token';

	/**
	 * Token Getter
	 *
	 * @param	Integer		$idToken
	 * @return	TodoyuToken
	 */
	public static function getToken($idToken) {
		return TodoyuRecordManager::getRecord('TodoyuToken', $idToken);
	}



	/**
	 * Get token of given extension and type by owner's person ID
	 *
	 * @param	Integer		$idPersonOwner
	 * @param	Integer		$extID
	 * @param	Integer		$idTokenType
	 * @return	String
	 */
	public static function getTokenByOwner($idPersonOwner, $extID, $idTokenType) {

	}



	/**
	 * Generate new token hash
	 *
	 * @param	Integer		$idTokenType
	 * @param	Integer		$idPersonOwner
	 * @param	Integer		$extID
	 * @param	Boolean		$storeInSession
	 * @return	String							Hash
	 */
	public static function generateHash($idTokenType, $extID, $idPersonOwner = 0, $storeInSession = false) {
		$idTokenType	= intval($idTokenType);
		$extID			= intval($extID);
		$idPersonOwner	= personid($idPersonOwner);
		$idPersonCreate	= personid();

		$prefix	= $extID . $idTokenType . $idPersonCreate . $idPersonOwner;
		$salt	= uniqid($prefix, microtime(true));
		$hash	= md5($salt);

		if( $storeInSession ) {
			self::storeHashInSession($idTokenType, $extID, $hash);
		}

		return $hash;
	}



	/**
	 * Store hash of given extension and type in session
	 *
	 * @param	Integer		$idTokenType
	 * @param	Integer		$extID
	 * @param	String		$hash
	 * @param	Integer		$idPersonOwner
	 */
	public static function storeHashInSession($idTokenType, $extID, $hash, $idPersonOwner = 0) {
		$idTokenType	= intval($idTokenType);
		$extID			= intval($extID);
		$idPersonOwner	= personid($idPersonOwner);

		$valuePath		= 'token/' . $extID . '/' . $idTokenType . '/' . $idPersonOwner;

		TodoyuSession::set($valuePath, $hash);
	}

}

?>