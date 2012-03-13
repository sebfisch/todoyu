<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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

	/**
	 * @var TodoyuDateRange
	 */
	protected $range;

	public static function setUpBeforeClass() {
		self::$dateStart	= mktime(0, 0, 0, 1, 1, 2011);
		self::$dateEnd		= mktime(0, 0, 0, 2, 1, 2011);
	}

	public function setUp() {
		$this->range = $this->getRange();
	}


	/**
	 * @return	TodoyuDateRange
	 */
	protected function getRange() {
		return new TodoyuDateRange(self::$dateStart, self::$dateEnd);
	}

	public function testGetStart() {
		$this->assertEquals(self::$dateStart, $this->range->getStart());
	}


	public function testGetEnd() {
		$this->assertEquals(self::$dateEnd, $this->range->getEnd());
	}


	public function testSetStart() {
		$this->range->setStart(5);

		$this->assertEquals(5, $this->range->getStart());
	}


	public function testSetStartDate() {
		$date	= mktime(0, 0, 0, 1, 1, 2015);

		$this->range->setDateStart(2015, 1, 1);

		$this->assertEquals($date, $this->range->getStart());
	}

	public function testSetEnd() {
		$this->range->setEnd(5);

		$this->assertEquals(5, $this->range->getEnd());
	}


	public function testSetEndDate() {
		$date	= mktime(0, 0, 0, 1, 1, 2015);

		$this->range->setDateEnd(2015, 1, 1);

		$this->assertEquals($date, $this->range->getEnd());
	}

	public function testEndsBefore() {
		$this->range->setEnd(5);

		$this->assertTrue($this->range->endsBefore(6));
	}


	public function testStartsBefore() {
		$this->range->setStart(5);

		$this->assertTrue($this->range->startsBefore(6));
	}


	public function testSetRange() {
		$this->range->setRange(5, 6);

		$this->assertEquals(5, $this->range->getStart());
		$this->assertEquals(6, $this->range->getEnd());
	}

	public function testEndsAfter() {
		$this->range->setEnd(5);

		$this->assertTrue($this->range->endsAfter(4));
	}


	public function testStartsAfter() {
		$this->range->setStart(5);

		$this->assertTrue($this->range->startsAfter(4));
	}


	public function testIsActive() {
		$date1	= mktime(0,0,0,1,2,2011);
		$date2	= mktime(0,0,0,1,2,2012);

		$this->assertTrue($this->range->isActive($date1));
		$this->assertFalse($this->range->isActive($date2));
	}


	public function testIsPeriodInRange() {
		$date1	= mktime(0,0,0,1,2,2011);
		$date2	= mktime(0,0,0,1,3,2011);
		$date3	= mktime(0,0,0,2,3,2011);
		$date4	= mktime(0,0,0,2,5,2011);

		$this->assertTrue($this->range->isPeriodInRange($date1, $date2));
		$this->assertFalse($this->range->isPeriodInRange($date1, $date3));
		$this->assertTrue($this->range->isPeriodInRange($date1, $date3, true));
		$this->assertFalse($this->range->isPeriodInRange($date3, $date4, true));
	}

	public function testGetDiff() {
		$this->range->setStart(1);
		$this->range->setEnd(5);

		$this->assertEquals(4, $this->range->getDuration());
	}


	public function testSetStartLimit() {
		$this->range->setStart(5);

		$this->range->setStartLimit(6);

		$this->assertEquals(6, $this->range->getStart());
	}

	public function testSetEndLimit() {
		$this->range->setEnd(5);

		$this->range->setEndLimit(4);

		$this->assertEquals(4, $this->range->getEnd());
	}


	public function testIsFullYearRange() {
		$this->range->setDateStart(2011, 1, 1);
		$this->range->setDateEnd(2011, 12, 31);

		$this->assertTrue($this->range->isFullYearRange());

		$this->range->setDateEnd(2011, 12, 30);

		$this->assertFalse($this->range->isFullYearRange());
	}


	public function testIsFullMonthRange() {
		$this->range->setDateStart(2011, 1, 1);
		$this->range->setDateEnd(2011, 1, 31);

		$this->assertTrue($this->range->isFullMonthRange());

		$this->range->setDateEnd(2011, 1, 30);

		$this->assertFalse($this->range->isFullMonthRange());
	}

	public function testIsInOneYear() {
		$this->range->setDateStart(2011, 1, 1);
		$this->range->setDateEnd(2011, 8, 1);

		$this->assertTrue($this->range->isInOneYear());

		$this->range->setDateEnd(2012, 1, 1);

		$this->assertFalse($this->range->isInOneYear());
	}


	public function testIsInOneMonth() {
		$this->range->setDateStart(2011, 1, 1);
		$this->range->setDateEnd(2011, 1, 20);

		$this->assertTrue($this->range->isInOneMonth());

		$this->range->setDateEnd(2012, 2, 1);

		$this->assertFalse($this->range->isInOneMonth());
	}


	public function testIsStartStartOfMonth() {
		$this->range->setDateStart(2011, 1, 1);

		$this->assertTrue($this->range->isStartStartOfMonth());

		$this->range->setDateStart(2011, 1, 2);

		$this->assertFalse($this->range->isStartStartOfMonth());
	}


	public function testIsEndEndOfMonth() {
		$this->range->setDateEnd(2011, 1, 31);

		$this->assertTrue($this->range->isEndEndOfMonth());

		$this->range->setDateEnd(2011, 1, 30);

		$this->assertFalse($this->range->isEndEndOfMonth());
	}

	public function testGetLabel() {
		Todoyu::setLocale('en_GB');

		$this->range->setDateStart(2011, 1, 1);
		$this->range->setDateEnd(2011, 12, 31);
		$expect	= '2011';
		$this->assertEquals($expect, $this->range->getLabel());

		$this->range->setDateStart(2011, 1, 1);
		$this->range->setDateEnd(2011, 1, 31);
		$expect	= 'January 2011';
		$this->assertEquals($expect, $this->range->getLabel());

		$this->range->setDateStart(2011, 1, 1);
		$this->range->setDateEnd(2011, 3, 31);
		$expect	= 'January 2011 - March 2011';
		$this->assertEquals($expect, $this->range->getLabel());

		$this->range->setDateStart(2011, 1, 2);
		$this->range->setDateEnd(2011, 3, 31);
		$expect	= 'January 02 2011 - March 2011';
		$this->assertEquals($expect, $this->range->getLabel());

		$this->range->setDateStart(2011, 1, 2);
		$this->range->setDateEnd(2012, 3, 30);
		$expect	= 'January 02 2011 - March 30 2012';
		$this->assertEquals($expect, $this->range->getLabel());
	}


	public function test__toString() {
		$this->range->setDateStart(2011, 1, 1);
		$this->range->setDateEnd(2011, 1, 2);

		$expected	= 'Sat, 01 Jan 2011 00:00:00 +0100 - Sun, 02 Jan 2011 00:00:00 +0100';

		$this->assertEquals($expected, trim($this->range));
	}

}

?>