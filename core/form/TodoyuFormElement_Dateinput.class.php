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
 * FormElement: Dateselector
 *
 * Input field with calendar popup to select date
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_Dateinput extends TodoyuFormElement {

	/**
	 * Constructor
	 *
	 * @param	String		$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array $config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		parent::__construct('dateinput', $name, $fieldset, $config);
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
	 * Generate javaScript code for calendar
	 *
	 * @return	String
	 */
	private function getJsSetup() {
		$calConf	= array(
			'inputField'	=> '"' . $this->getHtmlID() . '"',
			'range'			=> '[1990,2020]',
			'ifFormat'		=> '"' . TodoyuTime::getFormat('date') . '"',
			'align'			=> '"br"',
			'button'		=> '"' . $this->getHtmlID() . '-calicon"',
			'firstDay'		=> 1
		);

		$custom	= is_array($this->config['calendar']) ? $this->config['calendar'] : array();
		$config	= array_merge($calConf, $custom);

		$jsConf	= array();

		foreach($config as $key => $value) {
			$jsConf[] = $key . ' : ' . $value;
		}

		$script	= TodoyuString::wrapScript('Calendar.setup({' . implode(',', $jsConf) . '});');

		return $script;
	}



	/**
	 * Set field value
	 * Can be timestamp, date or mysql date format
	 * Formats: 1262214000, 31.12.2009 (locale), 2009-12-31
	 *
	 * @param	Mixed		$value
	 */
	public function setValue($value) {
			// Check for "no-data" values
		if( $value === false || intval($value) === 0 || trim($value) == '' || trim($value) == '0000-00-00')	 {
			$value = false;
		} elseif( ! is_numeric($value) ) {
			$value	= TodoyuTime::parseDate($value);
		}

		parent::setValue($value);
	}



	/**
	 * Get formatted template value (datetime)
	 *
	 * @return	String
	 */
	public function getValueForTemplate() {
		$value	= $this->getValue();

		return $value === false ? '' : TodoyuTime::format($value, 'date');
	}



	/**
	 * Get storage data
	 * Format as MYSQL-date if flag 'storeAsDate' is set in form
	 *
	 * @return	Mixed		Integer or String
	 */
	public function getStorageData() {
			// Check for no storage flag
		if( $this->isNoStorageField() ) {
			return false;
		} else {
			$storageData= $this->getValue();
			
				// If storeAsDate, format in mysql date format
			if( $this->hasAttribute('storeAsDate') ) {
					// Set to zero if no data entered
				if( $storageData === false ) {
					$storageData = '0000-00-00';
				} else {
					$storageData = date('Y-m-d', $storageData);
				}
			} else {
				$storageData	= intval($storageData);
			}
		}

		return $storageData;
	}

}

?>