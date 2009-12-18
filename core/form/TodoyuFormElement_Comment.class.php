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
 * FormElement: Comment
 *
 * Comment without a form element, just text
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_Comment extends TodoyuFormElement {

	/**
	 * TodoyuFormElement comment constructor
	 *
	 * @param	String		$name
	 * @param 	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		parent::__construct('comment', $name, $fieldset, $config);
	}



	/**
	 * Init comment
	 * Parse value as locallang
	 *
	 */
	protected function init() {
		$this->setValue($this->getValue());
	}



	/**
	 * Set value
	 * Parse value as locallang
	 *
	 * @param	String		$value		Locallang reference or just a string
	 */
	public function setValue($value) {
		$value	= TodoyuDiv::getLabel($value);

		parent::setValue($value);
	}

}

?>