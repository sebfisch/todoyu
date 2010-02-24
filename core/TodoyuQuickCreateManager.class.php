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
 * Manager class for the quick create
 *
 */

class TodoyuQuickCreateManager {

	/**
	 * Get creation engine configuration array
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	String		$labelMode
	 * @param	Integer		$position
	 * @return	Array
	 */
	private static function getEngineConfig($ext, $type, $labelMode, $position = 100) {
		$type		= strtolower(trim($type));
		$position	= intval($position);
		return array(
			'ext'			=> $ext,
			'type'			=> $type,
			'labelMode'		=> $labelMode,
			'position'		=> $position
		);
	}



	/**
	 * Add a new create engine and register needed functions
	 *
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	String		$labelMode
	 * @param	Integer		$position
	 */
	public static function addEngine($ext, $type, $labelMode = '', $position = 100) {
		$engineConfig	= self::getEngineConfig($ext, $type, $labelMode, $position);

		$GLOBALS['CONFIG']['create']['engines']['all'][$type] = $engineConfig;
	}



	/**
	 * Add a new area related (primary listed) create engine and register needed functions
	 *
	 * @param	Integer		$idArea
	 * @param	String		$ext
	 * @param	String		$type
	 * @param	String		$labelMode
	 * @param	Integer		$position
	 */
	public static function addAreaEngine($idArea, $ext, $type, $labelMode = '', $position = 100) {
			// @todo	check + fix to use AREA constant
		$requestVars= TodoyuRequest::getCurrentRequestVars();
		$area		= intval($requestVars['area']);

//		if ( AREA === $idArea ) {
		if ( $area === $idArea ) {
			$engineConfig	= self::getEngineConfig($ext, $type, $labelMode, $position);

			$GLOBALS['CONFIG']['create']['engines']['primary'][$type] = $engineConfig;
		}
	}



	/**
	 * Get all registered create engines in correct order
	 *
	 * @return	Array
	 */
	public static function getEngines($area	= 'all') {
			// Load /config/type.php configfiles of all loaded extensions)
		TodoyuExtensions::loadAllCreate();

		$createEngines	= TodoyuArray::assure($GLOBALS['CONFIG']['create']['engines'][$area]);

			// Sort by position
		if( sizeof($createEngines) > 0 ) {
			$createEngines = TodoyuArray::sortByLabel($createEngines, 'position');
		}

		return $createEngines;
	}



	/**
	 * Get area related (primary) create engines in correct order
	 *
	 * @return	Array
	 */
	public static function getAreaEngines() {
		return self::getEngines('primary');
	}

}

?>