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
 * General time functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuTime {

	const SECONDS_MIN	= 60;
	const SECONDS_HOUR	= 3600;
	const SECONDS_DAY	= 86400;
	const SECONDS_WEEK	= 604800;

	/**
	 * Make timestamp for start of day
	 *
	 * @param	Integer		$timestamp
	 * @return	Integer
	 */
	public static function timestampStartOfDay($timestamp = false) {
		$timestamp = $timestamp === false ? NOW : intval($timestamp);

		return mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
	}



	/**
	 * Make timestamp for end of day
	 *
	 * @param	Integer		$timestamp
	 * @return	Integer
	 */
	public static function timestampEndOfDay($timestamp = false) {
		$timestamp = $timestamp === false ? NOW : intval($timestamp);

		return mktime(23, 59, 59, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
	}



	/**
	 * Get timestamps for the first and the last second of a day
	 *
	 * @param	Integer		$timestamp
	 * @return	Array		[start,end]
	 */
	public static function getDayRange($timestamp = false) {
		return array(
		'start'	=> self::timestampStartOfDay($timestamp),
		'end'	=> self::timestampEndOfDay($timestamp)
		);
	}


	public static function getWeekRange($timestamp) {
		$timestamp	= intval($timestamp);
		$start		= self::getWeekstart($timestamp);
		$end		= $start + 7 * 86400 - 1;

		return array(
		'start'	=> $start,
		'end'	=> $end
		);
	}

	public static function getMonthRange($timestamp) {
		$timestamp	= intval($timestamp);
		$start		= self::getMonthStart($timestamp);
		$end		= mktime(23, 59, 59, date('n', $start), date('t', $start), date('Y', $start));

		return array(
		'start'	=> $start,
		'end'	=> $end
		);
	}




	/**
	 * Get start and end timestamp of every day in the week of the timestamp
	 *
	 * @param		Integer		$timestamp		Timstamp
	 * @return 		Integer		Timestamp of monday of the week the given timestamp belongs to
	 */
	public static function getWeekStart($timestamp) {
		$timestamp	= intval($timestamp);
		$dayStart	= self::getDayStart($timestamp);
		$weekday	= self::getWeekday($timestamp, true);
		$weekStart	= $dayStart - $weekday * 86400;

		return $weekStart;
	}



	/**
	 * Get timestamp for the day in timestamp, but with hours, minutes and seconds = 0
	 *
	 * @param	Integer		$timestamp
	 * @return	integer
	 */
	public static function getDayStart($timestamp) {
		$timestamp	= intval($timestamp);

		return mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
	}

	public static function getMonthStart($timestamp) {
		$timestamp	= intval($timestamp);

		return mktime(0, 0, 0, date('n', $timestamp), 1, date('Y', $timestamp));
	}



	/**
	 * Get weekday of a timestamp. With $mondayFirst monday will be 0 and sunday 6
	 *
	 * @param	Integer		$timestamp
	 * @param	Bool		$mondayFirst
	 * @return	Integer		0 = monday, 6 = sunday
	 */
	public static function getWeekday($timestamp, $mondayFirst = true) {
		$timestamp	= intval($timestamp);
		$weekday	= date('w', $timestamp);

		if( $mondayFirst ) {
			$weekday= ($weekday+6)%7;
		}

		return $weekday;
	}



	/**
	 * Get time parts (hours, minutes, seconds) from an integer
	 * which represents seconds
	 *
	 * @param	Integer		$seconds		Number of seconds
	 * @return	Array		[hours,minutes,seconds]
	 */
	public static function getTimeParts($seconds) {
		$seconds	= TodoyuDiv::intPositive($seconds);
		$hours		= floor($seconds / 3600);
		$seconds	= $seconds - $hours * 3600;
		$minutes	= floor($seconds / 60);
		$seconds	= $seconds - $minutes * 60;

		return array(
		'hours'		=> $hours,
		'minutes'	=> $minutes,
		'seconds'	=> $seconds
		);
	}



	/**
	 * Convert seconds (integer) to a readable format with hours and minutes (03:10 = 3 hours and 10 minutes)
	 *
	 * @param	Integer		$seconds		Seconds
	 * @return	String		Formatted
	 */
	public static function sec2hour($seconds) {
		$timeParts	= self::getTimeParts($seconds);

		if( $timeParts['seconds'] >= 30 ) {
			$timeParts['minutes'] += 1;
		}

		return sprintf('%02d:%02d', $timeParts['hours'], $timeParts['minutes']);
	}



	/**
	 * Convert seconds (integer) to a readable format with hours, minutes and seconds (01:10:45 = 1 hour, 10 minutes and 45 seconds)
	 *
	 * @param	Integer		$seconds
	 * @return	String
	 */
	public static function sec2time($seconds) {
		$timeParts	= self::getTimeParts($seconds);

		return sprintf('%02d:%02d:%02d', $timeParts['hours'], $timeParts['minutes'], $timeParts['seconds']);
	}



	/**
	 * Format time values 23:59 or 23:59:59
	 *
	 * @param	Integer		$seconds
	 * @param	Boolean		$withSeconds
	 * @return	String
	 */
	public static function formatTime($seconds, $withSeconds = false) {
		$seconds	= intval($seconds);
		$timeParts	= self::getTimeParts($seconds);

		if( $withSeconds ) {
			$formated	= sprintf('%02d:%02d:%02d', $timeParts['hours'], $timeParts['minutes'], $timeParts['seconds']);
		} else {
			if( $timeParts['seconds'] >= 30 ) {
				$timeParts['minutes'] += 1;
			}
			$formated	= sprintf('%02d:%02d', $timeParts['hours'], $timeParts['minutes']);
		}

		return $formated;
	}



	/**
	 * Parse date string with check if its a dateString or a dateTimeString
	 *
	 * @param	String	$dateString
	 * @return	Integer
	 */
	public static function parseDateString($dateString)	{
		$time = self::parseDateTime($dateString);

		// If parseDateTime did not work, try parseDate
		if( $time === false ) {
			$time = self::parseDate($dateString);
		}

		return $time;
	}



	/**
	 * Parse date string
	 *
	 * @param	String 		$dateString
	 * @return	Integer		Unix timestamp
	 */
	public static function parseDate($dateString) {
		$dateString	= trim($dateString);
		$time		= 0;

		if( $dateString !== '' ) {
			$format		= self::getFormat('date');
			$dateParts	= strptime($dateString, $format);

				// Fix for built in function (windows function works correctly)
			if( PHP_OS !== 'WINNT' && $dateParts !== false ) {
				$dateParts['tm_year']	= $dateParts['tm_year'] + 1900;
				$dateParts['tm_mon']	= $dateParts['tm_mon'] + 1;
			}

			$time = mktime(0, 0, 0, $dateParts['tm_mon'], $dateParts['tm_mday'], $dateParts['tm_year']);
		}

		return $time;
	}



	/**
	 * Parse date time string
	 *
	 * @param	String		$dateTimeString
	 * @return	Integer
	 */
	public static function parseDateTime($dateTimeString) {
		$format		= self::getFormat('datetime');
		$dateParts	= strptime($dateTimeString, $format);

		if( $dateParts === false ) {
			return 0;
		}

		if( array_key_exists('timestamp', $dateParts) ) {
			return $dateParts['timestamp'];
		} else {
			$dateParts['tm_year'] += 1900;
			$dateParts['tm_mon'] += 1;

			return mktime(
				$dateParts['tm_hour'],
				$dateParts['tm_min'],
				$dateParts['tm_sec'],
				$dateParts['tm_mon'],
				$dateParts['tm_mday'],
				$dateParts['tm_year']
			);
		}
	}



	/**
	 * Parse time string
	 * Timeformat is based on the format time or timesec
	 *
	 * @param	String		$timeString		Time string: 23:59 or 23:59:59 (for the second you have to set $withSeconds)
	 * @param	Boolean		$withSeconds	Timestring
	 * @return	Integer		Seconds
	 */
	public static function parseTime($timeString, $withSeconds = false) {
		$format		= $withSeconds ? self::getFormat('timesec') : self::getFormat('time') ;
		$timeParts	= strptime($timeString, $format);

		$hours	= intval($timeParts['tm_hour']);
		$minutes= intval($timeParts['tm_min']);
		$seconds= intval($timeParts['tm_sec']);

		return $hours * 3600 + $minutes * 60 + $seconds;
	}



	/**
	 * Format a timestamp with one of the default dateformats in todoyu
	 *
	 * @see		core/config/dateformat.xml
	 * @param	Integer		$timestamp
	 * @param	String		$formatName
	 * @return	String		Formatted date
	 */
	public static function format($timestamp, $formatName = 'datetime') {
		$timestamp	= intval($timestamp);
		$format		= self::getFormat($formatName);

		return strftime($format, $timestamp);
	}



	/**
	 * Get format config string
	 *
	 * @see		core/config/dateformat.xml
	 * @param	String		$formatName
	 * @return	String
	 */
	public static function getFormat($formatName) {
		$localeKey	= 'dateformat.' . $formatName;

		return TodoyuLocale::getLabel($localeKey);
	}



	/**
	 * Get number of day of week of given timestamp (0 = sunday, 1 = monday... or 0 = monday, 1 = tuesday..)
	 *
	 * @param	Integer			$timestamp
	 * @return	Integer
	 */
	public static function getWeekdayNum( $timestamp, $startWithMonday = false ) {
		$dayNum = date('w', $timestamp);

		if ($startWithMonday) {
			$dayNum -= 1;
			if ($dayNum == -1) {
				$dayNum = 6;
			}
		}

		return $dayNum;
	}



	/**
	 * Round timestamp to next full or half an hour
	 *
	 * @param	Integer	$time		Timestamp
	 * @return	Integer				rounded timestamp
	 */
	public static function roundToHalfHour( $timestamp ) {
		$timestamp	= intval($timestamp);
		$timestamp	= ceil( $timestamp / 600 ) * 600;

		$roundedTime	= date('H:i', $timestamp);
		switch( substr($roundedTime, - 2) ) {
			case '10':	case '40':
				$timestamp += 1200;
				break;


			case '20':	case '50':
				$timestamp += 600;
				break;
		}

		return $timestamp;
	}



	/**
	 * Get seconds of day's time (seconds since 00:00:00 of day of given timestamp)
	 *
	 *	@param	Integer	$timestamp		UNIX timestamp
	 *	@return	Integer
	 */
	public function getSecondsOfDayTime($timestamp) {
		$secondsOfHours		= date('G', $timestamp)	* 60 * 60;
		$secondsOfMinutes	= date('i', $timestamp)	* 60;
		$seconds			= date('s', $timestamp);

		return $secondsOfHour + $secondsOfMinutes + $seconds;
	}



	/**
	 * Get weeknumber (1-52) of year of given date
	 *
	 * @param 	Integer	$timestamp
	 * @return	String	Number
	 */
	public static function getWeeknumber($timestamp) {

		return date('W', $timestamp);
	}



	/**
	 * Get number of week of month
	 *
	 * @param 	Integer $timestamp
	 * @return	String	Date
	 */
	public static function getWeekOfMonth( $timestamp = NOW ) {
		return ceil( date( 'j', $timestamp )/7 );
	}



	/**
	 * Get dates of the (days of) full week which the given date is in
	 *
	 * @param		Mixed		$time		Timestamp
	 * @return 		Array		Dates of the week
	 */
	public static function getDayTimesOfWeek($time) {
		$time		= intval($time);
		$weekRange	= self::getWeekRange($time);
		$dayTimes	= array();

		for($dayTime = $weekRange['start']; $dayTime < $weekRange['end']; $dayTime += self::SECONDS_DAY) {
			$dayTimes[] = $dayTime;
		}

		return $dayTimes;
	}




	/**
	 * Get amount of days in month (of timestamp, or in a "shifted" month before / after that, depending of "shiftMonthBy"-offset)
	 *
	 * @param 	Integer	$month	Month to be searched
	 * @param 	Integer	$year	Year to be searched
	 * @return	Integer	Days of last month as a number
	 */
	public static function getAmountOfDaysInMonth($timestamp, $shiftMonthBy = 0) {
		$year	= date('Y', $timestamp);
		$month	= date('n', $timestamp);

		// get timestamp of previous month
		$timestamp	= mktime(0, 0, 0, ($month + $shiftMonthBy), 1, $year);

		return date( 't' , $timestamp );
	}




	/**
	 * Finds whether the timespans within the given boundaries intersect
	 *
	 * @param	Integer	$start1		UNIX timestamp start of span1
	 * @param	Integer	$end1		UNIX timestamp end of span1
	 * @param	Integer	$start2		UNIX timestamp start of span2
	 * @param	Integer	$end2		UNIX timestamp end of span2
	 * @return	Boolean
	 */
	public function spansIntersect($start1, $end1, $start2, $end2) {
		if (		// span2 ends within span1
		($end2 >= $start1 && $end2 <= $end1)

		// span2 lays within span1
		||	($start2 >= $start1 && $end2 <= $end1)

		// span2 starts within span 1
		||	($start2 >= $start1 && $start2 <= $end1)

		// span2 wraps span1
		||	($start2 <= $start1 && $end2 >= $end1)
		) {
			$intersect = true;

			if ($start2 == $end1 || $start1 == $end2) {
				$intersect = false;
			}

		} else {
			$intersect = false;
		}

		return $intersect;
	}



	/**
	 * Check if two time ranges overlap.
	 *
	 * @param	Integer		$dateStart1
	 * @param	Integer		$dateEnd1
	 * @param	Integer		$dateStart2
	 * @param	Integer		$dateEnd2
	 * @return	Bool
	 */
	public static function rangeOverlaps($dateStart1, $dateEnd1, $dateStart2, $dateEnd2) {
		$dateStart1	= intval($dateStart1);
		$dateEnd1	= intval($dateEnd1);
		$dateStart2	= intval($dateStart2);
		$dateEnd2	= intval($dateEnd2);

		if( $dateEnd2 <= $dateStart1 ) {
			return false;
		}

		if( $dateStart2 >= $dateEnd1 ) {
			return false;
		}

		return true;
	}



	/**
	 * Returns the timestamps of the last cyle borders.
	 *
	 * eg: param = 1
	 *
	 * start/end of last month
	 *
	 * param = 3
	 *
	 * start/end of last quarter
	 *
	 * @param	Int $monthStart;
	 */
	public static function getCycleBorderDates($monthsPerCycle = 0)	{
		$monthStart = mktime(0, 0, 0, date('n')-$monthsPerCycle, 1, date('Y'));

		return array('start' => $monthStart, 'end' => mktime(23, 59, 59, (date('n')-$monthsPerCycle), date('t', $monthStart), date('Y')));
	}


}

?>