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
 * Manage todoyu installation processing
 *
 * @package		Todoyu
 * @subpackage	Installer
 */
class TodoyuInstallerManager {

	/**
	 * Process first step of installation: locale selection for installer and as preset for system locale
	 *
	 * @param	Array	$data
	 * @return	Array
	 */
	public static function processLocale(array $data) {
		$result	= array();

		if( array_key_exists('locale', $data) ) {
			TodoyuSession::set('installer/locale', $data['locale']);
			TodoyuLocaleManager::setLocaleCookie($data['locale']);
			TodoyuInstaller::setStep('license');
			TodoyuHeader::location('index.php');
		}

		return $result;
	}



	/**
	 * Process installation step: license agreement accepted
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processLicense(array $data) {
		$result	= array();

		if( intval($data['install']) === 1 ) {
			TodoyuInstaller::setStep('servercheck');
		}

		return $result;
	}



	/**
	 * Check server compatibility with todoyu
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processServerCheck(array $data) {
		$result	= array();

		if( intval($data['checked']) === 1 ) {
			TodoyuInstaller::setStep('dbconnection');
		}

		return $result;
	}



	/**
	 * Check database connection data validity
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processDbConnection(array $data) {
		$result	= array();

		if( isset($data['server']) ) {
				// Received DB server connection data?
			if( strlen($data['server']) > 0 && strlen($data['username']) > 0 ) {
				$info	= TodoyuDbAnalyzer::checkDbConnection($data['server'], $data['username'], $data['password']);

				if( $info['status'] === true ) {
					TodoyuSession::set('installer/db', $data);
					TodoyuInstaller::setStep('dbselect');
				} else {
					$result['text']		= $info['error'];
					$result['textClass']= 'error';
				}
			} else {
				$result['text']		= Label('installer.dbconnection.text');
				$result['textClass']= 'error';
			}
		}

		return $result;
	}



	/**
	 * Try saving DB server connection data
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processDbSelect(array $data) {
		$result		= array();

		$database	= trim($data['database']);
		$databaseNew= trim($data['database_new']);

		if( !empty($database) || !empty($databaseNew) ) {
			$dbConf		= TodoyuSession::get('installer/db');
			$databases	= TodoyuDbAnalyzer::getDatabasesOnServer($dbConf);
			$success	= false;
			$useDatabase= false;
			$createDb	= false;

			if( ! empty($databaseNew) ) {
				if( ! in_array($databaseNew, $databases) ) {
					$useDatabase= $databaseNew;
					$createDb	= true;
				} else {
					$result['text'] 	= Label('installer.dbselect.text.dbNameExists');
					$result['textClass']= 'error';
				}
			} else {
				$useDatabase	= $database;
			}

				// If a database to use was submitted and valid
			if( $useDatabase !== false ) {
					// Create a new database
				if( $createDb ) {
					$status	= TodoyuInstallerManager::addDatabase($useDatabase, $dbConf);

					if( $status === true ) {
						$dbConf['database']	= $useDatabase;
						$success = true;
					} else {
						$result['errorMessage'] = Label('install.dbselect.text.notCreated');
					}
				} else {
						// Use existing database
					$dbConf['database']	= $useDatabase;
					$success = true;
				}
			}

			if( $success ) {
				TodoyuInstallerManager::saveDbConfigInFile($dbConf);
				TodoyuInstaller::setStep('importtables');
			} elseif( empty($result['text']) ) {
				$data['text']		= $result['errorMessage'];
				$data['textClass']	= 'error';
			}
		} elseif( isset($data['database']) ) {
			$result['text']		= Label('installer.dbselect.text.notCreated');
			$result['textClass']= 'error';
		} else {
			$result['text']		= Label('installer.dbselect.text');
			$result['textClass']= 'info';
		}

		return $result;
	}



	/**
	 * Import table structure
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processImportDbTables(array $data) {
		$result	= array();

		if( intval($data['import']) === 1 ) {
				// Create database structure from all table files
			TodoyuSQLManager::updateDatabaseFromTableFiles();
			self::importStaticData();
			self::importBasicData();

			TodoyuInstaller::setStep('systemconfig');
		}

		return $result;
	}



	/**
	 * Save system config file 'config/system.php' (data: name, email, primary language)
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processSystemConfig(array $data) {
		$result	= array();

		if( isset($data['name']) ) {
			if( TodoyuValidator::isEmail($data['email']) && trim($data['name']) !== '' ) {
				self::saveSystemConfig($data);
				TodoyuLocaleManager::setLocaleCookie($data['locale']);
				TodoyuSession::set('installer/systememail', $data['email']);
				TodoyuInstaller::setStep('adminaccount');
			} else {
				$result['text']		= Label('installer.systemconfig.text.error');
				$result['textClass']= 'error';
			}
		}

		return $result;
	}



	/**
	 * Process administrator account data update: create internal company, update administrator person and user data
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processAdminAccount(array $data) {
		$result	= array();

			// Verify account data
		if( isset($data['password']) && isset($data['company']) && isset($data['firstname']) && isset($data['lastname']) ) {
			$emailOk	= TodoyuString::isValidEmail(trim($data['email']));
			$companyOk	= strlen(trim($data['company'])) >= 1;
			$firstNameOk= strlen(trim($data['firstname'])) >= 1;
			$lastNameOk	= strlen(trim($data['lastname'])) >= 1;
			$passwordOk	= strlen(trim($data['password'])) >= 5 && $data['password'] === $data['password_confirm'];

				// Verified. save account data
			if( $emailOk && $companyOk && $firstNameOk && $lastNameOk && $passwordOk ) {
				self::saveInternalCompanyName($data['company']);
				self::saveAdminAccountData($data['email'], $data['password'], $data['firstname'], $data['lastname']);

				TodoyuInstaller::setStep('importdemodata');
			} else {
					// Verification failed, display failure response
				$result['text']		= Label('installer.adminaccount.error');
				$result['textClass']= 'error';
			}
		}

		return $result;
	}



	/**
	 * Process demo data import
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processImportDemoData(array $data) {
		if( isset($data['importdemodata']) ) {
			$import	= intval($data['import']) === 1;

			if( $import ) {
				self::importDemoData();
			}

			TodoyuInstaller::setStep('finish');
		}

		return array();
	}



	/**
	 * Process installation finish
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processFinish(array $data) {
		$result	= array();

		if( intval($data['finish']) === 1 ) {
			self::finishInstallerAndJumpToLogin();
		}

		return $result;
	}



	/**
	 * Process update start screen
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processUpdate(array $data) {
		$result	= array();

		if( intval($data['start']) === 1 ) {
			TodoyuInstaller::setStep('updatetocurrentversion');
		} else {
			$result['text']		= Label('installer.update.info');
			$result['textClass']= 'info';
		}

		return $result;
	}



	/**
	 * Execute SQL files to update the database to the current version
	 *
	 * @param	Array		$data
	 */
	public static function processUpdateToCurrentVersion(array $data) {
		$result	= array();

		if( intval($data['update']) === 1 ) {
				// Process db update
			$dbVersion	= self::getDBVersion();
			$files		= array();

			switch($dbVersion) {
				case 'beta1':
					$files[]	= 'install/db/update_beta1_to_beta2.sql';

				case 'beta2':
					$files[]	= 'install/db/update_beta2_to_beta3.sql';

				case 'beta3':
					$files[]	= 'install/db/update_beta3_to_rc1.sql';

				case 'rc1':
					$files[]	= 'install/db/update_rc1_to_rc2.sql';

				case 'rc2':
					// do nothing
					break;
			}

				// Apply version update files
			foreach($files as $file) {
				TodoyuSQLManager::executeQueriesFromFile($file);
			}

				// Apply structure updates from table files
			TodoyuSQLManager::updateDatabaseFromTableFiles();

			TodoyuInstaller::setStep('finishupdate');
		}

		return $result;
	}



