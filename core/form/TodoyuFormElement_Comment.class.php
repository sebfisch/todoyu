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
	 */
	protected function init() {

	}



	public function getValueForTemplate() {
		return TodoyuLanguage::getLabel($this->config['comment']);
	}



	/**
	 * Comment fields are never stored in the database
	 *
	 * @return	Boolean
	 */
	public function isNoStorageField() {
		return true;
	}

}

?>