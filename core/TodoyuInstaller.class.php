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
 * Installer
 *
 * @package		Todoyu
 * @subpackage	Installer
 */
class TodoyuInstaller {

	/**
	 * Run Installer
	 */
	public static function run() {
			// Restart?
		if( intval($_GET['restart']) == 1 ) {
			TodoyuInstallerStepManager::reinitStepNum();
			self::reload();
		}

			// If data has been submitted, have it processed
		if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			$error = TodoyuInstallerStepManager::processStep($_POST);
		}
			// Display step output
		TodoyuInstallerStepManager::displayStep($error);
	}



	/**
	 *
	 */
	public static function getPhpVersionStatus() {
		if( version_compare(PHP_VERSION, '5.2.0', '>=') ) {
			$versionStatus	= 'OK';
		} else {
			$versionStatus	= 'PROBLEM';
		}

		return $versionStatus;
	}



	/**
	 *
	 */
	public static function getWritableStatuses() {
		$writableStatus	= array();
		$next			= true;
		$writablePaths	= array(
			'files',
			'config',
			'cache/tmpl/compile',
			'config/db.php',
			'config/extensions.php',
			'config/extconf.php'
		);

		foreach($writablePaths as $path) {
			$absPath	= PATH . '/' . $path;

			TodoyuFileManager::setDefaultAccessRights($absPath);

			$writableStatus[$path]	= is_writable($absPath);

			if( $writableStatus[$path] === false ) {
				$next = false;
			}
		}

		return array($writableStatus, $next);
	}



	/**
	 * Get files which will be executed on an update
	 *
	 * @return Array
	 */
	public static function getRequiredVersionUpdates() {
		$dbVersion	= self::getDBVersion();
		$updates	= array();

		switch($dbVersion) {
			case 'beta1':
				$updates[] = array(
					'title'	=> 'Updates from beta1 to beta2',
					'file'	=> 'install/config/db/update_beta1_to_beta2.sql'
				);

			case 'beta2':
				$updates[] = array(
					'title'	=> 'Updates from beta2 to beta3',
					'file'	=> 'install/config/db/update_beta2_to_beta3.sql'
				);
		}

		return $updates;
	}



	/**
	 * Check whether the installation has been carried out before
	 *
	 * 	@return	Boolean
	 */
	public static function isDatabaseConfigured() {
		return $GLOBALS['CONFIG']['DB']['autoconnect'] === true;
	}


	public static function hasDoubleEnableFile() {
		$file1	= TodoyuFileManager::pathAbsolute(PATH . '/install/ENABLE');
		$file2	= TodoyuFileManager::pathAbsolute(PATH . '/install/_ENABLE');

		return is_file($file1) && is_file($file2);
	}

	public static function isUpdate() {
		return self::isDatabaseConfigured() || self::hasDoubleEnableFile();
	}



	/**
	 *
	 *	@param unknown_type $data
	 */
	public static function checkDbConnection($data) {
		if (	array_key_exists('server', $data)
			&&	array_key_exists('username', $data)
			&&	array_key_exists('password', $data)
		) {
			$_SESSION['todoyuinstaller']['db'] = $data;
			try {
				TodoyuDbAnalyzer::checkDbConnection($data);
			} catch(Exception $e) {
				$error = $e->getMessage();
			}
		}

		return $error;
	}



	/**
	 *
	 *	@param unknown_type $data
	 */
	public static function saveDbSelect($data) {
		$error	= self::dbSelect($data);

		if ( strlen($error) == 0 ) {
			TodoyuInstallerStepManager::jumpToNextStep();
		} else {
			// DB select failed - have renderFunc be executed, it'll ask for correct credentials
		}
	}


	public static function importStaticData($data) {
		TodoyuInstallerDbHelper::importStaticData();

		TodoyuInstallerStepManager::jumpToNextStep();
	}


	/**
	 *
	 *	@param unknown_type $data
	 */
	public static function dbSelect($data) {
		try {
			TodoyuInstallerDbHelper::addDatabase();
			TodoyuInstallerDbHelper::saveDbConfigInFile();
		} catch(Exception $e)	{
			$error = $e->getMessage();
		}

		return $error;
	}



	/**
	 *
	 *	@param unknown_type $data
	 */
	public static function tryUpdateConfig($data) {
		try {
			TodoyuInstaller::updateConfig($data);
		} catch (Exception $e)	{
			$error = $e->getMessage();
		}

		return $error;
	}



	/**
	 *
	 *	@param unknown_type $data
	 */
	public static function saveAdminPassword($data) {
		$ok	= true;

		try {
			TodoyuInstallerDbHelper::updateAdminPassword($data['password'], $data['password_confirm']);
		} catch(Exception $e)	{
			$error = $e->getMessage();

			$ok	= false;
			TodoyuInstaller::reload();
		}

		if ($ok) {
			TodoyuInstallerStepManager::jumpToNextStep();
		}

		return $error;
	}


	/**
	 * Update config
	 *
	 *	@param	Array	$data
	 */
	private static function updateConfig($data) {
		if( ! (strlen($data['email']) > 0 && strlen($data['systemname']) > 0) )	{
			throw new Exception('Please set an email address and a system name');
		}

		$data	= array(
			'config'		=> $data,
			'encryptionKey'	=> TodoyuInstaller::makeEncryptionKey()
		);
		$tmpl	= 'install/view/configs/system.php.tmpl';

		$config	= render($tmpl, $data);
		$code	= '<?php' . $config . '?>';
		$file	= PATH . '/config/system.php';

		file_put_contents($file, $code);
	}



	/**
	 * Deactivate the installer
	 */
	private static function deactivate() {
		rename(PATH . '/install/ENABLE', PATH . '/install/_ENABLE');

		if( file_exists(PATH . '/index.html') )	{
			unlink(PATH . '/index.html');
		}
	}



	/**
	 * Reload the installer
	 */
	public static function reload() {
		header('Location: ' . $_SERVER['SCRIPT_NAME']);
		exit();
	}



	/**
	 *	Forward to todoyu login
	 */
	private static function gotoLogin() {
		header('Location: ' . dirname(TODOYU_URL));
		exit();
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
	 * Execute sql files to update the database to the current version
	 *
	 * @param	Array		$data
	 */
	public static function updateToCurrentVersion(array $data) {
		$dbVersion	= self::getDBVersion();

		switch($dbVersion) {
			case 'beta1':
				self::updateBeta1ToBeta2();

			case 'beta2':
				self::updateBeta2ToBeta3();
		}
	}



	/**
	 * Update the database from beta1 to beta2
	 */
	private static function updateBeta1ToBeta2() {
		$updateFile	= 'install/config/db/update_beta1_to_beta2.sql';

		$numQueries	= self::executeQueriesFromFile($updateFile);
	}



	/**
	 * Update the database from beta2 to beta3
	 */
	private static function updateBeta2ToBeta3() {
		$updateFile	= 'install/config/db/update_beta2_to_beta3.sql';

		$numQueries	= self::executeQueriesFromFile($updateFile);
	}



	/**
	 * Execute the queries in the version update file
	 *
	 * @param	String		$updateFile			Path to update file
	 * @return	Integer		Number of executed queries
	 */
	private static function executeQueriesFromFile($updateFile) {
		$updateFile	= TodoyuFileManager::pathAbsolute($updateFile);
		$queries	= TodoyuSqlParser::getQueriesFromFile($updateFile);
		$count		= 0;

		foreach($queries as $query) {
			$query	= trim($query);
			if( $query !== '' ) {
				Todoyu::db()->query($query);
				$count++;
			}
		}

		return $count;
	}



	/**
	 * Get current version of the database
	 *
	 * @return	String
	 */
	public static function getDBVersion() {
		$tables	= Todoyu::db()->getTables();

		if( in_array('ext_portal_tab', $tables) ) {
			return 'beta1';
		}

		if( in_array('ext_user_customerrole', $tables) ) {
			return 'beta2';
		}

		return 'beta3';
	}



	/**
	 * Gather and perform queries, forward to next step (update finished)
	 */
	public static function finishUpdate($data) {
		$dbDiff	= TodoyuInstallerDbHelper::getDBstructureDiff();

		if ( count($dbDiff) > 0  ) {
			TodoyuInstallerDbHelper::compileAndRunInstallerQueries($dbDiff);
		}

		TodoyuInstallerStepManager::jumpToNextStep();
	}



	/**
	 * Finish the installer, go to todoyu login page
	 */
	public static function finish($data) {
		self::deactivate();
		TodoyuInstallerStepManager::reinitStepNum();

		self::gotoLogin();
	}

}
?>