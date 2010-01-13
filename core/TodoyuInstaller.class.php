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
	 * Get configuration to required version to version database update scripts
	 *
	 * @param	Array	$versionData
	 * @return	Array
	 */
	public static function getRequiredVersionUpdates(array $versionData = array()) {
		if ( count($versionData) == 0 ) {
			$versionData	= Todoyu::getVersionData();
		}

		$updatesConf	= array(
			array(
				'title'	=> 'Updates from beta1 to beta2',
				'file'	=> '../config/db/update_beta1_to_beta2.sql'
			),
			array(
				'title'	=> 'Updates from beta2 to beta3',
				'file'	=> '../config/db/update_beta2_to_beta3.sql'
			)
		);

		return $updatesConf;
	}



	/**
	 * Check whether the installation has been carried out before
	 *
	 * 	@return	Boolean
	 */
	public static function hasBeenInstalledBefore() {
		return ( $GLOBALS['CONFIG']['DB']['autoconnect'] === true );
	}



	public static function checkDbConnection($data) {
		$_SESSION['todoyuinstaller']['db'] = $data;
		try {
			TodoyuDbAnalyzer::checkDbConnection($data);
		} catch(Exception $e) {
			$error = $e->getMessage();
		}

		return $error;
	}



	public static function dbSelect($data) {
		try {
			TodoyuInstallerDbHelper::addDatabase();
			TodoyuInstallerDbHelper::saveDbConfigInFile();
		} catch(Exception $e)	{
			$error = $e->getMessage();
		}

		return $error;
	}



	public static function tryUpdateConfig($data) {
		try {
			TodoyuInstaller::updateConfig($data);
		} catch (Exception $e)	{
			$error = $e->getMessage();
		}

		return $error;
	}



	public static function setAdminPassword($data) {
		try {
			TodoyuInstallerDbHelper::updateAdminPassword($data['password'], $data['password_confirm']);
		} catch(Exception $e)	{
			$error = $e->getMessage();
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
		$tmpl	= 'install/view/system.php.tmpl';

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
	private static function reload() {
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
	 * Finish the installer, go to todoyu login page
	 */
	public static function finish($data) {
		self::deactivate();
		TodoyuInstallerStepManager::reinitStepNum();
		self::gotoLogin();
	}



	public static function updatebeta1tobeta2($data) {
		include( PATH . '/install/config/db/update_beta1_to_beta2.php');
	}



	/**
	 * Gather and perform queries, forward to next step (update finished)
	 */
	public static function finishUpdate($data) {
		$dbDiff	= TodoyuInstallerDbHelper::getDBstructureDiff();

		if ( count($dbDiff) > 0  ) {
			TodoyuInstallerDbHelper::compileAndRunInstallerQueries($dbDiff);
		}

		TodoyuInstallerStepManager::setStepNum(11);
	}

}
?>