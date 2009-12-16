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
	 * Available steps
	 *
	 * @var	Array
	 */
	private static $steps = array(
		'welcome',
		'servercheck',
		'dbconnection',
		'dbselect',
		'importstatic',
		'config',
		'setadminpassword',
		'finish',

		'welcometoupdate',
		'updatebeta1tobeta2',
		'dbstructurecheck',
		'finishupdate',
	);



	/**
	 * Get current step
	 *
	 * @return	Integer
	 */
	public static function getStepNum() {
		$step	= intval($_SESSION['todoyuinstaller']['step']);

			// Initial step?
		if ($step == 0) {
				// Check whether installation has been carried out before
			if ( self::hasBeenInstalledBefore() ) {
				$step	= 8;	// 'welcometoupdate'
			}
		}

		return $step;
	}



	/**
	 * Check whether the installation has been carried out before
	 *
	 * 	@return	Boolean
	 */
	private static function hasBeenInstalledBefore() {
		return ( $GLOBALS['CONFIG']['DB']['autoconnect'] === true );

//		$query	= '	SELECT * FROM INFORMATION_SCHEMA.COLUMNS
//					WHERE TABLE_NAME IN (\'ext_project_project\', \'ext_project_task\')';
//
//		$hasRes	= Todoyu::db()->queryHasResult($query);
//
//		return $hasRes;
	}



	/**
	 * Set current step
	 *
	 * @param	Integer		$step
	 */
	public static function setStepNum($step) {
		$_SESSION['todoyuinstaller']['step'] = intval($step);
	}



	/**
	 * Process current step data (order of evocation is: process, display)
	 *
	 *	@param	Array		$data
	 *	@return	String
	 */
	public static function processStep($data) {
		$action	= $data['action'];

		switch($action) {
				// Install
			case 'start':
				self::setStepNum(1);
				break;

			case 'servercheck':
				self::setStepNum(2);
				break;

			case 'dbconnection':
				$_SESSION['todoyuinstaller']['db'] = $data;
				try {
					TodoyuDbAnalyzer::checkDbConnection($data);
					self::setStepNum(3);
				} catch(Exception $e) {
					$error = $e->getMessage();
				}
				break;

			case 'dbselect':
				try {
					TodoyuInstallerDbHelper::addDatabase();
					self::setStepNum(4);
					TodoyuInstallerDbHelper::saveDbConfigInFile();
				} catch(Exception $e)	{
					$error = $e->getMessage();
				}
				break;

			case 'importstatic':
				TodoyuInstallerDbHelper::importStaticData();
				self::setStepNum(5);
				break;

			case 'config':
				try {
					self::updateConfig($data);
					self::setStepNum(6);
				} catch (Exception $e)	{
					$error = $e->getMessage();
				}
				break;

				break;

			case 'setadminpassword':
				try {
					TodoyuInstallerDbHelper::updateAdminPassword($data['password'], $data['password_confirm']);
					self::setStepNum(7);
				} catch(Exception $e)	{
					$error = $e->getMessage();
				}
				break;

			case 'finish':
				self::finish();
				self::setStepNum(0);
				break;



				// Update
			case 'welcometoupdate':
				break;

			case 'updatebeta1tobeta2':
					// have mandatory updates be carried out
				include( PATH . '/install/db/update_beta1_to_beta2.php');
				self::setStepNum(9);

				break;

			case 'finishupdate':
				self::finishUpdate();

				break;

		}

		return $error;
	}



	/**
	 * Display output for current step (order of evocation is: process, display)
	 *
	 *	@param	String	$error
	 */
	public static function displayStep($error) {
		$stepNr		= self::getStepNum();
		$stepName	= self::$steps[$stepNr];

		switch($stepName) {
				// Install
			case 'welcome':
				echo TodoyuInstallerRenderer::renderWelcome($error);
				break;

			case 'servercheck':
				echo TodoyuInstallerRenderer::renderServercheck($error);
				break;

			case 'dbconnection':
				echo TodoyuInstallerRenderer::renderDbConnection($error);
				break;

			case 'dbselect':
				echo TodoyuInstallerRenderer::renderDbSelect($error);
				break;

			case 'importstatic':
				echo TodoyuInstallerRenderer::renderImportStatic($error);
				break;

			case 'config':
				echo TodoyuInstallerRenderer::renderConfig($error);
				break;

			case 'setadminpassword':
				echo TodoyuInstallerRenderer::renderAdminPassword($error);
				break;

			case 'finish':
				echo TodoyuInstallerRenderer::renderFinish($error);
				break;


				// Update
			case 'welcometoupdate':
				echo TodoyuInstallerRenderer::renderWelcomeToUpdate($error);
				break;

			case 'updatebeta1tobeta2':
			case 'dbstructurecheck':
					// check for changes in 'tables.sql' files against DB
				echo TodoyuInstallerRenderer::renderDBstructureCheck($error);
				break;

			case 'finishupdate':
				echo TodoyuInstallerRenderer::renderUpdateFinished($error);
				break;
		}
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
	 * Finish installer steps
	 * Rename ENABLE file to disable the installer
	 */
	private static function finish() {
		$old	= PATH . '/install/ENABLE';
		$new	= PATH . '/install/_ENABLE';

		rename($old, $new);

		if( file_exists(PATH . '/index.html') )	{
			unlink(PATH . '/index.html');
		}

		self::setStepNum(0);

		header('Location: ' . dirname(SERVER_URL));
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
	 * Gather and perform queries, forward to next step (update finished)
	 */
	private static function finishUpdate() {
		$dbDiff	= TodoyuInstallerDbHelper::getDBstructureDiff();

		if ( count($dbDiff) > 0  ) {
			TodoyuInstallerDbHelper::compileAndRunInstallerQueries($dbDiff);
		}

		self::setStepNum(11);
	}

}
?>