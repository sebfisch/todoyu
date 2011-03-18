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
		$utf8		= 'スノーフレイクは、プレ';
		$ascii		= 'This is ASCII text';
		$iso8859_15	= 'Ich möchte Umlaute haben';

//		$this->assertTrue(TodoyuString::isUTF8($utf8));
//		$this->assertFalse(TodoyuString::isUTF8($ascii));
//		$this->assertFalse(TodoyuString::isUTF8($iso8859_15));



//		$utf8		= 'häöüllilu';
//		$nonUtf8	= 'Hallo';
//		$integer	= 6699;
//		$float		= 123.5;
//
//		$this->assertFalse(TodoyuString::isUTF8($nonUtf8));
//
//		$toUtf8	= utf8_encode($nonUtf8);
//		$this->assertTrue(TodoyuString::isUTF8($toUtf8));
//		$this->assertTrue($utf8);
//
//		$this->assertFalse(TodoyuString::isUTF8($integer) );
//		$this->assertFalse(TodoyuString::isUTF8($float) );
	}



	/**
	 * Test TodoyuString::convertToUTF8
	 *
	 * @todo Implement testConvertToUTF8().
	 */
	public function testConvertToUTF8() {
//		$string	= 'h��llilu';
//
//		$utf8	= TodoyuString::convertToUTF8($string);
//		$this->assertEquals(true, TodoyuString::isUTF8($utf8) );
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

		$this->assertEquals(true, TodoyuString::isValidEmail($emailValid));

		foreach($invalid as $emailInvalid) {
			$this->assertFalse(TodoyuString::isValidEmail($emailInvalid), 'email: ' . $emailInvalid );
		}
	}



	/**
	 * Test TodoyuString::crop
	 *
	 */
	public function testCrop() {
		$text	= 'Open source is a development method for software that harnesses the power of distributed peer review and transparency of process. The promise of open source is better quality, higher reliability, more flexibility, lower cost, and an end to predatory vendor lock-in.';
		$expect	= 'Open source is a:::';
		$cropped= TodoyuString::crop($text, 20, ':::');

		$this->assertEquals($expect, $cropped);

		$text	= 'スノーフレイクは、プレミアム・オープンソース・ソフトウェア（※）の実装（※）を手がける情報通信  ソリュー';
		$expect	= 'スノーフレイクは、プレミアム・オープンソ...';
		$cropped= TodoyuString::crop($text, 20);

		$this->assertEquals($expect, $cropped);
	}



	/**
	 * Test TodoyuString::wrap
	 *
	 */
	public function testWrap() {
		$text	= 'This is a Test';
		$wrap	= '<strong>|</strong>';
		$result	= TodoyuString::wrap($text, $wrap);
		$expect	= '<strong>This is a Test</strong>';

		$this->assertEquals($expect, $result);
	}



	/**
	 * Test TodoyuString::splitCamelCase
	 *
	 * @todo Implement testSplitCamelCase().
	 */
	public function testSplitCamelCase() {
		$camelCase	= 'TodoyuTaskAndProjectManagementSoftware';
		$parts		= TodoyuString::splitCamelCase($camelCase);

		$this->assertType('array', $parts);
		$this->assertEquals(6, sizeof($parts));
	}



	/**
	 * Test TodoyuString::html2text
	 *
	 * @todo Implement testHtml2text().
	 */
	public function testHtml2text() {
		$html_1		= '<strong>strong</strong>';
		$expect_1	= 'strong';
		$html_2		= 'line1<br>line2<br />line3';
		$expect_2	= "line1\nline2\nline3";
		$html_3		= '<ul><li>繁体字</li></ul>';
		$expect_3	= "繁体字";

		$result_1	= TodoyuString::html2text($html_1);
		$result_2	= TodoyuString::html2text($html_2, true);
		$result_3	= TodoyuString::html2text($html_3);

		$this->assertEquals($expect_1, $result_1);
		$this->assertEquals($expect_2, $result_2);
		$this->assertEquals($expect_3, $result_3);
	}



	/**
	 * Test TodoyuString::getSubstring
	 *
	 * @todo Implement testGetSubstring().
	 */
	public function testGetSubstring() {
		$text	= 'Open source is a development method for software that harnesses the power of distributed peer review and transparency of process. The promise of open source is better quality, higher reliability, more flexibility, lower cost, and an end to predatory vendor lock-in.';
		$sub	= TodoyuString::getSubstring($text, 'power');
		$expect	= 'that harnesses the power of distributed peer';

		$this->assertEquals($expect, $sub);
	}



	/**
	 * Test TodoyuString::generatePassword
	 *
	 * @todo Implement testGeneratePassword().
	 */
	public function testGeneratePassword() {
		$password	= TodoyuString::generatePassword();

		$this->assertEquals(8, strlen($password));

		$password	= TodoyuString::generatePassword(10, false, false);

		$this->assertEquals($password, strtolower($password));
		$this->assertNotRegExp('/\d/', $password);
	}



	/**
	 * Test TodoyuString::addToList
	 *
	 */
	public function testAddToList() {
		$list	= '1,2,3';

		$newList	= TodoyuString::addToList($list, 4);

		$this->assertEquals('1,2,3,4', $newList);
	}



	/**
	 * Test TodoyuString::isInList($item, $listString, $listSeparator = ',')
	 *
	 */
	public function testIsInList() {
		$list	= '3,test,345345.44,boarders,4,2,6,3';

		$this->assertTrue(TodoyuString::isInList(3, $list));
		$this->assertTrue(TodoyuString::isInList('test', $list));
		$this->assertTrue(TodoyuString::isInList(345345.44, $list));
		$this->assertFalse(TodoyuString::isInList(4444444, $list));
	}



	/**
	 * Test TodoyuString::formatSize($filesize, array $labels = null, $noLabel = false)
	 *
	 */
	public function testFormatSize() {
		$sizeB	= 756;			// 756 B
		$sizeKB	= 38242;		// 38.2 KB
		$sizeMB	= 34556789; 	// 34.5..MB
		$sizeGB	= 2560593443;	// 2.5 GB

		$expectB	= '756 B';
		$expectKB	= '37 KB';
		$expectMB	= '33 MB';
		$expectGB	= '2.4 GB';

		$formatB	= TodoyuString::formatSize($sizeB);
		$formatKB	= TodoyuString::formatSize($sizeKB);
		$formatMB	= TodoyuString::formatSize($sizeMB);
		$formatGB	= TodoyuString::formatSize($sizeGB);

		$this->assertEquals($expectB, $formatB);
		$this->assertEquals($expectKB, $formatKB);
		$this->assertEquals($expectMB, $formatMB);
		$this->assertEquals($expectGB, $formatGB);


		$noLabel	= TodoyuString::formatSize($sizeB, null, true);
		$expectNoL	= '756';

		$this->assertEquals($expectNoL, $noLabel);
	}

}

?>