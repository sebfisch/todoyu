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
 * Factory class for document type objects
 * 
 * @package		Todoyu
 * @subpackage	Document
 */
class TodoyuTemplateDocumentFactory {

	/**
	 * Get document object for a type
	 *
	 * @param	String		$type
	 * @param	Array		$data
	 * @param	String		$template
	 * @param	Array		$config
	 * @return	TodoyuTemplateDocumentIf|Boolean
	 */
	public static function getTemplateDocument($type, array $data, $template, array $config = array()) {
		$typeClass	= self::getTypeClass($type);
		$template	= $template === false ? false : TodoyuFileManager::pathAbsolute($template);
		$document	= false;

		if( $typeClass !== false ) {
			$document	= new $typeClass($data, $template, $config);

			if( !($document instanceof TodoyuTemplateDocumentIf) ) {
				Todoyu::log('Document class "' . $typeClass . '" doesn\'t implement TodoyuDocumentIf', TodoyuLogger::LEVEL_ERROR);
			}
		} else {
			Todoyu::log('Document class "' . $typeClass . '" not found', TodoyuLogger::LEVEL_ERROR);
		}

		return $document;
	}

	

	/**
	 * Get the class name for a document type
	 * Pattern "TodoyuDocumentTYPE"
	 *
	 * @param	String		$type
	 * @return	String|Boolean
	 */
	private static function getTypeClass($type) {
		$className	= 'TodoyuTemplateDocument' . trim($type);

		return class_exists($className, true) ? $className : false;		
	}

}

?>