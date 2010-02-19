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
 * Role manager
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuRoleManager {

	const TABLE = 'system_role';


	/**
	 * Get a usergroup object
	 *
	 * @param	Integer		$idUsergroup
	 * @return	TodoyuRole
	 */
	public static function getRole($idRole) {
		$idRole	= intval($idRole);

		return TodoyuCache::getRecord('TodoyuRole', $idRole);
	}



	/**
	 * Get all usergroups
	 *
	 * @return	Array
	 */
	public static function getAllRoles() {
		$fields	= '*';
		$table	= self::TABLE;
		$where	= '	deleted = 0 AND
					active	= 1';
		$order	= '	active,
					title';

		return Todoyu::db()->getArray($fields, $table, $where, '', $order);
	}



	/**
	 * Save role
	 *
	 * @param	Array		$data
	 * @return	Integer		Role ID
	 */
	public static function saveRole(array $data) {
		$xmlPath= 'ext/user/config/form/usergroup.xml';
		$idRole	= intval($data['id']);

			// If new usergroup, create empty container to work with the ID
		if( $idRole === 0 ) {
			$idRole = self::addRole();
		}

		$data	= self::saveRoleForeignRecords($data, $idRole);
		$data	= TodoyuFormHook::callSaveData($xmlPath, $data, $idRole);

		self::updateRole($idRole, $data);

		return $idRole;
	}



	/**
	 * Save foreign records of a usergroup
	 *
	 * @param	Array		$data
	 * @param	Integer		$idUsergroup
	 * @return	Array
	 */
	public static function saveRoleForeignRecords(array $data, $idRole) {
		$idRole = intval($idRole);

			// Remove all persons
		self::removePersons($idRole);

			// Add users
		if( ! empty($data['persons']) ) {
			$personIDs = TodoyuArray::getColumn($data['persons'], 'id');

			self::addPersons($idRole, $personIDs);
		}
		unset($data['persons']);

		return $data;
	}



	/**
	 * Remove an user from a group
	 *
	 * @param	Integer		$idGroup
	 * @param	Integer		$idUser
	 */
	public static function removePerson($idRole, $idPerson) {
		$idRole		= intval($idRole);
		$idPerson	= intval($idPerson);

		TodoyuDbHelper::removeMMrelation('ext_contact_mm_person_role', 'id_role', 'id_person', $idRole, $idPerson);
	}



	/**
	 * Remove all users from a group
	 *
	 * @param	Integer		$idGroup
	 */
	public static function removePersons($idRole) {
		$idRole= intval($idRole);

		TodoyuDbHelper::removeMMrelations('ext_contact_mm_person_role', 'id_role', $idRole);
	}



	/**
	 * Remove all roles for a person
	 *
	 * @param	Integer		$idPerson
	 */
	public static function removeRoles($idPerson) {
		$idPerson= intval($idPerson);

		TodoyuDbHelper::removeMMrelations('ext_contact_mm_person_role', 'id_person', $idPerson);
	}



	/**
	 * Add a user to an usergroup
	 *
	 * @param	Integer		$idRole
	 * @param	Integer		$idPerson
	 */
	public static function addPerson($idRole, $idPerson) {
		$idRole		= intval($idRole);
		$idPerson	= intval($idPerson);

		self::removePerson($idRole, $idPerson);

		TodoyuDbHelper::addMMLink('ext_contact_mm_person_role', 'id_role', 'id_person', $idRole, $idPerson);
	}



	/**
	 * Add users to an usergroup
	 *
	 * @param	Integer		$idRole
	 * @param	Array		$personIDs
	 */
	public static function addPersons($idRole, array $personIDs) {
		$idRole		= intval($idRole);
		$personIDs	= TodoyuArray::intval($personIDs, true, true);
		$personIDs	= array_unique($personIDs);

		TodoyuDbHelper::addMMLinks('ext_contact_mm_person_role', 'id_role', 'id_person', $idRole, $personIDs, true);
	}



	/**
	 * Add an user to multiple groups
	 *
	 * @param	Integer		$idPerson
	 * @param	Array		$roleIDs
	 */
	public static function addPersonToRoles($idPerson, array $roleIDs) {
		$idPerson	= intval($idPerson);
		$roleIDs	= array_unique(TodoyuArray::intval($roleIDs, true, true));

		TodoyuDbHelper::addMMLinks('ext_contact_mm_person_role', 'id_person', 'id_role', $idPerson, $roleIDs, true);
	}



	/**
	 * Add a new usergroup
	 *
	 * @param	Array		$data
	 * @return	Integer		New role ID
	 */
	public static function addRole(array $data = array()) {
		return TodoyuRecordManager::addRecord(self::TABLE, $data);
	}



	/**
	 * Update a usergrop
	 *
	 * @param	Integer		$idUsergroup
	 * @param	Array		$data
	 * @return	Bool
	 */
	public static function updateRole($idRole, array $data) {
		return TodoyuRecordManager::updateRecord(self::TABLE, $idRole, $data);
	}



	/**
	 * Delete Usergroup (set deleted flag to 1)
	 *
	 * @param	Integer		$idUsergroup
	 */
	public static function deleteRole($idRole)	{
		return TodoyuRecordManager::deleteRecord(self::TABLE, $idRole);
	}



	/**
	 * Get IDs of the group users
	 *
	 * @param	Integer		$idRole
	 * @return	Array
	 */
	public static function getPersonIDs($idRole) {
		$idRole	= intval($idRole);

		$field	= 'id_person';
		$table	= 'ext_contact_mm_person_role';
		$where	= 'id_role = ' . $idRole;

		return Todoyu::db()->getColumn($field, $table, $where);
	}



	/**
	 * Get number of users in the group
	 *
	 * @param	Integer		$idUsergroup
	 * @return	Integer
	 */
	public static function getNumPersons($idRole) {
		$idRole		= intval($idRole);
		$personIDs	= self::getPersonIDs($idRole);

		return sizeof($personIDs);
	}



	/**
	 * Get array with group users
	 *
	 * @param	Integer		$idRole
	 * @return	Array
	 */
	public static function getPersonData($idRole) {
		$idRole	= intval($idRole);

		$fields	= '	p.*';
		$table	= '	ext_contact_person p,
					ext_contact_mm_person_role mm';
		$where	= '	mm.id_role = ' . $idRole . ' AND
					mm.id_person = p.id';

		return Todoyu::db()->getArray($fields, $table, $where);
	}

}

?>