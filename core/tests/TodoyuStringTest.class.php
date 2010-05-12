<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
		$utf8		= 'häöüllilu';
		$nonUtf8	= 'Hallo';
		$integer	= 6699;
		$float		= 123.5;

		$this->assertFalse(TodoyuString::isUTF8($nonUtf8));

		$toUtf8	= utf8_encode($nonUtf8);
		$this->assertTrue(TodoyuString::isUTF8($toUtf8));
		$this->assertTrue($utf8);

		$this->assertFalse(TodoyuString::isUTF8($integer) );
		$this->assertFalse(TodoyuString::isUTF8($float) );
	}



	/**
	 * Test TodoyuString::convertToUTF8
	 *
	 * @todo Implement testConvertToUTF8().
	 */
	public function testConvertToUTF8() {
		$string	= 'h��llilu';

		$utf8	= TodoyuString::convertToUTF8($string);
		$this->assertEquals(true, TodoyuString::isUTF8($utf8) );
	}



	/**
	 * Test TodoyuString::isValidEmail
	 *
	 * @todo Implement testIsValidEmail().
	 */
	public function testIsValidEmail() {
		$emailValid		= 'testmann@snowflake.ch';

		$invalid	= array(
			'test.@ann@snowflake.ch',
			'www.snowflake.ch',
			'http://www.snowflake.ch/',
			'h@ttp://www.snowflake.ch/',
			'@snowflake.ch',
			'www@snowflakech'
		);

		$this->assertEquals(true, TodoyuString::isValidEmail($emailValid) );

		foreach($invalid as $emailInvalid) {
			$this->assertFalse(TodoyuString::isValidEmail($emailInvalid), 'tested invalid email: ' . $emailInvalid );
		}
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



	/**
	 * Test TodoyuString::addToList
	 *
	 * @todo Implement testAddToList().
	 */
	public function testAddToList() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuString::isInList($item, $listString, $listSeparator = ',')
	 *
	 * @todo Implement testIsInList().
	 */
	public function testIsInList() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuString::formatSize($filesize, array $labels = null, $noLabel = false)
	 *
	 * @todo Implement testFormatSize().
	 */
	public function testFormatSize() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

}

?>