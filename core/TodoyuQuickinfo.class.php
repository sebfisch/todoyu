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
 * Todoyu Quickinfo
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuQuickinfo {

	/**
	 * Element key. Mostly a record ID
	 */
	private $element = 0;

	/**
	 * Quickinfo type
	 */
	private $type;

	/**
	 * Infos
	 *
	 * @var	Array
	 */
	private $elements = array();



	/**
	 * Create a new quickinfo object
	 * Call all registered functions to let them add items
	 */
	public function __construct($type, $element) {
		$this->type		= $type;
		$this->element	= $element;

		$this->init();
	}



	/**
	 * Initialize quickinfo
	 * Load data from registered callback functions
	 */
	private function init() {
		$funcRefs	= TodoyuQuickinfoManager::getTypeFunctions($this->type);

			// Get items from all functions
		foreach($funcRefs as $funcRef) {
			TodoyuFunction::callUserFunction($funcRef['function'], $this, $this->element);
		}

			// Sort items
		$this->elements = TodoyuArray::sortByLabel($this->elements, 'position');
	}



	/**
	 * Add new info
	 *
	 * @param	String		$key		Identifier and classname
	 * @param	String		$label		Labeltext
	 * @param	Integer		$position
	 */
	public function addInfo($key, $label, $position = 100) {
		$this->elements[$key] = array(
			'key'		=> $key,
			'label'		=> str_replace("\n", '<br />', htmlentities($label, ENT_QUOTES, 'utf-8')),
			'position'	=> intval($position)
		);
	}



	/**
	 * Remove an info by key
	 *
	 * @param	String		$key
	 */
	public function removeInfo($key) {
		unset($this->elements[$key]);
	}



	/**
	 * Get current infos
	 *
	 * @return	Array
	 */
	public function getInfos() {
		return TodoyuArray::sortByLabel($this->elements, 'position');
	}



	/**
	 * Get infos encoded as json
	 *
	 * @return	String
	 */
	public function getJSON() {
		return json_encode($this->getInfos());
	}



	/**
	 * Print info struct json encoded
	 */
	public function printJSON() {
		TodoyuHeader::sendHeaderJSON();

		echo $this->getJSON();
	}



	/**
	 * Remove all infos
	 */
	public function clear() {
		$this->elements = array();
	}

}

?>