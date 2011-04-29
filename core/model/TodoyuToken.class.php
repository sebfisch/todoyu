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
 * Server informations
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuToken extends TodoyuBaseObject {

	/**
	 * Constructor
	 *
	 * @param	Integer		$idToken
	 */
	public function __construct($idToken) {
		parent::__construct($idToken, 'system_token');
	}



	/**
	 * Get token ext ID
	 *
	 * @return	Integer
	 */
	public function getExtID() {
		return intval($this->data['ext']);
	}



	/**
	 * Get token type ID
	 *
	 * @return	Integer
	 */
	public function getType() {
		return intval($this->data['token_type']);
	}



	/**
	 * Get token hash
	 *
	 * @return	String
	 */
	public function getHash() {
		return $this->data['hash'];
	}

}

?>