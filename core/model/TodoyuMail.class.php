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

require_once( PATH_LIB . '/php/phpmailer/class.phpmailer.php' );

/**
 * Todoyu mail
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuMail extends PHPMailer {

	/**
	 * Temporary HTML content. Render it before sending
	 *
	 * @var	String
	 */
	private $contentHTML = '';

	/**
	 * Additional CSS styles
	 *
	 * @var	Array
	 */
	private $cssStyles	= array();

	/**
	 * Headline of the todoyu email
	 *
	 * @var	String
	 */
	private $headline	= null;

	/**
	 * Default config
	 *
	 * @var	Array
	 */
	private $config = array(
		'exceptions'=> true,
		'mailer'	=> 'mail',
		'charset'	=> 'utf-8'
	);



	/**
	 * Initialize with config
	 *
	 * @param	Array		$config
	 */
	public function __construct(array $config = array()) {
		$config	= TodoyuHookManager::callHookDataModifier('core', 'mail.construct', $config);

		$this->config['mailer']	= Todoyu::$CONFIG['SYSTEM']['mailer'];

		if( Todoyu::$CONFIG['SYSTEM']['mailer'] === 'smtp' ) {
			$idSmtpAccount	= Todoyu::$CONFIG['SYSTEM']['id_smtpaccount'];
			$this->initSmtpConfig($idSmtpAccount);
		}
		$this->config	= TodoyuArray::mergeRecursive($this->config, $config);

		parent::__construct($this->config['exceptions']);

			// Config
		$this->Mailer	= $this->config['mailer'];
		$this->CharSet	= $this->config['charset'];

			// Set SMTP config
		if( $this->config['mailer'] === 'smtp' ) {
		 	$this->IsSMTP();
			$this->SMTPAuth	= intval($this->config['smtp_authentication']) === 1;
			$this->Host		= $this->config['smtp_host'];
			$this->Port		= intval($this->config['smtp_port']);

			if( intval($this->config['smtp_authentication']) === 1 ) {
				$this->Username	= $this->config['smtp_username'];
				$this->Password	= $this->config['smtp_password'];
			}

			$this->SMTPDebug  = 0; // 0 = disable debug/ 1 = echo errors+messages/ 2 = messages only
		}

		if( is_array($this->config['from']) ) {
			$this->SetFrom($this->config['from']['email'], $this->config['from']['name'], 0);
		} elseif( is_numeric($this->config['from']) ) {
			$this->setSender($this->config['from']);
		} elseif( $this->config['from'] !== false ) {
			$this->setSystemAsSender();
		}
	}



	/**
	 * Initialize SMTP configuration: add credentials from given record
	 *
	 * @param	Integer		$idSmtpAccount
	 */
	public function initSmtpConfig($idSmtpAccount) {
		$idSmtpAccount	= intval($idSmtpAccount);
		$smtpAccount	= TodoyuSysmanagerSmtpAccountManager::getAccount($idSmtpAccount);

		$this->config['smtp_host']			= $smtpAccount->getHost();
		$this->config['smtp_port']			= $smtpAccount->getPort();
		$this->config['smtp_authentication']= $smtpAccount->getAuthentication();
		if( $this->config['smtp_authentication'] === 1 ) {
			$this->config['smtp_username']	= $smtpAccount->getUsername();
			$this->config['smtp_password']	= $smtpAccount->getPassword();
		}
	}



	/**
	 * Render and set email HTML message
	 */
	private function renderHtmlContent() {
		$tmpl	= 'core/view/email-html.tmpl';
		$data	= array(
			'content'	=> $this->fullyQualifyLinksInHtml($this->contentHTML),
			'subject'	=> $this->Subject,
			'headline'	=> $this->headline,
			'cssStyles'	=> $this->cssStyles
		);

		$html	= Todoyu::render($tmpl, $data);

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

			$replace[$link] = SERVER_URL . '/' . ltrim($link, '/');
		}

		return str_replace(array_keys($replace), array_values($replace), $html);
	}



	/**
	 * Set email headline (can be a label)
	 *
	 * @param	String		$headline
	 */
	public function setHeadline($headline) {
		$headline	= Todoyu::Label($headline);
		$headline	= TodoyuHookManager::callHookDataModifier('core', 'mail.setHeadline', $headline);

		$this->headline	= $headline;
	}



	/**
	 * Add CSS style code
	 *
	 * @param	String		$cssStyle
	 */
	public function addCssStyles($cssStyle) {
		$this->cssStyles[] = $cssStyle;
	}



	/**
	 * Send mail
	 *
	 * @param	Boolean		$catchExceptions		Catch the exceptions and log them automatically. Returns true or false
	 * @return	Boolean		Sending was successful
	 */
	public function send($catchExceptions = true) {
		$this->renderHtmlContent();
		$status = false;

		if( $catchExceptions ) {
			try {
				$status = parent::Send();
			} catch(phpmailerException $e) {
				TodoyuLogger::logError($e->getMessage());
			} catch(Exception $e) {
				TodoyuLogger::logError($e->getMessage());
			}
		} else {
			$status = parent::Send();
		}

		return $status;
	}



	/**
	 * Set system as sender of the email (system name and email)
	 */
	public function setSystemAsSender() {
		$this->SetFrom(Todoyu::$CONFIG['SYSTEM']['email'], Todoyu::$CONFIG['SYSTEM']['name'], 0);
	}



	/**
	 * Set currently logged in user as sender
	 * Fallback to system if no user is logged in
	 */
	public function setCurrentUserAsSender() {
		$idPerson	= Todoyu::personid();

		if( $idPerson === 0 ) {
			$this->setSystemAsSender();
		} else {
			$this->setSender($idPerson);
		}
	}




	/**
	 * Set mail subject
	 *
	 * @param	String		$subject
	 */
	public function setSubject($subject) {
		$subject	= Todoyu::Label($subject);

		$subject	= TodoyuHookManager::callHookDataModifier('core', 'mail.setSubject', $subject);

		$this->Subject = $subject;
	}



	/**
	 * Set html content of the mail
	 *
	 * @param	String		$html
	 */
	public function setHtmlContent($html) {
		$this->contentHTML	= $html;
	}



	/**
	 * Set plaintext content of the mail
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
	 * Add receiver from TodoyuMailReceiver
	 *
	 * @param	String		$mailReceiverID
	 * @return	Boolean
	 */
	public function addReceiver($mailReceiverID) {
		$mailReceiver	= TodoyuMailReceiverManager::getMailReceiverObject($mailReceiverID);

		$email		= $mailReceiver->getAddress();
		$fullname	= $mailReceiver->getName();

		$hookParams	= array(
//			'idPerson'		=> $idPerson,
			'receiver'		=> $mailReceiver,
			'TodoyuMail'	=> $this
		);
		$email		= TodoyuHookManager::callHookDataModifier('core', 'mail.addReceiver.email', $email, $hookParams);

		if( !$email ) {
			return false;
		}

		$fullname	= TodoyuHookManager::callHookDataModifier('core', 'mail.addReceiver.fullname', $fullname, $hookParams);

		$this->AddAddress($email, $fullname);

		return true;
	}



	/**
	 * Add a person as reply to
	 *
	 * @param	Integer		$idPerson
	 * @return	Boolean
	 */
	public function addReplyToPerson($idPerson) {
		$idPerson	= (int) $idPerson;
		$person		= TodoyuContactPersonManager::getPerson($idPerson);

		$email		= $person->getEmail();

		if( !$email ) {
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
	 * @param  Integer		$idPerson
	 * @return Boolean
	 */
	public function setSender($idPerson) {
		$idPerson	= (int) $idPerson;
		$person		= TodoyuContactPersonManager::getPerson($idPerson);

		$email		= $person->getEmail();

		if( !$email ) {
			return false;
		}

		$this->SetFrom($email, $person->getFullName());

		return true;
	}

}

?>