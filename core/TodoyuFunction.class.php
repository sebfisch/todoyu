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
 * Function handling
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuFunction {

	/**
	 * Call user function. Works the same as call_user_func(), but accepts also a
	 * string function reference like 'MyClass::myMethod'
	 * First argument is the function reference, all others are normal parameters passed to the function
	 *
	 * @param	String		$funcRefString		Function reference
	 * @return	Mixed
	 */
	public static function callUserFunction($funcRefString) {
		$funcArgs	= func_get_args();
		$funcRef	= array_shift($funcArgs);

		if( self::isFunctionReference($funcRefString) ) {
			$funcRefParts = explode('::', $funcRefString);

			$result	= call_user_func_array($funcRefParts, $funcArgs);
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
	 * @param	String		$funcRefString
	 * @param	Array		$funcArgs
	 * @return	Mixed
	 */
	public static function callUserFunctionArray($funcRefString, array $funcArgs) {
		if( self::isFunctionReference($funcRefString) ) {
			$funcRefParts = explode('::', $funcRefString);

			$result	= call_user_func_array($funcRefParts, $funcArgs);
		} else {
			Todoyu::log('Function not found: ' . $funcRefString, LOG_LEVEL_FATAL);
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

}

?>