	/**
	 * Process update finishing
	 *
	 * @param	Array		$data
	 * @return	Array
	 */

	public static function processFinishUpdate(array $data) {
		$result	= array();

		if( intval($data['finish']) === 1 ) {
			self::finishInstallerAndJumpToLogin();
		}

		return $result;
	}



	/**
	 * Disable the installer, remove redirection files, clear session and go to login
	 */
	public static function finishInstallerAndJumpToLogin() {
		self::disableInstaller();
		self::removeIndexRedirector();

		TodoyuSession::remove('installer');

		self::goToLogInPage();

		exit();
	}



	/**
	 * Disable the installer
	 */
	public static function disableInstaller() {
		$fileOld	= TodoyuFileManager::pathAbsolute('install/ENABLE');
		$fileNew	= TodoyuFileManager::pathAbsolute('install/_ENABLE');

		if( is_file($fileOld) ) {
			if( is_file($fileNew) ) {
				unlink($fileOld);
			} else {
				rename($fileOld, $fileNew);
			}
		}
	}



	/**
	 * Jump to log-in page
	 */
	public static function goToLogInPage() {
		TodoyuHeader::location(dirname(TODOYU_URL) . '/index.php', true);
	}



	/**
	 * Remove index.html file and its redirection to the installer
	 *
	 * @return	Boolean
	 */
	public static function removeIndexRedirector() {
		$success= false;
		$file	= TodoyuFileManager::pathAbsolute('index.html');

		if( is_file($file) ) {
			$success	= unlink($file);
		}

		return $success;
	}



