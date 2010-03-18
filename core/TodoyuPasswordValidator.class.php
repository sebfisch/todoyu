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
 * Validates for a good password
 *
 * @package		Todoyu
 * @subpackage	User
 */
class TodoyuPasswordValidator {

	/**
	 * Enabled checks
	 * @var	Array
	 */
	private $checks	= array();

	/**
	 * Occured errors
	 * @var	Array
	 */
	private $errors = array();


	/**
	 * Initialize validator with active checks
	 *
	 */
	public function __construct() {
		$this->checks	= self::getChecks();
	}



	/**
	 * Validate $value with registered checks
	 * @param	Mixed	$value
	 * @return	Bool
	 */
	public function validate($value) {
		$this->resetErrors();

		$functions	= array_keys($this->checks);

		foreach($functions as $function) {
				// Only check if config is not set to FALSE
			if ( $this->checks[$function] !== false ) {
					// Check if validtor exists
				if ( method_exists($this, $function) ) {
					call_user_func(array($this, $function), $value, $this->checks[$function]);
				} else {
					Todoyu::log('Invalid password validator function: ' . $function, LOG_LEVEL_ERROR);
				}
			}
		}

		return $this->hasErrors() === false;
	}



	/**
	 * Reset error array (for second use of same object)
	 * @return	Array
	 */
	private function resetErrors() {
		$this->errors = array();
	}


	/**
	 * Add a new error
	 * @param	String		$errorMessage
	 */
	private function addError($errorMessage) {
		$this->errors[] = $errorMessage;
	}



	/**
	 * Check if errors are registered
	 * @return	Bool
	 */
	public function hasErrors() {
		return sizeof($this->errors) > 0;
	}



	/**
	 * Get registered errors
	 * @return	Array
	 */
	public function getErrors() {
		return $this->errors;
	}



	/**
	 * Checks if password has a minimum length
	 *
	 * @return	Bool
	 */
	private function minLength($value, $config) {
		$value	= trim($value);
		$length	= intval($config);

		if ( strlen($value) < $length ) {
			$errorMessage = str_replace('%s', $length, Label('LLL:contact.password.minLengthIfNotEmpty'));
			$this->addError($errorMessage);
		}
	}



	/**
	 * Checks password for numbers
	 */
	private function hasNumbers($value, $config) {
		$pattern= '/[0-9]+/';
		$valid	= preg_match($pattern, $value);

		if ( ! $valid ) {
			$this->addError(Label('contact.password.numbers'));
		}
	}



	/**
	 * Checks password for lower case
	 */
	private function hasLowerCase($value, $config)	{
		$pattern= '/[a-z]+/';
		$valid	= preg_match($pattern, $value);

		if ( ! $valid ) {
			$this->addError(Label('contact.password.lower'));
		}
	}



	/**
	 * Checks password for upper case
	 */
	private function hasUpperCase($value, $config)	{
		$pattern= '/[A-Z]+/';
		$valid	= preg_match($pattern, $value);

		if ( ! $valid ) {
			$this->addError(Label('contact.password.upper'));
		}
	}



	/**
	 * Checks if password has special chars
	 */
	private function hasSpecialChars($value, $config) {
		$pattern= '/[^a-zA-Z0-9]+/';
		$valid	= preg_match($pattern, $value);

		if ( ! $valid ) {
			$this->addError(Label('contact.password.special'));
		}
	}



	/**
	 * Get check config
	 *
	 * @return	Array
	 */
	public static function getChecks() {
		return TodoyuArray::assure($GLOBALS['CONFIG']['goodPassword']);
	}

}

?>