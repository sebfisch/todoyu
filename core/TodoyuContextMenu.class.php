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
 * Dynamic context menu loaded by ajax request
 * Extensions can register menu items for menu types
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuContextMenu {

	/**
	 * Type of the context menu
	 *
	 * @var	String
	 */
	private $type = '';

	/**
	 * Id of the element, the context menu is generated for (ex: Task-ID)
	 *
	 * @var	Integer
	 */
	private $idElement = 0;

	/**
	 * Items in the context menu
	 *
	 * @var	Array
	 */
	private $elements = array();



	/**
	 * Initialize context menu with id of the processed element
	 *
	 * @param	String		$type
	 * @param	Insteger	$idElement
	 */
	public function __construct($type, $idElement) {
		$this->type			= $type;
		$this->idElement	= intval($idElement);

		$this->init();
	}



	/**
	 * Initialize contextmenu with elements
	 */
	private function init() {
		TodoyuExtensions::loadAllContextMenus();

		$funcRefs	= TodoyuContextMenuManager::getTypeFunctions($this->type);

		TodoyuDebug::printInFirebug($funcRefs, $this->type);

			// Get items from all functions
		foreach($funcRefs as $funcRef) {
			$modified	=  TodoyuDiv::callUserFunction($funcRef['function'], $this->idElement, $this->elements);

			if( is_array($modified) ) {
				$this->elements	= $modified;
			}
		}

			// Sort items
		$this->elements = TodoyuArray::sortByLabel($this->elements, 'position');

			// Parse labels and jsActions
		$this->elements	= $this->parseElements($this->elements);
	}



	/**
	 * Parse elements (label and jsAction)
	 *
	 * @param	Array	$elements
	 * @return	Array
	 */
	private function parseElements(array $elements) {
		foreach($elements as $index => $element) {
				// Parse jsAction and label
			$elements[$index]['jsAction']	= $this->renderJsAction($element['jsAction']);
			$elements[$index]['label']		= $this->renderLabel($element['label']);

				// Parse recursive for submenus
			if( is_array($element['submenu']) ) {
				$elements[$index]['submenu'] = $this->parseElements($element['submenu']);
			}
		}

		return $elements;
	}



	/**
	 * Get contextmenu elements array
	 *
	 * @return	Array
	 */
	public function getElements() {
		return $this->elements;
	}


	/**
	 * Get contextmenu elements as JSON encoded string
	 *
	 * @return	String
	 */
	public function getJSON() {
		return json_encode($this->getElements());
	}



	/**
	 * Print json encoded contextmenu struct
	 *
	 */
	public function printJSON() {
		TodoyuHeader::sendHeaderJSON();

		echo $this->getJSON();
	}


	/**
	 * Replace the #ID# placeholder with the current element ID
	 *
	 * @param	String		$jsAction		JavaScript link
	 * @return	String
	 */
	private function renderJsAction($jsAction) {
		$jsAction	= str_replace('#ID#', $this->idElement,	$jsAction);

		return $jsAction;
	}



	/**
	 * Render label if there is a function reference
	 *
	 * @param	String		$label
	 * @return	String
	 */
	private function renderLabel($label) {
			// Check if there is a function reference in the label
		if( strstr($label, '::') ) {
			$label = TodoyuDiv::callUserFunction($label, $this->idElement);
		} else {
			$label	= TodoyuLanguage::getLabel($label);
		}

		return $label;
	}

}


?>