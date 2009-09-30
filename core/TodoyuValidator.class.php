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
 * Various validators
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuValidator {


	/**
	 * Check a value with a validator function
	 *
	 * @param	Stirng		$name		Validator function name
	 * @param	Mixed		$value		Value to check
	 * @param	Array		$config		Validator config if necessary
	 * @return	Boolean
	 */
	public static function validate($validatorName, $fieldValue, array $validatorConfig, TodoyuFormElement $formElement, array $formData = array()) {
		if( method_exists('TodoyuValidator', $validatorName) ) {
			return call_user_func(array('TodoyuValidator', $validatorName), $fieldValue, $validatorConfig, $formElement, $formData);
		} else {
			TodoyuDebug::printHtml("Validator '$validatorName' not found", 'Invalid validator');

			return false;
		}
	}



	/**
	 * Validate string being email address
	 *
	 * @param	String		$email
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function isEmail($value) {
		if( ! self::isNotEmpty($value, $validatorConfig) ) {
			return false;
		}

		return ereg('^[A-Za-z0-9\._-]+[@][A-Za-z0-9\._-]+[\.].[A-Za-z0-9]+$', $value) ? true : false;
	}



	/**
	 * Validate variable being numeric digit
	 *
	 * @param	String 		$digit
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function isDigit($value, array $validatorConfig = array(), TodoyuFormElement $formElement = null, array $formData = array()) {
		return trim(intval($value)) === trim($value);
	}



	/**
	 * Validate string not being empty
	 *
	 * @param	String		$string
	 * @param	Array		$config
	 * @return	String
	 */
	public static function isNotEmpty($value) {
		return trim($value) !== '';
	}



	/**
	 * Validate timestamp not being at 00:00:00
	 *
	 * @param	Integer	$time
	 * @return	Boolean
	 */
	public static function isNotZerotime($value) {
		$parts	= explode(':', $value);
		$check	= intval($parts[0]) + intval($parts[1]);

		return $check > 0;
	}



	/**
	 * Validate string being of at least given minimum length
	 *
	 * @param	Integer		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function hasMinLength($value, $minLength) {
		$minLength	= intval($minLength);

		return strlen(trim($value)) >= $minLength;
	}



	/**
	 * Validate string not being longer than given length
	 *
	 * @param	Integer		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function hasMaxLength($value, $maxLength) {
		$maxLength	= intval($maxLength);

		return strlen(trim($value)) <= $maxLength;
	}





	/**
	 * Validate string not containing given illegal characters
	 *
	 * @param	String		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function illegalChars($value, array $validatorConfig = array(), TodoyuFormElement $formElement = null, array $formData = array()) {
		$chars	= $validatorConfig['char'];

		if( is_array($chars) ) {
			foreach($chars as $char) {
				if( stristr($value, $char) !== false ) {
					return false;
				}
			}
		}

		return true;
	}




}


?>