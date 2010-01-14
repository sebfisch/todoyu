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
	 * Get current step num
	 *
	 * @return	Integer
	 */
	public static function getCurrentStepNum() {
		$step	= intval($_SESSION['todoyuinstaller']['step']);

			// Initial step? Check whether installation has been carried out before -> proceed with update
		if ( $step == 0 ) {
			if ( TodoyuInstaller::isUpdate() ) {
				$step	= 8;	// 'welcometoupdate'
			}
		}

		return $step;
	}



	public static function getCurrentStepName() {
		$stepNum	= self::getCurrentStepNum();

		return	self::getStepName($stepNum);
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
	 * Reinit step num
	 */
	public static function reinitStepNum() {
		self::setStepNum(0);
	}



	/**
	 * Set step num via given step's name
	 *
	 * @param	String	$action
	 */
	private static function setStepNumFromName($action = '') {
		$stepNum	= self::getStepNumFromName($action);

		if ( $stepNum !== false ) {
			self::setStepNum($stepNum);
		}
	}



//	private static function setNextStepNumFromName($action = '') {
//		$nextStepNum	= self::getNextStepNumFromName($action);
//
//		if ( $nextStepNum !== false ) {
//			self::setStepNum($nextStepNum);
//		}
//	}



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
	 * Get name of next step
	 *
	 * @param	Integer	$stepNum
	 * @return	String
	 */
	private static function getNextStepName($stepNum = 0) {
		$steps		= $GLOBALS['CONFIG']['INSTALLER']['steps'];
		$nextStepNum= $steps[$stepNum]['nextStepNum'];

		return $steps[$nextStepNum]['name'];
	}



	/**
	 * Get num of current step via current step's name
	 *
	 * @param unknown_type $action
	 */
	private static function getStepNumFromName($action = '') {
		$steps	= $GLOBALS['CONFIG']['INSTALLER']['steps'];

		foreach($steps as $num => $step) {
			if ( $step['name'] == $action ) {
				$stepNum	= $num;
			}
		}

		return $stepNum;
	}



	/**
	 * Get num of next step via current step's name
	 *
	 * @param	String	$action
	 * @return	Integer
	 */
	private static function getNextStepNumFromName($action = '') {
		$steps	= $GLOBALS['CONFIG']['INSTALLER']['steps'];

		foreach($steps as $num => $step) {
			if ( $step['name'] == $action ) {
				$stepNum	= $step['nextStepNum'];
			}
		}

		return $stepNum;
	}



	/**
	 * Get render or processing function reference of current step
	 *
	 * @param	Integer	$stepnum
	 * @param	String	$type
	 * @return	Array
	 */
	public static function getStepFunc($stepNum, $type = 'render') {
		$stepNum= intval($stepNum);
		$step	= $GLOBALS['CONFIG']['INSTALLER']['steps'][$stepNum];

		return explode('::', $step[$type . 'FuncRef']);
	}



	/**
	 * Process current step data (order of evocation is: process, display)
	 *
	 * @param	Array		$data
	 * @return	String
	 */
	public static function processStep($data) {
		$action	= $data['action'];

			// Set next step num from current action
		self::setStepNumFromName($action);

		$stepNum		= self::getCurrentStepNum();
		$processFunc	= self::getStepFunc($stepNum, 'process');

		if ($processFunc !== false ) {
			if( method_exists($processFunc[0], $processFunc[1]) ) {
				$error	= call_user_func($processFunc, $data);
			}
		}

		return $error;
	}



	/**
	 * Display output for current step (order of evocation is: process, display)
	 *
	 * @param	String	$error
	 */
	public static function displayStep($error) {
		$stepNum	= self::getCurrentStepNum();
		$renderFunc	= self::getStepFunc($stepNum, 'render');

		if( method_exists($renderFunc[0], $renderFunc[1]) ) {
			$nextStepName	= self::getNextStepName($stepNum);

			echo call_user_func($renderFunc, $nextStepName, $error);
		}
	}



	/**
	 * Jump to next step
	 */
	public static function jumpToNextStep()	{
		$steps		= $GLOBALS['CONFIG']['INSTALLER']['steps'];

		$curStep	=	self::getCurrentStepNum();
		$nextStepNum=	$steps[$curStep]['nextStepNum'];

		self::setStepNum($nextStepNum);

		TodoyuInstaller::reload();
	}

}
?>