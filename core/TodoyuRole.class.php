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