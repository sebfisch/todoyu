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
 * todoyu iCalender
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuIcal {

	/**
	 * @var		vcalendar
	 */
	const calendar = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		require_once( PATH_LIB . DIR_SEP . 'php' . DIR_SEP . 'iCalcreator' . DIR_SEP . 'iCalcreator.class.php' );

			// Set unique ID
		$config = array(
			'unique_id' => Todoyu::$CONFIG['SYSTEM']['todoyuURL']
		);

			// Create new calendar instance
		$this->calendar = new vcalendar($config);

		$this->calendar->setProperty( 'method', 'PUBLISH');
		$this->calendar->setProperty( "x-wr-calname", 'todoyu Calendar');
		$this->calendar->setProperty( "X-WR-CALDESC", 'todoyu Calendar');
		$this->calendar->setProperty( "X-WR-TIMEZONE", Todoyu::$CONFIG['SYSTEM']['timezone']);

		return $this->calendar;
	}



	/**
	 * Add given event to vCalendar
	 *
	 * @param 	Array		$eventData
	 */
	public function addEvent(array $eventData) {
		$isPrivateEvent	= intval($eventData['is_private']) === 1;

		if( $isPrivateEvent ) {
				// Add private event to calendar as freebusy component only
			$this->addFreebusy($eventData);
		} else {
				// Add event component to calendar
			$vEvent = & $this->calendar->newComponent( 'vevent' );

				// Set props
			$vEvent->setProperty( 'dtstart', array(
				'year'	=> date('Y', $eventData['date_start']),
				'month'	=> date('n', $eventData['date_start']),
				'day'	=> date('j', $eventData['date_start']),
				'hour'	=> date('G', $eventData['date_start']),
				'min'	=> date('i', $eventData['date_start']),
				'sec'	=> date('s', $eventData['date_start']),
			));

			$vEvent->setProperty( 'dtend', array(
				'year'	=> date('Y', $eventData['date_end']),
				'month'	=> date('n', $eventData['date_end']),
				'day'	=> date('j', $eventData['date_end']),
				'hour'	=> date('G', $eventData['date_end']),
				'min'	=> date('i', $eventData['date_end']),
				'sec'	=> date('s', $eventData['date_end']),
			));

			$vEvent->setProperty('summary',		$eventData['title']);

			if( ! empty($eventData['description']) ) {
				$vEvent->setProperty('description',	$eventData['description']);
			}

//			$vEvent->setProperty('comment',		'');

				// Add attendees (email address)
			foreach($eventData['attendees'] as $personAttend) {
				$idPersonAttend	= $personAttend['id_person'];
				if( $idPersonAttend > 0 ) {
					$person	= TodoyuContactPersonManager::getPerson($idPersonAttend);
					$vEvent->setProperty( 'attendee', $person->getEmail());
				}
			}

			if( ! empty($eventData['place']) ) {
				$vEvent->setProperty('LOCATION',	$eventData['place']);
			}
		}
	}



	/**
	 * Add freebusy period of given event data to vCalendar
	 *
	 * @param 	Array		$eventData
	 * @param	String		$freebusyType	'BUSY' (default) / 'BUSY-UNAVAILABLE' / 'BUSY-TENTATIVE'
	 */
	public function addFreebusy(array $eventData, $freebusyType = 'BUSY') {
			// Create an event calendar component
		$vEvent = & $this->calendar->newComponent( 'vfreebusy' );
			// Set props
		$fbPeriods	= array(
			array(
				array('timestamp'	=> $eventData['date_start']),
				array('timestamp'	=> $eventData['date_end'])
			)
		);
		$vEvent->setProperty( 'freebusy', $freebusyType, $fbPeriods);
	}



	/**
	 * Get iCal formatted calendar data
	 *
	 * @return	String
	 */
	public function render() {
		return $this->calendar->createCalendar();
	}

}

?>