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
 * Test for: TodoyuNumeric
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuNumericTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Array
	 */
	private $array;



	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->array = array(

		);

	}



	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}



	/**
	 * Test TodoyuNumeric::intInRange($integer, $min = 0, $max = 2000000000)
	 */
	public function testIntInRange() {
		$this->assertEquals(50, TodoyuNumeric::intInRange(50, 0, 100) );
		$this->assertEquals(-50, TodoyuNumeric::intInRange(-50, -100, 100) );

		$this->assertEquals(100, TodoyuNumeric::intInRange(500, 1, 100) );
		$this->assertEquals(-100, TodoyuNumeric::intInRange(-500, -100, 100) );
	}



	/**
	 * Test TodoyuNumeric::intPositive
	 *
	 */
	public function testIntPositive() {
		$this->assertEquals(0, TodoyuNumeric::intPositive(0));
		$this->assertEquals(0, TodoyuNumeric::intPositive(-10));
		$this->assertEquals(10, TodoyuNumeric::intPositive(10));
	}



	/**
	 * Test TodoyuNumeric::percent($percent, $value)
	 *
	 * @todo Implement testPercent().
	 */
	public function testPercent() {
		$result_1	= TodoyuNumeric::percent(100, 47.4);
		$result_2	= TodoyuNumeric::percent(100, 101);
		$result_3	= TodoyuNumeric::percent(100, 0.5, true);
		$result_4	= TodoyuNumeric::percent(100, 0.5);
		$result_5	= TodoyuNumeric::percent(100, 0.001, true);

		$this->assertEquals(47.4, $result_1);
		$this->assertEquals(101.0, $result_2);
		$this->assertEquals(50.0, $result_3);
		$this->assertEquals(0.5, $result_4);
		$this->assertEquals(0.1, $result_5);
	}


}

?>