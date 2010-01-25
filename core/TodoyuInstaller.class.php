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
			// Start output buffer
		ob_start();



		if( ! self::hasStep() || self::isRestart() ) {
			self::initStep();
		}

		$step	= self::getStep();

		if( self::hasData() ) {
			$result	= self::process($step, $_POST);
		} else {
			$result	= array();
		}

		$step	= self::getStep();

		echo self::display($step, $result);

			// Flush output buffer
		ob_end_flush();
	}


	public static function setStep($step) {
		TodoyuSession::set('installer/step', $step);
	}


	public static function getStep() {
		return TodoyuSession::get('installer/step');
	}


	private static function hasStep() {
		return TodoyuSession::isIn('installer/step');
	}


	private static function isRestart() {
		return intval($_GET['restart']) === 1;
	}


	private static function hasData() {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

	public static function isEnabled() {
		$file	= TodoyuFileManager::pathAbsolute('install/ENABLE');

		return is_file($file);
	}


	private static function initStep() {
		if( self::isUpdate() ) {
			self::setStep('update');
			TodoyuSession::set('installer/mode', 'update');
		} else {
			self::setStep('install');
			TodoyuSession::set('installer/mode', 'install');
		}
	}


	public static function getStepConfig($step) {
		return $GLOBALS['CONFIG']['INSTALLER']['steps'][$step];
	}



	private static function process($step, array $data = array()) {
		$stepConfig	= self::getStepConfig($step);

		if( TodoyuDiv::isFunctionReference($stepConfig['process']) ) {
			return TodoyuDiv::callUserFunction($stepConfig['process'], $data);
		} else {
			return array();
		}
	}


	private static function display($step, array $result = array()) {
		$stepConfig	= self::getStepConfig($step);
		$tmpl		= 'install/view/' . $stepConfig['tmpl'];

		if( TodoyuDiv::isFunctionReference($stepConfig['render']) ) {
			$data	= TodoyuDiv::callUserFunction($stepConfig['render'], $result);
		} else {
			$data	= array();
		}

		$data['progress'] = TodoyuInstallerRenderer::getProgressRenderData();

		return render($tmpl, $data);
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
	 * Get files which will be executed on an update
	 *
	 * @return	Array
	 */
	public static function getRequiredVersionUpdates() {
		$dbVersion	= TodoyuDbAnalyzer::getDBVersion();
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


}
?>