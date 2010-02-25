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
	 * @param	Integer		$idArea			areas where to list this type as primary
	 * @param	Boolean		$areaOnly		show type within resp. area only?
	 */
	public static function addEngine($ext, $type, $labelMode = '', $position = 100, $idArea = 0, $areaOnly = false) {
		$idArea		= intval($idArea);

		$requestVars= TodoyuRequest::getCurrentRequestVars();
		$area		= intval($requestVars['area']);

			// Register creation type global in all areas or in general of current area if its the primary one
		if ( ! $areaOnly || $area === $idArea ) {
			$engineConfig	= self::getEngineConfig($ext, $type, $labelMode, $position);
			$GLOBALS['CONFIG']['create']['engines']['all'][$type] = $engineConfig;
		}

			// Register as primary displayed creation type in resp. area
		if ( $idArea !== 0 ) {
	//		if ( AREA === $idArea ) {
	// 		@todo	check + fix to use AREA constant

			if ( $area === $idArea ) {
				$engineConfig	= self::getEngineConfig($ext, $type, $labelMode, $position);

				$GLOBALS['CONFIG']['create']['engines']['primary'][$type] = $engineConfig;
			}
		}
	}



	/**
	 * Get all registered create engines in correct order
	 *
	 * @param	String	$area
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