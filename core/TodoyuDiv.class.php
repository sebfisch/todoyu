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
 * Various useful functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuDiv {

	/**
	 * Mcrypt instance
	 *
	 * @var	Object
	 */
	private static $mcrypt = null;



	/**
	 * Send php array (or any other data) JSON encoded to the client
	 * The output is sent UTF8 encoded and script stops
	 * after the output has been sent
	 *
	 * @param	Array		$phpData		Data to send encoded
	 * @param	Boolean		$stopScript		Abort the script after sending the content
	 */
	public static function sendAsJson($phpData, $stopScript = true) {
		header("Content-Type: application/json, charset=utf-8");

		echo json_encode($phpData);

		if( $stopScript ) {
			exit();
		}
	}



	/**
	 * Get keywords for autocomplete from a table
	 * Search all fields with like and key the full word parts
	 *
	 * @param	String		$searchWords		Space seperated keywords Ex: "snowflake productions zurich"
	 * @param	String		$table				Table name
	 * @param	String		$searchInFields		Comma seperated list of fields to search in
	 * @param	Integer		$numKeywords		Number of keywords in the result array
	 * @return	Array
	 * @todo	Move it to a database class or something else
	 */
	function getAutocompleteValues($searchWords, $table, $searchInFields, $numKeywords = 10) {
			// Get database result
		$elements	= $this->searchTable($table, $searchInFields, $searchWords, $searchInFields, '', '', '', '', 0, $numKeywords*5);

			// Make a big string
		$allWords	= '';
		foreach($elements as $element) {
			$allWords .= implode(' ', $element);
		}

			// Replace all whitespaces by single space
		$allWords = strtolower(preg_replace('|\W|', ' ', $allWords));

			// Search matching words
		$pattern	= '/([^ ]*' . $searchWords . '[^ ]*)/i';
		preg_match_all($pattern, $allWords, $matches);

			// Clean up
		$keywords	= array_map('trim', $matches[0]);
		$keywords	= array_unique($keywords);

		return array_slice($keywords, 0, $numKeywords);
	}



	/**
	 * Search a table for several keywords
	 *
	 * @param	String		$table
	 * @param	Array		$searchInFields
	 * @param	Array		$fulltextKeywords
	 * @param	String		$fieldsInResult
	 * @param	Array		$extraTables
	 * @param	String		$extraWhere
	 * @param	String		$groupBy
	 * @param	String		$orderBy
	 * @param	Integer		$limitOffset
	 * @param	Integer		$limitRows
	 * @return	Array
	 * @todo	Move it to a database class or something else
	 */
	public static function searchTable($table, array $searchInFields, array $searchWords, $fieldsInResult = '*', array $extraTables = array(), $extraWhere = '', $groupBy = '', $orderBy = '', $limitOffset = 0, $limitRows = 200) {
			// Compile selected fields
			// Prepend table name, if all fields are requested
		if( $fieldsInResult === '*' ) {
			$fieldsInResult = $table . '.*';
		} else {
				// Split fields and prepend table if not done yet
			$fields = TodoyuArray::trimExplode(',', $fieldsInResult, true);
			foreach($fields as $key => $field) {
				if( strstr($field, '.') === false ) {
					$fields[$key] = $table . '.' . $field;
				}
			}
			$fieldsInResult = implode(', ', $fields);
		}

		$fields	= $fieldsInResult;
		$tables	= implode(', ', array_unique(array_merge($extraTables, array($table))));
		$where	= Todoyu::db()->buildLikeQuery($searchWords, $searchInFields);
		$limit	= TodoyuNumeric::intPositive($limitOffset) . ',' . TodoyuNumeric::intPositive($limitRows);


		if( $extraWhere != '' ) {
			$where .= ' ' . $extraWhere;
		}

		return Todoyu::db()->getArray($fields, $tables, $where, $groupBy, $orderBy, $limit);
	}



	/**
	 * Parse label if there is a label reference (LLL:)
	 * Else, just return the label
	 *
	 * @param	String		$label		Label or label reference
	 * @param	String		$locale		For output for this locale
	 * @return	String		Real label
	 */
	public static function getLabel($label, $locale = null) {
		if( ! is_string($label) ) {
			return '';
		} elseif( strncmp('LLL:', $label, 4) === 0 ) {
			$labelKey = substr($label, 4);
			return TodoyuLanguage::getLabel($labelKey, $locale);
		} else {
			return $label;
		}
	}



	/**
	 * Wrap into JS tag
	 *
	 * @param	String	$jsCode
	 * @return	String
	 */
	public static function wrapScript($jsCode) {
		return '<script language="javascript" type="text/javascript">' . $jsCode . '</script>';
	}



	/**
	 * Get short md5 hash of a string
	 *
	 * @param	String		$string
	 * @return	String		10 characters md5 hash value of the string
	 */
	public static function md5short($string) {
		return substr(md5($string), 0, 10);
	}



	/**
	 * Call user function. Works the same as call_user_func(), but accepts also a
	 * string function reference like 'MyClass::myMethod'
	 * First argument is the function reference, all others are normal parameters passed to the function
	 *
	 * @param	String		$funcRef		Function reference
	 * @return	Mixed
	 */
	public static function callUserFunction($funcRef) {
		$funcArgs	= func_get_args();
		$funcRef	= array_shift($funcArgs);

		if( self::isFunctionReference($funcRef) ) {
			$funcRef = explode('::', $funcRef);

			$result	= call_user_func_array($funcRef, $funcArgs);
		} else {
			$result = false;
		}

		return $result;
	}



	/**
	 * Call user function where parameters are stored in an array
	 * @see		callUserFunction()
	 * @see		call_user_func_array()
	 *
	 * @param	String		$funcRef
	 * @param	Array		$funcArgs
	 * @return	Mixed
	 */
	public static function callUserFunctionArray($funcRef, array $funcArgs) {
		if( self::isFunctionReference($funcRef) ) {
			$funcRef = explode('::', $funcRef);

			$result	= call_user_func_array($funcRef, $funcArgs);
		} else {
			TodoyuDebug::printInFirebug($funcRef, 'Function not found');
			$result = false;
		}

		return $result;
	}



	/**
	 * Check if a function/method reference is valid
	 *
	 * @param	String		$funcRefString		Format: function or class::method
	 * @return	Boolean
	 */
	public static function isFunctionReference($funcRefString) {
		if( strpos($funcRefString, '::') === false ) {
			return function_exists($funcRefString);
		} else {
			$parts	= explode('::', $funcRefString);

			return method_exists($parts[0], $parts[1]);
		}
	}



	/**
	 * Build an URL with given parameters prefixed with todoyu path
	 *
	 * @param	Array		$params		Parameters as key=>value
	 * @param	String		$hash		Hash (#hash)
	 * @param	Boolean		$absolute	Absolute URL with host server
	 * @return	String
	 */
	public static function buildUrl(array $params = array(), $hash = '', $absolute = false) {
		$query		= rtrim(PATH_WEB, '/\\') . 'index.php';
		$queryParts	= array();

			// Add question mark if there are query parameters
		if( sizeof($params) > 0 ) {
			$query .= '?';
		}

			// Add all parameters encoded
		foreach($params as $name => $value) {
			$queryParts[] = $name . '=' . urlencode($value);
		}

			// Concatinate
		$query .= implode('&', $queryParts);

			// Add hash
		if( ! empty($hash) ) {
			$query .= '#' . $hash;
		}

			// Add absolute server url
		if( $absolute ) {
			$query = SERVER_URL . $query;
		}

		return $query;
	}



	/**
	 * Initialize mcypt
	 */
	private static function initMcrypt() {
		if( is_null(self::$mcrypt) ) {
				// Open module
			self::$mcrypt = mcrypt_module_open('tripledes', '', 'ecb', '');
				// Random seed
			$random = 596328;
				// Generate initialisation vector
			$vector	= mcrypt_create_iv(mcrypt_enc_get_iv_size(self::$mcrypt), $random);
				// Get the expected key size based on mode and cipher
			$expectedKeySize = mcrypt_enc_get_key_size(self::$mcrypt);
				// Get a key in the needed length (use typo3 key)
			$key = substr($GLOBALS['CONFIG']['SYSTEM']['encryptionKey'], 0, $expectedKeySize);
				// Initialize mcrypt library with mode/cipher, encryption key, and random initialization vector
			mcrypt_generic_init(self::$mcrypt, $key, $vector);
		}
	}



	/**
	 * Encrypt element
	 *
	 * @param	Mixed		$input		String,Array,Object,... (will be serialized)
	 * @return	String
	 */
	public static function encrypt($input) {
		self::initMcrypt();

		$stringToEncrypt	= serialize($input);
		$encryptedString	= mcrypt_generic(self::$mcrypt, $stringToEncrypt);

		return base64_encode($encryptedString);
	}



	/**
	 * Decrypt string to element
	 *
	 * @param	String		$encryptedString
	 * @return	Mixed		With unserialize
	 */
	public static function decrypt($encryptedString) {
		self::initMcrypt();

		$encryptedString	= base64_decode($encryptedString);
		$decryptedString	= mdecrypt_generic(self::$mcrypt, $encryptedString);

		return unserialize($decryptedString);
	}



	/**
	 * Analyze version string and return array of contained sub versions and attributes
	 *
	 * @param	String		$versionString
	 * @return	Array		[major,minor,revision,status]
	 */
	public static function getVersionInfo($versionString) {
		$info			= array();

		if( strpos($versionString, '-') !== false ) {
			$temp	= explode('-', $versionString);
			$version= explode('.', $temp[0]);
			$status	= $temp[1];
		} else {
			$version= explode('.', $versionString);
			$status	= 'stable';
		}

		$info['major']		= intval($version[0]);
		$info['minor']		= intval($version[1]);
		$info['revision']	= intval($version[2]);
		$info['status']		= $status;

		return $info;
	}

}

?>