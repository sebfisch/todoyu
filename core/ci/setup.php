<?php

require_once(realpath(dirname(__FILE__) . '/../inc/global.php'));
require_once(PATH_CORE . '/ci/setup_config.php');

Todoyu::$CONFIG['NO_INIT'] = true;

require_once(PATH_CORE . '/inc/init.php');


	// Setup db connection
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

	// Install unittest extension
TodoyuExtInstaller::installExtension('unittest');

echo "todoyu setup done";

?>