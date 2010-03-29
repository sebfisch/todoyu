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
 * Role view helper
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuRoleViewHelper {

	/**
	 * Get options array for role selector
	 *
	 * @param	TodoyuFormElement	$field
	 * @return	Array
	 */
	public static function getRoleOptions(TodoyuFormElement $field) {
		$options= array();
		$roles	= TodoyuRoleManager::getAllRoles(true);
		$reform	= array(
			'id'	=> 'value',
			'title'	=> 'label'
		);

		return TodoyuArray::reform($roles, $reform);
	}

}

?>