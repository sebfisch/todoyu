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
 * Manage headlets
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuHeadletManager {

	public static function saveOpenStatus($headlet, $open = true) {
		$pref	= 'headlet-open-' . strtolower(trim($headlet));

		if( $open ) {
			TodoyuPreferenceManager::savePreference(0, $pref, 1);
		} else {
			TodoyuPreferenceManager::deletePreference(0, $pref);
		}
	}


	public static function isOpen($headlet) {
		$pref	= 'headlet-open-' . strtolower(trim($headlet));

		$open	= TodoyuPreferenceManager::getPreference(0, $pref);

		return intval($open) === 1;
	}

}

?>