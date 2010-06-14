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

require_once(realpath(dirname(__FILE__) . '/../inc/global.php'));
require_once(PATH_CORE . '/ci/setup_config.php');

Todoyu::$CONFIG['NO_INIT'] = true;

require_once(PATH_CORE . '/inc/init.php');



	// Setup DB connection
TodoyuInstallerManager::saveDbConfigInFile($SETUPCONFIG['db']);

require(PATH . '/config/db.php');



	// Update database
TodoyuSQLManager::updateDatabaseFromTableFiles();

TodoyuInstallerManager::importStaticData();
TodoyuInstallerManager::importBasicData();

	// Save system config
TodoyuInstallerManager::saveSystemConfig($SETUPCONFIG['system']);

require(PATH . '/config/system.php');

	// Import demo data
TodoyuInstallerManager::importDemoData();

TodoyuCache::flush();

	// Install unitTest extension
TodoyuExtInstaller::installExtension('unittest');

echo "todoyu setup done";

?>