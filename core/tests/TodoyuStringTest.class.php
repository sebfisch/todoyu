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
 * Test for: TodoyuString
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuStringTest extends PHPUnit_Framework_TestCase {

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
	 * Test TodoyuString::isUTF8
	 */
	public function testIsUTF8() {
		$string	= 'hдцllilu';
		$integer= 6699;
		$float	= 123.5;

		$nonUtf8	= $string;
		$this->assertNotEquals(true, TodoyuString::isUTF8($nonUtf8) );

		$utf8	= utf8_encode($string);
		$this->assertEquals(true, TodoyuString::isUTF8($utf8) );

		$this->assertNotEquals(true, TodoyuString::isUTF8($integer) );
		$this->assertNotEquals(true, TodoyuString::isUTF8($float) );
	}



	/**
	 * Test TodoyuString::convertToUTF8
	 *
	 * @todo Implement testConvertToUTF8().
	 */
	public function testConvertToUTF8() {
		$string	= 'hдцllilu';

		$uft8	= TodoyuString::convertToUTF8($string);
		$this->assertEquals(true, TodoyuString::isUTF8($utf8) );
	}



	/**
	 * Test TodoyuString::isValidEmail
	 *
	 * @todo Implement testIsValidEmail().
	 */
	public function testIsValidEmail() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuString::crop
	 *
	 * @todo Implement testCrop().
	 */
	public function testCrop() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuString::wrap
	 *
	 * @todo Implement testWrap().
	 */
	public function testWrap() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuString::splitCamelCase
	 *
	 * @todo Implement testSplitCamelCase().
	 */
	public function testSplitCamelCase() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuString::html2text
	 *
	 * @todo Implement testHtml2text().
	 */
	public function testHtml2text() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuString::getSubstring
	 *
	 * @todo Implement testGetSubstring().
	 */
	public function testGetSubstring() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuString::generatePassword
	 *
	 * @todo Implement testGeneratePassword().
	 */
	public function testGeneratePassword() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

}

?>