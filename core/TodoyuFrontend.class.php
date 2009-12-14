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
 * Helper class to configure rendering of a full page frontend
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuFrontend {

	/**
	 * Get active tab
	 *
	 * @return	String
	 */
	public static function getActiveTab() {
		$tab	= TodoyuPreferenceManager::getPreference(0, 'tab');

		if( $tab === false ) {
			$tab = self::getDefaultTab();
		}

		return $tab;
	}



	/**
	 * Get active tab submenu tab
	 *
	 * @param	String		$parentTab
	 * @return	String
	 */
	public static function getActiveSubmenuTab($parentTab) {
		$tab	= TodoyuPreferenceManager::getPreference(0, 'tabSubmenu_' . $parentTab);

		if( $tab === false ) {
			$tab = self::getDefaultTab();
		}

		return $tab;
	}



	/**
	 * Set active tab (and save in preferences)
	 *
	 * @param	String		$activeTab
	 */
	public static function setActiveTab($activeTab) {
		TodoyuPreferenceManager::savePreference(0, 'tab', $activeTab, 0, true);
	}



	/**
	 * Set active tab (and save in preferences)
	 *
	 * @param	String		$activeTab
	 * @param	String		$parentTab
	 */
	public static function setActiveSubmenuTab($parentTab, $activeTab) {
		$idUser = userid();

		TodoyuPreferenceManager::savePreference(0, 'tabSubmenu_' . $parentTab, $activeTab, 0, true, $idUser);
	}



	/**
	 * Get default active tab. Because we remember the last tab,
	 * this is only a fallback for new users
	 *
	 * @return	String
	 */
	public static function getDefaultTab() {
		return $GLOBALS['CONFIG']['FE']['TAB']['default'];
	}



	/**
	 * Set default tab
	 *
	 * @param	String		$defaultTab
	 */
	public static function setDefaultTab($defaultTab) {
		$GLOBALS['CONFIG']['FE']['TAB']['default'] = $defaultTab;
	}



	/**
	 * Add a new tab to the configuration
	 *
	 * @param	String		$key
	 * @param	String		$label
	 * @param	String		$href
	 * @param	Integer		$position
	 */
	public static function addMenuEntry($key, $label, $href, $position = 50) {
		if( ! is_array($GLOBALS['CONFIG']['FE']['NAVI']['entries'][$key]) ) {
			$GLOBALS['CONFIG']['FE']['NAVI']['entries'][$key] = array();
		}

		$GLOBALS['CONFIG']['FE']['NAVI']['entries'][$key]['key']		= $key;
		$GLOBALS['CONFIG']['FE']['NAVI']['entries'][$key]['label']		= $label;
		$GLOBALS['CONFIG']['FE']['NAVI']['entries'][$key]['href']		= $href;
		$GLOBALS['CONFIG']['FE']['NAVI']['entries'][$key]['position']	= $position;
	}



	/**
	 * Add a submenu tab
	 *
	 * @param	String		$parentKey
	 * @param	String		$key
	 * @param	String		$label
	 * @param	String		$href
	 * @param	Integer		$position
	 * @param	String		$type
	 */
	public static function addSubmenuEntry($parentKey, $key, $label, $href, $position = 50, $type = '') {
		$GLOBALS['CONFIG']['FE']['NAVI']['entries'][$parentKey]['submenu'][] = array(
			'key'		=> $key,
			'label'		=> TodoyuDiv::getLabel($label),
			'href'		=> $href,
			'position'	=> $position,
			'type'		=> $type,
		);
	}



	/**
	 * Get submenu tabs
	 *
	 * @param	String		$parentKey
	 * @return	Array
	 */
	public static function getSubmenuTabs($parentKey) {
		$submenu	= $GLOBALS['CONFIG']['FE']['TAB']['tabs'][$parentKey]['submenu'];
		$active		= self::getActiveSubmenuTab($parentKey);

		if( is_array($submenu) ) {
			foreach($submenu as $key => $vals) {
				if ($vals['key'] == $active) {
					$submenu[$key]['active'] = true;
				}
			}
			$submenu = TodoyuArray::sortByLabel($submenu, 'position');
		} else {
			$submenu = array();
		}

		return $submenu;
	}



	/**
	 * Get configured tabs with parsed labels and sorted by position
	 *
	 * @return	Array
	 */
	public static function getMenuEntries() {
		$tabs	= $GLOBALS['CONFIG']['FE']['NAVI']['entries'];

		$active	= self::getActiveTab();

		if( array_key_exists($active, $tabs) ) {
			$tabs[$active]['active'] = true;
		}

			// Get label for menu entry and sort submenus
		foreach($tabs as $index => $tab) {
			$tabs[$index]['label'] = TodoyuDiv::getLabel($tabs[$index]['label']);

			if( $tabs[$index]['submenu'] ) {
					// sort by 'position', remove duplicate entries
				$tabs[$index]['submenu'] = TodoyuArray::sortByLabel($tabs[$index]['submenu'], 'position', false, false, false, SORT_REGULAR, 'href');
			}
		}

		$tabs = TodoyuArray::sortByLabel($tabs, 'position');

		return $tabs;
	}


	public static function setDefaultView($ext, $controller) {
		$GLOBALS['CONFIG']['FE']['DEFAULT'] = array(
			'ext'		=> $ext,
			'controller'=> $controller
		);
	}

}


?>