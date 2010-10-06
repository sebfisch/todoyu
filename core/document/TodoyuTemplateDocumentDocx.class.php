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
//		$this->prepareRowXML();
	}


	protected function prepareRowXML() {
/*			// Remove text spans around the row tags
//		$patternRowTagA	= '|<text:span[^>]*?>(\[ROW:)</text:span>|sm';
//		$patternRowTagB	= '|<text:span[^>]*?>(--ROW\])</text:span>|sm';
//
//		$this->xmlContent	= preg_replace($patternRowTagA, '\1', $this->xmlContent);
//		$this->xmlContent	= preg_replace($patternRowTagB, '\1', $this->xmlContent);
*/
			// Pattern to find all table rows
		$patternRow		= '|<w:tr.*?>.*?</w:tr>|s';
			// Pattern to find sub parts in a table row if  it contains the row syntax '[--ROW:'
		$patternRowParts= '|(<table:table-row[^>]*?>)(.*?)\[--ROW:({.*?})(.*?)({/.*?})--ROW\](.*?)(</table:table-row>)|sm';
		$replaces		= array();

			// Find all rows
		preg_match_all($patternRow, $this->xmlContent, $rowMatches);

			// Check for the row syntax in the matched row parts and modify the row
		foreach($rowMatches[0] as $rowXML) {
			if( preg_match($patternRowParts, $rowXML, $partMatches) ) {
				$replaces[$rowXML] = $partMatches[3] . $partMatches[1] . $partMatches[2] . $partMatches[4] . $partMatches[6] . $partMatches[7] . $partMatches[5];
			}
		}

		$this->xmlContent = str_replace(array_keys($replaces), array_values($replaces), $this->xmlContent);


	}
}

?>