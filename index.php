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

	// Measure processing time
define('TIME_START', microtime(true));

	// Include global include file
require_once('core/inc/global.php');
	// Load default init script
require_once('core/inc/init.php');

	// Send "no cache" header
TodoyuHeader::sendNoCacheHeaders();


	// Start output buffering
ob_start();

	// Get valid request variables (here it will be checked for login, etc)
$requestVars	= TodoyuRequest::getCurrentRequestVars();

	// Set definitive request vars as constants
define('EXT',		$requestVars['ext']);
define('CONTROLLER',$requestVars['ctrl']);
define('ACTION', 	$requestVars['action']);
define('AREA', 		$requestVars['area']);

	// Dispatch request to selected controller
TodoyuActionDispatcher::dispatch();

	// Measure processing time
define('TIME_END', microtime(true));
define('TIME_TOTAL', TIME_END - TIME_START);

	// Include finishing script
require( PATH_CORE. '/inc/finish.php' );

	// Query debugging
if( $_GET['qh'] == 1 ) {
	TodoyuDebug::printHtml(Todoyu::db()->getQueryHistory());
}

	// Send output
ob_end_flush();

?>