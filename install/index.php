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

	// Activate error reporting
error_reporting(E_ALL ^ E_NOTICE);

	// Change current work directory to main directory to prevent path problems
chdir(dirname(dirname(__FILE__)));

	// Declare PATH constants
require_once( dirname(__FILE__) . '/../core/config/constants.php');

	// Check if _ENABLE file is available (installer has finished). Redirect to login
if( is_file(PATH . '/install/_ENABLE') ) {
	@unlink(PATH . '/index.html');
	header('Location: ../index.php');
	exit();
}

	// Turn on output buffering
ob_start();

	// Include global include file
require_once(PATH_CORE . '/inc/global.php');

	// Make sure the user is logged out
TodoyuAuth::logout();

	// Load default init script
require_once( PATH_CORE . '/inc/init.php');
require_once( PATH_CORE .'/inc/version.php');

	// Restart?
if( $_GET['restart'] == 1 ) {
	TodoyuInstaller::setStepNum(0);
	header('Location: ' . $_SERVER['SCRIPT_NAME']);
	exit();
}

	// If data has been submitted, process it
if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	$error = TodoyuInstaller::processStep($_POST);
}

	// Display step output
TodoyuInstaller::displayStep($error);

	// Flush output buffer
ob_end_flush();

?>