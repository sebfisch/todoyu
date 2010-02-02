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
 * Test for: TodoyuArray
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuArrayTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test getColumn()
	 *
	 */
	public function testGetColumn() {
		$array	= array(
			array('id'	=> 32),
			array('id'	=> 45),
			array('id'	=> 12),
			array('id'	=> 84),
			array('id'	=> 15)
		);

		$idColumn	= TodoyuArray::getColumn($array, 'id');

		$this->assertEquals(32, $idColumn[0]);
	}



	/**
	 * Test getFirstKey()
	 *
	 */
	public function testGetFirstKey() {
		$array	= array(
			'firstname'	=> 'Max',
			'lastname'	=> 'Miller',
			'street'	=> 'Franklin Street'
		);

		$firstKey	= TodoyuArray::getFirstKey($array);

		$this->assertEquals('firstname', $firstKey);
	}

}

?>