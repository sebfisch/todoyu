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
 * [Enter Class Description]
 * 
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuCountry extends TodoyuBaseObject {

	/**
	 * Constructor
	 *
	 * @param	Integer		$idCountry
	 */
	public function __construct($idCountry) {
		parent::__construct($idCountry, 'static_country');		
	}



	/**
	 * Get phone code of country
	 *
	 * @return	String
	 */
	public function getPhoneCode() {
		return $this->get('phone');
	}



	/**
	 * Get ISO alpha2 code of country
	 *
	 * @return	String
	 */
	public function getCode2() {
		return $this->get('iso_alpha2');
	}



	/**
	 * Get ISO alpha2 code of country
	 *
	 * @return	String
	 */
	public function getCode3() {
		return $this->get('iso_alpha3');
	}



	/**
	 * Get ISO number of country
	 *
	 * @return	String
	 */
	public function getIsoNum() {
		return $this->get('iso_num');
	}



	/**
	 * Get currency of country
	 *
	 * @return		TodoyuCurrency
	 */
	public function getCurrency() {
		$field	= 'id';
		$table	= 'static_currency';
		$where	= 'iso_num = ' . $this->getIsoNum();

		$idCurrency	= Todoyu::db()->getFieldValue($field, $table, $where);

		return TodoyuCurrencyManager::getCurrency($idCurrency);
	}



	/**
	 * Get country label
	 *
	 * @return	String
	 */
	public function getLabel() {
		return TodoyuLanguage::getLabel('static_country.' . $this->getCode3());
	}

}

?>