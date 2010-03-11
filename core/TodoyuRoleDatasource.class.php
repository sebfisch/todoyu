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
 * Handles the Datasource for filter-widgets which belong to roles
 *
 * @package Todoyu
 */

class TodoyuRoleDatasource {

	const TABLE = 'system_role';



	/**
	 * Prepares roles options for rendering in the widget.
	 *
	 * @param	Array	$definitions
	 * @return	Array
	 */
	public static function getRoleOptions(array $definitions)	{
		$options	= array();

		$roleIDs	= array();
		$roles		= TodoyuRoleManager::getRoles($roleIDs);

		$selected	= TodoyuArray::intExplode(',', $definitions['value'], true, true);

		foreach($roles as $role) {
			$options[] = array(
				'label'		=> $role['title'],
				'value'		=> $role['id'],
//				'selected'	=> in_array($status['index'], $selected)
			);
		}

		$definitions['options'] = $options;

		return $definitions;
	}

}
?>