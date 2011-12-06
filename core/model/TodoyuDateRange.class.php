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
 * The two constructor parameters are start and end of the range
 * start	end		result
 * 0		0		full range
 * 0		x		everything before x
 * x		0		everything from x
 * x		y		everything between x and y
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
	 * Minimal date for maximal range
	 *
	 * @var	Integer
	 */
	protected $dateMin = -2145920400;

	/**
	 * Maximal date for maximal range
	 *
	 * @var	Integer
	 */
	protected $dateMax = 2145913200;
	


	/**
	 * Initialize with range
	 * 
	 * @param	Integer		$dateStart
	 * @param	Integer		$dateEnd
	 */
	public function __construct($dateStart = 0, $dateEnd = 0) {
		$dateStart	= intval($dateStart);
		$dateEnd	= intval($dateEnd);

		if( $dateStart === 0 ) {
			$dateStart = $this->dateMin;
		}

		if( $dateEnd === 0 ) {
			$dateEnd = $this->dateMax;
		}

		$this->setRange($dateStart, $dateEnd);
	}



	/**
	 * Set to maximum ranges
	 * 1910-2037 should be enough
	 *
	 */
	public function setMaxRanges() {
		$this->setStart(PHP_INT_MIN);
		$this->setEnd(PHP_INT_MAX);
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
		$this->dateStart = intval($date);
	}



	/**
	 * Set end date
	 *
	 * @param	Integer		$date
	 */
	public function setEnd($date) {
		$this->dateEnd = intval($date);
	}



	/**
	 * Set range start by date
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Integer		$day
	 * @param	Integer		$hour
	 * @param	Integer		$minute
	 * @param	Integer		$second
	 */
	public function setStartDate($year, $month, $day, $hour = 0, $minute = 0, $second = 0) {
		$this->setStart(mktime($hour, $minute, $second, $month, $day, $year));
	}

	

	/**
	 * Set range end by date
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Integer		$day
	 * @param	Integer		$hour
	 * @param	Integer		$minute
	 * @param	Integer		$second
	 */
	public function setEndDate($year, $month, $day, $hour = 0, $minute = 0, $second = 0) {
		$this->setEnd(mktime($hour, $minute, $second, $month, $day, $year));
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
	 * @param	Boolean		$allowSame
	 * @return	Boolean
	 */
	public function startsBefore($date, $allowSame = false) {
		$date	= intval($date);

		if( $allowSame ) {
			return $this->dateStart <= $date;
		} else {
			return $this->dateStart < $date;
		}
	}



	/**
	 * Check whether this range ends after the given date
	 *
	 * @param	Integer		$date
	 * @param	Boolean		$allowSame
	 * @return	Boolean
	 */
	public function endsAfter($date, $allowSame = false) {
		$date	= intval($date);

		if( $allowSame ) {
			return $this->dateEnd >= $date;
		} else {
			return $this->dateEnd > $date;
		}
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
		$date	= TodoyuTime::time($date);

		return $this->isInRange($date);
	}



	/**
	 * Check whether this range is (partly) in the period between the given start and end date
	 *
	 * @param	Integer		$dateStart
	 * @param	Integer		$dateEnd
	 * @param	Boolean		$partly			It's enough when the range and the period just overlap at some date
	 * @param	Boolean		$allowLimits	Allow the period to start or end exactly at the start or end date
	 * @return	Boolean
	 */
	public function isPeriodInRange($dateStart, $dateEnd, $partly = false, $allowLimits = false) {
		$dateStart	= intval($dateStart);
		$dateEnd	= intval($dateEnd);

		if( $partly ) {
			return $this->isInRange($dateStart, $allowLimits) || $this->isInRange($dateEnd, $allowLimits);
		} else {
			return $this->startsBefore($dateStart, $allowLimits) && $this->endsAfter($dateEnd, $allowLimits);
		}
	}



	/**
	 * Check whether the given date is inside of this range
	 *
	 * @param	Integer		$date
	 * @param	Boolean		$allowLimits		Allow the date to be at the start or end date
	 * @return	Boolean
	 */
	public function isInRange($date, $allowLimits = true) {
		$date = intval($date);
		
		return $this->startsBefore($date, $allowLimits) && $this->endsAfter($date, $allowLimits);
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
			$this->setStart($dateStart);
		}
	}



	/**
	 * Check whether dateRange spans one full year (01.01. to 12.31.)
	 *
	 * @return	Boolean
	 */
	public function isFullYearRange() {
		return $this->isInOneYear() && date('m-d', $this->getStart()) === '01-01' && date('m-d', $this->getEnd()) === '12-31';
	}



	/**
	 * Check whether dateRange spans one full month
	 *
	 * @return	Boolean
	 */
	public function isFullMonthRange() {
		return $this->isInOneMonth() && $this->isStartStartOfMonth() && $this->isEndEndOfMonth();
	}



	/**
	 * Check whether dateRange span lays within one (start/end the same) year
	 *
	 * @return	Boolean
	 */
	public function isInOneYear() {
		return date('Y', $this->getStart()) === date('Y', $this->getEnd());
	}



	/**
	 * Check whether dateRange span lays within one (start/ end the same) month
	 *
	 * @return	Boolean
	 */
	public function isInOneMonth() {
		return date('Y-m', $this->getStart()) === date('Y-m', $this->getEnd());
	}



	/**
	 * Check whether dateRange starts at 1st day of month
	 *
	 * @return	Boolean
	 */
	public function isStartStartOfMonth() {
		return date('d', $this->getStart()) === '01';
	}



	/**
	 * Check whether dateRange ends on last day of month
	 *
	 * @return	Boolean
	 */
	public function isEndEndOfMonth() {
		$lastDay	= date('t', $this->getEnd());

		return date('d', $this->getEnd()) === $lastDay;
	}



	/**
	 * Get dates as array
	 * 
	 * @return	Array	[start,end]
	 */
	public function getDates() {
		return array(
			'start'	=> $this->getStart(),
			'end'	=> $this->getEnd()
		);
	}



	/**
	 * Get label for range
	 * Format depends on start, end times
	 * - Full year:
	 *
	 * @return	String
	 */
	public function getLabel() {
			// Full year range: 2011
		if( $this->isFullYearRange() ) {
			return date('Y', $this->getStart());
		}

			// Full month range: January 2011
		if( $this->isFullMonthRange() ) {
			return TodoyuTime::format($this->getStart(), 'MlongY4');
		}

			// Starts on first of the month: January 2011 / January 13 2011
		if( $this->isStartStartOfMonth() ) {
			$start	= TodoyuTime::format($this->getStart(), 'MlongY4');
		} else {
			$start	= TodoyuTime::format($this->getStart(), 'D2MlongY4');
		}

			// Ends on last of the month. March / March 13
		if( $this->isEndEndOfMonth() ) {
			$end	= TodoyuTime::format($this->getEnd(), 'MlongY4');
		} else {
			$end	= TodoyuTime::format($this->getEnd(), 'D2MlongY4');
		}

		return $start . ' - ' . $end;
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