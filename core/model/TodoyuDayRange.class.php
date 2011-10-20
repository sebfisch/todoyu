<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * [Add class description]
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuDayRange extends TodoyuDateRange {


	/**
	 * Set start date
	 *
	 * @param	Integer		$date
	 */
	public function setStart($date) {
		$date	= TodoyuTime::getStartOfDay($date);

		parent::setStart($date);
	}



	/**
	 * Set end date
	 *
	 * @param	Integer		$date
	 */
	public function setEnd($date) {
		$date	= TodoyuTime::getEndOfDay($date);

		parent::setEnd($date);
	}



	/**
	 * Set range start by date
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Integer		$day
	 */
	public function setStartDate($year, $month, $day) {
		parent::setStartDate($year, $month, $day, 0, 0, 0);
	}



	/**
	 * Set range end by date
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Integer		$day
	 */
	public function setEndDate($year, $month, $day) {
		parent::setEndDate($year, $month, $day, 23, 59, 59);
	}


	public function setDate($date) {
		$this->setStart($date);
		$this->setEnd($date);
	}



	/**
	 * Get duration of this range in days
	 *
	 * @param	Bool	$absolute
	 * @return	Integer
	 */
	public function getDiffInDays($absolute = false) {
		return round($this->getDiff($absolute) / TodoyuTime::SECONDS_DAY, 0);
	}



	/**
	 * Get timestamps for days in range
	 * The timestamps always have the time 00:00:00 for all days inside the range
	 *
	 * @param	String|Boolean	$format		Format timestamp with date and given format (false = integer)
	 * @return	Array
	 */
	public function getDayTimestamps($format = false) {
		$dateStart	= $this->getStart();
		$dateEnd	= $this->getEnd();
		$day		= date('j', $dateStart);
		$month		= date('n', $dateStart);
		$year		= date('Y', $dateStart);
		$count		= 0;
		$date		= $dateStart;
		$days		= array();

			// Loop while end date not reached
		while( $date < $dateEnd ) {
			$date	= mktime(0, 0, 0, $month, $day + $count, $year);
			$days[]	= $date;
			$count++;
		}

			// Remove last date. It is after the end date
		array_pop($days);

			// Format?
		if( $format ) {
			foreach($days as $index => $timestamp) {
				$days[$index] = date($format, $timestamp);
			}
		}

		return $days;
	}



	/**
	 * Get array with a key for every day in the range
	 * By default, it's the timestamp, but when format is a string, it will be formatted with date()
	 * Value is the value which will be set for every item
	 *
	 * @param	Boolean		$format
	 * @param	Mixed		$value
	 * @return	Array
	 */
	public function getDayTimestampsMap($format = false, $value = 0) {
		$timestamps	= $this->getDayTimestamps($format);

		return TodoyuArray::createMap($timestamps, $value);
	}

}

?>