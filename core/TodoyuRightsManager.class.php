<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * Manage user access rights
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuRightsManager {

	/**
	 * Default working table
	 */
	const TABLE = 'system_right';

	/**
	 * Rights array loaded from session
	 *
	 * @var	Array
	 */
	private static $rights = null;


	private static $checkRightsCache = array();



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
				// Check if rights are stored in session and are up-to-date
			if( TodoyuSession::isIn('rights') && ! self::areRightsInSessionExpired() ) {
				self::$rights = TodoyuSession::get('rights');
			} else {
				self::saveCompiledRightsInSession();
			}
		}
	}



	/**
	 * Get roles and resp. person's rights from DB, save rights compiled in session
	 */
	public static function saveCompiledRightsInSession() {
			// Get roles from DB
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

				// Update session modification timestamp
			TodoyuSession::set('mtime', NOW);

		} else {
				// If no roles found, set an empty rights array to prevent loading again (nothing allowed)
			self::$rights = array();
		}
	}



	/**
	 * Check whether rights stored in session haven't expired (rights definitions havent changed since)
	 *
	 * @return	Boolean
	 */
	public static function areRightsInSessionExpired() {
		$timestampSession	= TodoyuSession::get('mtime');
		$timestampRightsMod	= self::getLastChangeTime();

		return ($timestampSession < $timestampRightsMod) ? true : false;
	}



	/**
	 * Get timestamp of last change of systemwide rights settings
	 *
	 * @return	Integer
	 */
	public static function getLastChangeTime() {
		$timestampFile	= PATH_CACHE . DIR_SEP . 'timeLastRightsChange';

		if ( TodoyuFileManager::isFile($timestampFile) ) {
				// Get modification time of file
			$fileMtime	= filemtime($timestampFile);
		} else {
			$fileMtime	= 0;
		}

		return $fileMtime;
	}



	/**
	 * Save timestamp of moment of system rights store / change
	 */
	public static function saveChangeTime() {
		$timestampFile	= PATH_CACHE . DIR_SEP . 'timeLastRightsChange';

		if ( ! TodoyuFileManager::isFile($timestampFile) ) {
				// Create timestamp file
			$handle	= fopen($timestampFile, 'w');
			fclose($handle);
		} else {
				// Update file modification timestamp
			TodoyuFileManager::setFileMtime($timestampFile, NOW);
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
			// Not logged in users have no rights at all
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

		$allowed	= intval(self::$rights[$extID][$right]) === 1;

			// If right was denied and checking is active, check if this right exists
		if( $allowed === false && Todoyu::$CONFIG['CHECK_DENIED_RIGHTS'] === true ) {
			self::checkIfRightExists($extKey, $right);
		}

		return $allowed;
	}



	/**
	 * Check if a right exists in the rights XML file
	 * 
	 * @param	String		$extKey
	 * @param	String		$right
	 */
	public static function checkIfRightExists($extKey, $right) {
		if( ! is_array(self::$checkRightsCache[$extKey]) ) {
			$xmlFile = TodoyuExtensions::getExtPath($extKey, '/config/rights.xml');
			self::$checkRightsCache[$extKey] = TodoyuArray::fromSimpleXml(simplexml_load_file($xmlFile));
		}

		$rightParts		= explode(':', $right, 2);
		$rightSection	= $rightParts[0];
		$rightName		= $rightParts[1];

		$found			= false;
		
		foreach(self::$checkRightsCache[$extKey] as $section) {
			foreach($section as $sectionElements) {
				if( $rightSection === $sectionElements['@attributes']['name'] ) {
					if( sizeof($sectionElements['right']) === 1 ) {
						$sectionRights	= array($sectionElements['right']);
					} else {
						$sectionRights	= $sectionElements['right'];
					}
					foreach($sectionRights as $rightNode) {
						if( $rightNode['@attributes']['name'] === $rightName ) {
							$found	= true;
							break 3;
						}
					}
				}
			}
		}

			// If right doesn't exist, log it
		if( $found !== true ) {
			Todoyu::log('Right not found: ' . $extKey . '::' . $right, LOG_LEVEL_SECURITY);
		}
	}



	/**
	 * Add new right for a role
	 *
	 * @param	Integer		$extID
	 * @param	Integer		$idRole
	 * @param	Integer		$right
	 * @return	Integer
	 */
	public static function setRight($extID, $idRole, $right) {
		$data	= array(
			'ext'		=> abs($extID),
			'right'		=> $right,
			'id_role'	=> abs($idRole)
		);

		return Todoyu::db()->doInsert(self::TABLE, $data);
	}



	/**
	 * Delete a right for a role
	 *
	 * @param	Integer		$extID
	 * @param	Integer		$idRole
	 * @param	Integer		$right
	 * @return	Boolean
	 */
	public static function deleteRight($extID, $idRole, $right) {
		$where	= '	ext		= ' . abs($extID) . ' AND
					id_role= ' . abs($idRole) . ' AND
					right	= ' . abs($right);
		$limit	= 1;

		return Todoyu::db()->doDelete(self::TABLE, $where, $limit) === 1;
	}



	/**
	 * Delete all role rights
	 *
	 * @param	Integer		$extID			ID of the extension
	 * @param	Integer		$idRole			ID of the role
	 * @return	Integer		Number of deleted rights
	 */
	public static function deleteExtRoleRights($extID, $idRole) {
		$extID	= intval($extID);
		$idRole	= intval($idRole);
		$where	= '	ext		= ' . $extID . ' AND
					id_role	= ' . $idRole;

		return Todoyu::db()->doDelete(self::TABLE, $where);
	}



	/**
	 * Delete all extension rights
	 *
	 * @param	Integer		$extID
	 * @return	Integer		Number of deleted rights
	 */
	public static function deleteExtensionRights($extID) {
		$extID	= intval($extID);
		$where	= 'ext	= ' . $extID;

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
	 */
	public static function reloadRights() {
		self::flushRights();
		self::loadRights();
	}



	/**
	 * Get extension user role rights
	 *
	 * @param	String	$ext
	 * @param	Array	$roles
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
	 * If user has no the right, display error message (or send error header for AJAX requests)
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
	 */
	public static function restrictInternal() {
		if( ! Todoyu::person()->isInternal() ) {
			self::deny('core', 'internal');
		}
	}



	/**
	 * Restrict access to admin
	 */
	public static function restrictAdmin() {
		if( ! TodoyuAuth::isAdmin() ) {
			self::deny('core', 'admin');
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