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
	 *
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
		$labelFunc	= $this->config['config']['acLabelFunc'];

		if( TodoyuDiv::isFunctionReference($labelFunc) ) {
			$data['displayLabel'] = TodoyuDiv::callUserFunction($labelFunc, $this->getValue());
		}

		return $data;
	}



	/**
	 * Renders the autocompletion - suggestions
	 *
	 * parameter results:
	 *
	 * array(elementID => elementLabel)
	 *
	 * @param	Array	$results
	 * @return	String
	 */
	public static function renderAutocompletion(array $results)	{
		return TodoyuRenderer::renderAutocompleteList($results);
	}

}
?>