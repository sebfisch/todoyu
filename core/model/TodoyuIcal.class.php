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
	 * @var	vcalendar
	 */
	public $calendar;



	/**
	 * Constructor
	 * 
	 * @param	String	$hash			Calendar token hash
	 * @param	String	$name			Calendar name
	 * @param	String	$description	Calendar description
	 */
	public function __construct($hash = '', $name = 'todoyu Calendar', $description = '') {
		require_once( PATH_LIB . DIR_SEP . 'php' . DIR_SEP . 'iCalcreator' . DIR_SEP . 'iCalcreator.class.php' );

			// Create new calendar instance
		$config = array(
//			'unique_id' => Todoyu::$CONFIG['SYSTEM']['todoyuURL']
			'unique_id' => 'token=' . $hash
		);
		$this->calendar = new vcalendar($config);

			// Init required properties
		$this->setMethod('PUBLISH');
		$this->setName($name);
		$this->setDescription($description);
		$this->setTimeZone();

		return $this->calendar;
	}



	/**
	 * Set calendar name property (x-wr-calname)
	 *
	 * @param	String	$name
	 */
	public function setMethod($method = 'PUBLISH') {
		$this->calendar->setProperty('method', $method);
	}



	/**
	 * Set calendar name property (x-wr-calname)
	 *
	 * @param	String	$name
	 */
	public function setName($name) {
		$this->calendar->setProperty('x-wr-calname', $name);
	}



	/**
	 * Set calendar description property (X-WR-CALDESC)
	 *
	 * @param	String $description
	 */
	public function setDescription($description = '')	{
		$this->calendar->setProperty('X-WR-CALDESC', $description);
	}



	/**
	 * Set calendar timezone property (X-WR-TIMEZONE)
	 *
	 * @param	String $timezone
	 */
	public function setTimezone($timezone = '') {
		if( empty($timezone) ) {
				// Default: take timezone from todoyu
			$timezone	= Todoyu::$CONFIG['SYSTEM']['timezone'];
		}

		$this->calendar->setProperty('X-WR-TIMEZONE', $timezone);
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
				/** @var vevent $vEvent */
			$vEvent = & $this->calendar->newComponent('vevent');

				// Set organizer (person create)
			$idPersonOrganizer	= $eventData['id_person_create'];
			$organizerEmail	= TodoyuContactPersonManager::getPerson($idPersonOrganizer)->getEmail();
			if( ! empty($organizerEmail) ) {
				$vEvent->setProperty('ORGANIZER', $organizerEmail);
			}

				// Set date start
			$dateStart	= TodoyuIcalManager::getDateParts($eventData['date_start']);
			$vEvent->setProperty('dtstart', $dateStart);

				// Set date end
			$dateEnd	= TodoyuIcalManager::getDateParts($eventData['date_end']);
			$vEvent->setProperty('dtend', $dateEnd);

				// Add summary (from title)
			$vEvent->setProperty('summary',	$eventData['title']);

				// Add description
			if( ! empty($eventData['description']) ) {
//				$description	= nl2br($eventData['description']);
				$description	= TodoyuString::cleanRTEText($eventData['description']);

				$vEvent->setProperty('description',	$description);
			}

				// Add attendees (email address)
			foreach($eventData['attendees'] as $personAttend) {
				$idPersonAttend	= $personAttend['id_person'];
				if( $idPersonAttend > 0 ) {
					$attendeeEmail	= TodoyuContactPersonManager::getPerson($idPersonAttend)->getEmail();
					if( ! empty($attendeeEmail) ) {
						$vEvent->setProperty('attendee', $attendeeEmail);
					}
				}
			}

				// Add place (location) of event
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
			/** @var vfreebusy $vEvent */
		$vEvent = & $this->calendar->newComponent('vfreebusy');

			// Set organizer (person create)
		$idPersonOrganizer	= $eventData['id_person_create'];
		$organizerEmail	= TodoyuContactPersonManager::getPerson($idPersonOrganizer)->getEmail();
		if( ! empty($organizerEmail) ) {
			$vEvent->setProperty('ORGANIZER', $organizerEmail);
		}

			// Set start, end
//		$dateStart	= TodoyuIcalManager::getDateParts($eventData['date_start']);
//		$dateEnd	= TodoyuIcalManager::getDateParts($eventData['date_end']);
//		$periods	= array($dateStart, $dateEnd);
//		$fbPeriods	= array($periods);
		$dateStart	= TodoyuIcalManager::getDateParts($eventData['date_start']);
		$vEvent->setProperty('dtstart', $dateStart);

		$dateEnd	= TodoyuIcalManager::getDateParts($eventData['date_end']);
		$vEvent->setProperty('dtend', $dateEnd);

			// Set freebusy type
		$vEvent->setProperty('freebusy', $freebusyType, $fbPeriods);
	}



	/**
	 * Get iCal formatted calendar data
	 *
	 * @return	String
	 */
	public function render() {
		return $this->calendar->createCalendar();
	}



	/**
	 * Send iCal file to browser via HTTP redirect header
	 */
	public function send() {
		return $this->calendar->returnCalendar();
	}
}

?>