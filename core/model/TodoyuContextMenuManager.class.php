<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * Context menu manager
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuContextMenuManager {

	/**
	 * Register a source function which adds items to a special context menu type
	 *
	 * @param	String		$type			Identifier for a context menu type
	 * @param	String		$function		Function reference
	 * @param	Integer		$position		Position when the function is called to fill the item queue
	 */
	public static function addFunction($type, $function, $position = 100) {
		$type		= strtoupper(trim($type));
		$position	= (int) $position;

		Todoyu::$CONFIG['ContextMenu'][$type][] = array(
			'function'	=> $function,
			'position'	=> $position
		);
	}



	/**
	 * Get all registered functions for a type
	 *
	 * @param	String		$type
	 * @return	Array
	 */
	public static function getTypeFunctions($type) {
		$type		= strtoupper(trim($type));
		$funcRefs	= TodoyuArray::assure(Todoyu::$CONFIG['ContextMenu'][$type]);

			// Sort registered functions by position flag
		return TodoyuArray::sortByLabel($funcRefs, 'position');
	}

}

?>