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
	const TABLE = 'ext_user_right';

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
		if( TodoyuSessionManager::isIn('rights') ) {
			self::$rights = TodoyuSessionManager::get('rights');
		} else {
			$userGroupIDs	= TodoyuAuth::getUser()->getGroupIDs();

			if( sizeof($userGroupIDs) > 0 )	{
				$fields	= '	ext, `right`';
				$table	= self::TABLE;

				$where	= '	id_group IN(' . implode(',', $userGroupIDs) . ')';

				$rights	= Todoyu::db()->getArray($fields, $table, $where);

				foreach($rights as $right) {
					self::$rights[$right['ext']][$right['right']] = 1;
				}

				TodoyuSessionManager::set('rights', self::$rights);
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
			'id_group'	=> abs($idGroup)
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
					id_group= ' . abs($idGroup) . ' AND
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
		$where	= 'id_group	= ' . abs($idGroup);

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
	 *
	 */
	public static function flushRights() {
		self::$rights = null;
		TodoyuSessionManager::remove('rights');
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
	public static function getExtGroupRights($ext, array $groups = array()) {
		$extID	= TodoyuExtensions::getExtID($ext);
		$groups	= TodoyuArray::intval($groups, true, true);

		$fields	= '`right`, id_group';
		$table	= self::TABLE;
		$where	= '	ext		= ' . $extID;

		if( sizeof($groups) > 0 ) {
			$where .= ' AND id_group IN(' . implode(',', $groups) . ')';
		}

		$rights	= Todoyu::db()->getArray($fields, $table, $where);

		$groupRights = array();

		foreach($rights as $right) {
			$groupRights[$right['id_group']][] = $right['right'];
		}

		return $groupRights;
	}



	/**
	 * Restrict access
	 * If user has no the right, display error message (or send error header for ajax requests)
	 *
	 * @param	String		$extKey
	 * @param	String		$right
	 */
	public static function restrict($extKey, $right) {
		if( ! allowed($extKey, $right) ) {
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
				echo render($tmpl, $data);
			}

			Todoyu::log('Access denied (' . $extKey . '/' . $right . ')', LOG_LEVEL_SECURITY);
			exit();
		}
	}

}


?>