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
	 * Type key, default: 'contactperson'
	 *
	 * @var	String
	 */
	private $type;

	/**
	 * The receiver's full name
	 *
	 * @var	String
	 */
	private $name;

	/**
	 * Email address of receiver
	 *
	 * @var	String
	 */
	private $address;

	/**
	 * Receiver type (e.g. 'ext_contact_person') record ID
	 *
	 * @var	Integer
	 */
	private $idReceiver;



	/**
	 * Construct object
	 *
	 * @param	Integer		$idPerson
	 */
	public function __construct($idPerson) {
		$idPerson	= intval($idPerson);

		$record	= TodoyuContactPersonManager::getPerson($idPerson);
		$type	= 'contacterson';

		$this->init($record->getFullName(), $record->getEmail(), $idPerson, $type);
	}



	/**
	 * Init - set properties: name, email address
	 *
	 * @param	String		$name			Full person name of receiver
	 * @param	String		$address		Email address of receiver
	 * @param	Integer		$idReceiver		ID of receiver object record, e.g. in table ext_contact_person
	 * @param	String		$type			Registered receiver type identifier
	 */
	public function init($name, $address, $idReceiver = 0, $type = 'contactperson') {
		$this->type			= $type;
		$this->idReceiver	= $idReceiver;

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



	/**
	 * Get ID of receiver record (e.g. ext_contact_person)
	 *
	 * @return	Integer
	 */
	public function getIdReceiver() {
		return $this->idReceiver;
	}



	/**
	 * Get key of registered receiver type, e.g. 'contactperson'
	 *
	 * @return	String
	 */
	public function getType() {
		return $this->type;
	}



	/**
	 * Get receiver tuple ('type:ID')
	 *
	 * @return	String
	 */
	public function getTuple() {
	    return $this->type . ':' . $this->idReceiver;
	}



	/**
	 * @return	Array
	 */
	public function getData() {
		return array(
//			'receiver_type'	=> $this->getType(),
//			'id_receiver'	=> $this->getIdReceiver(),
			'name'		=> $this->getName(),
			'address'		=> $this->getAddress()
		);
	}



	/**
	 * Get receiver label
	 *
	 * @param	Boolean	$withAddress
	 * @return	String
	 */
	public function getLabel($withAddress = true) {
		$label	= $this->getName();

		if( empty($label) || $withAddress ) {
			$label .= ' <' . $this->getAddress() . '>';
		}

		return $label;
	}

}

?>