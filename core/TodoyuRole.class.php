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
 * Role for rights management
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuRole extends TodoyuBaseObject {

	/**
	 * Constructs a usergroup
	 *
	 * @param	Integer	$idUsergroup
	 */
	public function __construct($idRole) {
		parent::__construct($idRole, 'system_role');
	}



	/**
	 * Get person IDs
	 *
	 * @return	Array
	 */
	public function getPersonIDs() {
		return TodoyuRoleManager::getPersonIDs($this->id);
	}



	/**
	 * Get persons with this role
	 *
	 * @return	Array
	 */
	public function getPersons() {
		return TodoyuRoleManager::getPersonData($this->id);
	}



	/**
	 * Get number of group users
	 *
	 * @return	Integer
	 */
	public function getNumPersons() {
		return TodoyuRoleManager::getNumPersons($this->id);
	}



	/**
	 * Check if group has any users
	 *
	 * @return	Bool
	 */
	public function hasPersons() {
		return sizeof($this->getNumPersons()) > 0;
	}



	/**
	 * Load foreign role data
	 *
	 */
	private function loadForeignData()  {
		$this->data['persons']	= TodoyuRoleManager::getPersonData($this->id);
	}



	/**
	 * Get templating data
	 *
	 * @param	Bool		$loadForeignRecords
	 * @return	Array
	 */
	public function getTemplateData($loadForeignRecords = false) {
		if( $loadForeignRecords ) {
			$this->loadForeignData();
		}

		return parent::getTemplateData();
	}

}


?>