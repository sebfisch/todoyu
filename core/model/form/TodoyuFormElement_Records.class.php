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
 * Select form element
 *
 * @package		Todoyu
 * @subpackage	Form
 */
abstract class TodoyuFormElement_Records extends TodoyuFormElement {

	/**
	 * Initialize
	 *
	 * @param	String			$name
	 * @param	TodoyuFormFieldset	$fieldset
	 * @param	Array			$config
	 */
	public function __construct($name, TodoyuFormFieldset $fieldset, array $config = array()) {
		parent::__construct('records', $name, $fieldset, $config);

		if( !is_array($this->config['options']) ) {
			$this->config['options'] = array();
		}
		$this->config['multiple'] = true;
	}



	/**
	 * Init
	 *
	 * @param	String		$type
	 * @param	String		$ext
	 * @param	String		$controller
	 * @param	String		$action
	 */
	protected function initRecords($type, $ext = null, $controller = null, $action = null) {
		$this->type				= 'records' . ucfirst($type);
		$this->config['type']	= $type;
		$this->config['class'] .= 'typeRecords records' . ucfirst($type);

		if( $ext ) {
			$this->config['options']['url'] = array(
				'ext'	=> $ext,
				'ctrl'	=> $controller,
				'action'=> $action
			);
		}
	}



	/**
	 * Get config options
	 *
	 * @return	Array
	 */
	protected function getOptions() {
		return TodoyuArray::assure($this->config['options']);
	}



	/**
	 * Set selected values
	 * Should be an array, but can also be a single value
	 *
	 * @param	Mixed		$value
	 */
	public function setValue($value) {
		parent::setValue($value);
	}



	/**
	 * Get selected option values as array
	 *
	 * @return	Array
	 */
	public function getValue() {
		return TodoyuArray::assure(parent::getValue());
	}



	/**
	 * Get data for template rendering
	 *
	 * @return	Array
	 */
	protected function getData() {
		$this->config['jsonOptions']= json_encode($this->getOptions());
		$this->config['records']	= $this->getRecords();

		return parent::getData();
	}


	abstract protected function getRecords();



	/**
	 * Get storage data as comma separated list (if multiple values are selected)
	 *
	 * @return	String
	 */
	protected function getStorageDataInternal() {
		return $this->getValue();
	}



	/**
	 * Validate required status
	 * The first value shall not be 0 (means please select)
	 *
	 * @return	Boolean
	 */
	public function validateRequired() {
		$firstValue	= reset($this->getValue());

		return !empty($firstValue);
	}

}

?>