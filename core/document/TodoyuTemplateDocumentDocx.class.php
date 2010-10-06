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
 * Document type: Microsoft Office Text Document (docx)
 *
 * @package		Todoyu
 * @subpackage	Document
 */
class TodoyuTemplateDocumentDocx extends TodoyuTemplateDocumentOpenXML implements TodoyuTemplateDocumentIf {

	/**
	 * Build parsed template
	 */
	protected function build() {
			// Set content type
		$this->setContentType('vnd.openxmlformats-officedocument.wordprocessingml');
			// Load the XML content from the template file
		$this->loadXMLContent('word/document.xml');
			// Prepare the XML content (move some markers)
		$this->prepareXML();
			// Create an archive again for the odt
		$this->buildArchive();
	}

	protected function prepareXML() {

	}
}

?>