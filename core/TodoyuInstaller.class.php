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
	 * Proccess and display current step of installer
	 */
	public static function run() {
		$step		= self::getCurrentStep();

			// Process, receive resulting values or errors
		$result	= self::processStep($step, $_POST);

			// Display
		self::displayStep($step, $result);
	}



	/**
	 * Get current step
	 *
	 * @return	Integer
	 */
	public static function getCurrentStep() {
		if( intval($_GET['restart']) != 1 ) {
				// Regular step handling
			$step	= $_POST['action'];
			if ( $step == '') {
					// Reinit step to restart installation / update
				$step	= self::isUpdate() === false ? 'welcome' : 'welcometoupdate';
			}
		} else {
				// Restart
			$step	= 'welcome';
		}

		return $step;
	}



	/**
	 * Get name of next step
	 *
	 * @param	String	$step
	 * @return	String
	 */
	public static function getNextStep($currentStep = '') {
		$steps			= $GLOBALS['CONFIG']['INSTALLER']['steps'];

		if ( array_key_exists($currentStep, $steps) ) {
		foreach($steps as $name => $conf) {
			if ( $name == $currentStep ) {
				$nextStep	= $conf['nextStep'];
			}
		}
		} else {
			$nextStep	= 'welcome';
		}

		return $nextStep;
	}



	/**
	 * Get render or processing function reference of current step
	 *
	 * @param	String	$step
	 * @param	String	$type
	 * @return	Array
	 */
	public static function getStepFunc($step, $type = 'render') {
		$stepData	= $GLOBALS['CONFIG']['INSTALLER']['steps'][$step];

		return explode('::', $stepData[$type . 'FuncRef']);
	}



	/**
	 * Set given step's render or processing function reference to given one
	 *
	 * @param	String	$step
	 * @param	String	$type
	 * @return	Array
	 */
	public static function setStepFunc($step, $funcRef = '', $type = 'render') {
		if ( is_array($funcRef) ) {
			$funcRef	= implode('::', $funcRef);
		}

		$GLOBALS['CONFIG']['INSTALLER']['steps'][$step][$type . 'FuncRef']	= $funcRef;
	}



	/**
	 * Set current step's render function reference to that of the next step
	 *
	 * @param	Boolean		$stepStep
	 */
	public static function setCurrentRenderFuncFromNextStep($setStep = false) {
		$currentStep	= self::getCurrentStep();
		$nextStep		= self::getNextStep($currentStep);
		$nextRenderFunc	= self::getStepFunc($nextStep, 'render');

		self::setStepFunc($currentStep, $nextRenderFunc, 'render');

		if ( $setStep == true ) {
			$secondNextStep	= $GLOBALS['CONFIG']['INSTALLER']['steps'][$nextStep]['nextStep'];

			$GLOBALS['CONFIG']['INSTALLER']['steps'][$currentStep]['nextStep'] = $secondNextStep;
		}
	}



	/**
	 * Process current step data (order of evocation is: process, display)
	 *
	 * @param	String		$step
	 * @param	Array		$data
	 * @return	String
	 */
	public static function processStep($step, $data) {
		$processFunc	= self::getStepFunc($step, 'process');

		if( method_exists($processFunc[0], $processFunc[1]) ) {
				$result	= call_user_func($processFunc, $data);
		} else {
			$result	= array('error' => false);
		}

		return $result;
	}



	/**
	 * Render and display output for current step
	 *
	 * @param	String	$step
	 * @param	String	$error
	 */
	public static function displayStep($step, $result) {
		$renderFunc	= self::getStepFunc($step, 'render');

		if( method_exists($renderFunc[0], $renderFunc[1]) ) {
				// Get next step for button 'action',
				// repeating current step or being next one is set in validation of processing func
			$nextStepName	= self::getNextStep($step);
			$result['isUpdate']	= self::isUpdate();

			echo call_user_func($renderFunc, $nextStepName, $result);
		}
	}



	/**
	 * Get only steps of installation or updating, depending on where given step belongs to
	 *
	 * @param String $curRenderStep
	 */
	public static function getStepsSegment($curRenderStep) {
		$allSteps	=	$GLOBALS['CONFIG']['INSTALLER']['steps'];

		$curStepOffset		= TodoyuArray::getKeyOffset($allSteps, $curRenderStep);
		$updateStepsStart	= TodoyuArray::getKeyOffset($allSteps, 'welcometoupdate');

		if ( $curStepOffset >= $updateStepsStart ) {
			$mode	= 'update';
			$steps	= array_slice($allSteps, $updateStepsStart);
		} else {
			$mode	= 'installation';
			$steps	= array_slice($allSteps, 0, $updateStepsStart);
		}

		return array($mode, $steps);
	}



	/**
	 * Check server compatibility with todoyu
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function checkServerCompatibility(array $data) {
		$result	= array('error'	=> false);

			// Check PHP version compatibility
		$result['versionStatus']	= self::getPhpVersionStatus();
		if ( $result['versionStatus']	!== 'OK' ) {
			$result['error']= 'Wrong PHP Version. ';
		}

			// Check files and folders being writable
		list($result['writableStatuses'], $result['writablePathsOk'])	= self::getWritableStatuses();
		if ( $result['writablePathsOk']	!== true ) {
			$result['error']	.= 'Make sure all required files and folders are writable.';
		}

		return $result;
	}



	/**
	 * Check if connection data is valid
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function checkDbConnection(array $data) {
		$result	= array(
			'error' 	=> false,
			'nextStep'	=> self::getCurrentStep()
		);

		$server		= trim($data['server']);
		$username	= $data['username'];
		$password	= $data['password'];

			// Received DB server connection data?
		if ( strlen($server . $username . $password ) > 6 ) {
			$result['db'] = array(
				'server'		=> $server,
				'username'		=> $username,
				'password'		=> $password,
			);

			$_SESSION['todoyuinstaller']['db']	= $result['db'];

			try {
				$connectionValid	= TodoyuDbAnalyzer::checkDbConnection($server, $username, $password);
			} catch(Exception $e) {
				$result['error'] = $e->getMessage();
			}

			if ( $result['error'] === false ) {
					// Pass-on DB data to next step via session, evoke next step's rendering
				self::setCurrentRenderFuncFromNextStep(false);
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
	public static function storeDbConfig(array $data) {
		$result	= array(
			'error' 	=> false,
			'nextStep'	=> self::getCurrentStep()
		);

		$server		= $_SESSION['todoyuinstaller']['db']['server'];
		$username	= $_SESSION['todoyuinstaller']['db']['username'];
		$password	= $_SESSION['todoyuinstaller']['db']['password'];

		$database	= trim($data['database']);
		$newDatabase= trim($data['database_new']);

		try {
			TodoyuInstallerDbHelper::addDatabase($newDatabase, $database, $server, $username, $password);
			if( strlen($newDatabase) > 0 )	{
				$database	= $newDatabase;
			}
			TodoyuInstallerDbHelper::saveDbConfigInFile($server, $username, $password, $database);
		} catch(Exception $e)	{
			$result['error'] = $e->getMessage();
		}

		if ( $result['error'] === false ) {
			self::setCurrentRenderFuncFromNextStep(false);
		}

		return $result;
	}



	/**
	 * Have static DB data imported
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function importStaticData($data) {
		$result	= array('error' => false);

		try {
			TodoyuInstallerDbHelper::importStaticData();
		} catch(Exception $e)	{
			$result['error'] = $e->getMessage();
		}

		if ( $result['error'] == false ) {
			self::setCurrentRenderFuncFromNextStep(true);
		}

		return $result;
	}



	/**
	 * Save system config file 'config/system.php' (data: name, email, primary language)
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function updateSytemConfig($data) {
		$result	= array('error' => false);

		if( ! (strlen($data['email']) > 0 && strlen($data['systemname']) > 0) )	{
			$result['error']	= 'Please set an email address and a system name';
		} else {
			$data	= array(
				'config'		=> $data,
				'encryptionKey'	=> self::makeEncryptionKey()
			);
			$tmpl	= 'install/view/configs/system.php.tmpl';

			$config	= render($tmpl, $data);
			$code	= '<?php' . $config . '?>';
			$file	= PATH . '/config/system.php';

			file_put_contents($file, $code);
		}

		return $result;
	}



	/**
	 * Update admin-user password (and username)
	 *
	 * @param	Array		$data
	 * @return	Array
	 */
	public static function saveAdminPassword($data) {
		$result	= array('error' => false);

		try {
			TodoyuInstallerDbHelper::updateAdminPassword($data['password'], $data['password_confirm']);
		} catch(Exception $e)	{
			$result['error'] = $e->getMessage();
		}

		return $result;
	}



	/**
	 * Execute sql files to update the database to the current version
	 *
	 * @param	Array		$data
	 */
	public static function mandatoryVersionUpdates(array $data) {
		$result	= array('error' => false);

		$dbVersion	= self::getDBVersion();
		switch($dbVersion) {
			case 'beta1':
				try {
					self::updateBeta1ToBeta2();
				} catch(Exception $e)	{
					$result['error'] = $e->getMessage();
				}
			case 'beta2':
				try {
					self::updateBeta2ToBeta3();
				} catch(Exception $e)	{
					$result['error'] = $e->getMessage();
				}
		}

		return $result;
	}



	/**
	 * Detect, perform changes as found in 'tables.sql' files compared against DB
	 *
	 * @param	Array	$data
	 * @return	Array
	 */
	public static function autoUpdateTablesSqlDifferences($data) {
		$result	= array('error' => false);

		$dbDiff	= TodoyuInstallerDbHelper::getDBstructureDiff();
		if ( count($dbDiff) > 0  ) {
			try {
				TodoyuInstallerDbHelper::compileAndRunInstallerQueries($dbDiff);
			} catch(Exception $e)	{
				$result['error'] = $e->getMessage();
			}
		}

		return $result;
	}



	/**
	 * Finish the installer, go to todoyu login page
	 */
	public static function finish($data) {
		self::deactivate();

		self::gotoLogin();
	}



	/**
	 * Check if installed php version is at least 5.2
	 *
	 * @return	String
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
	 * Check writable status of important files
	 *
	 * @return	Array
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
	 * @return	Array
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
	 * Check if two enable files are existing
	 *
	 * @return	Boolean
	 */
	public static function hasDoubleEnableFile() {
		$file1	= TodoyuFileManager::pathAbsolute(PATH . '/install/ENABLE');
		$file2	= TodoyuFileManager::pathAbsolute(PATH . '/install/_ENABLE');

		return is_file($file1) && is_file($file2);
	}



	/**
	 * Check if system is already set up, and this is an update call
	 *
	 * @return	Boolean
	 */
	public static function isUpdate() {
		return TodoyuInstallerDbHelper::isDatabaseConfigured() || self::hasDoubleEnableFile();
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
	 * Reload the current script
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
	 * Update the database from beta1 to beta2
	 */
	private static function updateBeta1ToBeta2() {
		$updateFile	= 'install/config/db/update_beta1_to_beta2.sql';

		$numQueries	= TodoyuInstallerDbHelper::executeQueriesFromFile($updateFile);
	}



	/**
	 * Update the database from beta2 to beta3
	 */
	private static function updateBeta2ToBeta3() {
		$updateFile	= 'install/config/db/update_beta2_to_beta3.sql';

		$numQueries	= TodoyuInstallerDbHelper::executeQueriesFromFile($updateFile);
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