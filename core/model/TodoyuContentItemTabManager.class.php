<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * Manager for tabs of content items (project, task, container, ...)
 *
 * @package		Todoyu
 * @subpackage	Project
 */
class TodoyuContentItemTabManager {

	/**
	 * Installed tabs for tasks
	 *
	 * @var	Array
	 */
	private static $tabs = array();



	/**
	 * Register an items tab
	 *
	 * @param	String		$extKey					Extension that originally implements the item
	 * @param	String		$itemKey				e.g. 'project' / 'task' / 'container' ...
	 * @param	String		$tabKey					Tab identifier
	 * @param	String		$labelFunction			Function which renders the label or just a label string
	 * @param	String		$contentFunction		Function which renders the content
	 * @param	Integer		$position
	 */
	public static function registerTab($extKey, $itemKey, $tabKey, $labelFunction, $contentFunction, $position = 100) {
		Todoyu::$CONFIG['EXT'][$extKey][$itemKey]['tabs'][$tabKey] = array(
			'id'		=> $tabKey,
			'label'		=> $labelFunction,
			'position'	=> intval($position),
			'content'	=> $contentFunction
		);
	}



	/**
	 * Get project detail tabs config array
	 *
	 * @param	String		$extKey         Extension that originally implements the item
	 * @param	String		$itemKey		'project' / 'task' / ...
	 * @param	Integer		$idItem
	 * @param	Boolean		$evalLabel		If true, all labels with a function reference will be parsed
	 * @param	Boolean		$noCache		Don't cache tabs
	 * @return	Array
	 */
	public static function getTabs($extKey, $itemKey, $idItem, $evalLabel = true, $noCache = false) {
		if( is_null(self::$tabs[$itemKey]) ) {
			$tabs	= TodoyuArray::assure(Todoyu::$CONFIG['EXT'][$extKey][$itemKey]['tabs']);
			self::$tabs[$itemKey] = TodoyuArray::sortByLabel($tabs);
		}

		$tabs = self::$tabs[$itemKey];

		if( $evalLabel ) {
			foreach($tabs as $index => $tab) {
				$labelFunc				= $tab['label'];
				$tabs[$index]['label']	= TodoyuFunction::callUserFunction($labelFunc, $idItem);
			}
		}

			// No cache = remove
		if( $noCache ) {
			unset(self::$tabs[$itemKey]);
		}

		return $tabs;
	}



	/**
	 * Get a project detail tab configuration
	 *
	 * @param	String		$extKey		Extension that originally implements the item
	 * @param	String		$itemKey
	 * @param	String		$tabKey
	 * @return	Array
	 */
	public static function getTabConfig($extKey, $itemKey, $tabKey) {
		return Todoyu::$CONFIG['EXT'][$extKey][$itemKey]['tabs'][$tabKey];
	}



	/**
	 * Get the tab which is active by default (if no preference is stored)
	 *
	 * @param	String		$extKey		Extension that originally implements the item
	 * @param	String		$itemKey
	 * @param	Integer		$idItem
	 * @param	Boolean		$noCache
	 * @return	String
	 */
	public static function getDefaultTab($extKey, $itemKey, $idItem, $noCache = false) {
		$tabs	= self::getTabs($extKey, $itemKey, $idItem, false, $noCache);
		$first	= array_shift($tabs);

		return $first['id'];
	}

}

?>