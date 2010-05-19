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



	/**
	 * Get value (text) of comment
	 * 
	 * @return String
	 */
	public function getValueForTemplate() {
		$type	= ( is_array($this->config['comment']) ) ? $this->config['comment']['@attributes']['type'] : false;

		if( $type === 'function' ) {
			$funcRef= explode('::', $this->config['comment']['function']);

			$value	= call_user_func($funcRef, $this);
		} else {
			$value =	TodoyuLanguage::getLabel($this->config['comment']);
		}

		return $value;
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