<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * Panel widget manager
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuPanelWidgetManager {

	/**
	 * Panel widget storage
	 *
	 * @var	Array
	 */
	private static $panelWidgets = array();



	/**
	 * Get default panel widgets defined in the config array
	 *
	 * @param	String		$ext		Extension key
	 * @return	Array
	 */
	public static function getAllPanelWidgets($ext) {
		$widgets	= Todoyu::$CONFIG['EXT'][$ext]['panelWidgets'];

		if( is_array($widgets) ) {
			$widgets = TodoyuArray::sortByLabel($widgets, 'position');
		} else {
			$widgets = array();
		}

		return $widgets;
	}



	/**
	 * Add a panel widget for all users
	 *
	 * @param	String		$ext			Extension key
	 * @param	String		$widget			Widget class
	 * @param	Integer		$position		Widget position
	 * @param	Array		$config
	 */
	public static function addPanelWidget($area, $ext, $widget, $position = 100, array $config = array()) {
		Todoyu::$CONFIG['EXT'][$area]['panelWidgets'][] = array(
			'ext'		=> $ext,
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
	public static function getPanelWidget($extension, $widgetName, $idArea = 0, array $params = array(), array $config = array()) {
		$widgetClassName= self::getPanelWidgetClassName($extension, $widgetName);

		if( ! array_key_exists($widgetClassName, self::$panelWidgets) ) {
			self::$panelWidgets[$widgetClassName] = new $widgetClassName($config, $params, $idArea);
		}

		return self::$panelWidgets[$widgetClassName];
	}



	/**
	 * Get class name for panel widget
	 *
	 * @param	String		$ext
	 * @param	String		$name
	 * @return	String
	 */
	public static function getPanelWidgetClassName($ext, $name) {
		return 'Todoyu' . ucfirst(strtolower($ext)) . 'PanelWidget' . ucfirst($name);
	}



	/**
	 * Save collapsed status
	 *
	 * @param	String		$widget
	 * @param	Bool		$expaded
	 */
	public static function saveCollapsedStatus($widget, $expanded = true) {
		$preference	= 'pwidget-collapsed-' . strtolower($widget);

		if( $expanded ) {
			TodoyuPreferenceManager::deletePreference(0, $preference, null, 0, AREA);
		} else {
			TodoyuPreferenceManager::savePreference(0, $preference, 1, 0, false, AREA);
		}
	}



	/**
	 * Check if a panelwidget is collapsed
	 *
	 * @param	String		$widget
	 * @return	Boolean
	 */
	public static function isCollapsed($widget) {
		$pref	= TodoyuPreferenceManager::getPreference(0, 'pwidget-collapsed-' . $widget, 0, AREA);

		return intval($pref) === 1;
	}
}

?>