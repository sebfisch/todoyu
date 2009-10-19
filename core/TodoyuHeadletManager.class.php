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
 * Manage headlets. Register in config and get registered headlets for area
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuHeadletManager {

	/**
	 * Register a headlet for the left headlet area
	 *
	 * @param	String		$className
	 * @param	Integer		$position
	 * @param	Array		$areas
	 */
	public static function registerLeft($className, $position = 100, array $areas = array()) {
		self::register('LEFT', $className, $position, $areas);
	}



	/**
	 * Register a headlet for the right headlet area
	 *
	 * @param	String		$className
	 * @param	Integer		$position
	 * @param	Array		$areas
	 */
	public static function registerRight($className, $position = 100, array $areas = array()) {
		self::register('RIGHT', $className, $position, $areas);
	}



	/**
	 * Register a headlet for rendering
	 *
	 * @param	String		$className		Class which implements the headlet
	 * @param	Integer		$position		Position in the toppanel
	 * @param	Array		$areas			List of extKeys in which the headlet should be rendered. Non = everywhere
	 */
	public static function register($type, $className, $position = 100, array $areas = array()) {
		$type = strtoupper(trim($type));

		$GLOBALS['CONFIG']['HEADLETS'][$type][$className] = array(
			'class'		=> $className,
			'position'	=> intval($position),
			'areas'		=> $areas
		);

	}


	/**
	 * Get all headlet configs for an area
	 *
	 * @param	String		$areaKey
	 * @return	Array
	 */
	public static function getAreaHeadlets($type, $areaKey) {
		$headlets	= array();
		$type		= strtoupper(trim($type));

		foreach($GLOBALS['CONFIG']['HEADLETS'][$type] as $className => $headlet) {
			if( sizeof($headlet['areas']) === 0 || in_array($areaKey, $headlet['areas']) ) {
				$headlets[$className] = $headlet;
			}
		}

		$headlets	= TodoyuArray::sortByLabel($headlets, 'position');

		return $headlets;
	}

}


?>