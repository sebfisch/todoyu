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
 * Configuration for continuous integration setup
 * Used to run unit tests and code analysis on hudson server
 */
$SETUPCONFIG['db'] = array(
	'server'		=> 'localhost',
	'username'		=> 'todoyu',
	'password'		=> '0kbcBbQPi',
	'database'		=> 'todoyu',
	'autoconnect'	=> true,
	'persistent'	=> true,
	'queryHistory'	=> true
);

$SETUPCONFIG['system'] = array(
	'name'			=> 'todoyu hudson ci',
	'email'			=> 'team@todoyu.com',
	'locale'		=> 'de_DE',
	'timezone'		=> 'Europe/Zurich',
	'encryptionKey'	=> 'abcdefghijklmnopqrstuvwxyz1234567890'
);

?>