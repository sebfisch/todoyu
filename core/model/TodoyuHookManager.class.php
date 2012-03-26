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
 * Register hooks and call them
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuHookManager {

	/**
	 * Registered callbacks for hooks grouped by extension and hook name
	 *
	 * @var	Array
	 */
	private static $hooks = array();



	/**
	 * Get registered hooks
	 *
	 * @param	String		$extKey
	 * @param	String		$name
	 * @return	Array
	 */
	public static function getHooks($extKey, $name) {
		$extKey	= strtolower($extKey);
		$name	= strtolower($name);
		$hooks	= TodoyuArray::assure(self::$hooks[$extKey][$name]);
		$hooks	= TodoyuArray::sortByLabel($hooks, 'position');

		return TodoyuArray::getColumn($hooks, 'function');
	}



	/**
	 * Call all registered hooks for an event
	 *
	 * @param	String		$extKey
	 * @param	String		$name			Hook name
	 * @param	Array		$params			Parameters for the hook function
	 * @return	Array		The return values of all hook functions
	 */
	public static function callHook($extKey, $name, array $params = array()) {
		$hookFuncRefs	= self::getHooks($extKey, $name);
		$returnValues	= array();

		TodoyuLogger::logCore('Hook: ' . $extKey . '/' . $name);

		foreach($hookFuncRefs as $hookFuncRef) {
			TodoyuLogger::logCore('Call: ' . $hookFuncRef);
			$returnValues[] = TodoyuFunction::callUserFunctionArray($hookFuncRef, $params);
		}

		return $returnValues;
	}



	/**
	 * Call hooks which modify a data variable (ex: an array)
	 *
	 * @param	String		$extKey
	 * @param	String		$name				Hook name
	 * @param	Mixed		$dataVar			Data variable which will be passed to each hook
	 * @param	Array		$additionalParams	Additional parameters which will be placed after the $dataVar
	 * @return	Mixed
	 */
	public static function callHookDataModifier($extKey, $name, $dataVar, array $additionalParams = array()) {
		$hookFuncRefs	= self::getHooks($extKey, $name);
		$hookParams		= $additionalParams;
			// Prepend data var
		array_unshift($hookParams, $dataVar);

		foreach($hookFuncRefs as $hookFuncRef) {
			TodoyuLogger::logCore('Hook: ' . $hookFuncRef);
			$hookParams[0] = TodoyuFunction::callUserFunctionArray($hookFuncRef, $hookParams);
		}

		return $hookParams[0];
	}



	/**
	 * Add a new hook functions for a hook event
	 *
	 * @param	String		$extKey			Extension key (of Ext to be extended)
	 * @param	String		$name			Hook name
	 * @param	String		$function		Function reference (e.g: 'Classname::method')
	 * @param	Integer		$position		Position of the hook (order of calling)
	 */
	public static function registerHook($extKey, $name, $function, $position = 100) {
		$extKey	= strtolower($extKey);
		$name	= strtolower($name);
		
		self::$hooks[$extKey][$name][] = array(
			'function'	=> $function,
			'position'	=> (int) $position
		);
	}

}

?>