	/**
	 * Update internal company DB record with given name
	 *
	 * @param	String		$name
	 */
	private static function saveInternalCompanyName($name) {
		$name	= trim($name);

		$table	= 'ext_contact_company';
		$where	= 'id = 1';
		$update	= array(
			'title'		=> $name,
			'shortname'	=> $name,
			'date_enter'=> NOW
		);

		Todoyu::db()->doUpdate($table, $where, $update);
	}



	/**
	 * Update admin-user password (and username)
	 *
	 * @param	String		$email
	 * @param	String		$password
	 * @param	String		$firstName
	 * @param	String		$lastName
	 */
	private static function saveAdminAccountData($email, $password, $firstName, $lastName) {
		$email		= trim($email);
		$firstName	= trim($firstName);
		$lastName	= trim($lastName);
		$password	= trim($password);

		$shortName	= strtoupper(substr($firstName, 0, 2) . substr($lastName, 0, 2));
		$passHash	= md5($password);

		$table	= 'ext_contact_person';
		$where	= 'username = \'admin\'';
		$update	= array(
			'email'			=> $email,
			'firstname'		=> $firstName,
			'lastname'		=> $lastName,
			'shortname'		=> $shortName,
			'password'		=> $passHash
		);

		Todoyu::db()->doUpdate($table, $where, $update);
	}



