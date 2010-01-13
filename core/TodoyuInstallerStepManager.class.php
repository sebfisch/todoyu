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

		if ( $stepNum !== false ) {
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
	 *	@param	String	$type
	 *  @return	Array
	 */
	public static function getStepFunc($stepNum, $type = 'render') {
		$stepNum= intval($stepNum);
		$step	= $GLOBALS['CONFIG']['INSTALLER']['steps'][$stepNum];

		return explode('::', $step[$type . 'FuncRef']);
	}


	/**
	 * Display output for current step (order of evocation is: process, display)
	 *
	 *	@param	String	$error
	 */
	public static function displayStep($error) {
		$stepNum	= self::getStepNum();
		$stepName	= self::getStepName($stepNum);
		$renderFunc	= self::getStepFunc($stepNum, 'render');

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

			// Set next step num from current action
		self::setNextStepNumFromAction($action);

		$stepNum		= self::getStepNum();
		$processFunc	= self::getStepFunc($stepNum, 'process');

		if ($processFunc !== false ) {
			if( method_exists($processFunc[0], $processFunc[1]) ) {
				$error	= call_user_func($processFunc, $data);
			}
		}

		return $error;
	}

}
?>