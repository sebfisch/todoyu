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
 * Factory to create new form field elements with their registered classes
 *
 * @package		Todoyu
 * @subpackage	Form
 */

class TodoyuFormFactory {

	/**
	 * Get class Todoyuwhich represents an object of the requested type
	 * A new instance will be created with the NEW operator
	 *
	 * @param	String		$type
	 * @return	String
	 */
	public static function getClass($type) {
		return $GLOBALS['CONFIG']['FORM']['TYPES'][$type]['class'];
	}



	/**
	 * Get the template for the input type
	 *
	 * @param	String		$type
	 * @return	String
	 */
	public static function getTemplate($type) {
		return $GLOBALS['CONFIG']['FORM']['TYPES'][$type]['template'];
	}



	/**
	 * Create a field of a type within its parent fieldset
	 *
	 * @param	String		$type		Type of the field
	 * @param	String		$name		Name of the field
	 * @param	TodoyuFieldset	$fieldset	Parent fieldset
	 * @param	Array		$config		Configuration array (XML child nodes)
	 * @return	FieldElement
	 */
	public static function createField($type, $name, TodoyuFieldset $fieldset, array $config = array()) {
		$class = self::getClass($type);

		if( ! is_null($class) && class_exists($class, true) ) {
			return  new $class($name, $fieldset, $config);
		} else {
			return false;
		}
	}

}

?>