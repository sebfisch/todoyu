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
 * FormElement: Dateselector
 *
 * Single line textinput, <input type="text"> <dateselector>
 *
 * @package		Todoyu
 * @subpackage	Form
 */

class TodoyuFormElement_DateTimeInput extends TodoyuFormElement {

	/**
	 * Constructs a date time input form field
	 *
	 * @param	String		$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		parent::__construct('datetimeinput', $name, $fieldset, $config);
	}



	/**
	 * Get field data. Convert timestamps into text dates and generate js code
	 *
	 * @return	Array
	 */
	public function getData() {
		$value	= $this->getValue();
		$data	= parent::getData();

		if( is_numeric($value) ) {
			if( $value == 0 ) {
				$data['default'] = '';
			} else {
				$data['default'] = TodoyuTime::format($value, 'datetime');
			}
		}

		$data['jsSetup'] = $this->getJsSetup();

		return $data;
	}



	/**
	 * Generate javascript code for calendar
	 *
	 * @return	String
	 */
	private function getJsSetup() {
		$calConf	= array(
			'inputField'	=> '"' . $this->getHtmlID() . '"',
			'range'			=> '[1990,2020]',
			'ifFormat'		=> '"' . TodoyuTime::getFormat('datetime') . '"',
			'align'			=> '"br"',
			'button'		=> '"' . $this->getHtmlID() . '-calicon"',
			'firstDay'		=> 1,
			'showsTime'		=> 'true'
		);

		$custom	= is_array($this->config['calendar']) ? $this->config['calendar'] : array();
		$config	= array_merge($calConf, $custom);

		$jsConf	= array();

		foreach($config as $key => $value) {
			$jsConf[] = $key . ' : ' . $value;
		}

		$script	= '<script>Calendar.setup({' . implode(',', $jsConf) . '});</script>';

		return $script;




//
//		return '<script>
//				Calendar.setup({
//				 inputField : "' . $this->getHtmlID() . '", // id of the input field
//				 range : [1990, 2020], // allowed years
//				 ifFormat : "' . TodoyuTime::getFormat('datetime') . '", // format of the input field
//				 align: "br",
//				 button : "' . $this->getHtmlID() . '-calicon", // trigger for the calendar (button ID)
//				 firstDay : 1,
//				 showsTime: true
//				 });</script>';
	}



	/**
	 * Set value
	 * If its not already a timestamp, parse it
	 *
	 * @param	Mixed		$value			Integer or string in datetime format
	 */
	public function setValue($value) {
		if( ! is_numeric($value) ) {
			$value = TodoyuTime::parseDateTime($value);
		}

		parent::setValue($value);
	}



	/**
	 * Get value to save in the database
	 *
	 * @return	String
	 */
	public function getStorageData() {
		return $this->getValue();
	}

}

?>