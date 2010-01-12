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
 * Form hook manager
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuFormHook {

	/**
	 * Registered hooks for forms
	 *
	 * @var	Array
	 */
	public static $hooks = array(
		'buildForm' => array(),
		'loadData'	=> array(),
		'saveData'	=> array()
	);



	/**
	 * Get registered hooks of a type for a form
	 *
	 * @param	String		$type
	 * @param	String		$xmlPath
	 * @return	Array		List of
	 */
	private static function getHooks($type, $xmlPath) {
		$xmlPath= TodoyuFileManager::pathWeb($xmlPath);
		$hooks	= self::$hooks[$type][$xmlPath];

		if( ! is_array($hooks) ) {
			$hooks = array();
		} else {
			$hooks = TodoyuArray::sortByLabel($hooks);
		}

		return $hooks;
	}



	/**
	 * Call hooks for form building (adding fields)
	 *
	 * @param	String		$xmlPath		Path to main XML form file
	 * @param	TodoyuForm		$form			Form object to modify
	 * @param	Integer		$idRecord		ID of the main record
	 * @return	TodoyuForm		Modified form object
	 */
	public static function callBuildForm($xmlPath, TodoyuForm $form, $idRecord) {
		$idRecord	= intval($idRecord);

		$hooks		= self::getHooks('buildForm', $xmlPath);

		foreach($hooks as $hook) {
			$method	= explode('::', $hook['funcRef']);
			$temp	= call_user_func($method, $form, $idRecord);

			if( $temp instanceof Form ) {
				$form = $temp;
			}
		}

		return $form;
	}



	/**
	 * Call hooks to modify HTML of contained fields of type "databaseRelation"
	 *
	 * @param	String		$xmlPath			Path to main XML form file
	 * @param	String		$fieldHTML			HTML (field(s)) to be modified
	 * @param	Array		$data				data array
	 * @return	String
	 */
	public static function callDatabaseRelationFieldModifier($xmlPath, $fieldHTML, $data) {
		$hooks		= self::getHooks('buildFormDatabaseRelation', $xmlPath);

		foreach($hooks as $hook) {
			$method	= explode('::', $hook['funcRef']);
			$fieldHTML	= call_user_func($method, $fieldHTML, $data);
		}

		return $fieldHTML;
	}



	/**
	 * Call hooks to add extra data
	 *
	 * @param	String		$xmlPath		Path to main XML form file
	 * @param	Array		$data			Record data array
	 * @param	Integer		$idRecord		Record ID
	 * @return	Array
	 */
	public static function callLoadData($xmlPath, array $data, $idRecord = 0) {
		$idRecord	= intval($idRecord);
		$hooks		= self::getHooks('loadData', $xmlPath);

		foreach($hooks as $hook) {
			$data	= TodoyuDiv::callUserFunction($hook['funcRef'], $data, $idRecord);
		}

		return $data;
	}



	/**
	 * Call hooks to save submitted form data (which is not saved by the main function)
	 * Registered functions have to modify the data array and remove their custom fields
	 *
	 * @param	String		$xmlPath		Path to the form XML file
	 * @param	Array		$data			Record data array
	 * @param	Integer		$idRecord		Record ID
	 * @return	Array
	 */
	public static function callSaveData($xmlPath, array $data, $idRecord) {
		$idRecord	= intval($idRecord);
		$hooks		= self::getHooks('saveData', $xmlPath);

		foreach($hooks as $hook) {
			$temp	= TodoyuDiv::callUserFunction($hook['funcRef'], $data, $idRecord);

			if( is_array($temp) ) {
				$data = $temp;
			}
		}

		return $data;
	}



	/**
	 * Register callback function for a type of form event
	 *
	 * @param	String		$type			Type of the registered function
	 * @param	String		$xmlPath		Path to the form XML file
	 * @param	String		$funcRef		Function reference
	 * @param	Integer		$position		Order of the callback function calls
	 */
	public static function register($type, $xmlPath, $funcRef, $position = 100) {
		$xmlPath	= TodoyuFileManager::pathWeb( $xmlPath );

		self::$hooks[ $type ][ $xmlPath ][] = array(
			'funcRef'	=> $funcRef,
			'position'	=> intval($position)
		);
	}



	/**
	 * Register (hook-in) a buildForm function
	 * Modify the form object before rendering
	 *
	 * @param	String		$xmlPath		Path to the form XML file
	 * @param	String		$funcRef		Function reference
	 * @param	Integer		$position		Order of the callback function calls
	 */
	public static function registerBuildForm($xmlPath, $funcRef, $position = 100) {
		self::register('buildForm', $xmlPath, $funcRef, $position);
	}



	/**
	 * Register (hook-in) a modification function for fields of type "DatabaseRelation"
	 *
	 * @param	String	$xmlPath
	 * @param	Array	$funcref
	 * @param	Integer	$position
	 */
	public static function registerDatabaseRelationFieldModifier($xmlPath, $funcref, $position = 100) {
		self::register('buildFormDatabaseRelation', $xmlPath, $funcref, $position);
	}



	/**
	 * Register a loadData function
	 * Load special data into the data array for custom added fields
	 *
	 * @param	String		$xmlPath		Path to the form XML file
	 * @param	String		$funcRef		Function reference
	 * @param	Integer		$position		Order of the callback function calls
	 */
	public static function registerLoadData($xmlPath, $funcRef, $position = 100) {
		self::register('loadData', $xmlPath, $funcRef, $position);
	}



	/**
	 * Register a saveData function
	 * Store special fields before saving basic record
	 *
	 * @param	String		$xmlPath		Path to the form XML file
	 * @param	String		$funcRef		Function reference
	 * @param	Integer		$position		Order of the callback function calls
	 */
	public static function registerSaveData($xmlPath, $funcRef, $position = 100) {
		self::register('saveData', $xmlPath, $funcRef, $position);
	}

}

?>