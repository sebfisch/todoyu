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
 * Context menu manager
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuContextMenuManager {

	/**
	 * Register a source function which adds items to a special contextmenu type
	 *
	 * @param	String		$type			Identifier for a contextmenu type
	 * @param	String		$function		Function reference
	 * @param	Integer		$position		Position when the function is called to fill the item queue
	 */
	public static function registerFunction($type, $function , $position = 100) {
		$type	= strtoupper(trim($type));

		$GLOBALS['CONFIG']['FE']['ContextMenu'][$type][] = array(
			'function'	=> $function,
			'position'	=> intval($position)
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
		$funcRefs	= $GLOBALS['CONFIG']['FE']['ContextMenu'][$type];

		if( ! is_array($funcRefs) ) {
			$funcRefs	= array();
		}

			// Sort registered functions by position flag
		$funcRefs = TodoyuArray::sortByLabel($funcRefs, 'position');

			// Check that the registered functions exist
		foreach($funcRefs as $index => $function) {
			if( ! TodoyuDiv::isFunctionReference($function['function']) ) {
				unset($funcRefs[$index]);
			}
		}

		return $funcRefs;
	}

}

?>