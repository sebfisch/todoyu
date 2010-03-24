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
 * Panel widget manager
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuPanelWidgetManager {

	/**
	 * Data table
	 *
	 */
	const TABLE = 'system_panelwidget';



	/**
	 * Get default panel widgets defined in the config array
	 *
	 * @param	String		$ext		Extension key
	 * @return	Array
	 */
	public static function getDefaultPanelWidgets($ext) {
		$widgets	= Todoyu::$CONFIG['EXT'][$ext]['panelWidgets'];

		if( is_array($widgets) ) {
			$widgets = TodoyuArray::sortByLabel($widgets, 'position');
		} else {
			$widgets = array();
		}

		return $widgets;
	}



	/**
	 * Get config of given widget of given extension, optionally gets value of specific config entry only
	 *
	 * @param	String	$ext
	 * @param	String	$widgetName
	 * @param	String	$configKey
	 * @return	Mixed
	 */
	public static function getDefaultPanelWidgetConfig($ext, $widgetName, $configKey = '') {
		$panelWidgets	= self::getDefaultPanelWidgets($ext);

		foreach($panelWidgets as $widgetData) {
			if ($widgetData['widget'] == $widgetName) {
				if ($configKey == '') {
					return $widgetData['config'];
				} else {
					foreach($widgetData['config'] as $key => $value) {
						if ($key == $configKey) {
							return $value;
						}
					}
				}
				break;
			}
		}

		return false;
	}



	/**
	 * Get user panel widgets from database
	 *
	 * @param	String		$ext		Extension key
	 * @return	Array
	 */
	public static function getUserPanelWidgets($ext) {
		$idUser		= TodoyuAuth::getPersonID();
		$extID 		= TodoyuExtensions::getExtID($ext);

		$fields		= 'widget, position, config';
		$table		= self::TABLE;
		$where		= '	id_person		= ' . $idUser . ' AND
						ext			= ' . $extID;
		$order		= 'position';

		$pWidgets	= Todoyu::db()->getArray($fields, $table, $where, '', $order);

		foreach($pWidgets as $index => $pWidget) {
			if( $pWidgets[$index]['config'] === '' ) {
				$pWidgets[$index]['config'] = array();
			} else {
				$pWidgets[$index]['config'] = unserialize($pWidgets[$index]['config']);
			}
		}

		return $pWidgets;
	}



	/**
	 * Add a panel widget for a user
	 *
	 * @param	String		$ext			Extension key
	 * @param	String		$widget			Widget class
	 * @param	Integer		$position		Widget position
	 * @param	Array		$config
	 * @return	Integer
	 */
	public static function addUserPanelWidget($ext, $widget, $position = 100, array $config = array()) {
		$idUser	= TodoyuAuth::getPersonID();
		$extID 	= TodoyuExtensions::getExtID($ext);
		$sorting= intval($sorting);

		$data	= array(
			'id_person'	=> $idUser,
			'ext'		=> $extID,
			'widget'	=> $widget,
			'position'	=> $position,
			'config'	=> serialize($config)
		);

		return TodoyuRecordManager::addRecord(self::TABLE, $data);
	}



	/**
	 * Add a panel widget for all users
	 *
	 * @param	String		$ext			Extension key
	 * @param	String		$widget			Widget class
	 * @param	Integer		$position		Widget position
	 * @param	Array		$config
	 */
	public static function addDefaultPanelWidget($ext, $widget, $position = 100, array $config = array()) {
		Todoyu::$CONFIG['EXT'][$ext]['panelWidgets'][] = array(
			'widget'	=> $widget,
			'position'	=> intval($position),
			'config'	=> $config
		);
	}



	/**
	 * Create a new panel widget with given params
	 *
	 * @param	String		$widgetClassName
	 * @param	Integer		$area
	 * @param	Array		$params
	 * @return	TodoyuPanelWidget
	 */
	public static function getPanelWidget($widgetName, $idArea = 0, array $params = array()) {
		$widgetClassName= 'TodoyuPanelWidget' . $widgetName;
		$config			= array();

		return new $widgetClassName($config, $params, $idArea);
	}



	/**
	 * Get panel widget extension
	 *
	 * @param	String	$widgetClass
	 * @return	String
	 */
	public static function getPanelWidgetExtension($widgetClass) {
		return Todoyu::$CONFIG['PANELWIDGETS'][$widgetClass]['ext'];
	}



	/**
	 * Get list of available panel widgets (Todoyu::$CONFIG['PANELWIDGETS'])
	 *
	 * @return unknown
	 */
	public static function getAvailablePanelWidgets() {
		return Todoyu::$CONFIG['PANELWIDGETS'];
	}



	/**
	 * Save collapsed status
	 *
	 * @param	Integer	$extID
	 * @param	String	$widget
	 * @param	String	$expaded
	 */
	public static function saveCollapsedStatus($idExt, $idWidget, $status) {
		$idExt	= intval($idExt);
		$widget	= 'pwidget-'.strtolower($idWidget);

		$collapsed	= trim($status) === 'collapse' ? 1 : 0;

		TodoyuPreferenceManager::savePreference($idExt, $widget, $collapsed, 0, true, 0, personid());
	}



	/**
	 * Loads the collapsed status of a panelwidget
	 *
	 * @param	Integer	$extID
	 * @param	String	$widget
	 * @return	Boolean
	 */
	public static function loadCollapsedStatus($idExt, $idWidget)	{
		$idExt	= TodoyuExtensions::getExtID($idExt);
		$widget	= 'pwidget-' . strtolower($idWidget);

		$collapsed	= TodoyuPreferenceManager::getPreference($idExt, $widget);

		return intval($collapsed) === 1;
	}
}


?>