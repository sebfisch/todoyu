<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * Mail Receiver Object
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuMailReceiver implements TodoyuMailReceiverInterface {

	/**
	 * @var	String
	 */
	public $name;

	/**
	 * @var	String
	 */
	public $address;



	/**
	 * Construct object
	 *
	 * @param	Integer		$idPerson
	 */
	public function __construct($idPerson) {
		$record	= TodoyuContactPersonManager::getPerson($idPerson);
		$this->init($record->getFullName(), $record->getEmail());
	}



	/**
	 * Init - set properties: name, email address
	 *
	 * @param	String	$name
	 * @param	String	$address
	 */
	public function init($name, $address) {
		$this->name		= $name;
		$this->address	= $address;
	}



	/**
	 * Get person name of IMAP address
	 *
	 * @return	String
	 */
	public function getName() {
		return $this->name;
	}



	/**
	 * Get email address of IMAP address
	 *
	 * @return	String
	 */
	public function getAddress() {
		return $this->address;
	}

}

?>