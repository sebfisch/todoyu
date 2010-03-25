<?php
/*********************************************************************
* todoyu 2.0 is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions
* are met:
*
*    * Redistributions of source code must retain the above copyright
*      notice, this list of conditions and the following disclaimer.
*    * Redistributions in binary form must reproduce the above
*      copyright notice, this list of conditions and the following
*      disclaimer in the documentation and/or other materials provided
*      with the distribution.
*    * Neither the name of the snowflake productions gmbh nor the names
*      of its contributors may be used to endorse or promote products
*      derived from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
* A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
* HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,INCIDENTAL,
* SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
* LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
* DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
* THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
* OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
**********************************************************************/


	// Measure processing time
define('TIME_START', microtime(true));

	// Include global include file
require_once('core/inc/global.php');
	// Load default init script
require_once('core/inc/init.php');

	// Send "no cache" header
TodoyuHeader::sendNoCacheHeaders();
TodoyuHeader::sendHeaderHTML();

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