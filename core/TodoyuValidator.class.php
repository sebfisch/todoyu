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
 * Various validators
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuValidator {

	/**
	 * Validate string being email address
	 *
	 * @param	String		$email
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function isEmail($value) {
		if( ! self::isNotEmpty($value) ) {
			return false;
		}

		return preg_match('/^[A-Za-z0-9\._-]+[@][A-Za-z0-9\._-]{2,}\.[A-Za-z0-9]{2,}$/', $value) === 1;
	}



	/**
	 * Validate variable being numeric digit
	 *
	 * @param	String 		$digit
	 * @return	Boolean
	 */
	public static function isDigit($value) {
		return trim(intval($value)) === trim($value);
	}



	/**
	 * Check if value is a number
	 *
	 * @param	String		$value
	 * @return	Boolean
	 */
	public static function isNumber($value ) {
		return is_numeric($value);
	}



	/**
	 * Check if value is decimal
	 *
	 * @param	String		$value
	 * @return	Boolean
	 */
	public static function isDecimal($value) {
		return trim(floatval($value)) === trim($value);
	}



	/**
	 * Validate string not being empty
	 *
	 * @param	String		$string
	 * @return	String
	 */
	public static function isNotEmpty($value) {
		return trim($value) !== '';
	}



	/**
	 * Check if number is in range. The numbers are intrepreted as float values
	 *
	 * @param	Float		$value
	 * @param	Float		$bottom
	 * @param	Float		$top
	 * @param	Boolean		$allowRanges
	 * @return	Boolean
	 */
	public static function isInRange($value, $bottom, $top, $allowRanges = true) {
		$value	= floatval($value);
		$bottom	= floatval($bottom);
		$top	= floatval($top);

		if( $allowRanges ) {
			return $value >= $bottom && $value <= $top;
		} else {
			return $value > $bottom && $value < $top;
		}
	}



	/**
	 * Validate timestamp not being at 00:00:00
	 *
	 * @param	String		$value
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
	public static function illegalChars($value, array $validatorConfig = array()) {
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