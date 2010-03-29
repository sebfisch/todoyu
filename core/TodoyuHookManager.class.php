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
 * Hook Manager.
 * Call hooks in a predefined config structure.
 * Define your hooks in this structure:
 * Todoyu::$CONFIG['EXT'][EXTNAME]['hooks'][HOOKNAME][]
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuHookManager {

	/**
	 * Get registered hooks
	 *
	 * @param	String		$ext
	 * @param	String		$name
	 * @return	Array
	 */
	public static function getHooks($ext, $name) {
		$hooks	= Todoyu::$CONFIG['HOOKS'][$ext][$name];

		if( ! is_array($hooks) ) {
			$hooks = array();
		}

		$hooks	= TodoyuArray::sortByLabel($hooks, 'position');

		return TodoyuArray::getColumn($hooks, 'function');
	}



	/**
	 * Call all registered hooks for an event
	 *
	 * @param	String		$ext			Extension key
	 * @param	String		$name			Hookname
	 * @param	Array		$params			Parameters for the hook function
	 * @return	Array		The return values of all hook functions
	 */
	public static function callHook($ext, $name, array $params = array()) {
		$hookFuncRefs	= self::getHooks($ext, $name);
		$returnValues	= array();

		foreach($hookFuncRefs as $hookFuncRef) {
			$returnValues[] = TodoyuFunction::callUserFunctionArray($hookFuncRef, $params);
		}

		return $returnValues;
	}



	/**
	 * Call hooks which modify a data variable (ex: an array)
	 *
	 * @param	String		$ext				Extension key
	 * @param	String		$name				Hook name
	 * @param	Mixed		$dataVar			Data variable which will be passed to each hook
	 * @param	Array		$additionalParams	Additional parameters which will be placed after the $dataVar
	 * @return	Mixed
	 */
	public static function callHookDataModifier($ext, $name, $dataVar, array $additionalParams = array()) {
		$hookFuncRefs	= self::getHooks($ext, $name);
		$hookParams		= $additionalParams;
			// Prepend data var
		array_unshift($hookParams, $dataVar);

		foreach($hookFuncRefs as $hookFuncRef) {
			$hookParams[0] = TodoyuFunction::callUserFunctionArray($hookFuncRef, $hookParams);
		}

		return $hookParams[0];
	}



	/**
	 * Add a new hook functions for a hook event
	 *
	 * @param	String		$ext			Extension key (of Ext to be extended)
	 * @param	String		$name			Hookname
	 * @param	String		$function		Function reference (e.g: 'Classname::method')
	 * @param	Integer		$position		Position of the hook (order of calling)
	 */
	public static function registerHook($ext, $name, $function, $position = 100) {
		Todoyu::$CONFIG['HOOKS'][$ext][$name][] = array(
			'function'	=> $function,
			'position'	=> intval($position)
		);
	}

}

?>