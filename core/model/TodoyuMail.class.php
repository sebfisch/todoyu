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

require_once( PATH_LIB . '/php/phpmailer/class.phpmailer-lite.php' );

/**
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuMail extends PHPMailerLite {

	/**
	 * Default config
	 *
	 * @var	Array
	 */
	private $config = array(
		'exceptions'=> true,
		'mailer'	=> 'mail',
		'charset'	=> 'uft-8'
	);



	/**
	 * Initialize with config
	 *
	 * @param	Array		$config
	 */
	public function __construct(array $config = array()) {
		$this->config	= TodoyuArray::mergeRecursive($this->config, $config);

		parent::__construct($this->config['exceptions']);

			// Config
		$this->Mailer	= $this->config['mailer'];
		$this->CharSet	= $this->config['charset'];

		if( is_array($this->config['from']) ) {
			$this->SetFrom($this->config['from']['email'], $this->config['from']['name']);
		} elseif( is_numeric($this->config['from']) ) {
			$this->setSender($this->config['from']);
		} elseif( $this->config['from'] !== false ) {
			$this->setSystemAsSender();
		}
	}



	/**
	 * Set system as sender of the email (system name and email)
	 *
	 */
	public function setSystemAsSender() {
		$this->SetFrom(Todoyu::$CONFIG['SYSTEM']['email'], Todoyu::$CONFIG['SYSTEM']['name']);
	}



	/**
	 * Set mail subject
	 *
	 * @param	String		$subject
	 */
	public function setSubject($subject) {
		$this->Subject = Todoyu::Label($subject);
	}


	/**
	 * Set html content of the mail
	 *
	 * @param	String		$html
	 */
	public function setHtmlContent($html) {
		$html	= $this->fullyQualifyLinksInHtml($html);

		$this->MsgHTML($html, PATH);
	}



	/**
	 * Prefix links with TODOYU_URL to make them work in mails
	 *
	 * @param	String		$html
	 * @return	String
	 */
	private function fullyQualifyLinksInHtml($html) {
		$pattern	= '/href=["\']{1}([^"\']*?)["\']{1}/is';
		$replace	= array();

		preg_match_all($pattern, $html, $matches);

		foreach($matches[1] as $link) {
			if( strncmp('http', $link, 4) === 0 ) {
				continue;
			}
			if( strncmp('javascript', $link, 10) === 0 ) {
				continue;
			}

			$replace[$link] = TODOYU_URL . '/' . $link;
		}

		return str_replace(array_keys($replace), array_values($replace), $html);
	}



	/**
	 * Set text content of the mail
	 *
	 * @param	String		$text
	 */
	public function setTextContent($text) {
		$this->AltBody = $text;
	}



	/**
	 * Add an attachment
	 *
	 * @param	String		$path
	 * @param	String		$name
	 */
	public function AddAttachment($path, $name) {
		$path	= TodoyuFileManager::pathAbsolute($path);

		parent::AddAttachment($path, $name);
	}



	/**
	 * Add a person as receiver
	 *
	 * @param	Integer		$idPerson
	 * @return	Boolean
	 */
	public function addReceiver($idPerson) {
		$idPerson	= intval($idPerson);
		$person		= TodoyuContactPersonManager::getPerson($idPerson);

		$email		= $person->getEmail();

		if( empty($email) ) {
			return false;
		}

		$this->AddAddress($email, $person->getFullName());

		return true;
	}



	/**
	 * Add a person as reply to
	 *
	 * @param	Integer		$idPerson
	 * @return	Boolean
	 */
	public function addReplyToPerson($idPerson) {
		$idPerson	= intval($idPerson);
		$person		= TodoyuContactPersonManager::getPerson($idPerson);

		$email		= $person->getEmail();

		if( empty($email) ) {
			return false;
		}

		$this->AddReplyTo($email, $person->getFullName());

		return true;
	}



	/**
	 * Add current person as reply to
	 *
	 * @return	Boolean
	 */
	public function addCurrentPersonAsReplyTo() {
		return $this->addReplyToPerson(Todoyu::personid());
	}



	/**
	 * Set sender of the email
	 *
	 * @param  $idPerson
	 * @return bool
	 */
	public function setSender($idPerson) {
		$idPerson	= intval($idPerson);
		$person		= TodoyuContactPersonManager::getPerson($idPerson);

		$email		= $person->getEmail();

		if( empty($email) ) {
			return false;
		}

		$this->SetFrom($email, $person->getFullName());

		return true;
	}
}

?>