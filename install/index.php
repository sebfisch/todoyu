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
 * Main file for todoyu installer
 *
 * @package		Todoyu
 * @subpackage	Installer
 */

	// Override PATH_WEB to simulate normal script run
define('PATH_WEB_OVERRIDE', dirname(dirname($_SERVER['SCRIPT_NAME'])));

 	// Change current work directory to main directory to prevent path problems
chdir( dirname(dirname(__FILE__)) );


	// Stop here if insufficient php version
if( version_compare(PHP_VERSION, '5.2.0') === -1 ) {
	die('YOU NEED AT LEAST PHP v5.2.0 TO WORK WITH todoyu');
}


	// Load normal global.php file
require_once('core/inc/global.php');
	// Load installer config
include_once('install/config/steps.php');
include_once('install/config/config.php');


	// Check if _ENABLE file is available (installer has finished). Redirect to login
if( ! TodoyuInstaller::isEnabled() ) {
	TodoyuInstallerManager::finishInstallerAndJumpToLogin();
	exit();
}

	// Make sure the user is logged out
TodoyuSession::remove('person');
unset($_COOKIE['todoyulogin']);
TodoyuCookieLogin::removeRemainLoginCookie();

	// Deactivate extensions during installation
Todoyu::$CONFIG['WITHOUT_EXTENSIONS'] 	= true;
Todoyu::$CONFIG['NO_INIT'] 				= true;

	// Load default init script
require_once('core/inc/init.php');

	// Run the installer
TodoyuInstaller::run();

?>