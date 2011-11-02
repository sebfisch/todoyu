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
	 * 
	 * @param	Integer		$dateStart
	 * @param	Integer		$dateEnd
	 */
	public function __construct($dateStart = 0, $dateEnd = 0) {
		$dateStart	= intval($dateStart);
		$dateEnd	= intval($dateEnd);

		if( $dateStart === 0 && $dateEnd === 0 ) {
			$this->setMaxRanges();
		} else {
			if( $dateStart > 0 && $dateEnd === 0 ) {
				$dateEnd = $dateStart;
			}

			$this->setRange($dateStart, $dateEnd);
		}
	}



	/**
	 * Set to maximum ranges
	 * 1910-2037 should be enough
	 *
	 */
	public function setMaxRanges() {
		$this->setStart(-1893463200);
		$this->setEnd(2114377200);
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
		$date	= TodoyuTime::time($date);

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


	public function isFullYearRange() {
		return $this->isInOneYear() && date('m-d', $this->getStart()) === '01-01' && date('m-d', $this->getEnd()) === '12-31';
	}


	public function isFullMonthRange() {
		return $this->isInOneMonth() && $this->isStartStartOfMonth() && $this->isEndEndOfMonth();
	}

	public function isInOneYear() {
		return date('Y', $this->getStart()) === date('Y', $this->getEnd());
	}


	public function isInOneMonth() {
		return date('Y-m', $this->getStart()) === date('Y-m', $this->getEnd());
	}

	public function isStartStartOfMonth() {
		return date('d', $this->getStart()) === '01';
	}


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