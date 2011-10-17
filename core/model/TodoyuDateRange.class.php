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
 * Date range element
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuDateRange {

	/**
	 * Start date
	 *
	 * @var	Integer
	 */
	protected $dateStart;

	/**
	 * End date
	 *
	 * @var	Integer
	 */
	protected $dateEnd;
	


	/**
	 * Initialize with range
	 * @param $dateStart
	 * @param $dateEnd
	 */
	public function __construct($dateStart, $dateEnd) {
		$this->setRange($dateStart, $dateEnd);
	}



	/**
	 * Get start date
	 *
	 * @return	Integer
	 */
	public function getStart() {
		return $this->dateStart;
	}



	/**
	 * Get end date
	 *
	 * @return	Integer
	 */
	public function getEnd() {
		return $this->dateEnd;
	}



	/**
	 * Set start date
	 *
	 * @param	Integer		$date
	 */
	public function setStart($date) {
		$this->dateStart = TodoyuTime::getStartOfDay($date);
	}



	/**
	 * Set end date
	 *
	 * @param	Integer		$date
	 */
	public function setEnd($date) {
		$this->dateEnd = TodoyuTime::getEndOfDay($date);
	}



	/**
	 * Set range start by date
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Integer		$day
	 */
	public function setStartDate($year, $month, $day) {
		$this->setStart(mktime(0, 0, 0, $month, $day, $year));
	}

	

	/**
	 * Set range end by date
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Integer		$day
	 */
	public function setEndDate($year, $month, $day) {
		$this->setEnd(mktime(0, 0, 0, $month, $day, $year));
	}



	/**
	 * Set range dates (start/end)
	 *
	 * @param	Integer		$dateStart
	 * @param	Integer		$dateEnd
	 */
	public function setRange($dateStart, $dateEnd) {
		$this->setStart($dateStart);
		$this->setEnd($dateEnd);
	}



	/**
	 * Check whether this range ends before the given date
	 *
	 * @param	Integer		$date
	 * @return	Boolean
	 */
	public function endsBefore($date) {
		$date	= intval($date);

		return $this->dateEnd < $date;
	}



	/**
	 * Check whether this range starts before the given date
	 *
	 * @param	Integer		$date
	 * @return	Boolean
	 */
	public function startsBefore($date) {
		$date	= intval($date);

		return $this->dateStart < $date;
	}



	/**
	 * Check whether this range ends after the given date
	 *
	 * @param	Integer		$date
	 * @return	Boolean
	 */
	public function endsAfter($date) {
		$date	= intval($date);

		return $this->dateEnd > $date;
	}



	/**
	 * Check whether this range starts after the given date
	 *
	 * @param	Integer		$date
	 * @return	Boolean
	 */
	public function startsAfter($date) {
		$date	= intval($date);

		return $this->dateStart > $date;
	}



	/**
	 * Check whether the range is active at the given date
	 * If no date given, use current date
	 *
	 * @param	Integer		$date
	 * @return	Boolean
	 */
	public function isActive($date = 0) {
		$date	= intval($date);

		if( $date === 0 ) {
			$date = time();
		}

		return $this->isInRange($date);
	}



	/**
	 * Check whether this range is (partly) in the period between the given start and end date
	 *
	 * @param	Integer		$dateStart
	 * @param	Integer		$dateEnd
	 * @param	Boolean		$partly			It's enough when the range and the period just overlap at some date
	 * @return	Boolean
	 */
	public function isPeriodInRange($dateStart, $dateEnd, $partly = false) {
		$dateStart	= intval($dateStart);
		$dateEnd	= intval($dateEnd);

		if( $partly ) {
			return $this->isInRange($dateStart) || $this->isInRange($dateEnd);
		} else {
			return $this->startsBefore($dateStart) && $this->endsAfter($dateEnd);
		}
	}



	/**
	 * Check whether the given date is inside of this range
	 *
	 * @param	Integer		$date
	 * @return	Boolean
	 */
	protected function isInRange($date) {
		$date = intval($date);
		
		return $this->startsBefore($date) && $this->endsAfter($date);
	}



	/**
	 * Get duration of this range in seconds
	 *
	 * @param	Boolean		$absolute			Make sure the difference is absolute
	 * @return	Integer
	 */
	public function getDiff($absolute = true) {
		$diff	= intval($this->dateEnd - $this->dateStart);

		if( $absolute ) {
			$diff = abs($diff);
		}

		return $diff;
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
	 * Set limit for end date
	 * If end date exceeds the limit, it will be adjusted
	 * The limit doesn't affect later operations
	 *
	 * @param	Integer		$dateEnd
	 */
	public function setEndLimit($dateEnd) {
		$dateEnd	= intval($dateEnd);

		if( $this->getEnd() > $dateEnd ) {
			$this->setEnd($dateEnd);
		}
	}



	/**
	 * Set limit for start date
	 * If start date exceeds the limit, it will be adjusted
	 * The limit doesn't affect later operations
	 *
	 * @param	Integer		$dateStart
	 */
	public function setStartLimit($dateStart) {
		$dateStart	= intval($dateStart);

		if( $this->dateStart < $dateStart ) {
			$this->dateStart = $dateStart;
		}	
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
	 * @param	Boolean	$format
	 * @param	Mixed	$value
	 * @return	Array
	 */
	public function getDayTimestampsMap($format = false, $value = 0) {
		$timestamps	= $this->getDayTimestamps($format);

		return TodoyuArray::createMap($timestamps, $value);
	}

	

	/**
	 * Get debug string of range
	 *
	 * @return	String
	 */
	public function __toString() {
		return date('r', $this->getStart()) . ' - ' . date('r', $this->getEnd());
	}

}

?>