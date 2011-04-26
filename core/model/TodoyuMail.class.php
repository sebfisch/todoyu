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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuMail extends PHPMailerLite {

	private $config = array(
		'exceptions'=> true,
		'mailer'	=> 'mail',
		'charset'	=> 'uft-8'
	);



	public function __construct(array $config) {
		$this->config	= TodoyuArray::mergeRecursive($this->config, $config);

		parent::__construct($this->config['exceptions']);

			// Config
		$this->Mailer	= $this->config['mailer'];
		$this->CharSet	= $this->config['charset'];
	}

	public function setSubject($subject) {
		$this->Subject = trim($subject);
	}


	public function setHtmlContent($html) {
//		$html	= $this->embedImages($html);

		$this->MsgHTML($html, PATH);

	}

//		/**
//	 * Embed images into the email
//	 *
//	 * @param	PHPMailerLite	$mailer
//	 * @param	String			$html
//	 * @return	String
//	 */
//	private function embedImages($html) {
//		$pattern	= '|<img.*?src="([\d\w\.\-/]*?)".*?/>|';
//
//		preg_match_all($pattern, $html, $matches);
//
//		foreach($matches[1] as $pathImage) {
//			$absPathImage	= TodoyuFileManager::pathAbsolute($pathImage);
//			$cidString		= str_replace(array('/', '\\', '-', '.'), '-', $pathImage);
//
//			$this->AddEmbeddedImage($absPathImage, $cidString, basename($pathImage));
//
//			$html	= str_replace($pathImage, 'cid:' . $cidString, $html);
//		}
//
//		return $html;
//	}


}

?>