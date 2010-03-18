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
class TodoyuFormElement_Textinput extends TodoyuFormElement {

	/**
	 * TodoyuFormElement textinput constructor
	 *
	 * @param	String		$name
	 * @param 	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		parent::__construct('textinput', $name, $fieldset, $config);
	}



	/**
	 * Initialize form element
	 *
	 */
	protected function init() {
		if( ! $this->hasAttribute('type') ) {
			$this->setInputType('text');
		}

			// Add password info
		if( $this->getInputType() === 'password' ) {
			if( isset($this->config['validate']['goodPassword']) ) {
				$validator	= new TodoyuPasswordValidator();
				$validator->validate('');
				$text		= implode('<br />', $validator->getErrors());

				$this->setAfterFieldText($text);
			}
		}
	}



	/**
	 * Set type attribute
	 *
	 * @param	String		$type
	 */
	public function setInputType($type) {
		$this->setAttribute('type', $type);
	}


	public function getInputType() {
		return $this->getAttribute('type');
	}



	/**
	 * Get field storage data
	 *
	 * @return	String
	 */
	public function getStorageData() {
		return $this->getValue();
	}



	/**
	 * Get value for template (hide password)
	 *
	 * @return	String
	 */
	public function getValueForTemplate() {
		return $this->getAttribute('type') === 'password' ? '' : parent::getValueForTemplate();
	}

}

?>