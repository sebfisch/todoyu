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
 * Wrapper for request inputs
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuRequest {


	/**
	 * Get parameter from request. POST data is prefered if available
	 *
	 * @param	String		$name			name of the variable
	 * @param	Boolean		$intval			Apply intval() on the value
	 * @return	Mixed
	 */
	public static function getParam($name, $intval = false) {
		$value	= isset($_POST[$name]) ? $_POST[$name] : $_GET[$name];

		if( $intval ) {
			$value = intval($value);
		}

			// Strip slashes on string values
		if( is_string($value) ) {
			$value = stripslashes($value);
		}

			// Strip slashes on array values
		if( is_array($value) ) {
			$value = TodoyuDiv::arrayStripslashes($value);
		}

		return $value;
	}



	/**
	 * Get all request data. POST overrides GET
	 *
	 * @return	Array
	 */
	public static function getAll() {
		$get	= TodoyuDiv::arrayStripslashes($_GET);
		$post	= TodoyuDiv::arrayStripslashes($_POST);

		return array_merge($get, $post);
	}



	/**
	 * Get request header data
	 *
	 * @param	String		$name
	 * @return	String
	 */
	public static function getHeader($name) {
		$name	= 'HTTP_' . strtoupper(str_replace('-', '_', $name));

		return $_SERVER[$name];
	}



	/**
	 * Get request method
	 *
	 * @return	String
	 */
	public static function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}



	/**
	 * Check if request is a POST request
	 *
	 * @return	Boolean
	 */
	public static function isPostRequest() {
		return self::getMethod() === 'POST';
	}



	/**
	 * Get currently requested URL
	 *
	 * @return	String
	 */
	public static function getRequestUrl() {
		return $_SERVER['REQUEST_URI'];
	}



	/**
	 * Get requested extension
	 *
	 * @return	String
	 */
	public static function getExt() {
		return self::getParam('ext');
	}



	/**
	 * Get requested action
	 *
	 * @return	String
	 */
	public static function getController() {
		return self::getParam('controller');
	}



	/**
	 * Get command if set
	 *
	 * @return	String
	 */
	public static function getCommand() {
		return self::getParam('cmd');
	}



	/**
	 * Get area of current request
	 *
	 * @return	String
	 */
	public static function getArea() {
		$area	= self::getParam('area');
		
		if( is_null($area) ) {
			$area = self::getParam('ext');
		}
		
		return $area;
	}



	/**
	 * Get area ID of current request
	 *
	 * @return	Integer
	 */
	public static function getAreaID() {
		return TodoyuExtensions::getExtID(self::getArea());
	}



	/**
	 * Check header if this is an ajax requet
	 *
	 * @return	Boolean
	 */
	public static function isAjaxRequest() {
		return $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}
	
	
	
	/**
	 * Get the four basic request vars which are always neccessary
	 *
	 * @return	Array		[ext,ctrl,cmd,area]
	 */
	public static function getBasicRequestVars() {
		return array(
			'ext'	=> self::getExt(),
			'ctrl'	=> self::getController(),
			'cmd'	=> self::getCommand(),
			'area'	=> self::getAreaID()
		);
	}
	
	
	
	/**
	 * Get current valid request vars
	 * The basic request vars (ext,controller,cmd,area) will be processed by
	 * the core/onload hooks. These hooks can modifiy the request vars (for login or what ever)
	 *
	 * @return	Array
	 */
	public static function getCurrentRequestVars() {
		$requestVars	= self::getBasicRequestVars();
		$requestVars	= TodoyuHookManager::callHookDataModifier('core', 'onload', $requestVars, array($requestVars));
		
		return $requestVars;
	}
	
	
	
	/**
	 * Set the default request vars if they are not defined in the request
	 * This is the first hook which processes the request vars
	 *
	 * @param	Array		$requestVars				Current request vars (may have been modified)
	 * @param	Array		$originalRequestVars		Originaly provided request vars
	 * @return	Array
	 */
	public static function setDefaultRequestVarsHook(array $requestVars, array $originalRequestVars) {
			// Check ext for a valid string and set defaults if needed
		if( empty($requestVars['ext']) ) {
			$ext = false;
			
			if( TodoyuAuth::isLoggedIn() ) {
				$ext	= TodoyuPreferenceManager::getLastExt();
			}
			
			if( $ext === false ) {
				$ext = $GLOBALS['CONFIG']['FE']['DEFAULT']['ext'];
			}
			
			$requestVars['ext'] = $ext;
		}
		
			// Check controller
		if( empty($requestVars['ctrl']) ) {
			$requestVars['ctrl'] = $GLOBALS['CONFIG']['FE']['DEFAULT']['controller'];
		}
		
			// Check command
		if( empty($requestVars['cmd']) ) {
			$requestVars['cmd']	= 'default';
		}
		
		return $requestVars;
	}

}

?>