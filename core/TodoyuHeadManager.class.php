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
class TodoyuHeadManager {

	/**
	 * Headlets
	 *
	 * @var	Array
	 */
	private static $headlets = array();


	/**
	 * Add a new headlet
	 *
	 * @param	String		$className
	 * @param	Integer		$initPosition
	 */
	public static function addHeadlet($className, $initPosition = 100) {
		self::$headlets[] = array(
			'class'		=> $className,
			'position'	=> intval($initPosition)
		);
	}



	/**
	 * Render head with headlets
	 *
	 * @return	String
	 */
	public static function render() {
		$tmpl	= 'core/view/head.tmpl';
		$data	= array(
			'headlets'	=> array()
		);

		$headlets	= TodoyuArray::sortByLabel(self::$headlets, 'position');

		foreach($headlets as $headletConfig) {
			$className	= $headletConfig['class'];
			$name		= strtolower(str_replace('TodoyuHeadlet', '', $className));
			$headlet	= new $className();

			$data['headlets'][$name] = array(
				'name'		=> $name,
				'phpClass'	=> $className,
				'content'	=> $headlet->render()
			);
		}

		return render($tmpl, $data);
	}

}

?>