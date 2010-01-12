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
class TodoyuInstallerStepManager {

	/**
	 * Set current step
	 *
	 * @param	Integer		$step
	 */
	public static function setStepNum($step) {
		$_SESSION['todoyuinstaller']['step'] = intval($step);
	}



	private static function setNextStepNumFromAction($action = '') {
		$stepNum	= self::getNextStepNumToAction($action);

		if ( $stepNum != false ) {
			self::setStepNum($stepNum);
		}
	}



	/**
	 * Reinit step num
	 */
	public static function reinitStepNum() {
		self::setStepNum(0);
	}



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
			if ( TodoyuInstaller::hasBeenInstalledBefore() ) {
				$step	= 8;	// 'welcometoupdate'
			}
		}

		return $step;
	}



	private static function getNextStepNumToAction($action = '') {
		$steps	= $GLOBALS['CONFIG']['INSTALLER']['steps'];

		foreach($steps as $num => $step) {
			if ( $step['processAction'] == $action ) {
				$stepNum	= $step['nextStepNum'];
			}
		}

		return $stepNum;
	}



	/**
	 * Get name of current step
	 *
	 *	@param	Integer	$stepnum
	 *  @return	String
	 */
	public static function getStepName($stepNum = 0) {
		$stepNum= intval($stepNum);
		$steps	= $GLOBALS['CONFIG']['INSTALLER']['steps'];

		return $steps[$stepNum]['name'];
	}



	/**
	 * Get render function reference of current step
	 *
	 *	@param	Integer	$stepnum
	 *  @return	Array
	 */
	public static function getStepRenderFunc($stepNum) {
		$stepNum= intval($stepNum);
		$step	= $GLOBALS['CONFIG']['INSTALLER']['steps'][$stepNum];

		return explode('::', $step['renderFuncRef']);
	}


	/**
	 * Display output for current step (order of evocation is: process, display)
	 *
	 *	@param	String	$error
	 */
	public static function displayStep($error) {
		$stepNum	= self::getStepNum();
		$stepName	= self::getStepName($stepNum);
		$renderFunc	= self::getStepRenderFunc($stepNum);

		if( method_exists($renderFunc[0], $renderFunc[1]) ) {
			echo call_user_func($renderFunc, $error);
		}
	}



	/**
	 * Process current step data (order of evocation is: process, display)
	 *
	 *	@todo	change hardcoded steps into variably configurable ones
	 *	@param	Array		$data
	 *	@return	String
	 */
	public static function processStep($data) {
		$action	= $data['action'];

			// set current step num from current action
		self::setNextStepNumFromAction($action);

		switch($action) {
				// Install
			case 'start':
				break;

				// Check server
			case 'servercheck':
				break;

				// Check DB connection
			case 'dbconnection':
				$_SESSION['todoyuinstaller']['db'] = $data;
				try {
					TodoyuDbAnalyzer::checkDbConnection($data);
				} catch(Exception $e) {
					$error = $e->getMessage();
				}
				break;

				// Add DB, save DB config
			case 'dbselect':
				try {
					TodoyuInstallerDbHelper::addDatabase();
					TodoyuInstallerDbHelper::saveDbConfigInFile();
				} catch(Exception $e)	{
					$error = $e->getMessage();
				}
				break;

				// Import static DB data
			case 'importstatic':
				TodoyuInstallerDbHelper::importStaticData();
				break;

				// Update system config file (/config/system.php)
			case 'config':
				try {
					TodoyuInstaller::updateConfig($data);
				} catch (Exception $e)	{
					$error = $e->getMessage();
				}
				break;

				break;

				// Validate password, store admin user and password in DB (table 'ext_user_user')
			case 'setadminpassword':
				try {
					TodoyuInstallerDbHelper::updateAdminPassword($data['password'], $data['password_confirm']);
				} catch(Exception $e)	{
					$error = $e->getMessage();
				}
				break;

				// Finish installer: deactivate, reinit step, go to todoyu login page
			case 'finish':
				TodoyuInstaller::finish();
				break;

				// --------------- Update -----------------

				// Welcome to version updates screen
			case 'welcometoupdate':
				break;

				// Update beta1 to beta2
			case 'updatebeta1tobeta2':
					// have mandatory updates be carried out
				include( PATH . '/install/config/db/update_beta1_to_beta2.php');
				break;

			case 'finishupdate':
				TodoyuInstaller::finishUpdate();
				break;
		}

		return $error;
	}

}
?>