<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

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
	 * @return	Boolean
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
			// Multiple user validators? handle each
		$isAttributesArray = TodoyuArray::getFirstKey($validatorConfig) !== '@attributes';
		if ( $isAttributesArray ) {
			foreach($validatorConfig as $subValidatorConfig) {
				$result	= self::user($value, $subValidatorConfig, $formElement, $formData);
				if ( $result === false ) {
					break;
				}
			}
		} else {
				// Validate
			$function	= $validatorConfig['function'];

			if( TodoyuFunction::isFunctionReference($function) ) {
				$result	= TodoyuFunction::callUserFunction($function, $value, $validatorConfig, $formElement, $formData);
			} else  {
				Todoyu::log('Formvalidator function not found: ' . $function, LOG_LEVEL_FATAL);
				$result	= false;
			}
		}

		return $result;
	}



	/**
	 * Validate value not being empty
	 *
	 * @param	String				$value		Field value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	private static function isNotEmpty($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return TodoyuValidator::isNotEmpty($value);
	}



	/**
	 * Validate value not being zero (time)
	 * Most time fields provide the value as numeric in seconds. String version separated by : is also valid
	 *
	 * @param	String				$value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	private static function isNotZeroTime($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		if( is_numeric($value) ) {
			return intval($value) > 0;
		} else {
			return TodoyuValidator::isNotZerotime($value);
		}
	}



	/**
	 * Validate value not being zero
	 *
	 * @param	String				$value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	private static function isNotZero($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return intval($value) > 0;
	}



	/**
	 * Validate value having at least given minimum amount of characters
	 *
	 * @param	String				$value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	private static function minLength($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$minLength	= intval($validatorConfig);

		return TodoyuValidator::hasMinLength($value, $minLength);
	}



	/**
	 * Validate value not exceeding maximum length
	 *
	 * @param	unknown_type		$value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	private static function maxLength($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$maxLength	= intval($validatorConfig);

		return TodoyuValidator::hasMaxLength($value, $maxLength);
	}



	/**
	 * Check whether value is decimal
	 *
	 * @param	String				$value
	 * @param	Array				$validatorConfig
	 * @param 	TodoyuFormElement	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	private static function isDecimal($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return TodoyuValidator::isDecimal($value);
	}



	/**
	 * Check if the checked value (date) is before an other date defined in the $config array
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
	 * Negate check of dateBefore. Check validity if the date is after or the same time
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
	 * Check if the checked value (date) is after an other date defined in the $config array
	 *
	 * @param	String		$value			Readable date format which works with strtotime()
	 * @param	Array		$config			Field config array
	 * @return	Boolean
	 */
	public static function dateAfter($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$secondFieldName	= $validatorConfig['field'];
		$secondFieldValue	= $formData[$secondFieldName];

		if( $value == 0 && array_key_exists('allowEmpty', $validatorConfig) ) {
			return true;
		}

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

		$fieldDate			= intval($value);
		$secondFieldDate	= intval($secondFieldValue);

		return $fieldDate < $secondFieldDate || $fieldDate === 0 || $secondFieldDate === 0;
	}



	/**
	 * Check if date and time are not before an other field
	 *
	 * @param	String		$value
	 * @param	Array		$config
	 * @return	Boolean
	 */
	public static function dateTimeNotBefore($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$secondFieldName	= $validatorConfig['field'];
		$secondFieldValue	= $formData[$secondFieldName];

		$fieldDate			= intval($value);
		$secondFieldDate	= intval($secondFieldValue);

		if( $fieldDate === 0 || $secondFieldDate === 0) {
			return true;
		}

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
	 * Check if two fields are equal
	 *
	 * @param	String				$value
	 * @param	Array				$validatorConfig		field = other fieldname
	 * @param	TodoyuFormElement 	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	public static function equals($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$secondFieldName	= $validatorConfig['field'];
		$secondFieldValue	= $formData[$secondFieldName];
		$allowEmpty			= isset($validatorConfig['allowEmpty']);

		$equal		= $secondFieldValue === $value;
		$notEmpty	= $allowEmpty ? true : $value !== '';

		return $equal && $notEmpty;
	}



	/**
	 * Validate value to have minimum length or be empty
	 *
	 * @param	Array				$value
	 * @param	TodoyuFormElement	$validatorConfig
	 * @param	Array				$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	public static function minLengthIfNotEmpty($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData)	{
		$minLength	= $validatorConfig['field'];

		if( strlen($value) == 0 )	{
			return true;
		}

		return strlen(trim($value)) == $minLength;
	}



	/**
	 * Validate for a good password
	 *
	 * @param	String				$value
	 * @param 	TodoyuFormElement 	$validatorConfig
	 * @param	Array				$formElement
	 * @param 	Array				$formData
	 * @return	Boolean
	 */
	public static function goodPassword($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$idUser		= intval($formData['id']);
		$pass		= trim($value);
		$allowEmpty	= isset($validatorConfig['allowEmpty']);

			// If empty allowed, validate as true if empty
		if( $pass === ''  && $allowEmpty ) {
			return true;
		}

		$validator	= new TodoyuPasswordValidator();

		if( $validator->validate($pass) === false ) {
			$errors	= $validator->getErrors();

			$formElement->setErrorMessage($errors[0]);

			return false;
		}

		return true;
	}



	/**
	 * Validate a field to be unique in the table
	 *
	 * @param	String				$value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement 	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	public static function unique($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$table		= trim($validatorConfig['table']);
		$field		= $formElement->getName();
		$idRecord	= intval($formData['id']);
		$value		= trim($value);

			// If empty is allowed, don't check
		if( $value === '' && isset($validatorConfig['allowEmpty']) ) {
			return true;
		}

			// If no table defined to check in
		if( $table === '') {
			Todoyu::log('Missing tablename in unique form validation for field ' . $formElement->getName(), LOG_LEVEL_ERROR);
			return false;
		}

			// Check if a record with this fieldvalue already exists (current record is ignored)
		$fields	= $field;
		$where	= Todoyu::db()->backtick($field) . ' = ' . Todoyu::db()->quote($value, true) . ' AND
					id != ' . $idRecord;

		$exists	= Todoyu::db()->hasResult($fields, $table, $where);

		if( $exists ) {
			$formElement->setErrorMessage('form.error.notUnique');
			return false;
		} else {
			return true;
		}
	}



	/**
	 * Validate form field for email
	 *
	 * @param	String				$value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement 	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	public static function email($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$value	= trim($value);

			// If empty is allowed, don't check
		if( $value === '' && isset($validatorConfig['allowEmpty']) ) {
			return true;
		}

		return TodoyuValidator::isEmail($value);
	}


	/**
	 * Check another field in the form. If this field is not empty, the checked field needs a value too
	 *
	 * @param	String				$value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement 	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	public static function requiredIfNotEmpty($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		$field	= $validatorConfig['field'];

		return empty($formData[$field]) || $value !== '';
	}



	/**
	 * Validate database relation field
	 *
	 * @uses	TodoyuFormElement_DatabaseRelation
	 * @param	String				$value
	 * @param	Array				$validatorConfig
	 * @param	TodoyuFormElement 	$formElement
	 * @param	Array				$formData
	 * @return	Boolean
	 */
	public static function validateSubRecords($value, array $validatorConfig, TodoyuFormElement $formElement, array $formData) {
		return $formElement->areAllRecordsValid();
	}

}

?>