	/**
	 * Save system configuration
	 *
	 * @param 	Array		$config
	 */
	public static function saveSystemConfig(array $config) {
		$config['encryptionKey'] = self::makeEncryptionKey();
		$tmpl	= 'install/view/configs/system.php.tmpl';
		$file	= TodoyuFileManager::pathAbsolute('config/system.php');

		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $config, true);
	}



	/**
	 * Import data from SQL file
	 *
	 * @param	String		$file
	 */
	private static function importSqlFromFile($file) {
		$file	= TodoyuFileManager::pathAbsolute($file);
		$queries= TodoyuSQLManager::getQueriesFromFile($file);

		foreach($queries as $query) {
			Todoyu::db()->query($query);
		}
	}



	/**
	 * Import static data
	 */
	public static function importStaticData() {
		self::importSqlFromFile('install/db/static_data.sql');
	}



	/**
	 * Import basic data
	 */
	public static function importBasicData() {
		self::importSqlFromFile('install/db/basic_data.sql');
	}



	/**
	 * Import the demo data from SQL file
	 */
	public static function importDemoData() {
		self::importSqlFromFile('install/db/demo_data.sql');
	}



	/**
	 * Generate encryption key
	 *
	 * @return	String
	 */
	private static function makeEncryptionKey() {
		return str_replace('=', '', base64_encode(md5(NOW . serialize($_SERVER) . session_id() . rand(1000, 30000))));
	}



	/**
	 * Check if installed PHP version is at least 5.2
	 *
	 * @return	String
	 */
	public static function hasAdequatePhpVersion() {
		return version_compare(PHP_VERSION, '5.2.0', '>=');
	}



	/**
	 * Check writable status of important files
	 *
	 * @return	Array
	 */
	public static function checkWritableStatus(array $elements) {
		$result	= array(
			'error'	=> false,
			'files'	=> array()
		);

		foreach($elements as $element) {
			$path	= TodoyuFileManager::pathAbsolute($element);

			TodoyuFileManager::setDefaultAccessRights($path);

			$isWritable	= is_writable($path);

			$result['files'][$element] = $isWritable;

			if( ! $isWritable ) {
				$result['error'] = true;
			}
		}

		return $result;
	}



	/**
	 * Check server requirements
	 *
	 * @return	Array
	 */
	public static function checkServer() {
		$result	= array(
			'stop'		=> false,
			'phpversion'=> true,
			'files'		=> array()
		);
		$stepConfig	= TodoyuInstaller::getStepConfig('servercheck');

			// Check PHP version compatibility
		if( ! self::hasAdequatePhpVersion() ) {
			$result['phpversion']	= false;
			$result['false'] 		= true;
		}

		$fileCheck		= self::checkWritableStatus($stepConfig['fileCheck']);
		$result['files']= $fileCheck['files'];
		if( $fileCheck['error'] === true ) {
			$result['stop']	= true;
		}

		return $result;
	}



	/**
	 * Get current version of the database
	 *
	 * @return	String
	 */
	public static function getDBVersion() {
		$dbVersion	= 'rc2';
		$tables		= Todoyu::db()->getTables();

		if( in_array('ext_portal_tab', $tables) ) {
			$dbVersion	= 'beta1';
		} elseif( in_array('ext_user_customerrole', $tables) ) {
			$dbVersion	= 'beta2';
		} elseif( in_array('history', $tables) ) {
			$dbVersion	= 'beta3';
		} elseif( in_array('ext_user_user', $tables) ) {
			$dbVersion	= 'rc1';
		}

		return $dbVersion;
	}



	/**
	 * Check whether the installation has been carried out before
	 *
	 * @return	Boolean
	 */
	public static function isDatabaseConfigured() {
		return Todoyu::$CONFIG['DB']['autoconnect'] === true;
	}



	/**
	 * Add a new database to the server
	 *
	 * @param	String		$databaseName
	 * @param	Array		$dbConfig
	 * @return	Boolean
	 */
	public static function addDatabase($databaseName, array $dbConfig) {
		$link	= mysql_connect($dbConfig['server'], $dbConfig['username'], $dbConfig['password']);
		$query	= 'CREATE DATABASE `' . $databaseName . '` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;';

		return @mysql_query($query, $link) !== false;
	}



	/**
	 * Save database configuration in the local config file
	 *
	 * @param	Array		$dbConfig
	 * @return	Integer
	 */
	public static function saveDbConfigInFile(array $dbConfig) {
		$tmpl	= 'install/view/configs/db.php.tmpl';
		$file	= PATH . '/config/db.php';

		return TodoyuFileManager::saveTemplatedFile($file, $tmpl, $dbConfig, true);
	}



	/**
	 * Clear cached files
	 */
	public static function clearCache() {
		TodoyuFileManager::deleteFolderContents(PATH_CACHE, false);
	}



	/**
	 * Remove files and folders left-over from previous versions and not in-use anymore
	 */
	public static function removeOldFiles() {
		$pathConfig		= TodoyuFileManager::pathAbsolute('install/config');
		$oldFilesConfigs= TodoyuFileManager::getFilesInFolder($pathConfig, false, array('deletefiles_'));

		foreach($oldFilesConfigs as $oldFilesConfig) {
				// Get path to deletions config file
			$filePath	= TodoyuFileManager::pathAbsolute($pathConfig . '/' . $oldFilesConfig);
				// Get directories and files to be deleted
			require_once($filePath);

				// Delete files
			$deletionFiles	= TodoyuArray::assure(Todoyu::$CONFIG['INSTALLER']['oldFiles']['deleteFiles']);
			foreach($deletionFiles as $pathFile) {
				$pathFile	= TodoyuFileManager::pathAbsolute($pathFile);
				if( is_file($pathFile) ) {
					unlink($pathFile);
				}
			}

				// Delete folder contents
			$deletionFolderContents	= TodoyuArray::assure(Todoyu::$CONFIG['INSTALLER']['oldFiles']['deleteFolderContents']);
			foreach($deletionFolderContents as $pathFolder) {
				$pathFolder	= TodoyuFileManager::pathAbsolute($pathFolder);
				if( is_dir($pathFolder) ) {
					TodoyuFileManager::deleteFolderContents($pathFolder);
				}
			}

				// Delete folders
			$deletionFolders	= TodoyuArray::assure(Todoyu::$CONFIG['INSTALLER']['oldFiles']['deleteFolders']);
			foreach($deletionFolders as $pathFolder) {
				$pathFolder	= TodoyuFileManager::pathAbsolute($pathFolder);
				if( is_dir($pathFolder) ) {
					TodoyuFileManager::deleteFolder($pathFolder);
				}
			}
		}
	}



	/**
	 * Update old style config variables if necessary, remove old extensions (dev, user) from ext list
	 */
	public static function updateConfigFileVariables() {
			// Remove deleted extensions
		$removeExts	= array('dev', 'user');
		$filePath	= 'config/extensions.php';
		$extContent	= TodoyuFileManager::getFileContent($filePath);

		foreach($removeExts as $ext) {
			$extContent	= str_replace(',\'' . $ext . '\'', '', $extContent);
		}

		TodoyuFileManager::saveFileContent($filePath, $extContent);

			// Fix older than RC2 config files compatibility
		$files = array(
			'db.php',
			'config.php',
			'extconf.php',
			'extensions.php',
			'override.php',
			'system.php'
		);

		$currentString	= '$CONFIG[';
		$newString		= 'Todoyu::$CONFIG[';

		foreach($files as $file) {
			$file	= TodoyuFileManager::pathAbsolute('config/' . $file);

			if( is_file($file) ) {
				$content	= file_get_contents($file);

				if( ! strstr($content, $newString) ) {
					$content = str_replace($currentString, $newString, $content);

					file_put_contents($file, $content);

					include($file);
				}
			}
		}
	}



	/**
	 * Move task assets to a new and better structure
	 * Move from tasks/TASKID/* to PROJECTID/TASKID/*
	 * 
	 */
	public static function changeFilesAssetStructure() {
		if( ! TodoyuInstaller::isUpdate() ) {
			return false;
		}

			// Initialize todoyu to use all the functions
		Todoyu::init();
		require( PATH_CORE . '/inc/load_extensions.php' );

			// Get base paths
		$pathAssets		= TodoyuFileManager::pathAbsolute('files/assets');
		$pathAssetTask	= TodoyuFileManager::pathAbsolute($pathAssets . '/task');

			// If there is still an old task folder, process it
		if( is_dir($pathAssetTask) ) {
			$taskFolders	= TodoyuFileManager::getFoldersInFolder($pathAssetTask);

				// All subfolders are task IDs, loop over them
			foreach($taskFolders as $taskFolder) {
				$idTask	= intval($taskFolder);

					// If the folder is a (task-)number
				if( $idTask > 0 ) {
						// Find project ID
					$idProject		= TodoyuTaskManager::getProjectID($idTask);
						// New folder for task files
					$pathTaskFiles	= TodoyuFileManager::pathAbsolute($pathAssets . '/' . $idProject . '/' . $idTask);
						// Get all task files
					$taskFiles		= TodoyuFileManager::getFilesInFolder($pathAssetTask . '/' . $taskFolder);
						// Make new task folder
					TodoyuFileManager::makeDirDeep($pathTaskFiles);

						// Process all files of a task
					foreach($taskFiles as $taskFile) {
						$pathSource	= TodoyuFileManager::pathAbsolute($pathAssetTask . '/' . $taskFolder . '/' . $taskFile);
						$pathDest	= TodoyuFileManager::pathAbsolute($pathTaskFiles . '/' . $taskFile);

							// Rename file
						rename($pathSource, $pathDest);

							// Update database
						$pathStorageOld	= str_replace($pathAssets . DIR_SEP, '', $pathSource);
						$pathStorageNew	= str_replace($pathAssets . DIR_SEP, '', $pathDest);
						$update	= array(
							'file_storage'	=> $pathStorageNew
						);
						$where	= 'file_storage = ' . Todoyu::db()->quote($pathStorageOld, true);

						Todoyu::db()->doUpdate('ext_assets_asset', $where, $update);
					}
				}
			}

				// Delete old task folder
//			TodoyuFileManager::deleteFolder($pathAssetTask);
		}

	}



	/**
	 * Run version updates.
	 *
	 */
	public static function runVersionUpdates() {
		TodoyuDebug::printInFireBug('runVersionUpdates');
		$lastVersion	= self::getLastVersion();

		self::runVersionUpdatesSQL($lastVersion);
		self::runVersionUpdatesPHP($lastVersion);

		self::saveCurrentVersion();
	}



	/**
	 * Run version updates from SQL files
	 *
	 * @param	String		$lastVersion
	 */
	public static function runVersionUpdatesSQL($lastVersion) {
		TodoyuDebug::printInFireBug('runVersionUpdatesSQL');
		$baseFolder	= 'install/update/db';
		$updateFiles= self::getUpdateFiles($baseFolder, 'sql', $lastVersion);

		foreach($updateFiles as $updateFile) {
			TodoyuSQLManager::executeQueriesFromFile($baseFolder . '/' . $updateFile);
		}
	}



	/**
	 * Run version updates from PHP files
	 *
	 * @param	String		$lastVersion
	 */
	public static function runVersionUpdatesPHP($lastVersion) {
		TodoyuDebug::printInFireBug('runVersionUpdatesPHP');
		$baseFolder	= 'install/update/php';
		$updateFiles= self::getUpdateFiles($baseFolder, 'php', $lastVersion);

		foreach($updateFiles as $updateFile) {
			$pathFile	= TodoyuFileManager::pathAbsolute($baseFolder . '/' . $updateFile);

			include($pathFile);
		}
	}



	/**
	 * Scan a folder for version files, compare them with last version
	 *
	 * @param	String		$pathToFolder			Path to folder
	 * @param	String		$extension				File extension to scan for
	 * @param	String		$lastVersion			Last version. Look only for newer updates
	 * @return	Array
	 */
	private static function getUpdateFiles($pathToFolder, $extension, $lastVersion) {
		$pathToFolder	= TodoyuFileManager::pathAbsolute($pathToFolder);
		$files			= TodoyuFileManager::getFilesInFolder($pathToFolder);
		$updateFiles	= array();

			// Sort the files by version number
		usort($files, 'version_compare');

			// Check all files if they are necessary for the update
		foreach($files as $filename) {
			$info		= pathinfo($pathToFolder . '/' . $filename);

				// Only use file with the requested extension
			if( $info['extension'] !== $extension ) {
				continue;
			}

				// The filename is the version number
			$fileVersion= $info['filename'];

				// Get all version which are higher than the db version
			if( version_compare($fileVersion, $lastVersion) === 1 ) {
				$updateFiles[] = $filename;
			}
		}

		return $updateFiles;
	}



	/**
	 * Find the last version of todoyu (and the database)
	 * Tries to read to LAST_VERSION file. If not available, use to current todoyu version
	 *
	 * @return	String
	 */
	public static function getLastVersion() {
		$pathFile	= TodoyuFileManager::pathAbsolute('install/config/LAST_VERSION');
		$version	= TODOYU_VERSION;

		if( is_file($pathFile) ) {
			$version	= trim(file_get_contents($pathFile));
		}

		return $version;
	}



	/**
	 * Save current todoyu version as DB version in the LAST_VERSION file
	 *
	 */
	public static function saveCurrentVersion() {
		$pathFile	= TodoyuFileManager::pathAbsolute('install/config/LAST_VERSION');

		file_put_contents($pathFile, TODOYU_VERSION);
	}

}

?>