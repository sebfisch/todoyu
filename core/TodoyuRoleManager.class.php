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
	 * Get an usergroup as an array
	 *
	 * @param	Integer		$idUsergroup
	 * @return	Array
	 */
	public static function getUsergroupArray($idUsergroup) {
		$idUsergroup	= intval($idUsergroup);

		return Todoyu::db()->getRecord(self::TABLE, $idUsergroup);
	}



	/**
	 * Get all usergroups
	 *
	 * @return	Array
	 */
	public static function getAllUsergroups() {
		$fields	= '*';
		$table	= self::TABLE;
		$where	= '	deleted 	= 0 AND
					is_active	= 1';
		$order	= 'is_active, title';

		return Todoyu::db()->getArray($fields, $table, $where, '', $order);
	}



	/**
	 * Get fixed user groups
	 *
	 * @return	Array
	 */
	public static function getFixedUserGroups() {
		$fields	= 'id,`key`,title';
		$table	= self::TABLE;
		$where	= 'is_active = 1 AND deleted = 0';
		$order	= 'id';

		return Todoyu::db()->getArray($fields, $table, $where, '', $order);
	}



	/**
	 * Save usergroup (update or create)
	 *
	 * @param	Integer		$idUsergroup
	 * @param	Array		$data
	 * @return	Integer		Usergroup ID
	 */
	public static function saveUsergroup($idUsergroup, array $data) {
		$idUsergroup= intval($idUsergroup);
		$xmlPath	= 'ext/user/config/form/usergroup.xml';

			// If new usergroup, create empty container to work with the ID
		if( $idUsergroup === 0 ) {
			$idUsergroup = self::addUsergroup();
		}

		$data	= self::saveUsergroupForeignRecords($data, $idUsergroup);
		$data	= TodoyuFormHook::callSaveData($xmlPath, $data, $idUsergroup);

		self::updateUsergroup($idUsergroup, $data);

		return $idUsergroup;
	}



	/**
	 * Save foreign records of a usergroup
	 *
	 * @param	Array		$data
	 * @param	Integer		$idUsergroup
	 * @return	Array
	 */
	public static function saveUsergroupForeignRecords(array $data, $idUsergroup) {
		$idUsergroup = intval($idUsergroup);

			// Remove all users first
		self::removeAllUsersFromGroup($idUsergroup);

			// Add users
		if( ! empty($data['users']) ) {
			$userIDs = TodoyuArray::getColumn($data['users'], 'id');

			self::addUsersToGroup($idUsergroup, $userIDs);
		}
		unset($data['users']);

		return $data;
	}



	/**
	 * Remove an user from a group
	 *
	 * @param	Integer		$idGroup
	 * @param	Integer		$idUser
	 */
	public static function removeUserFromGroup($idGroup, $idUser) {
		$idGroup= intval($idGroup);
		$idUser	= intval($idUser);

		TodoyuDbHelper::removeMMrelation('ext_contact_mm_person_role', 'id_group', 'id_user', $idGroup, $idUser);
	}



	/**
	 * Remove all users from a group
	 *
	 * @param	Integer		$idGroup
	 */
	public static function removeAllUsersFromGroup($idGroup) {
		$idGroup= intval($idGroup);

		TodoyuDbHelper::removeMMrelations('ext_contact_mm_person_role', 'id_group', $idGroup);
	}



	/**
	 * Add a user to an usergroup
	 *
	 * @param	Integer		$idUsergroup
	 * @param	Integer		$idUser
	 */
	public static function addUserToGroup($idUsergroup, $idUser) {
		$idUsergroup= intval($idUsergroup);
		$idUser		= intval($idUser);

		self::removeUserFromGroup($idUsergroup, $idUser);

		TodoyuDbHelper::addMMrelation('ext_contact_mm_person_role', 'id_group', 'id_user', $idUsergroup, $idUser);
	}



	/**
	 * Add users to an usergroup
	 *
	 * @param	Integer		$idUsergroup
	 * @param	Array		$userIDs
	 */
	public static function addUsersToGroup($idUsergroup, array $userIDs) {
		$idUsergroup= intval($idUsergroup);
		$userIDs	= TodoyuArray::intval($userIDs, true, true);
		$userIDs	= array_unique($userIDs);

		TodoyuDbHelper::saveMMrelations('ext_contact_mm_person_role', 'id_group', 'id_user', $idUsergroup, $userIDs, true);
	}



	/**
	 * Add an user to multiple groups
	 *
	 * @param	Integer		$idUser
	 * @param	Array		$groupIDs
	 */
	public static function addUserToGroups($idUser, array $groupIDs) {
		$idUser		= intval($idUser);
		$groupIDs	= array_unique(TodoyuArray::intval($groupIDs, true, true));

		TodoyuDbHelper::saveMMrelations('ext_contact_mm_person_role', 'id_user', 'id_group', $idUser, $groupIDs, true);
	}



	/**
	 * Add a new usergroup
	 *
	 * @param	Array		$data
	 * @return	Integer		New usergroup ID
	 */
	public static function addUsergroup(array $data = array()) {
		unset($data['id']);

		$data['date_create']	= NOW;
		$data['id_user_create']	= TodoyuAuth::getUserID();

		return Todoyu::db()->addRecord(self::TABLE, $data);
	}



	/**
	 * Update a usergrop
	 *
	 * @param	Integer		$idUsergroup
	 * @param	Array		$data
	 * @return	Bool
	 */
	public static function updateUsergroup($idUsergroup, array $data) {
		unset($data['id']);
		$idUsergroup	= intval($idUsergroup);

		return Todoyu::db()->updateRecord(self::TABLE, $idUsergroup, $data);
	}



	/**
	 * Delete Usergroup (set deleted flag to 1)
	 *
	 * @param	Integer		$idUsergroup
	 */
	public static function deleteUsergroup($idUsergroup)	{
		$idUsergroup = intval($idUsergroup);

		$update	= array(
			'date_update'	 => NOW,
			'deleted'		=> 1
		);

		self::updateUsergroup($idUsergroup, $update);
	}



	/**
	 * Get IDs of the group users
	 *
	 * @param	Integer		$idUsergroup
	 * @return	Array
	 */
	public static function getGroupUserIDs($idUsergroup) {
		$idUsergroup	= intval($idUsergroup);

		$field	= 'id_user';
		$table	= 'ext_contact_mm_person_role';
		$where	= 'id_group = ' . $idUsergroup;

		return Todoyu::db()->getColumn($field, $table, $where);
	}



	/**
	 * Get number of users in the group
	 *
	 * @param	Integer		$idUsergroup
	 * @return	Integer
	 */
	public static function getNumUsers($idUsergroup) {
		$idUsergroup= intval($idUsergroup);
		$userIDs	= self::getGroupUserIDs($idUsergroup);

		return sizeof($userIDs);
	}



	/**
	 * Get array with group users
	 *
	 * @param	Integer		$idUsergroup
	 * @return	Array
	 */
	public static function getGroupUsers($idUsergroup) {
		$idUsergroup	= intval($idUsergroup);

		$fields	= 'u.*';
		$table	= 'ext_contact_person u,
					ext_contact_mm_person_role mm';
		$where	= 'mm.id_group = ' . $idUsergroup . ' AND
					mm.id_user = u.id';

		return Todoyu::db()->getArray($fields, $table, $where);
	}
}

?>