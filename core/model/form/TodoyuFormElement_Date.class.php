<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
class TodoyuFormElement_Date extends TodoyuFormElement {

	/**
	 * Constructor
	 *
	 * @param	String			$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array $config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		parent::__construct('date', $name, $fieldset, $config);
		parent::setValue(false);
	}



	/**
	 * Get render data.
	 * Add jsCalendar setup code
	 *
	 * @return	Array
	 */
	public function getData() {
		$data	= parent::getData();

		$data['jsSetup'] = $this->getJsSetup();

		return $data;
	}



	/**
	 * Generate jsCalendar setup code
	 *
	 * @return	String
	 */
	protected function getJsSetup() {
		$defaultConfig	= $this->getDefaultSetupOptions();
		$customConfig	= TodoyuArray::assure($this->config['calendar']);
		$config			= array_merge($defaultConfig, $customConfig);

		$htmlID	= $this->getHtmlID();
		$format	= $this->getFormat();

			// Setup JsCal
		$jsCode	= self::getCalendarSetupJS($config);
		$jsCode.= self::getCalendarFormatSetupJS($htmlID, $format);

			// Add onchange validation observer
		$jsCode.= 'Todoyu.DateField.addValidator(\'' . $htmlID . '\', \'' . $format . '\');';

		return TodoyuString::wrapScript($jsCode);
	}




	/**
	 * Get JavaScript setup code for calendar
	 *
	 * @param	Array	$config
	 * @return	String
	 */
	public static function getCalendarSetupJS(array $config) {
		$jsConf	= array();

		foreach($config as $key => $value) {
			$jsConf[] = $key . ' : ' . $value;
		}

		return 'Calendar.setup({' . implode(',', $jsConf) . '});';
	}



	/**
	 * Get JavaScript code for setting format of calendar
	 *
	 * @param	String	$htmlID
	 * @param	String	$format
	 * @return	String
	 */
	public static function getCalendarFormatSetupJS($htmlID, $format = '') {
		if( empty($format) ) {
			$format	= TodoyuTime::getFormat('date');
		}

		return 'Todoyu.JsCalFormat["' . $htmlID . '"] = "' . $format . '";';
	}



	/**
	 * Get default init options for calendar
	 *
	 * @return	Array
	 */
	protected function getDefaultSetupOptions() {
		return self::getSetupOptions($this->getHtmlID(), $this->getFormat());
	}



	/**
	 * Get init options for calendar
	 *
	 * @param	String	$htmlID
	 * @param	String	$format
	 * @return	Array
	 */
	public static function getSetupOptions($htmlID, $format) {
		return array(
			'inputField'	=> '"' . $htmlID . '"',
			'range'			=> '[1990,2020]',
			'ifFormat'		=> '"' . $format . '"',
			'align'			=> '"br"',
			'button'		=> '"' . $htmlID . '-calicon"',
			'firstDay'		=> TodoyuSysmanagerSystemConfigManager::getFirstDayOfWeek()
		);
	}



	/**
	 * Get key to date format
	 *
	 * @return	String
	 */
	protected function getFormatKey() {
		return 'date';
	}



	/**
	 * Get date format (for strftime())
	 *
	 * @return	String
	 */
	protected function getFormat() {
		return TodoyuTime::getFormat($this->getFormatKey());
	}



	/**
	 * Parse the input value (date string)
	 *
	 * @param	String		$dateString
	 * @return	Integer
	 */
	protected function parseDate($dateString) {
		return TodoyuTime::parseDate($dateString);
	}



	/**
	 * Set field value
	 * Can be timestamp, date or MySQL date format
	 * Formats: 1262214000, 31.12.2009 (locale), 2009-12-31
	 *
	 * @param	Mixed		$value
	 */
	public function setValue($value) {
		if( is_numeric($value) ) {
			$value	= (int) $value;
		} elseif( trim($value) == '' || $value == '0000-00-00' ) {
			$value	= false;
		} elseif( $value !== false ) {
			$value	= $this->parseDate($value);
		}

			// 0 = invalid date, so false for empty field
		if( $value === 0 ) {
			$value = false;
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

		return $value === false ? '' : TodoyuTime::format($value, $this->getFormatKey());
	}



	/**
	 * Get storage data
	 * Format as MYSQL-date if flag 'storeAsDate' is set in form
	 *
	 * @return	Mixed		Integer or String
	 */
	protected function getStorageDataInternal() {
		$storageData= $this->getValue();

			// If storeAsDate, format in MySQL date format
		if( $this->hasAttribute('storeAsDate') ) {
				// Set to zero if no data entered
			if( $storageData === false ) {
				$storageData = '0000-00-00';
			} else {
				$storageData = date('Y-m-d', $storageData);
			}
		} else {
			$storageData = (int) $storageData;
		}

		return $storageData;
	}

}

?>