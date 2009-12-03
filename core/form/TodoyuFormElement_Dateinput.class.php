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
		$value	= $this->getValue();

		if( $value == 0 ) {
			$data['default']	= '';
		} else {
			$data['default']	= TodoyuTime::format($value, 'date');
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


		$script	= '<script>Calendar.setup({' . implode(',', $jsConf) . '});</script>';

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
		if( ! is_numeric($value) ) {
			$value	= TodoyuTime::parseDate($value);
		}

		parent::setValue($value);
	}



	/**
	 * Get storage data
	 * Format as MYSQL-date if flag 'storeAsDate' is set in form
	 *
	 * @return	Mixed		Integer or string
	 */
	public function getStorageData() {
		$storageData	= parent::getStorageData();

		if( $storageData !== false && $this->hasAttribute('storeAsDate') ) {
			$storageData = date('Y-m-d', $this->getValue());
		}

		return $storageData;
	}

}

?>