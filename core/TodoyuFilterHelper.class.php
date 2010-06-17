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
 * Various filter helper functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuFilterHelper {

	/**
	 * Get query parts for date field filtering
	 *
	 * @param	String		$field
	 * @param	Integer		$date
	 * @param	Boolean		$negate
	 * @return	Boolean
	 */
	public static function getDateFilterQueryparts($tables, $field, $date, $negate = false) {
		$queryParts	= false;
		$timestamp	= TodoyuTime::parseDate($date);

		if( $timestamp !== 0 ) {
			$info	= self::getTimeAndLogicForDate($timestamp, $negate);

			$queryParts = array(
				'tables'=> $tables,
				'where'	=> $field . ' ' . $info['logic'] . ' ' . $info['timestamp']
			);
		}

		return $queryParts;
	}



	/**
	 * Return timestamp and conjunction logic for date-input queries
	 *
	 * @param	Integer		$timestamp
	 * @param	Boolean		$negate
	 * @return	Array		[timestamp,logic]
	 */
	public static function getTimeAndLogicForDate($timestamp, $negate = false)	{
		$timestamp	= intval($timestamp);

		if( $negate ) {
			$info	= array(
				'timestamp'	=> TodoyuTime::getStartOfDay($timestamp),
				'logic'		=> '>='
			);
		} else {
			$info	= array(
				'timestamp'	=> TodoyuTime::getEndOfDay($timestamp),
				'logic'		=> '<='
			);
		}

		return $info;
	}
	
}

?>