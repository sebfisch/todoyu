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
 * This file is called from command line by the continuous integration server Hudson
 *
 * Steps:
 *  - Load configuration
 *  - Update database connection file
 *  - Load database connection file
 *  - Update database with table configuration from core and extensions
 *  - Import basic and demo data for testing
 *  - Update system configuration
 *  - Install the unittest extension to run unittests
 */

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
ini_set('show_errors', true);

require_once(realpath(dirname(__FILE__) . '/../inc/global.php'));
require_once(PATH_CORE . '/config/continuous_integration.php');

	// Prevent todoyu init
Todoyu::$CONFIG['INIT'] = false;

	// Initialize todoyu
require_once(PATH_CORE . '/inc/init.php');

	// Load all extensions
TodoyuExtensions::loadAllExtensions();

	// Setup DB connection
TodoyuInstallerManager::saveDbConfigInFile(Todoyu::$CONFIG['CI']['db']);

require(PATH . '/config/db.php');



	// Update database
TodoyuSQLManager::updateDatabaseFromTableFiles();

TodoyuInstallerManager::importStaticData();
TodoyuInstallerManager::importBasicData();

	// Save system config
TodoyuInstallerManager::saveSystemConfig(Todoyu::$CONFIG['CI']['system']);

require(PATH . '/config/system.php');

	// Import demo data
TodoyuInstallerManager::importDemoData();

TodoyuCache::flush();

	// Install unitTest extension
TodoyuSysmanagerExtInstaller::installExtension('unittest');
TodoyuSysmanagerExtInstaller::installExtension('currency');
TodoyuSysmanagerExtInstaller::installExtension('projectbilling');

echo "todoyu setup done";

?>