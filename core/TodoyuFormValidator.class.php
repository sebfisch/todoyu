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
 * Special validators for the form class
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuFormValidator {

	/**
	 * Validate form field
	 *
	 * @param	String				$validatorName
	 * @param	Mixed			 	$fieldValue
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement	$formElement
	 * @param	Array				$formData
	 * @return	Bool
	 */
	public static function validate($validatorName, $fieldValue, array $validatorConfig, TodoyuFormElement $formElement, array $formData = array()) {
		if( method_exists('TodoyuFormValidator', $validatorName) ) {
			return call_user_func(array('TodoyuFormValidator', $validatorName), $fieldValue, $validatorConfig, $formElement, $formData);
		} else {
			TodoyuDebug::printInFirebug("Validator '$validatorName' not found", 'Invalid validator');

			return false;
		}
	}


	/**
	 * User custom function caller
	 * Calls the function defined in the <function> attribute the same way
	 * as a normal function in the
	 *
	 * @param	Mixed		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function user($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$function	= $validatorConfig['function'];

		if( TodoyuDiv::isFunctionReference($function) ) {
			return TodoyuDiv::callUserFunction($function, $value, $validatorConfig, $formElement, $formData);
		} else  {
			return false;
		}
	}



	private static function isNotEmpty($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return TodoyuValidator::isNotEmpty($value);
	}


	private static function isNotZeroTime($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return TodoyuValidator::isNotZerotime($value);
	}

	private static function minLength($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$minLength	= intval($validatorConfig);

		return TodoyuValidator::hasMinLength($value, $minLength);
	}

	private static function maxLength($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$maxLength	= intval($validatorConfig);

		return TodoyuValidator::hasMaxLength($value, $maxLength);
	}



	/**
	 * Check if the checked value (date) is before an other date
	 * defined in the $config array
	 *
	 * @param	String		$value			Readable date format which works with strtotime()
	 * @param	Array		$config			Field config array
	 * @return	Boolean
	 */
	public static function dateBefore($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$secondFieldName	= $validatorConfig['field'];
		$secondFieldValue	= $formData[$secondFieldName];

			// If field is empty and allowEmpty is set
		if( $value == 0 && array_key_exists('allowEmpty', $validatorConfig) ) {
			return true;
		}

			// Convert dates to timestamps
		$fieldDate		= intval($value);
		$secondFieldDate= intval($secondFieldValue);

		return $fieldDate < $secondFieldDate;
	}



	/**
	 * Negate check of dateBefore
	 * Check is valid if the date is after or the same time
	 *
	 * @see 	dateBefore()
	 * @param	String		$value			Readable date format which works with strtotime()
	 * @param	Array		$config			Field config array
	 * @return	Boolean
	 */
	public static function dateNotBefore($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
			// If field is empty and allowEmpty is set
		if( $value == 0 && array_key_exists('allowEmpty', $validatorConfig) ) {
			return true;
		}

		return self::dateBefore($value, $validatorConfig, $formElement, $formData) === false;
	}



	/**
	 * Check if the checked value (date) is after an other date
	 * defined in the $config array
	 *
	 * @param	String		$value			Readable date format which works with strtotime()
	 * @param	Array		$config			Field config array
	 * @return	Boolean
	 */
	public static function dateAfter($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$secondFieldName	= $validatorConfig['field'];
		$secondFieldValue	= $formData[$secondFieldName];

			// Convert dates to timestamps
		$fieldDate		= intval($value);
		$secondFieldDate= intval($secondFieldValue);

		return $fieldDate > $secondFieldDate;
	}



	/**
	 * Negate check of dateAfter
	 * Check is valid if the date is before or the same time
	 *
	 * @see 	dateAfter()
	 * @param	String		$value			Readable date format which works with strtotime()
	 * @param	Array		$config			Field config array
	 * @return	Boolean
	 */
	public static function dateNotAfter($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return self::dateAfter($value, $validatorConfig, $formElement, $formData) === false;
	}



	/**
	 * Check if date and time of a field is before an other field
	 *
	 * @param	String		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function dateTimeBefore($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$secondFieldName	= $validatorConfig['field'];
		$secondFieldValue	= $formData[$secondFieldName];

		$fieldDate		= intval($value);
		$secondFieldDate= intval($secondFieldValue);

		return $fieldDate < $secondFieldDate;
	}



	/**
	 * Check if date and time are not before an other field
	 *
	 * @param	String		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function dateTimeNotBefore($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return self::dateTimeBefore($value, $validatorConfig, $formElement, $formData) === false;
	}



	/**
	 * Check if date and time are after an other field
	 *
	 * @param	String		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function dateTimeAfter($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$secondFieldName	= $validatorConfig['field'];
		$secondFieldValue	= $formData[$secondFieldName];

		$fieldDate		= intval($value);
		$secondFieldDate= intval($secondFieldValue);

		return $fieldDate > $secondFieldDate;
	}



	/**
	 * Check if date and time are not after an other field
	 *
	 * @param	String		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function dateTimeNotAfter($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return self::dateTimeAfter($value, $validatorConfig, $formElement, $formData) === false;
	}



	/**
	 * checks if current fieldvalue equals the value of the given second field
	 *
	 * @param	mixed	$value
	 * @param	array	$config
	 * @return	Boolean
	 */
	public static function fieldEqualsField($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData)	{
		$secondFieldName	= $validatorConfig['field'];
		$secondFieldValue	= $formData[$secondFieldName];


		return $secondFieldValue == $value;
	}



	/**
	 *
	 * @param array $value
	 * @param TodoyuFormElement $validatorConfig
	 * @param array $formElement
	 * @param $formData
	 * @return unknown_type
	 */
	public static function minLengthIfNotEmpty($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData)	{
		$minLength	= $validatorConfig['field'];

		if(strlen($value) == 0)	{
			return true;
		}

		return strlen(trim($value)) == $minLength;
	}

}


?>