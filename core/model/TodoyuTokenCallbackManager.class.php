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
 * Manage callbacks of tokens
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuTokenCallbackManager {

	/**
	 * Register a source function to given token type
	 *
	 * @param	Integer		$extID
	 * @param	Integer		$idTokenType
	 * @param	String		$function		Function reference
	 */
	public static function addFunction($extID, $idTokenType, $function) {
		$extID			= intval($extID);
		$idTokenType	= intval($idTokenType);

		Todoyu::$CONFIG['Token'][$extID][$idTokenType][] = array(
			'function'	=> $function
		);
	}



	/**
	 * Get registered callback of given token
	 *
	 * @param	TodoyuToken	$token
	 * @return	String
	 */
	public static function getCallback(TodoyuToken $token) {
		$extID		= $token->getExtID();
		$idTokenType= $token->getTokenType();

		return Todoyu::$CONFIG['Token'][$extID][$idTokenType][0]['function'];
	}



	/**
	 * Get registered callback to type and extID of token
	 *
	 * @return	String
	 */
	public static function getCallbackByTokenID($idToken) {
		$idToken	= intval($idToken);
		$token		= TodoyuTokenManager::getToken($idToken);

		return self::getCallback($token);
	}



	/**
	 * Get rendered content of callback of current token
	 *
	 * @return	String
	 */
	public static function getCallbackResultByHash($hash) {
		$token	= TodoyuTokenManager::getTokenByHash($hash);

		if( ! $token || $token->getID() === 0 ) {
			return 'Invalid Token!';
		}

		$callback	= self::getCallback($token);

		$params		= $token->get('callback_params');
		if( ! empty($params) ) {
			$params	= json_decode($params);
		} else {
			$params	= array();
		}

		$params['idPersonOwner']	= $token->getPersonID('owner');

		return TodoyuFunction::callUserFunctionArray($callback, $params);
	}

}

?>