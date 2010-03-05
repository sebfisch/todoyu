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
 * Manage user access rights
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuRightsManager {

	/**
	 * Default working table
	 *
	 */
	const TABLE = 'system_right';

	/**
	 * Rights array loaded from session
	 *
	 * @var	Array
	 */
	private static $rights = null;



	/**
	 * Load rights. If they are stored in session, use them, else load all
	 * from database and store it in session
	 *
	 * On the first session request, rights will be loaded from database, all
	 * following requests will use the data in the session
	 */
	public static function loadRights() {
			// Check if rights are loaded (is array)
		if( ! is_array(self::$rights) ) {
				// Check if rights are stored in session
			if( TodoyuSession::isIn('rights') ) {
				self::$rights = TodoyuSession::get('rights');
			} else {
					// Get roles from db
				$roleIDs	= TodoyuAuth::getPerson()->getRoleIDs();

					// If person has roles, get rights for the roles and compile them
				if( sizeof($roleIDs) > 0 )	{
					$fields	= '	ext, `right`';
					$table	= self::TABLE;

					$where	= '	id_role IN(' . implode(',', $roleIDs) . ')';

					$rights = Todoyu::db()->getArray($fields, $table, $where);

					foreach($rights as $right) {
						self::$rights[$right['ext']][$right['right']] = 1;
					}

						// Save compiled rights in session
					TodoyuSession::set('rights', self::$rights);
				} else {
						// If no roles found, set an empty rights array to prevent loading again (nothing allowed)
					self::$rights = array();
				}
			}
		}
	}



	/**
	 * Check if an action is allowed
	 *
	 * @param	String		$extKey		Extension key
	 * @param	String		$right		Right
	 * @return	Boolean
	 */
	public static function isAllowed($extKey, $right) {
			// Not logged in user have no rights at all
		if( ! TodoyuAuth::isLoggedIn() ) {
			return false;
		}

			// Allow all for admin
		if( TodoyuAuth::isAdmin() ) {
			return true;
		}

			// Load rights if not stored in object
		if( is_null(self::$rights) ) {
			self::loadRights();
		}

			// Get ID of the extension to access to right
		$extID = TodoyuExtensions::getExtID($extKey);

		return intval(self::$rights[$extID][$right]) === 1;
	}



	/**
	 * Add new right for a group
	 *
	 * @param	Integer		$extID
	 * @param	Integer		$idGroup
	 * @param	Integer		$right
	 * @return	Integer
	 */
	public static function setRight($extID, $idGroup, $right) {
		$data	= array(
			'ext'		=> abs($extID),
			'right'		=> $right,
			'id_role'	=> abs($idGroup)
		);

		return Todoyu::db()->doInsert(self::TABLE, $data);
	}



	/**
	 * Delete a right for a group
	 *
	 * @param	Integer		$extID
	 * @param	Integer		$idGroup
	 * @param	Integer		$right
	 * @return	Boolean
	 */
	public static function deleteRight($extID, $idGroup, $right) {
		$where	= '	ext		= ' . abs($extID) . ' AND
					id_role= ' . abs($idGroup) . ' AND
					right	= ' . abs($right);
		$limit	= 1;

		return Todoyu::db()->doDelete(self::TABLE, $where, $limit) === 1;
	}



	/**
	 * Delete all group rights
	 *
	 * @param	Integer		$idGroup
	 * @return	Integer		Number of deleted rights
	 */
	public static function deleteGroupRights($idGroup) {
		$where	= 'id_role	= ' . abs($idGroup);

		return Todoyu::db()->doDelete(self::TABLE, $where);
	}



	/**
	 * Delete all extension rights
	 *
	 * @param	Integer		$extID
	 * @return	Integer		Number of deleted rights
	 */
	public static function deleteExtensionRights($extID) {
		$where	= 'ext	= ' . abs($extID);

		return Todoyu::db()->doDelete(self::TABLE, $where);
	}



	/**
	 * Flush all rights from session. The rights will automaticly be reloaded
	 * on the next request on isAllowed. Use this function if you update the rights configuration
	 */
	public static function flushRights() {
		self::$rights = null;
		TodoyuSession::remove('rights');
	}



	/**
	 * Flush and reload all rights config
	 *
	 */
	public static function reloadRights() {
		self::flushRights();
		self::loadRights();
	}



	/**
	 * Get extension user group rights
	 *
	 * @param	String	$ext
	 * @param	Array	$groups
	 * @return	Array
	 */
	public static function getExtRoleRights($ext, array $roles = array()) {
		$extID	= TodoyuExtensions::getExtID($ext);
		$roles	= TodoyuArray::intval($roles, true, true);

		$fields	= '	`right`,
					id_role';
		$table	= self::TABLE;
		$where	= '	ext		= ' . $extID;

		if( sizeof($roles) > 0 ) {
			$where .= ' AND id_role IN(' . implode(',', $roles) . ')';
		}

		$rights	= Todoyu::db()->getArray($fields, $table, $where);

		$roleRights = array();

		foreach($rights as $right) {
			$roleRights[$right['id_role']][] = $right['right'];
		}

		return $roleRights;
	}



	/**
	 * Restrict access
	 * If user has no the right, display error message (or send error header for ajax requests)
	 *
	 * @param	String		$extKey
	 * @param	String		$right
	 */
	public static function restrict($extKey, $right) {
		if( ! self::isAllowed($extKey, $right) ) {
			self::deny($extKey, $right);
		}
	}



	/**
	 * Restrict access to internal persons
	 *
	 */
	public static function restrictInternal() {
		if( ! Todoyu::person()->isInternal() ) {
			self::deny('core', 'internal');
		}
	}



	/**
	 * Deny access and send no-access info
	 *
	 * @param	String		$extKey
	 * @param	String		$right
	 */
	public static function deny($extKey, $right) {
		$output	= '';

		if( TodoyuRequest::isAjaxRequest() ) {
			TodoyuHeader::sendNoAccessHeader();
			TodoyuHeader::sendTodoyuHeader('noAccess-right', $extKey . '/' . $right);
		} else {
			$tmpl	= 'core/view/noaccess.tmpl';
			$data	= array(
				'requestURL'	=> $_SERVER['REQUEST_URI'],
				'extKey'		=> $extKey,
				'right'			=> $right
			);

			ob_end_clean();

			$output = render($tmpl, $data);
		}

		Todoyu::log('Access denied (' . $extKey . '/' . $right . ')', LOG_LEVEL_SECURITY);

		die($output);
	}



	/**
	 * Get the currently cached rights config
	 *
	 * @return	Array
	 */
	public static function getCachedRights() {
		return self::$rights;
	}

}


?>