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
 * Use an openoffice writer document as template, and replace all markers
 * Dwoo will process the content.xml file for Dwoo variables
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuTemplateDocumentOdt extends TodoyuTemplateDocumentOpenXML {

	/**
	 * Build parsed template
	 */
	protected function build() {
			// Set content type
		$this->setContentType('application/vnd.oasis.opendocument.text');
			// Load the XML content from the template file
		$this->loadXMLContent('content.xml');
			// Prepare the XML content (move some markers)
		$this->prepareXML();
			// Create an archive again for the odt
		$this->buildArchive();
	}



	/**
	 * Prepare the XML files
	 * Move sections markers where necessary
	 */
	private function prepareXML() {
		$this->removeSoftPageBreaks();
		$this->prepareDwooTagSpans();
		$this->prepareListXML();
		$this->prepareRowXML();
		$this->prepareConditionXML();
		$this->preparePhpXML();
		$this->prepareForeach();

//		TodoyuHeader::sendHeaderXML();
//		echo $this->xmlContent;
//		exit();
	}



	/**
	 * Remove soft-page-break tags from xml
	 * They destroy all dwoo code
	 *
	 */
	private function removeSoftPageBreaks() {
		$this->xmlContent = str_replace('<text:soft-page-break/>', '', $this->xmlContent);
	}



	/**
	 * Prepare list xml sections to repeat list elements
	 */
	private function prepareListXML() {
		$patternList= '|(<text:list-item>)(.*?)\[--LI:({.*?})(.*?)({/.*?})--LI\](.*?)(</text:list-item>)|sm';
		$replaces	= array();

		preg_match_all($patternList, $this->xmlContent, $matches);

		foreach($matches[0] as $index => $listXML) {
			$replaces[$listXML] = $matches[3][$index] . $matches[1][$index] . $matches[2][$index] . $matches[4][$index] . $matches[6][$index] . $matches[7][$index] . $matches[5][$index];
		}

		$this->xmlContent = str_replace(array_keys($replaces), array_values($replaces), $this->xmlContent);
	}



	/**
	 * Prepare the XML with the row syntax
	 */
	private function prepareRowXML() {
		$markerRowStart	= '\[TR:';
		$markerRowEnd	= ':TR\]';

			// Remove text spans around the row tags
		$patternRowTagA	= '|<text:span[^>]*?>(' . $markerRowStart . ')</text:span>|s';
		$patternRowTagB	= '|<text:span[^>]*?>(' . $markerRowEnd . ')</text:span>|s';

		$this->xmlContent	= preg_replace($patternRowTagA, '\1', $this->xmlContent);
		$this->xmlContent	= preg_replace($patternRowTagB, '\1', $this->xmlContent);


			// Pattern to find all table rows
		$patternRow		= '|<table:table-row[^>]*?>.*?</table:table-row>|s';
		$replaces		= array();

			// Find row start and endtags
		$patternRowStart= '/' . $markerRowStart . '({[^}]*})/';
		$patternRowEnd	= '/({\/[^}]*})' . $markerRowEnd . '/';

			// Find all rows
		preg_match_all($patternRow, $this->xmlContent, $rowMatches);

			// Check for the row syntax in the matched row parts and modify the row
		foreach($rowMatches[0] as $rowXML) {
				// Search start tags
			preg_match_all($patternRowStart, $rowXML, $rowStartMatches);
				// Search end tags
			preg_match_all($patternRowEnd, $rowXML, $rowEndMatches);

			if( sizeof($rowStartMatches[0]) === 0 && sizeof($rowEndMatches[0]) === 0 ) {
				continue;
			}

				// Remove row markers and the dwoo tag
			$newRowXML	= str_replace($rowStartMatches[0], '', $rowXML, $count);
				// Remove row markers and the dwoo tag
			$newRowXML	= str_replace($rowEndMatches[0], '', $newRowXML);
				// Pre- and postfix the found markers
			$newRowXML	= implode($rowStartMatches[1], '') . $newRowXML . implode($rowEndMatches[1], '');

			$replaces[$rowXML] = $newRowXML;
		}

		$this->xmlContent = str_replace(array_keys($replaces), array_values($replaces), $this->xmlContent);
	}



	/**
	 * Prepare the XML for the conditions (if,else)
	 * Free them from wrapping with text nodes
	 */
	private function prepareConditionXML() {
			// Condition in same line
		$pattern	= '#(<text:p[^>]*?>)\s*?({if[^}]*?})(.*?)({/if})(</text:p>)#sm';
		$replace	= '$2$1$3$5$4';

		$this->xmlContent = preg_replace($pattern, $replace, $this->xmlContent);

			// Condition in single lines
		$pattern	= '#(<text:p[^>]*?>)({[/]?(?:if|else)[^}]*?})(</text:p>)#sm';
		$replace	= '$2';

		$this->xmlContent = preg_replace($pattern, $replace, $this->xmlContent);
	}



	/**
	 * Prepare the XML to include php code
	 *
	 */
	private function preparePhpXML() {
		$replace	= array(
			'[--PHP:'	=> '<?php',
			'--PHP]'	=> '?>',
			'„'			=> '"',
			'“'			=> '"'
		);

		$this->xmlContent = str_replace(array_keys($replace), array_values($replace), $this->xmlContent);
	}



	/**
	 * Prepare the XML for Dwoo tags in span
	 * Between Dwoo braces, there may be <span> tags which add formatting information.
	 * Move them out of the Dwoo tags
	 *
	 */
	private function prepareDwooTagSpans() {
		$pattern	= '/{.*?}/';

		$this->xmlContent = preg_replace_callback($pattern, array($this, 'replaceStyleTagsInDwooTags'), $this->xmlContent);
	}



	/**
	 * Callback to replace office style tags in dwoo tags
	 *
	 * @param	Array	$matchingElements
	 * @return	String
	 */
	private function replaceStyleTagsInDwooTags(array $matchingElements) {
		$dwooTag	= $matchingElements[0];

			// Replace space tags
		$dwooTag	= str_replace('<text:s/>', '', $dwooTag);

			// Pattern for open and closing tags
		$patternWrappings	= '/(<(.*?) ?[^>]*?>)(.*?)(<\/\2>)/';
		$replaceWrappings	= '\3';

		$dwooTag	= preg_replace($patternWrappings, $replaceWrappings, $dwooTag);


			// Move single opening tags to the start of the string
		$patternOpen	= '/({.*?)(<[^\/]*?:[^ ] ?[^>]*?>)(.*?})/';
		$replaceOpen	= '\2\1\3';

		$dwooTag	= preg_replace($patternOpen, $replaceOpen, $dwooTag);

			// Move single closing tags to the end of the string
		$patternClose	= '/({.*?)(<\/.*?>)(.*?})/';
		$replaceClose	= '\1\3\2';

		$dwooTag	= preg_replace($patternClose, $replaceClose, $dwooTag);

		return $dwooTag;
	}


	private function prepareForeach() {
		$pattern	= '/(<text:[^>]*?>)({[\/]?foreach[^\}]*?})(<\/text:[^>]*?>)/';
		$replace	= '\2';

		$this->xmlContent	= preg_replace($pattern, $replace, $this->xmlContent);

		$pattern	= '/(<text:p [^>]*?>)({foreach[^}]*?})(.*?)(<\/text:p>)/';
		$replace	= '\2\1\3\4';

		$this->xmlContent	= preg_replace($pattern, $replace, $this->xmlContent);
	}

}

?>