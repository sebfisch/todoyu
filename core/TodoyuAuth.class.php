<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * Authentification class
 * Get acces to the current person, check rights and handle login
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuAuth {

	/**
	 * Instance of the logged in person
	 *
	 * @var	TodoyuPerson
	 */
	private static $person = null;



	/**
	 * Check if current person is logged in
	 *
	 * @return	Boolean
	 */
	public static function isLoggedIn() {
		return self::getPersonID() !== 0;
	}



	/**
	 * Get person object of current person
	 *
	 * @param	Boolean		$reload		Force to reinit person from current session value
	 * @return	TodoyuPerson
	 */
	public static function getPerson($reload = false) {
		if( is_null(self::$person) || $reload ) {
			if( self::getPersonID() !== 0 ) {
				self::$person = TodoyuPersonManager::getPerson(self::getPersonID());
			} else {
				self::$person = TodoyuPersonManager::getPerson(0);
			}
		}

		return self::$person;
	}



	/**
	 * Get role IDs of the current person
	 *
	 * @return	Array
	 */
	public static function getRoleIDs() {
		return self::getPerson()->getRoleIDs();
	}



	/**
	 * Get ID of the currently logged in person
	 * 0 means there is no person logged in
	 *
	 * @return	Integer
	 */
	public static function getPersonID() {
		return intval(TodoyuSession::get('person'));
	}



	/**
	 * Set ID of currently logged in person
	 *
	 * @param	Integer		$idPerson
	 */
	public static function setPersonID($idPerson) {
		TodoyuSession::set('person', intval($idPerson));
	}



	/**
	 * Register person as logged in
	 *
	 * @param	Integer		$idPerson
	 */
	public static function login($idPerson) {
			// Log successful login
		Todoyu::log('Login person (' . $idPerson . ')', TodoyuLogger::LEVEL_NOTICE, $idPerson);
			// Generate a new session id for the logged in person
		session_regenerate_id(true);
			// Set current person id
		self::setPersonID($idPerson);
			// Reload rights
		TodoyuRightsManager::reloadRights();
			// Set new person in Todoyu object
		Todoyu::reset();
	}



	/**
	 * Logout current person
	 */
	public static function logout() {
		TodoyuHookManager::callHook('core', 'logout');
			// Clear session
		TodoyuSession::clear();

			// Delete relogin cookie
		TodoyuCookieLogin::removeRemainLoginCookie();

			// Generate a new session id for the logged out person
		session_regenerate_id(true);
	}



	/**
	 * Check if $username and $password are a valid login
	 *
	 * @param	String		$username		Username
	 * @param	String		$passwordHash	Password as md5
	 * @return	Boolean
	 */
	public static function isValidLogin($username, $passwordHash) {
		return TodoyuPersonManager::isValidLogin($username, $passwordHash);
	}



	/**
	 * Check if an action is allowed
	 *
	 * @param	Integer		$extKey
	 * @param	Integer		$right
	 * @return	Boolean
	 */
	public static function isAllowed($extKey, $right) {
		return TodoyuRightsManager::isAllowed($extKey, $right);
	}



	/**
	 * Check if current person is admin
	 *
	 * @return	Boolean
	 */
	public static function isAdmin() {
		return self::getPerson()->isAdmin();
	}



	/**
	 * There can be actions of extensions defined, which do not require a login
	 * (I know, this can be a security problem!)
	 * This is for example used to verfiy login data by loginpage (which is an normal action)
	 *
	 * Extensions can add their own actions to the config array Todoyu::$CONFIG['AUTH']['noLoginRequired'][EXTNAME][] = ACTION
	 *
	 * @param	String		$extension
	 * @param	String		$controller
	 * @return	Boolean
	 */
	public static function isNoLoginRequired($extension, $controller) {
		$extension	= strtolower($extension);
		$controller	= strtolower($controller);

			// Check if for this extension an array is defined
		if( is_array(Todoyu::$CONFIG['AUTH']['noLoginRequired'][$extension]) ) {
			if( in_array($controller, Todoyu::$CONFIG['AUTH']['noLoginRequired'][$extension]) ) {
				return true;
			}
		}

		return false;
	}



	/**
	 * Send not logged in message for ajax requests
	 *
	 * @param	Array		$requestVars
	 * @param	Array		$originalRequestVars
	 * @return	Array
	 */
	public static function hookNotLoggedInAjax(array $requestVars, array $originalRequestVars) {
		if( ! self::isLoggedIn() && ! self::isNoLoginRequired($requestVars['ext'], $requestVars['ctrl']) ) {
			if( TodoyuRequest::isAjaxRequest() ) {
				self::sendNotLoggedInHeader();
				echo "NOT LOGGED IN";
				exit();
			}
		}

		return $requestVars;
	}



	/**
	 * Override request vars, if person is not logged in
	 *
	 * @param	Array		$requestVars
	 * @param	Array		$originalRequestVars
	 * @return	Array
	 */
	public static function hookCheckLoginStatus(array $requestVars, array $originalRequestVars) {
		if( ! self::isLoggedIn() && ! self::isNoLoginRequired($requestVars['ext'], $requestVars['ctrl']) ) {
				// On normal request, change controller to login page
			$requestVars['ext']		= Todoyu::$CONFIG['AUTH']['login']['ext'];
			$requestVars['ctrl']	= Todoyu::$CONFIG['AUTH']['login']['controller'];
			$requestVars['action']	= 'default';
		}

		return $requestVars;
	}



	/**
	 * Send header to inform the user that the ajax request failed because of logout
	 */
	private static function sendNotLoggedInHeader() {
		TodoyuHeader::sendTodoyuHeader('notLoggedIn', 1);
	}


}

?>