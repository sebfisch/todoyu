<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
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
	 * Infos
	 *
	 * @var	Array
	 */
	private $data = array();

	/**
	 * Create a new quickinfo object
	 *
	 */
	public function __construct() {

	}



	/**
	 * Add new info
	 *
	 * @param	String		$key		Identifier and classname
	 * @param	String		$label		Labeltext
	 * @param	Integer		$position
	 */
	public function addInfo($key, $label, $position = 100) {
		$this->data[$key] = array(
			'key'		=> $key,
			'label'		=> htmlentities($label, ENT_QUOTES, 'utf-8'),
			'position'	=> intval($position)
		);
	}



	/**
	 * Remove an info by key
	 *
	 * @param	String		$key
	 */
	public function removeInfo($key) {
		unset($this->data[$key]);
	}



	/**
	 * Get current infos
	 *
	 * @return	Array
	 */
	public function getInfos() {
		return TodoyuArray::sortByLabel($this->data, 'position');
	}



	/**
	 * Get infos encoded as json
	 *
	 * @return	String
	 */
	public function getInfoJSON() {
		return json_encode($this->getInfos());
	}



	/**
	 * Print info struct json encoded
	 */
	public function printInfoJSON() {
		TodoyuHeader::sendHeaderJSON();

		echo $this->getInfoJSON();
	}



	/**
	 * Remove all infos
	 */
	public function clear() {
		$this->data = array();
	}

}

?>