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
		TodoyuInstaller::setStep('servercheck');

		return array();
	}



	/**
	 * Check server compatibility with todoyu
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processServercheck(array $data) {
		if( intval($data['checked']) === 1 ) {
			TodoyuInstaller::setStep('dbconnection');
		}

		return array();
	}



	/**
	 * Check if connection data is valid
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function processDbconnection(array $data) {
		$result	= array(
			'error'	=> false
		);

		if( isset($data['server']) ) {
			$server		= trim($data['server']);
			$username	= trim($data['username']);
			$password	= trim($data['password']);

			$fields		= array(
				'server'	=> $server,
				'username'	=> $username,
				'password'	=> $password
			);

				// Received DB server connection data?
			if( strlen($server) > 0 && strlen($username) > 0 ) {
				$info	= TodoyuDbAnalyzer::checkDbConnection($server, $username, $password);

				if( $info['status'] === true ) {
					TodoyuSession::set('installer/db', $fields);
					TodoyuInstaller::setStep('dbselect');
				} else {
					$result['errorMessage']	= $info['error'];
					$result['error']		= true;
					$result['fields']		= $fields;
				}
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
					$result['errorMessage'] = Label('installer.dbselect.text.dbNameExists');
				}
			} else {
				$useDatabase	= $data['database'];
			}

				// If a database to use was submitted and valid
			if( $useDatabase !== false ) {
					// Create a new database
				if( $createDb ) {
					$status	= TodoyuInstallerDbHelper::addDatabase($useDatabase, $dbConf);

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

			$result['error'] = $success === false;
			$result['fields']= $data;

			if( $success ) {
				TodoyuInstallerDbHelper::saveDbConfigInFile($dbConf);
				TodoyuInstaller::setStep('importtables');
			}
		}

		return $result;
	}



	/**
	 * Have static DB data imported
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function proccessImportTables(array $data) {
		if( intval($data['import']) === 1 ) {
			TodoyuSQLManager::updateDatabaseFromTableFiles();
			self::importBasicStaticData();

			TodoyuInstaller::setStep('systemconfig');
		}

		return array();
	}



	/**
	 * Save system config file 'config/system.php' (data: name, email, primary language)
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function procesSystemConfig(array $data) {
		$result	= array();

		if( isset($data['systemname']) ) {
			if( trim($data['email']) !== '' && trim($data['systemname']) !== '' ) {
				self::saveSystemConfig($data);

				TodoyuInstaller::setStep('adminpassword');
			} else {
				$result['error'] = true;
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
			if( trim($data['password']) !== '' && $data['password'] === $data['password_confirm'] ) {
				self::saveAdminPassword($data['password']);

				TodoyuInstaller::setStep('finish');
			} else {
				$result['error'] = true;
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
		if( intval($data['finish']) === 1 ) {
			self::finishInstallerAndJumpToLogin();
		}

		return array();
	}




	public static function processUpdate(array $data) {
		if( intval($data['start']) === 1 ) {
			TodoyuInstaller::setStep('updatetocurrentversion');
		}

		return array();
	}



	/**
	 * Execute sql files to update the database to the current version
	 *
	 * @param	Array		$data
	 */
	public static function processUpdateToCurrentVersion(array $data) {
		$result	= array();

		if( intval($data['update']) === 1 ) {
			$dbVersion	= self::getDBVersion();

			switch($dbVersion) {
				case 'beta1':
					self::updateBeta1ToBeta2();

				case 'beta2':
					self::updateBeta2ToBeta3();
			}

			TodoyuSQLManager::updateDatabaseFromTableFiles();

			TodoyuInstaller::setStep('finishupdate');
		}

		return $result;
	}



	public static function processFinishUpdate(array $data) {
		if( intval($data['finish']) === 1 ) {
			self::finishInstallerAndJumpToLogin();
		}

		return array();
	}







	public static function finishInstallerAndJumpToLogin() {
		self::disableInstaller();
		self::removeIndexRedirecter();

		TodoyuSession::remove('installer');

		self::goToLoginPage();

		exit();
	}








	/**
	 * Deactivate the installer
	 */
	public static function disableInstaller() {
		$fileOld	= TodoyuFileManager::pathAbsolute('install/ENABLE');
		$fileNew	= TodoyuFileManager::pathAbsolute('install/_ENABLE');
		$fileIndex	= TodoyuFileManager::pathAbsolute('index.html');

		if( is_file($fileOld) ) {
			if( is_file($fileNew) ) {
				unlink($fileOld);
			} else {
				rename($fileOld, $fileNew);
			}
		}

		if( is_file($fileIndex) ) {
			unlink($fileIndex);
		}
	}


	/**
	 *	Forward to todoyu login
	 */
	public static function goToLoginPage() {
		TodoyuHeader::location(dirname(TODOYU_URL), true);
	}


	public static function removeIndexRedirecter() {
		$file	= TodoyuFileManager::pathAbsolute('index.html');

		if( is_file($file) ) {
			unlink($file);
		}
	}











	/**
	 * Update the database from beta1 to beta2
	 */
	private static function updateBeta1ToBeta2() {
		$updateFile	= 'install/config/db/update_beta1_to_beta2.sql';

		$numQueries	= TodoyuSQLManager::executeQueriesFromFile($updateFile);
	}



	/**
	 * Update the database from beta2 to beta3
	 */
	private static function updateBeta2ToBeta3() {
		$updateFile	= 'install/config/db/update_beta2_to_beta3.sql';

		$numQueries	= TodoyuSQLManager::executeQueriesFromFile($updateFile);
	}



	/**
	 * Update admin-user password (and username)
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	private static function saveAdminPassword($password) {
		$table	= 'ext_user_user';
		$where	= 'username = \'admin\'';
		$update	= array(
			'password'	=> md5(trim($password))
		);

		Todoyu::db()->doUpdate($table, $where, $update);
	}







	private static function saveSystemConfig(array $config) {
		$config['encryptionKey'] = self::makeEncryptionKey();
		$tmpl	= TodoyuFileManager::pathAbsolute('install/view/configs/system.php.tmpl');
		$file	= TodoyuFileManager::pathAbsolute('config/system.php');

		TodoyuFileManager::saveTemplatedFile($file, $tmpl, $config, true);
	}


















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
		$dbVersion	= 'beta3';
		$tables		= Todoyu::db()->getTables();

		if( in_array('ext_portal_tab', $tables) ) {
			$dbVersion	= 'beta1';
		} elseif( in_array('ext_user_customerrole', $tables) ) {
			$dbVersion	= 'beta2';
		}

		return $dbVersion;
	}

}



?>