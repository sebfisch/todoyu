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
 * @subpackage	Hosting
 */
class TodoyuDateRangeTest extends PHPUnit_Framework_TestCase {

	protected static $dateStart;

	protected static $dateEnd;

	public static function setUpBeforeClass() {
		self::$dateStart	= mktime(0, 0, 0, 1, 1, 2011);
		self::$dateEnd		= mktime(0, 0, 0, 2, 1, 2011);
	}


	/**
	 * @return	TodoyuDateRange
	 */
	protected function getRange() {
		return new TodoyuDateRange(self::$dateStart, self::$dateEnd);
	}

	public function testGetStart() {
		$range	= $this->getRange();

		$this->assertEquals(self::$dateStart, $range->getStart());
	}


	public function testGetEnd() {
		$range	= $this->getRange();

		$this->assertEquals(self::$dateEnd, $range->getEnd());
	}


	public function testSetStart() {
		$range	= $this->getRange();

		$range->setStart(5);

		$this->assertEquals(5, $range->getStart());
	}

	public function testSetEnd() {
		$range	= $this->getRange();

		$range->setEnd(5);

		$this->assertEquals(5, $range->getEnd());
	}

	public function testEndsBefore() {
		$range	= $this->getRange();

		$range->setEnd(5);

		$this->assertTrue($range->endsBefore(6));
	}


	public function testStartsBefore() {
		$range	= $this->getRange();

		$range->setStart(5);

		$this->assertTrue($range->startsBefore(6));
	}


	public function testSetRange() {
		$range	= $this->getRange();

		$range->setRange(5, 6);

		$this->assertEquals(5, $range->getStart());
		$this->assertEquals(6, $range->getEnd());
	}

	public function testEndsAfter() {
		$range	= $this->getRange();

		$range->setEnd(5);

		$this->assertTrue($range->endsAfter(4));
	}


	public function testStartsAfter() {
		$range	= $this->getRange();

		$range->setStart(5);

		$this->assertTrue($range->startsAfter(4));
	}


	public function testIsActive() {
		$range	= $this->getRange();
		$date	= mktime(0,0,0,1,2,2011);

		$this->assertTrue($range->isActive($date));
	}


	public function testIsPeriodInRange() {
		$range	= $this->getRange();
		$date1	= mktime(0,0,0,1,2,2011);
		$date2	= mktime(0,0,0,1,3,2011);
		$date3	= mktime(0,0,0,2,3,2011);
		$date4	= mktime(0,0,0,2,5,2011);

		$this->assertTrue($range->isPeriodInRange($date1, $date2));
		$this->assertFalse($range->isPeriodInRange($date1, $date3));
		$this->assertTrue($range->isPeriodInRange($date1, $date3, true));
		$this->assertFalse($range->isPeriodInRange($date3, $date4, true));
	}

	public function testGetDiff() {
		$range	= $this->getRange();

		$range->setStart(1);
		$range->setEnd(5);

		$this->assertEquals(4, $range->getDiff());
	}

}

?>