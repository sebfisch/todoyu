<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * Panel widget renderer
 * Collects the registered panel widgets and renders them
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuPanelWidgetRenderer {

	/**
	 * Render panel widgets for an area
	 *
	 * @param	String		$extKey		Extension key
	 * @param	Array		$params		Custom parameters for current area
	 * @return	String
	 */
	public static function renderPanelWidgets($extKey, array $params = array()) {
		TodoyuExtensions::loadAllPanelWidget();

		$content	= '';

		// Render default widgets from config
		$content	.= self::renderDefaultPanelWidgets($extKey, $params);

		// Render user defined widgets from database
//		$content	.= self::renderUserPanelWidgets($extKey, $params);

		return $content;
	}



	/**
	 * Render a single panel widget
	 *
	 * @param	String		$widget
	 * @param	Array		$params
	 * @param	Integer		$idArea
	 * @return	String
	 */
	public static function renderPanelWidget($widget, array $params = array(), $idArea = 0) {
		$config	= array(); // How we get the config?

		$widget	= new $widget($config, $params, $idArea);

		return $widget->render();
	}







	/**
	 * Render default widgets from config
	 *
	 * @param	String		$extKey		Extension key
	 * @param	Array		$params		Custom parameters for current area
	 * @return	String
	 */
	private static function renderDefaultPanelWidgets($extKey, array $params = array()) {
		$panelWidgets	= TodoyuPanelWidgetManager::getDefaultPanelWidgets($extKey);

		return self::render($extKey, $panelWidgets, $params);
	}



	/**
	 * Render user defined widgets from database
	 *
	 * @param	String		$extKey		Extension key
	 * @param	Array		$params		Custom parameters for current area
	 * @return	String
	 */
	private static function renderUserPanelWidgets($extKey, array $params = array()) {
		$panelWidgets	= TodoyuPanelWidgetManager::getUserPanelWidgets($extKey);

		return self::render($extKey, $panelWidgets, $params);
	}



	/**
	 * Render panel widget by config
	 *
	 * @param	Array		$panelWidgets		PanelWidgets configurations
	 * @param	Array		$params				Custom parameters for panel widgets
	 * @return	String
	 */
	private static function render($extKey, array $panelWidgets, array $params = array()) {
		$content	= '';
		$idArea		= TodoyuExtensions::getExtID($extKey);

			// Render the widgets
		foreach($panelWidgets as $pWidgetConfig) {
			$widgetClass	= $pWidgetConfig['widget'];
			$config			= is_array($pWidgetConfig['config']) ? $pWidgetConfig['config'] : array();
			// Changed: if no array key exists expand the widget by default.

			if( class_exists($widgetClass) ) {
					// Check whether panelWidget is allowed to be displayed
				if( call_user_func(array($widgetClass, 'isAllowed') ) ) {
					$widget	= new $widgetClass($config, $params, $idArea);
					$content .= $widget->render();
				} else {
						// Widget not allowed
//					TodoyuDebug::printInFirebug('PanelWidget ' . $widgetClass . ' is not allowed');
				}
			} else {
				$debug	= 'Can\'t find requested panel widget: "' . $widgetClass . '"';
				TodoyuDebug::printHtml($debug, 'PanelWidget not found!', null, true);
				TodoyuDebug::printHtml($pWidgetConfig, 'Widget config');
			}
		}

		return $content;
	}

}

?>