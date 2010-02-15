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
 * Manage todoyu installation processing
 *
 * @package		Todoyu
 * @subpackage	Installer
 */
class TodoyuInstallerManager {

	/**
	 * Process installation step (first)
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processInstall(array $data) {
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
	public static function processServercheck(array $data) {
		$result	= array();

		if( intval($data['checked']) === 1 ) {
			TodoyuInstaller::setStep('dbconnection');
		}

		return $result;
	}



	/**
	 * Check if connection data is valid
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processDbconnection(array $data) {
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

		if( isset($data['database']) || isset($data['database_new']) ) {
			$dbConf		= TodoyuSession::get('installer/db');
			$databases	= TodoyuDbAnalyzer::getDatabasesOnServer($dbConf);
			$success	= false;
			$useDatabase= false;
			$createDb	= false;

			if( ! empty($data['database_new']) ) {
				if( ! in_array($data['database_new'], $databases) ) {
					$useDatabase= $data['database_new'];
					$createDb	= true;
				} else {
					$result['text'] 	= Label('installer.dbselect.text.dbNameExists');
					$result['textClass']= 'error';
				}
			} else {
				$useDatabase	= $data['database'];
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
	public static function proccessImportTables(array $data) {
		$result	= array();

		if( intval($data['import']) === 1 ) {
			TodoyuSQLManager::updateDatabaseFromTableFiles();
			self::importBasicStaticData();

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
	public static function procesSystemConfig(array $data) {
		$result	= array();

		if( isset($data['name']) ) {
			if( TodoyuValidator::isEmail($data['email']) && trim($data['name']) !== '' ) {
				self::saveSystemConfig($data);

				TodoyuInstaller::setStep('adminpassword');
			} else {
				$result['text']		= Label('installer.systemconfig.text.error');
				$result['textClass']= 'error';
			}
		}

		return $result;
	}




	/**
	 * Process admin password update
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processAdminPassword(array $data) {
		$result	= array();

		if( isset($data['password']) ) {
			if( strlen(trim($data['password'])) >= 5 && $data['password'] === $data['password_confirm'] ) {
				self::saveAdminPassword($data['password']);

				TodoyuInstaller::setStep('finish');
			} else {
				$result['text']		= Label('installer.adminpassword.text');
				$result['textClass']= 'error';
			}
		}

		return $result;
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
	 * Execute sql files to update the database to the current version
	 *
	 * @param	Array		$data
	 */
	public static function processUpdateToCurrentVersion(array $data) {
		$result	= array();

		if( intval($data['update']) === 1 ) {
				// Process db update
			$dbVersion	= self::getDBVersion();

			switch($dbVersion) {
				case 'beta1':
					self::updateBeta1ToBeta2();

				case 'beta2':
					self::updateBeta2ToBeta3();

				case 'beta3':
					self::updateBeta3ToRc1();
			}

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
	 * Disable the installer, remove redirector files, clear session and go to login
	 *
	 */
	public static function finishInstallerAndJumpToLogin() {
		self::disableInstaller();
		self::removeIndexRedirecter();

		TodoyuSession::remove('installer');

		self::goToLoginPage();

		exit();
	}



	/**
	 * Disbale the installer
	 *
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
	 * Jump to loginpage
	 *
	 */
	public static function goToLoginPage() {
		TodoyuHeader::location(dirname(TODOYU_URL), true);
	}



	/**
	 * Remove index.html redirect
	 *
	 */
	public static function removeIndexRedirecter() {
		$file	= TodoyuFileManager::pathAbsolute('index.html');

		if( is_file($file) ) {
			unlink($file);
		}
	}



	/**
	 * Update the database from beta1 to beta2
	 *
	 */
	private static function updateBeta1ToBeta2() {
		$updateFile	= 'install/config/db/update_beta1_to_beta2.sql';

		$numQueries	= TodoyuSQLManager::executeQueriesFromFile($updateFile);
	}



	/**
	 * Update the database from beta2 to beta3
	 *
	 */
	private static function updateBeta2ToBeta3() {
		$updateFile	= 'install/config/db/update_beta2_to_beta3.sql';

		$numQueries	= TodoyuSQLManager::executeQueriesFromFile($updateFile);
	}



	/**
	 * Update the database from beta3 to rc1
	 *
	 */
	private static function updateBeta3ToRc1() {
		$updateFile	= 'install/config/db/update_beta3_to_rc1.sql';

		$numQueries	= TodoyuSQLManager::executeQueriesFromFile($updateFile);
	}



	/**
	 * Update admin-user password (and username)
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	private static function saveAdminPassword($password) {
		$table	= 'ext_contact_person';
		$where	= 'username = \'admin\'';
		$update	= array(
			'password'	=> md5(trim($password))
		);

		Todoyu::db()->doUpdate($table, $where, $update);
	}



	/**
	 * Save system configuration
	 *
	 * @param 	Array		$config
	 */
	private static function saveSystemConfig(array $config) {
		$config['encryptionKey'] = self::makeEncryptionKey();
		$tmpl	= 'install/view/configs/system.php.tmpl';
		$file	= TodoyuFileManager::pathAbsolute('config/system.php');

		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $config, true);
	}



	/**
	 * Import static data
	 *
	 */
	private static function importBasicStaticData() {
		$file	= TodoyuFileManager::pathAbsolute('install/config/db/db_data.sql');
		$queries= TodoyuSQLManager::getQueriesFromFile($file);

		foreach($queries as $query) {
			Todoyu::db()->query($query);
		}
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
	 * Check if installed php version is at least 5.2
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

			$result['files'][$element] = is_writable($path);
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
		$dbVersion	= 'rc1';
		$tables		= Todoyu::db()->getTables();

		if( in_array('ext_portal_tab', $tables) ) {
			$dbVersion	= 'beta1';
		} elseif( in_array('ext_user_customerrole', $tables) ) {
			$dbVersion	= 'beta2';
		} elseif( in_array('history', $tables) ) {
			$dbVersion	= 'beta3';
		}

		return $dbVersion;
	}



	/**
	 * Check whether the installation has been carried out before
	 *
	 * @return	Boolean
	 */
	public static function isDatabaseConfigured() {
		$dbConfig	= TodoyuArray::assure($GLOBALS['CONFIG']['DB']);

		return (	$dbConfig['autoconnect'] === true
				&&	$$dbConfig['server']	!== ''
				&&	$dbConfig['username']	!== ''
				&&	$dbConfig['password']	!== ''
				&&	$dbConfig['database']	!== '');
	}



	/**
	 * Add a new database to the server
	 *
	 * @param	String		$databaseName
	 * @param	Array		$dbConfig
	 * @return	Bool
	 */
	public static function addDatabase($databaseName, array $dbConfig)	{
		$link	= mysql_connect($dbConfig['server'], $dbConfig['username'], $dbConfig['password']);
		$query	= 'CREATE DATABASE `' . $databaseName . '`';

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
	 *
	 */
	public static function clearCache() {
		$paths	= array(
			PATH_CACHE . '/css',
			PATH_CACHE . '/js',
			PATH_CACHE . '/img',
			PATH_CACHE . '/language',
			PATH_CACHE . '/tmpl/compile'
		);

		foreach($paths as $path) {
			if( is_dir($path) ) {
				TodoyuFileManager::deleteFolderContent($path, false);
			}
		}
	}

}



?>