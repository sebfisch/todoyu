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

	// Measure processing time
define('TIME_START', microtime(true));

	// Include global include file
require_once('core/inc/global.php');
	// Load default init script
require_once('core/inc/init.php');

	// Send "no cache" header
TodoyuHeader::sendNoCacheHeaders();
TodoyuHeader::sendTypeHTML();

	// Start output buffering
ob_start();


	// Load all boot.php files of the installed extensions
TodoyuExtensions::loadAllBoot();

	// Define request vars as constants
TodoyuRequest::initRequest();

	// Load all init.php files of the installed extensions
TodoyuExtensions::loadAllInit();

	// Dispatch request to selected controller
TodoyuActionDispatcher::dispatch();


	// Measure processing time
define('TIME_END', microtime(true));
define('TIME_TOTAL', TIME_END - TIME_START);

	// Include finishing script
require( PATH_CORE. '/inc/finish.php' );

	// Query debugging
//if( $_GET['qh'] == 1 ) {
//	TodoyuDebug::printHtml(Todoyu::db()->getQueryHistory());
//}

	// Send output
ob_end_flush();

?>