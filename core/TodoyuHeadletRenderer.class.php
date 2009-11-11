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
 * Render headlets
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuHeadletRenderer {

	/**
	 * Render headlets for an area
	 *
	 * @param	String		$type			Headlet type
	 * @param	String		$areaKey		Key of the current area
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderAreaHeadlets($type, $areaKey, array $params = array()) {
		$type		= strtoupper(trim($type));
		$headlets	= TodoyuHeadletManager::getAreaHeadlets($type, $areaKey);
		$content	= '';

		foreach($headlets as $headletConfig) {
			$class	= $headletConfig['class'];

			if( class_exists($class) ) {
				$headlet = new $class($params);

				$content .= $headlet->render();
			}
		}

		return $content;
	}

}

?>