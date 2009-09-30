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
 * Access the todoyu session
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuSessionManager {


	/**
	 * Get key where session is stored
	 *
	 * @return	String
	 */
	private static function key() {
		return $GLOBALS['CONFIG']['SESSION']['key'];
	}



	/**
	 * Combined setter and getter for the session array.
	 * If $data is provided, value of the key will be set/overridden by $data,
	 * else the existing data will be returned	 *
	* @param	String		$name		Point seperated path to entry (in session)
	 * @param	Mixed		$data		Data to store
	 * @return	Mixed		Or null if set-mode
	 */
	private static function getSet($name, $data = null, $removeKey = false) {
			// Split name
		$nameParts	= 	explode('.', $name);
			// Get reference to todoyu session
		$node		=& 	$_SESSION[self::key()];
			// Pop the key of the value
		$keyname	= array_pop($nameParts);

			// Make sure the node is always an array
		if( ! is_array($node) ) {
			$node = array();
		}

			// Digg down to the requested entry
		foreach( $nameParts as $namePart ) {
			if( ! array_key_exists($namePart, $node) ) {
				$node[$namePart] = array();
			}
			$node =& $node[$namePart];
		}

			// Remove key and stop processing
		if( $removeKey ) {
			unset($node[$keyname]);
			return true;
		}

			// Return data for get-mode or store data in set-mode
		if( is_null($data) ) {
			return $node[$keyname];
		} else {
			$node[$keyname] = $data;
			return true;
		}
	}



	/**
	 * Get data from the session	 *
	* @param	String		$name
	 * @return	Mixed
	 */
	public static function get($name) {
		return self::getSet($name);
	}



	/**
	 * Save data to the session	 *
	* @param	String		$name
	 * @param	Mixed		$data
	 */
	public static function set($name, $data) {
		self::getSet($name, $data);
	}



	/**
	 * Check if under this name is data stored	 *
	* @param	String		$name
	 * @return	Boolean
	 */
	public static function isIn($name) {
		return self::get($name) !== null;
	}



	/**
	 * Remove data from the session	 *
	* @param	String		$name
	 */
	public static function remove($name) {
		self::getSet($name, null, true);
	}



	/**
	 * Clear all todoyu session data
	 *
	 */
	public static function clearSession() {
		$_SESSION[self::key()] = null;
	}

}

?>