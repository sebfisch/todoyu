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
 * Input field with calendar popup to select date and time
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
	 * Get field data. Convert timestamps into text dates and generate JS code
	 *
	 * @return	Array
	 */
	public function getData() {
		$data	= parent::getData();

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
	 * Get value (timestamp)
	 *
	 * @return	Integer
	 */
	public function getValue() {
		return intval(parent::getValue());
	}



	/**
	 * Get formatted template value (datetime)
	 *
	 * @return	String
	 */
	public function getValueForTemplate() {
		$value	= $this->getValue();

		return $value === 0 ? '' : TodoyuTime::format($value, 'datetime');
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