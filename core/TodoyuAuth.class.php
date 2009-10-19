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
 * Authentification class
 * Get acces to the current user, check rights and handle login
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuAuth {

	/**
	 * Instance of the logged in user
	 *
	 * @var	TodoyuUser
	 */
	private static $user = null;



	/**
	 * Check if current a user is logged in
	 *
	 * @return	Boolean
	 */
	public static function isLoggedIn() {
		return self::getUserID() !== 0;
	}



	/**
	 * Get user object of current user
	 *
	 * @return	User
	 */
	public static function getUser() {
		if( is_null(self::$user) ) {
			if( self::getUserID() !== 0 ) {
				self::$user = TodoyuUserManager::getUser(self::getUserID());
			} else {
				self::$user = new TodoyuUser(0);
			}
		}

		return self::$user;
	}



	/**
	 * Get group IDs of the current user
	 *
	 * @return	Array
	 */
	public static function getGroupIDs() {
		return self::getUser()->getGroupIDs();
	}



	/**
	 * Get user ID of the currently logged in user
	 * 0 means there is no user logged in
	 *
	 * @return	Integer
	 */
	public static function getUserID() {
		return intval(TodoyuSessionManager::get('userid'));
	}



	/**
	 * Register user as logged in
	 *
	 * @param	Integer		$idUser
	 */
	public static function login($idUser) {
			// Log successful login
		Todoyu::log('Login', LOG_LEVEL_NOTICE, $idUser);
			// Generate a new session id for the logged in user
		session_regenerate_id(true);
			// Set current user id
		TodoyuSessionManager::set('userid', intval($idUser) );
			// Reload rights
		TodoyuRightsManager::reloadRights();
	}



	/**
	 * Logout current user
	 *
	 */
	public static function logout() {
			// Clear session
		TodoyuSessionManager::clearSession();

			// Delete relogin cookie
		setcookie($GLOBALS['CONFIG']['AUTH']['loginCookieName'], '', 1);

			// Generate a new session id for the logged out user
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
		return TodoyuUserManager::isValidLogin($username, $passwordHash);
	}



	/**
	 * Check if an action is allowed
	 *
	 * @param	Integer		$extID
	 * @param	Integer		$right
	 * @return	Boolean
	 */
	public static function isAllowed($extID, $right) {
		return TodoyuRightsManager::isAllowed($extID, $right);
	}



	/**
	 * Check if current user is admin
	 *
	 * @return	Boolean
	 */
	public static function isAdmin() {
		return self::getUser()->isAdmin();
	}



	/**
	 * There can be actions of extensions defined, which do not require a login
	 * (I know, this can be a security problem!)
	 * This is for example used to verfiy login data by loginpage (which is an normal action)
	 *
	 * Extensions can add their own actions to the config array $CONFIG['AUTH']['noLoginRequired'][EXTNAME][] = ACTION
	 *
	 * @param	String		$extension
	 * @param	String		$controller
	 * @return	Boolean
	 */
	public static function isNoLoginRequired($extension, $controller) {
		$extension	= strtolower($extension);
		$controller	= strtolower($controller);

			// Check if for this extension an array is defined
		if( is_array($GLOBALS['CONFIG']['AUTH']['noLoginRequired'][$extension]) ) {
			if( in_array($controller, $GLOBALS['CONFIG']['AUTH']['noLoginRequired'][$extension]) ) {
				return true;
			}
		}

		return false;
	}



	/**
	 * Hook to process the "remain login" cookie if not yet logged in
	 * Called by the core->onload hook
	 *
	 * @param	Array		$requestVars
	 * @param	Array		$originalRequestVars
	 * @return	Array
	 */
	public static function cookieLoginHook(array $requestVars, array $originalRequestVars) {
		return $requestVars;
		$cookieName	= $GLOBALS['CONFIG']['AUTH']['loginCookieName'];
		$cookieValue= $_COOKIE[$cookieName];

			// Only make cookie login, if not already done
		if( ! self::isLoggedIn() ) {
			if( ! empty($cookieValue) ) {
					// Decrypt cookie data
				$cookieData	= TodoyuDiv::decrypt($cookieValue);

					// If
				if( is_array($cookieData) ) {
					$userAgendHash	= md5($_SERVER['HTTP_USER_AGENT']);

					if( $cookieData['useragentHash'] === $userAgendHash ) {
						if( self::isValidLogin($cookieData['username'], $cookieData['passhash']) ) {
							$idUser = TodoyuUserManager::getUserIDbyUsername($cookieData['username']);
							TodoyuAuth::login($idUser);
							self::setRemainLoginCookie($idUser);

							TodoyuHeader::reload();
							exit();
						} else {
							Todoyu::log('Cookie login failed (username/password)', LOG_LEVEL_SECURITY);
						}
					} else {
						Todoyu::log('Cookie login failed (useragent)', LOG_LEVEL_SECURITY);
					}
				}
			}
		}

		return $requestVars;
	}



	/**
	 * Override request vars, if user is not logged in
	 *
	 * @param	Array		$requestVars
	 * @param	Array		$originalRequestVars
	 * @return	Array
	 */
	public static function onLoadHook(array $requestVars, array $originalRequestVars) {
		if( ! self::isLoggedIn() && ! self::isNoLoginRequired($requestVars['ext'], $requestVars['ctrl']) )  {
			$requestVars['ext']	= $GLOBALS['CONFIG']['AUTH']['login']['ext'];
			$requestVars['ctrl']= $GLOBALS['CONFIG']['AUTH']['login']['controller'];
		}

		return $requestVars;
	}



	/**
	 * Set encrypted login cookie for direct login
	 *
	 * @param	Integer		$idUser
	 */
	public static function setRemainLoginCookie($idUser) {
		$cookieName	= $GLOBALS['CONFIG']['AUTH']['loginCookieName'];
		$value		= self::generateRemainLoginCode($idUser);
		$expires	= NOW + TodoyuTime::SECONDS_WEEK;

		setcookie($cookieName, $value, $expires, PATH_WEB, null, false, true);
	}



	/**
	 * Generate the encrypted content for the remain login cookie
	 *
	 * @param	Integer		$idUser
	 * @return	String
	 */
	public static function generateRemainLoginCode($idUser) {
		$idUser	= intval($idUser);
		$user	= TodoyuUserManager::getUser($idUser);
		$data	= array(
			'username'		=> $user->getUsername(),
			'passhash'		=> $user->getPassword(),
			'useragentHash'	=> md5($_SERVER['HTTP_USER_AGENT'])
		);

		return TodoyuDiv::encrypt($data);
	}

}


?>