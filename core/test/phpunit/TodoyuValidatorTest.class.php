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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuValidatorTest extends PHPUnit_Framework_TestCase {

	public function testIsEmail() {
		$this->assertTrue(TodoyuValidator::isEmail('team@todoyu.com'));
		$this->assertTrue(TodoyuValidator::isEmail('a@bc.de'));
		$this->assertTrue(TodoyuValidator::isEmail('with-dash@sub.domain.com'));
		$this->assertFalse(TodoyuValidator::isEmail('with space@sub.domain.com'));
	}

	public function testIsDigit() {
		$this->assertTrue(TodoyuValidator::isDigit(1));
		$this->assertTrue(TodoyuValidator::isDigit('0'));
		$this->assertTrue(TodoyuValidator::isDigit(-2342342));
		$this->assertFalse(TodoyuValidator::isDigit('a10'));
	}

	public function testIsNumber() {
		$this->assertTrue(TodoyuValidator::isNumber(1));
		$this->assertTrue(TodoyuValidator::isNumber(-234));
		$this->assertTrue(TodoyuValidator::isNumber(0));
		$this->assertTrue(TodoyuValidator::isNumber('123'));
		$this->assertFalse(TodoyuValidator::isNumber('1d'));
	}


	public function testIsDecimal() {
		$this->assertTrue(TodoyuValidator::isDecimal(1));
		$this->assertTrue(TodoyuValidator::isDecimal(2.32423));
	}



}

?>