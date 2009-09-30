<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Parses an XML form structure into a form object
 *
 * @package		Todoyu
 * @subpackage	Form
 */

class TodoyuFormXmlParser {

	/**
	 * Form instance to add all elements to
	 *
	 * @var	Form
	 */
	private static $form;

	/**
	 * File where form structure is defined
	 *
	 * @var	String
	 */
	private static $xmlFile;

	/**
	 * XML object to process
	 *
	 * @var	SimpleXMLElement
	 */
	private static $xml;



	/**
	 * Parse form definition into a form object
	 *
	 * @param	TodoyuForm		$form
	 * @param	String		$xmlFile
	 * @return 	Boolean
	 */
	public static function parse($form, $xmlFile) {
		self::$form		= $form;
		self::$xmlFile	= TodoyuDiv::pathAbsolute($xmlFile);

		if( ! is_file($xmlFile) ) {
			Todoyu::log('Form XML file not found (\'' . self::$xmlFile . '\')', LOG_LEVEL_CRITICAL);
			return false;
		}

			// Load xml file as simple xml object
		self::$xml	= simplexml_load_file($xmlFile, null, LIBXML_NOCDATA);

			// Parse form attributes
		self::parseAttributes();
			// Parse hidden fields
		self::parseHiddenFields();
			// Parse main fieldsets
		self::parseTopFieldsets();

		return true;
	}



	/**
	 * Parse form attributes from xml
	 *
	 */
	private static function parseAttributes() {
		if( self::$xml->attributes ) {
			foreach( self::$xml->attributes->attribute as $attribute ) {
				self::$form->setAttribute((string)$attribute['name'], (string)$attribute);
			}
		}
	}



	/**
	 * Parse hidden fields from xml
	 *
	 */
	private static function parseHiddenFields() {
		if( self::$xml->hiddenFields ) {
			foreach( self::$xml->hiddenFields->field as $field ) {
				self::$form->setHiddenField((string)$field['name'], (string)$field);
			}
		}
	}



	/**
	 * Parse fieldsets with their fields from xml
	 *
	 */
	private static function parseTopFieldsets() {
		$children	= self::$xml->fieldsets->children();
		if (is_object($children)) {
			foreach( $children as $fieldset ) {
				self::addFieldset(self::$form, $fieldset);
			}
		}
	}



	/**
	 * Add a fieldset to the form object or a fieldset from a XML node
	 *
	 * @param	TodoyuFieldset			$parentElement
	 * @param	SimpleXmlElement	$fieldsetXmlObj
	 */
	private static function addFieldset(&$parentElement, SimpleXmlElement $fieldsetXmlObj) {
		$fieldset = $parentElement->addFieldset((string)$fieldsetXmlObj['name']);

			// Set legend if available
		if( $fieldsetXmlObj->legend ) {
			$fieldset->setLegend((string)$fieldsetXmlObj->legend);
		}

			// Set class if available
		if( $fieldsetXmlObj->class ) {
			$fieldset->setClass((string)$fieldsetXmlObj->class);
		}

			// If fieldset has an "elements" tag, add all elements
		if( $fieldsetXmlObj->elements ) {
			foreach( $fieldsetXmlObj->elements->children() as $nodeName => $element ) {
				switch( $nodeName ) {
					case 'fieldset':
						self::addFieldset($fieldset, $element);
					break;

					case 'field':
						self::addField($fieldset, $element);
				}
			}
		}
	}



	/**
	 * Add a field to a fieldset from an XML node
	 *
	 * @param	TodoyuFieldset			$fieldset
	 * @param	SimpleXmlElement	$fieldXmlObj
	 * @return 	Boolean
	 */
	private static function addField(TodoyuFieldset $fieldset, SimpleXmlElement $fieldXmlObj) {
		$type	= trim($fieldXmlObj['type']);
		$name	= trim($fieldXmlObj['name']);

		$config	= TodoyuDiv::simpleXmlToArray($fieldXmlObj);

		$field	= TodoyuFormFactory::createField($type, $name, $fieldset, $config);

		if( $field !== false ) {
			$fieldset->addField($name, $field);
			return true;
		} else {
			TodoyuDebug::printInFirebug($name, 'Cannot add the field');
			return false;
		}
	}

}


?>