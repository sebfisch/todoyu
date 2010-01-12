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
 * Main file for todoyu installer
 *
 * @package		Todoyu
 * @subpackage	Installer
 */

include_once( dirname(__FILE__) . '/config/init.php');
include_once( dirname(__FILE__) . '/config/steps.php');



	// Turn on output buffering
ob_start();

	// Include global include file
require_once(PATH_CORE . '/inc/global.php');

	// Make sure the user is logged out
TodoyuAuth::logout();

	// Load default init script
require_once( PATH_CORE . '/inc/init.php');
require_once( PATH_CORE .'/inc/version.php');

	// Run the actual installer
TodoyuInstaller::run();

	// Flush output buffer
ob_end_flush();

?>