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
 * General time functions
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuTime {

	/**
	 * @var	Integer		Seconds per minute
	 */
	const SECONDS_MIN	= 60;

	/**
	 * @var	Integer		Seconds per hour
	 */
	const SECONDS_HOUR	= 3600;

	/**
	 * @var	Integer		Seconds per day
	 */
	const SECONDS_DAY	= 86400;

	/**
	 * @var	Integer		Seconds per week
	 */
	const SECONDS_WEEK	= 604800;



	/**
	 * Get timestamp of start of day
	 *
	 * @param	Integer		$timestamp
	 * @return	Integer
	 */
	public static function getStartOfDay($timestamp = false) {
		$timestamp = $timestamp === false ? NOW : intval($timestamp);

		return mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
	}



	/**
	 * Make timestamp for end of day
	 *
	 * @param	Integer		$timestamp
	 * @return	Integer
	 */
	public static function getEndOfDay($timestamp = false) {
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
			'start'	=> self::getStartOfDay($timestamp),
			'end'	=> self::getEndOfDay($timestamp)
		);
	}



	/**
	 * Get timestamps of start and of week that contains the given timestamp
	 *
	 * @param	Integer	$timestamp
	 */
	public static function getWeekRange($timestamp) {
		$timestamp	= intval($timestamp);
		$start		= self::getWeekstart($timestamp);

		return array(
			'start'	=> $start,
			'end'	=> $start + self::SECONDS_WEEK - 1
		);
	}



	/**
	 * Get range (start and end timestamp) of month
	 *
	 * @param	Integer		$timestamp
	 * @return	Array
	 */
	public static function getMonthRange($timestamp) {
		return array(
			'start'	=> self::getMonthStart($timestamp),
			'end'	=> self::getMonthEnd($timestamp)
		);
	}



	/**
	 * Get start and end timestamp of every day in the week of the timestamp
	 *
	 * @param		Integer		$timestamp		Timestamp
	 * @return 		Integer		Timestamp of beginning of week the given timestamp belongs to
	 */
	public static function getWeekStart($timestamp) {
		$diff	= (date('w', $timestamp)+6)%7;

		return mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp)-$diff, date('Y', $timestamp));
	}



	/**
	 * Get timestamp for the end of the week (last second in the week)
	 *
	 * @param	Integer		$timestamp
	 * @return	Integer
	 */
	public static function getWeekEnd($timestamp) {
		$diff	= (7-date('w', $timestamp))%7;

		return mktime(23, 59, 59, date('n', $timestamp), date('j', $timestamp)+$diff, date('Y', $timestamp));
	}



	/**
	 * Get timestamp of first day of month
	 *
	 * @param	Integer	$timestamp
	 */
	public static function getMonthStart($timestamp) {
		$timestamp	= intval($timestamp);

		return mktime(0, 0, 0, date('n', $timestamp), 1, date('Y', $timestamp));
	}



	/**
	 * Get timestamp for end of month (last second in the month, 23:59:59)
	 *
	 * @param	Integer		$timestamp
	 * @return	Integer
	 */
	public static function getMonthEnd($timestamp) {
		return mktime(0, 0, 0, date('n', $timestamp) + 1, 1, date('Y', $timestamp)) - 1;
	}



	/**
	 * Get timestamp of last of month of given timestamp
	 *
	 * @param	Integer		$timestamp
	 * @return	Integer
	 */
	public static function getLastDayOfMonth($timestamp = NOW) {
		$range  = self::getMonthRange($timestamp);

		return $range['end'];
	}



	/**
	 * Get day-number of last day of month of given timestamp
	 *
	 * @param	Integer		$timestamp
	 * @return	Integer
	 */
	public static function getNumberOfLastDayOfMonth($timestamp = NOW) {
		$timestampLastDay   = self::getLastDayOfMonth($timestamp);

		return date('j', $timestampLastDay);
	}



	/**
	 * Get weekday of a timestamp. Like date('w'), but starts with monday
	 * With $mondayFirst monday will be 0 and sunday 6
	 *
	 * @param	Integer		$timestamp
	 * @param	Boolean		$mondayFirst
	 * @return	Integer		0 = monday, 6 = sunday
	 */
	public static function getWeekday($timestamp, $mondayFirst = true) {
		$timestamp	= intval($timestamp);
		$weekday	= date('w', $timestamp);

		return ( $mondayFirst ) ? ($weekday + 6) % 7 : $weekday;
	}



	/**
	 * Get time parts (hours, minutes, seconds) from an integer which represents seconds
	 *
	 * @param	Integer		$seconds		Number of seconds
	 * @return	Array		[hours,minutes,seconds]
	 */
	public static function getTimeParts($seconds) {
		$seconds	= TodoyuNumeric::intPositive($seconds);
		$hours		= floor($seconds / self::SECONDS_HOUR);
		$seconds	= $seconds - $hours * self::SECONDS_HOUR;
		$minutes	= floor($seconds / self::SECONDS_MIN);
		$seconds	= $seconds - $minutes * self::SECONDS_MIN;

		return array(
			'hours'		=> $hours,
			'minutes'	=> $minutes,
			'seconds'	=> $seconds
		);
	}



	/**
	 * Get present amount of first hour of given amount of hours
	 *
	 * @todo	What does this function? Right place?
	 * @param	Float $hours
	 * @return	Float
	 */
	public static function firstHourLeftOver($hours) {
		if( $hours > 1.0 ) {
			$hours	= 1.0;
		} elseif( $hours <= 0.0 ) {
			$hours	= 0.0;
		}

		return $hours;
	}



	/**
	 * Convert seconds (integer) to a readable format with hours and minutes (03:10 = 3 hours and 10 minutes)
	 *
	 * @param	Integer		$seconds		Seconds
	 * @return	String		Formatted
	 */
	public static function sec2hour($seconds) {
		$timeParts	= self::getTimeParts($seconds);

			// Round up minute, if more than 30 seconds
		if( $timeParts['seconds'] >= 30 ) {
			$timeParts['minutes'] += 1;

				// If the minute round up caused 60 minutes, increment hour
			if( $timeParts['minutes'] == 60 ) {
				$timeParts['minutes'] = 0;
				$timeParts['hours'] += 1;
			}
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
	 * @param	Boolean		$round			Round or cut seconds
	 * @return	String
	 */
	public static function formatTime($seconds, $withSeconds = false, $round = true) {
		$seconds	= intval($seconds);
		$timeParts	= self::getTimeParts($seconds);

		if( $withSeconds ) {
			$formatted	= sprintf('%02d:%02d:%02d', $timeParts['hours'], $timeParts['minutes'], $timeParts['seconds']);
		} else {
			if( $round && $timeParts['seconds'] >= 30 ) {
				$timeParts['minutes'] += 1;
			}
			$formatted	= sprintf('%02d:%02d', $timeParts['hours'], $timeParts['minutes']);
		}

		return $formatted;
	}



	/**
	 * Format a timestamp with one of todoyu's default date formats
	 *
	 * @see		core/config/dateformat.xml
	 * @param	Integer		$timestamp
	 * @param	String		$formatName
	 * @return	String		Formatted date
	 */
	public static function format($timestamp, $formatName = 'datetime') {
		$timestamp	= intval($timestamp);

		if( $timestamp === 0 ) {
			return '-';
		}

		$format		= self::getFormat($formatName);
		$string		= strftime($format, $timestamp);

			// If server locale file is not yet UTF8, convert the string
		if( ! TodoyuString::isUTF8($string) ) {
			$string = utf8_encode($string);
		}

		return $string;
	}



	/**
	 * Get format config string
	 *
	 * @see		core/config/dateformat.xml
	 * @param	String		$formatName
	 * @return	String
	 */
	public static function getFormat($formatName) {
		$localeKey	= 'core.dateformat.' . $formatName;

		return TodoyuLabelManager::getLabel($localeKey);
	}



	/**
	 * Parse date string with check if its a dateString or a dateTimeString
	 *
	 * @param	String	$dateString
	 * @return	Integer
	 */
	public static function parseDateString($dateString) {
		$time = self::parseDateTime($dateString);

			// if parseDateTime did not work, try parseDate
		if( $time === 0 ) {
			$time = self::parseDate($dateString);
		}

		return $time;
	}



	/**
	 * Parse date string (formatted according to current locale) to UNIX timestamp
	 *
	 * @param	String 		$dateString
	 * @return	Integer		UNIX timestamp
	 */
	public static function parseDate($dateString) {
		$dateString	= trim($dateString);
		$time		= 0;
		$parsedTime	= strtotime($dateString);

		if( $parsedTime !== false ) {
			$time = $parsedTime;
		} elseif( $dateString !== '' ) {
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
	 * Parse date time string (get UNIX timestamp)
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
			$dateParts['tm_year']	+= 1900;
			$dateParts['tm_mon']	+= 1;

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
	 * Parse time string to UNIX timestamp (time format is based on the format time or timesec)
	 *
	 * @param	String		$timeString		Time string: 23:59 or 23:59:59 (function auto-detects seconds part)
	 * @return	Integer		Seconds
	 */
	public static function parseTime($timeString) {
		$colons		= substr_count($timeString, ':');
		$format		= $colons === 2 ? self::getFormat('timesec') : self::getFormat('time') ;
		$timeParts	= strptime($timeString, $format);

		$hours	= intval($timeParts['tm_hour']);
		$minutes= intval($timeParts['tm_min']);
		$seconds= intval($timeParts['tm_sec']);

		return $hours * self::SECONDS_HOUR + $minutes * self::SECONDS_MIN + $seconds;
	}



	/**
	 * Parse duration to seconds (format: 32:50)
	 *
	 * @param	String		$timeString
	 * @return	Integer
	 */
	public static function parseDuration($timeString) {
		$parts	= explode(':', $timeString);

		return intval($parts[0])* self::SECONDS_HOUR + intval($parts[1]) * self::SECONDS_MIN;
	}



	/**
	 * Round minutes by given steps
	 *
	 * @param	Integer		$time
	 * @param	Integer		$steps
	 * @return	Integer		Rounded time
	 */
	public static function getRoundedTime($time = 0, $steps = 5) {
		$time	= intval($time);
		$factor	= intval( self::SECONDS_MIN / $steps);

		if( $time === 0 ) {
			$time = NOW;
		}

		$currentMinutes	= intval(date('i', $time));
		$roundedMinutes	= intval(round(($currentMinutes * $factor) / self::SECONDS_MIN, 0) * $steps);
		$currentSeconds	= intval(date('s', $time));
		$newTime		= $time + ($roundedMinutes - $currentMinutes) * self::SECONDS_MIN - $currentSeconds;

		return $newTime;
	}



	/**
	 * Get dates of the (days of) full week which the given date is in
	 *
	 * @param		Mixed		$time		Timestamp
	 * @return 		Array		Dates of the week
	 */
	public static function getTimestampsForWeekdays($time) {
		$time		= intval($time);
		$weekRange	= self::getWeekRange($time);
		$dayTimes	= array();

		for($dayTime = $weekRange['start']; $dayTime < $weekRange['end']; $dayTime += self::SECONDS_DAY) {
			$dayTimes[] = $dayTime;
		}

		return $dayTimes;
	}



	/**
	 * Get (timestamps at 00:00 of) days inside given timespan
	 *
	 * @param	Integer		$from
	 * @param	Integer		$until
	 * @return	Array
	 */
	public static function getDaysInTimespan($from, $until) {
		$timestamp	= self::getStartOfDay($from);
		$until		= intval($until);

		$days	= array();
		while($timestamp <= $until) {
			$days[]	= self::getStartOfDay($timestamp);
			$timestamp	+= TodoyuTime::SECONDS_DAY;
		}

		return $days;
	}



	/**
	 * Get dates of the days (at 00:00) which intersect the two given timespans
	 *
	 * @param	Integer		$start1
	 * @param	Integer		$end1
	 * @param	Integer		$start2
	 * @param	Integer		$end2
	 * @return	Array
	 */
	public static function getIntersectingDays($start1, $end1, $start2, $end2) {
		$start1	= intval($start1);
		$end1	= intval($end1);
		$start2	= intval($start2);
		$end2	= intval($end2);

		$span1	= self::getDaysInTimespan($start1, $end1);	// view
		$span2	= self::getDaysInTimespan($start2, $end2);	// event

		return array_intersect($span2, $span1);
	}



	/**
	 * Get days in month for the month of the timestamp
	 * Use monthDelta to query an other month (ex: last = -1, next = 1, etc)
	 *
	 * @param	Integer		$timestamp
	 * @param	Integer		$monthDelta
	 * @return	Integer
	 */
	public static function getDaysInMonth($timestamp, $monthDelta = 0) {
		$monthDelta	= intval($monthDelta);

		if( $monthDelta !== 0 ) {
			$year	= date('Y', $timestamp);
			$month	= date('n', $timestamp);

				// get timestamp of previous month
			$timestamp	= mktime(0, 0, 0, ($month + $monthDelta), 1, $year);
		}

		return date('t', $timestamp);
	}



	/**
	 * Check whether two time ranges overlap.
	 *
	 * @param	Integer		$dateStart1
	 * @param	Integer		$dateEnd1
	 * @param	Integer		$dateStart2
	 * @param	Integer		$dateEnd2
	 * @return	Boolean
	 */
	public static function rangeOverlaps($dateStart1, $dateEnd1, $dateStart2, $dateEnd2) {
		$dateStart1	= intval($dateStart1);
		$dateEnd1	= intval($dateEnd1);
		$dateStart2	= intval($dateStart2);
		$dateEnd2	= intval($dateEnd2);

		if( $dateEnd2 <= $dateStart1 || $dateStart2 >= $dateEnd1 ) {
			return false;
		}

		return true;
	}



	/**
	 * Round-UP given time in seconds to given rounding minute
	 *
	 * @param	Integer		$timestamp
	 * @param	Integer		$roundingMinute
	 * @return	Integer							rounded time in seconds
	 */
	public static function roundUpTime($timestamp, $roundingMinute = 1) {
		$roundingSeconds	=	$roundingMinute * self::SECONDS_MIN;

		return intval( ceil( intval($timestamp) / $roundingSeconds ) * $roundingSeconds );
	}



	/**
	 * Add given amount of days to given date
	 *
	 * @param	Integer		$time
	 * @param	Integer		$days
	 * @return	Integer
	 */
	public static function addDays($time, $days) {
		$time	= intval($time);
		$days	= intval($days);

		return mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time), date('j', $time)+$days, date('Y', $time));
	}
}

?>