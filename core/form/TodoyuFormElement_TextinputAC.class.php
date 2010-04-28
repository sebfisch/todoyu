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
 * FormElement: Textinput Autocomplete
 *
 * Single line textinput, <input type="text"> with autocomplete function
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_TextinputAC extends TodoyuFormElement {

	/**
	 * Constructor
	 *
	 * @param	String		$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	function __construct($name, TodoyuFieldset $fieldset, array $config = array())	{
		parent::__construct('textinputAC', $name, $fieldset, $config);
	}



	/**
	 * Initialize form element
	 */
	protected function init()	{
		if( $this->hasAttribute('config') )	{
			$this->setAttribute('acConfigJson', json_encode($this->getAttribute('config')));
		}
	}



	/**
	 * Set form element type
	 *
	 * @param	String	$type
	 */
	public function setType($type) {
		$this->setAttribute('type', $type);
	}



	/**
	 * Set form element data
	 *
	 * @return	Array
	 */
	public function getData() {
		$data = parent::getData();

			// Check label function
		$labelFunc	= $this->config['config']['acLabel'];

		if( TodoyuFunction::isFunctionReference($labelFunc) ) {
			$data['displayLabel'] = TodoyuFunction::callUserFunction($labelFunc, $this->getValue());
		}

		return $data;
	}

}
?>