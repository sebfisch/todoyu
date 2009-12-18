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
 * FormElement: Textinput
 *
 * Single line textinput, <input type="text">
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_Timeinput extends TodoyuFormElement_Textinput {

	/**
	 * Initialize timeinput field
	 *
	 * @param	String		$name
	 * @param 	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		TodoyuFormElement::__construct('timeinput', $name, $fieldset, $config);
	}



	/**
	 * Init basic values
	 *
	 */
	protected function init() {
		$this->setType('text');
	}



	/**
	 * Set field value (seconds)
	 *
	 * @param	Mixed		$value
	 */
	public function setValue($value) {
		if( is_numeric($value) ) {
			$value	= intval($value);
		} else {
			$value	= TodoyuTime::parseTime($value, false);
		}

		parent::setValue($value);
	}



	/**
	 * Get value of the timeinput field (as numeric seconds value)
	 * @return	Integer		Seconds
	 */
	public function getValue() {
		return intval(parent::getValue());
	}



	/**
	 * Get formatted value for template
	 *
	 * @return	String
	 */
	public function getValueForTemplate() {
		$value = $this->getValue();

		return $value === 0 ? '' : TodoyuTime::formatTime($value);
	}



	/**
	 * Get storage data
	 *
	 * @return	Integer
	 */
	public function getStorageData() {
		return intval($this->getValue());
	}

}

?>