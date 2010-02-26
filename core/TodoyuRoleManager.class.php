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
	 * Get informations about the roles defined in $groupIDs
	 *
	 * @param	Array		$groupIDs		IDs of the groups to the get information from
	 * @return	Array
	 */
	public static function getRoles(array $roleIDs) {
		$roleIDs	= TodoyuArray::intval($roleIDs, true, true);

		$fields	= 'id, title, is_active';
		$table	= self::TABLE;
		$where	= 'deleted = 0';
		$order	= 'is_active DESC, title';

		if( sizeof($roleIDs) > 0 ) {
			$where .= ' AND id IN(' . implode(',', $roleIDs) . ')';
		}

		return Todoyu::db()->getArray($fields, $table, $where, '', $order);
	}



	/**
	 * Get all usergroups
	 *
	 * @param	Boolean		$onlyActive
	 * @return	Array
	 */
	public static function getAllRoles($onlyActive = false) {
		$fields	= '*';
		$table	= self::TABLE;
		$where	= '	deleted = 0' . ($onlyActive ? ' AND is_active = 1' : '');
		$order	= ' is_active, title';

		return Todoyu::db()->getArray($fields, $table, $where, '', $order);
	}



	/**
	 * Add a new role
	 *
	 * @param	Array		$data
	 * @return	Integer		New role ID
	 */
	public static function addRole(array $data = array()) {
		unset($data['id']);

		$data['date_create']		= NOW;
		$data['id_person_create']	= TodoyuAuth::getPersonID();

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
		$idRole	= intval($idRole);

		return TodoyuRecordManager::updateRecord(self::TABLE, $idRole, $data);
	}



	/**
	 * Assign multiple persons to an event
	 *
	 * @param	Integer		$idEvent
	 * @param	Array		$personIDs
	 */
	public static function assignPersonsToRole($idRole, array $personIDs) {
		$idRole		= intval($idRole);
		$personIDs	= TodoyuArray::intval($personIDs, true, false);

		foreach($personIDs as $idPerson) {
			self::assignPersonToRole($idRole, $idPerson);
		}
	}



	/**
	 * Assign a single person to a role
	 *
	 * @param	Integer		$idRole
	 * @param	Integer		$idPerson
	 */
	public static function assignPersonToRole($idRole, $idPerson) {
		$idRole		= intval($idRole);
		$idPerson	= intval($idPerson);

		$table	= 'ext_contact_mm_person_role';
		$data	= array(
			'id_role'			=> $idRole,
			'id_person'			=> $idPerson
		);

		Todoyu::db()->addRecord($table, $data);
	}



	/**
	 * Remove role from cache
	 *
	 * @param	Integer	$idRole
	 */
	public static function removeRoleFromCache($idRole) {
		$idRole = intval($idRole);

		TodoyuCache::removeRecord('Role', $idRole);
		TodoyuCache::removeRecordQuery(self::TABLE, $idRole);
	}



	/**
	 * Save role (add or update)
	 *
	 * @param	Array	$storageData
	 */
	public static function saveRole(array $data) {
		$xmlPath= 'core/config/form/role.xml';

			// New created record?
		$idRole	= intval($data['id']);
		if ( $idRole == 0 ) {
			$idRole	= self::addRole(array());
		}

			// Person assignments
		$data['persons'] = TodoyuArray::getColumn(TodoyuArray::assure($data['persons']), 'id');

		$data	= TodoyuFormHook::callSaveData($xmlPath, $data, $idRole);

			// Save foreign records (person assignments)
		$data	= self::saveRoleForeignRecords($data, $idRole);

			// Update the event with the definitive data
		self::updateRole($idRole, $data);

			// Remove record and query from cache
		self::removeRoleFromCache($idRole);

		return $idRole;
	}



	/**
	 * Save foreign records of a usergroup
	 *
	 * @param	Array		$data
	 * @param	Integer		$idRole
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
	 * Remove an user from a role
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
	 * Remove all users from a role
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

		foreach($personIDs as $idPerson) {
			self::addPerson($idRole, $idPerson);
		}
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