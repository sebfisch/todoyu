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

require_once( PATH_LIB . '/php/pclzip/pclzip.lib.php');


/**
 * Use an openoffice writer document as template, and replace all markers
 * Dwoo will process the content.xml file for Dwoo variables
 * 
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuTemplateDocumentOdt extends TodoyuTemplateDocumentAbstract implements TodoyuTemplateDocumentIf {

	/**
	 * ODT content type
	 */
	private $contentType = 'application/vnd.oasis.opendocument.text';

	/**
	 * Working directory (where odt archive will be extracted
	 */
	private $tempDir;

	/**
	 * Path to the content.xml file
	 */
	private $pathXML;

	/**
	 * Path to the temporary copy of the odt file
	 */
	private $pathOdt;

	/**
	 * Original XML content
	 */
	private $xmlContent;

	/**
	 * Parsed XML content
	 */
	private $xmlParsed;


	/**
	 * Cleanup all temporary files when finished
	 *
	 */
	public function __destruct() {
		if( is_dir($this->tempDir) ) {
			TodoyuFileManager::deleteFolder($this->tempDir);
		}
	}



	/**
	 * Build new file
	 * Replace all markers
	 */
	protected function build() {
			// Load the XML content from the template file
		$this->loadXMLContent();
			// Encode HTML entities
		$this->encodeData();
			// Prepare the XML content (move some markers)
		$this->prepareXML();
			// Render the XML into $xmlParsed
		$this->renderXML();
			// Create an archive again for the odt
		$this->buildArchive();
	}



	/**
	 * Prepare the XML files
	 * Move sections markers where necessary
	 */
	private function prepareXML() {
		$this->prepareListXML();
		$this->prepareRowXML();
		$this->prepareConditionXML();
		$this->preparePhpXML();
		$this->prepareDwooTagSpans();
		$this->prepareForeach();

//		TodoyuHeader::sendHeaderXML();
//		echo $this->xmlContent;
//		exit();
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
			// Pattern to find all table rows
		$patternRow		= '|<table:table-row>.*?</table:table-row>|sm';
			// Pattern to find sub parts in a table row if  it contains the row syntax '[--ROW:'
		$patternRowParts= '|(<table:table-row>)(.*?)\[--ROW:({.*?})(.*?)({/.*?})--ROW\](.*?)(</table:table-row>)|sm';
		$replaces		= array();

			// Find all rows
		preg_match_all($patternRow, $this->xmlContent, $rowMatches);

//		TodoyuDebug::printInFireBug($rowMatches, 'rows');

			// Check for the row syntax in the matched row parts and modify the row
		foreach($rowMatches[0] as $rowXML) {
			if( preg_match($patternRowParts, $rowXML, $partMatches) ) {
				$replaces[$rowXML] = $partMatches[3] . $partMatches[1] . $partMatches[2] . $partMatches[4] . $partMatches[6] . $partMatches[7] . $partMatches[5];
			}
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

			// Condition in single line
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



	/**
	 * Render the xml file
	 */
	private function renderXML() {
		$this->xmlParsed = render(new Dwoo_Template_String($this->xmlContent), $this->data);
	}



	/**
	 * Extract the template file (which is in fact a zip archive)
	 */
	private function extractTemplate() {
		$zip			= new ZipArchive();
		$path			= PATH_CACHE . '/temp/document/' . md5(microtime(true));
		$this->tempDir	= TodoyuFileManager::pathAbsolute($path);
		$this->pathXML	= TodoyuFileManager::pathAbsolute($this->tempDir . '/content.xml');

		TodoyuFileManager::makeDirDeep($this->tempDir);

		$zip->open($this->template);
		$zip->extractTo($this->tempDir);
		$zip->close();
	}



	/**
	 * Load the xml content from content.xml
	 */
	private function loadXMLContent() {
		if( is_null($this->tempDir) ) {
			$this->extractTemplate();
		}

		if( is_file($this->pathXML) ) {
			$this->xmlContent = file_get_contents($this->pathXML);
		} else {
			Todoyu::log('Can\'t load content.xml for odt. File not found', TodoyuLogger::LEVEL_ERROR);
		}
	}



	/**
	 * Encode HTML special chars into their entities to generate valid XML
	 * when the data is inserted
	 *
	 */
	private function encodeData() {
		$this->data	= TodoyuArray::htmlspecialchars($this->data);
	}



	/**
	 * Build the archive/odt file
	 */
	private function buildArchive() {
		file_put_contents($this->pathXML, $this->xmlParsed);

		$this->pathOdt	= TodoyuFileManager::pathAbsolute($this->tempDir . '.odt');

		copy($this->template, $this->pathOdt);

		$zip	= new PclZip($this->pathOdt);

		$zip->delete(PCLZIP_OPT_BY_NAME, 'content.xml');
		$zip->add($this->pathXML, PCLZIP_OPT_REMOVE_ALL_PATH);
	}



	/**
	 * Get document content
	 *
	 * @return	String
	 */
	public function getFileData() {
		return file_get_contents($this->pathOdt);
	}



	/**
	 * Send the file to the browser
	 *
	 * @param	String		$filename
	 */
	public function sendFile($filename) {
		parent::sendFile($this->pathOdt, $filename, $this->contentType);
	}



	/**
	 * Save the file to the server
	 *
	 * @param	String		$pathFile
	 * @return	Bool
	 */
	public function saveFile($pathFile) {
		$pathFile	= TodoyuFileManager::pathAbsolute($pathFile);

		return copy($this->pathOdt, $pathFile);
	}



	/**
	 * Get path of the created document
	 *
	 * @return	String
	 */
	public function  getFilePath() {
		return $this->pathOdt;
	}



	/**
	 * The the XML content of the document after it is prepared
	 * This content will be rendered by Dwoo
	 *
	 * @return	String		XML content
	 */
	public function getPreparedXMLContent() {
		return $this->xmlContent;
	}

}

?>