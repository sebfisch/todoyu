<?php

require_once(realpath(dirname(__FILE__) . '/../inc/global.php'));
require_once(PATH_CORE . '/ci/setup_config.php');

Todoyu::$CONFIG['NO_INIT'] = true;

require_once(PATH_CORE . '/inc/init.php');


	// Setup db connection
TodoyuInstallerManager::saveDbConfigInFile($SETUPCONFIG['db']);

	// Update database
TodoyuSQLManager::updateDatabaseFromTableFiles();
TodoyuInstallerManager::importStaticData();
TodoyuInstallerManager::importBasicData();

	// Save system config
TodoyuInstallerManager::saveSystemConfig($SETUPCONFIG['system']);

	// Import demo data
TodoyuInstallerManager::importDemoData();

	// Install unittest extension
TodoyuExtInstaller::installExtension('unittest');


echo "todoyu setup done";

?>