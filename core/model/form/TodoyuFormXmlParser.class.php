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
	 * @param	String			$xmlFile
	 * @param	Array			$preParseMarkers
	 * @return 	Boolean
	 */
	public static function parse($form, $xmlFile, $preParseMarkers = array()) {
		self::$form		= $form;
		self::$xmlFile	= TodoyuFileManager::pathAbsolute($xmlFile);

		if( ! is_file($xmlFile) ) {
			TodoyuLogger::logFatal('Form XML file not found (\'' . self::$xmlFile . '\')');
			return false;
		}

		if( sizeof($preParseMarkers) > 0 ) {
			$xmlString	= file_get_contents($xmlFile);
			foreach($preParseMarkers as $key => $value) {
				$xmlString	= str_replace($key, $value, $xmlString);
			}
			self::$xml	= simplexml_load_string($xmlString, null, LIBXML_NOCDATA);
		} else {
				// Load xml file as simple xml object
			self::$xml	= simplexml_load_file($xmlFile, null, LIBXML_NOCDATA);
		}



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
	 */
	private static function parseHiddenFields() {
		if( self::$xml->hiddenFields ) {
			foreach( self::$xml->hiddenFields->field as $field ) {
				self::$form->addHiddenField((string)$field['name'], (string)$field['value'], ((string)$field['noStorage']==='true'), ((string)$field['noWrap']==='true'));
			}
		}
	}



	/**
	 * Parse fieldsets with their fields from XML
	 */
	private static function parseTopFieldsets() {
		$children	= self::$xml->fieldsets->children();
		if(is_object($children)) {
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
			// If restricted to internal persons
		if( $fieldsetXmlObj->restrictInternal ) {
			if( ! Todoyu::person()->isAdmin() && ! Todoyu::person()->isInternal() ) {
				return false;
			}
		}

			// If restricted by rights
		if( $fieldsetXmlObj->restrict ) {
			$config	= TodoyuArray::fromSimpleXML($fieldsetXmlObj);

				// Check if fieldset has restrictions and if they match
			if( ! self::isAllowed($config) ) {
				return false;
			}
		}

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
						break;

					default:
						TodoyuLogger::logError('Unknown field type (not field or fieldset)');
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

		$config	= TodoyuArray::fromSimpleXML($fieldXmlObj);

			// Check if field has restrictions and if they match
		if( ! self::isAllowed($config) ) {
			return false;
		}

		$field	= TodoyuFormFactory::createField($type, $name, $fieldset, $config);

		if( $field instanceof TodoyuFormElement ) {
			$fieldset->addField($name, $field);
			return true;
		} else {
			TodoyuLogger::logError('Can\'t create field object. Invalid config?', $config);
			return false;
		}

	}



	/**
	 * Check if a field has requirements
	 *
	 * @param	Array		$config			Field config
	 * @return	Boolean
	 */
	private static function isAllowed(array $config) {
		if( array_key_exists('restrictAdmin', $config) ) {
			return TodoyuAuth::isAdmin();
		} elseif( array_key_exists('restrict', $config) ) {
			$restrict 	= $config['restrict'];
			$and		= strtoupper(trim($restrict['@attributes']['conjunction'])) === 'AND';
			$rights		= TodoyuArray::assure($restrict['allow']);

				// SimpleXML handles the elements different if there is only one.
				// If only one element, pack it into an array, so behaviour is the same
			if( sizeof($rights) === 1 ) {
				$rights = array($rights);
			}

				// Check each right
			foreach($rights as $right) {
					// Check if the ext and right keys are available
				if( isset($right['@attributes']['ext']) && isset($right['@attributes']['right']) ) {
						// Check if right is allowed
					if( Todoyu::allowed($right['@attributes']['ext'], $right['@attributes']['right']) ) {
							// If right allowed and conjunction is OR, field is allowed
						if( ! $and ) {
							return true;
						}
					} else {
							// If right is disallowed and conjunction is AND, field is disallowed
						if( $and ) {
							return false;
						}
					}
				} elseif( isset($right['@attributes']['function']) ) {
						// Use function to decide if allowed
					$function	= $right['@attributes']['function'];

					if( TodoyuFunction::isFunctionReference($function) ) {
						$allowed	= TodoyuFunction::callUserFunction($function, $config);

						if( $allowed && !$and ) {
							return true;
						} elseif( !$allowed && $and ) {
							return false;
						}
					} else {
						TodoyuLogger::logError('FormElement rights function not found <' . $function . '>');
						return true;
					}
				} else {
					TodoyuLogger::logError('Misconfigured right in form');
				}
			}

				// If all rights processed without a return
				// AND = allowed, all rights passed		OR = disallowed, no right matched
			return $and;
		} elseif( array_key_exists('restrictInternal', $config) ) {
				// Restricted to internal persons
			return Todoyu::person()->isAdmin() ||  Todoyu::person()->isInternal();
		}

			// If no requirements found for the filed, allow it
		return true;
	}

}

?>