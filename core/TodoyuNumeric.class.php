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
 * Numeric helper functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuNumeric {

	/**
	 * Make sure an integer is in a range. If the integer is out of range,
	 * set it to one of the boundaries
	 *
	 * @param	Integer		$integer
	 * @param	Integer		$min
	 * @param	Integer		$max
	 * @return	Integer
	 */
	public static function intInRange($integer, $min = 0, $max = 2000000000)	{
		$integer = intval($integer);

		if( $integer < $min ) {
			$integer = $min;
		}

		if( $integer > $max ) {
			$integer = $max;
		}

		return $integer;
	}



	/**
	 * Get the positive integer of a value
	 *
	 * @param	String		$value		A string or integer value
	 * @return	Integer		Integer equal or greater than 0
	 */
	public static function intPositive($value) {
		$integer = intval($value);

		if( $integer < 0 ) {
			$integer = 0;
		}

		return $integer;
	}



	/**
	 * Get integer representation of version string
	 * Borrowed from TYPO3
	 *
	 * @param	String		$version
	 * @return	Integer
	 */
	public static function getIntVersion($version) {
		if(!preg_match('/^(\d+)\.(\d+)\.(\d+)(?:(?:\.|-(rc|dev|beta|alpha))(\d+)?)?$/', $version, $matches)) {
			return false;
		}

		// Increase value for subversions
		if( ! empty($matches[4]) ) {
			switch($matches[4]) {
				case 'rc':
					$added = 30;
					break;

				case 'beta':
					$added = 20;
					break;

				case 'alpha':
					$added = 10;
					break;

				case 'dev':
					$added = 0;
					break;
			}
		} else {
			$added = 50; // for final
		}
			// Add version of subversion (ex: alpha3 = +3)
		if( ! empty($matches[5]) ) {
			$added = $added + $matches[5];
		}

		return $matches[1] * 1000000 + $matches[2] * 10000 + $matches[3] * 100 + $added;
	}

	

	/**
	 * Get percent of a value
	 *
	 * @param	Float		$value				Base value
	 * @param	Float		$percent			Percent: 25.0 or 0.25
	 * @param	Boolean		$isFraction			Percent value is already a fraction (<0)
	 * @return	Float
	 */
	public static function percent($value, $percent, $isFraction = false) {
		$value		= floatval($value);
		$percent	= floatval($percent);

		if( $percent !== true ) {
			$percent = floatval($percent/100);
		}

		return floatval($percent * $value);
	}



	/**
	 * Calculate fraction (how many percent is the given value of the given total?)
	 *
	 * @param	Integer	$fraction
	 * @param	Integer	$total
	 * @return	Integer
	 */
	public static function fraction($fraction = 75, $total = 300) {
		if($total > 0) {
			$rc = intval (($fraction/ $total) * 100);
		} else {
			TodoyuDebug::printHtml('error in fraction(...) - division by 0!');
		}

		return $rc;
	}

}

?>