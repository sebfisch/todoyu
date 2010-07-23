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
 * Exception for TemplateDocuments
 * 
 * @package		Todoyu
 * @subpackage	Document
 */
class TodoyuTemplateDocumentException extends Exception {

	protected $document;

	/**
	 * Initialize document
	 *
	 * @param	Array		$data
	 * @param	String		$template
	 * @param	Array		$config
	 */
	public function __construct($document, $message = '', $code = 0) {
		parent::__construct($message, $code);

			// Log all document exceptions
		Todoyu::log($message, TodoyuLogger::LEVEL_ERROR);

		$this->document = $document;
	}



	/**
	 * Get document which caused the exception
	 * Can be null if exception wasn't thrown in a document instance
	 * 
	 * @return	TodoyuTemplateDocumentAbstract
	 */
	public function getDocument() {
		return $this->document;
	}



	/**
	 * Check whether the exception has a document object
	 * 
	 * @return	Boolean
	 */
	public function hasDocument() {
		return $this->document instanceof TodoyuTemplateDocumentIf;
	}

}

?>