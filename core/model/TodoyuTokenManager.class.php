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
	 * Generate new token hash
	 *
	 * @param	Integer		$idTokenType
	 * @param	Integer		$idPersonOwner
	 * @param	Boolean		$storeInSession
	 * @return	String						Hash
	 */
	public static function generateHash($idTokenType, $idPersonOwner = 0, $storeInSession = false) {
		$idTokenType	= intval($idTokenType);
		$idPersonOwner	= personid($idPersonOwner);
		$idPersonCreate	= personid();

		$prefix	= $idTokenType . $idPersonCreate . $idPersonOwner;
		$salt	= uniqid($prefix, microtime(true));
		$hash	= md5($salt);

		if( $storeInSession ) {
			self::storeHashInSession($idTokenType, $hash);
		}

		return $hash;
	}



	/**
	 * Store hash in session
	 *
	 * @param	Integer		$idTokenType
	 * @param	String		$hash
	 * @param	Integer		$idPersonOwner
	 */
	public static function storeHashInSession($idTokenType, $hash, $idPersonOwner = 0) {
		$idTokenType	= intval($idTokenType);
		$idPersonOwner	= personid($idPersonOwner);

		$tokenTypeKey	= self::getTokenTypeKey($idTokenType);
		$valuePath		= 'token/' . $tokenTypeKey . '/' . $idPersonOwner;

		TodoyuSession::set($valuePath, $hash);
	}



	/**
	 * Get key to given token type ID
	 *
	 * @param	Integer		$idTokenType
	 * @return	String
	 */
	public static function getTokenTypeKey($idTokenType) {
		//@todo	implement for calendar and generally configurable/parseable

		return 'calendar/personal';
	}

}

?>