<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

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
		
			// No installation step or restart? initialize installer
		if( ! self::hasStep() || self::isRestart() ) {
				// Cleanup before initializing
			self::onInitCleanup();
				// Initialize step in session
			self::initStep();
				// Clear all cache
			TodoyuInstallerManager::clearCache();
		}

		$step	= self::getStep();

			// Get post data
		if( self::hasData() ) {
			$postData	= TodoyuRequest::getAll();
		} else {
			$postData	= array();
		}

			// Set installation language
		$locale	= TodoyuSession::get('installer/locale');
		if( $locale != '' ) {
			Todoyu::setLocale($locale);
		}
		
			// Process current step of installation
		$result	= self::process($step, $postData);

		$step	= self::getStep();

		echo self::display($step, $result, $postData);

			// Flush output buffer
		ob_end_flush();
	}



	/**
	 * Cleanup installation before initializing
	 */
	private static function onInitCleanup() {
			// Delete all cache
		TodoyuFileManager::deleteFolderContents(PATH_CACHE, false);

			// Update task assets file structure
		TodoyuInstallerManager::changeFilesAssetStructure();

		/**
		 * /Was only necessary for the RC2 release
		 */

			// Remove files of old installations
//		TodoyuInstallerManager::removeOldFiles();
			// Update config files if necessary
//		TodoyuInstallerManager::updateConfigFileVariables();
	}



	/**
	 * Set installation step (session)
	 *
	 * @param	String		$step
	 */
	public static function setStep($step) {
		TodoyuSession::set('installer/step', $step);
	}



	/**
	 * Get current installation step
	 *
	 * @return	String
	 */
	public static function getStep() {
		return TodoyuSession::get('installer/step');
	}



	/**
	 * Check if a step is set
	 *
	 * @return	Boolean
	 */
	private static function hasStep() {
		return TodoyuSession::isIn('installer/step');
	}



	/**
	 * Check if restart flag is set
	 *
	 * @return	Boolean
	 */
	private static function isRestart() {
		return intval($_GET['restart']) === 1;
	}



	/**
	 * Check if data is submitted
	 *
	 * @return	Bool
	 */
	private static function hasData() {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}



	/**
	 * Check if ENABLE file is available
	 *
	 * @return	Boolean
	 */
	public static function isEnabled() {
		$file	= TodoyuFileManager::pathAbsolute('install/ENABLE');

		return is_file($file);
	}



	/**
	 * Initialize first step. Install or update? Save mode in session
	 */
	private static function initStep() {
		if( self::isUpdate() ) {
			self::setStep(INSTALLER_INITIALSTEP_UPDATE);
			self::setMode('update');
		} else {
			self::setStep(INSTALLER_INITIALSTEP_INSTALL);
			self::setMode('install');
		}
	}



	/**
	 * Set run mode (install or update)
	 *
	 * @param	String		$mode
	 */
	private static function setMode($mode) {
		TodoyuSession::set('installer/mode', $mode);
	}



	/**
	 * Get run mode
	 *
	 * @return	String
	 */
	public static function getMode() {
		return TodoyuSession::get('installer/mode');
	}



	/**
	 * Get configuration array for a step
	 *
	 * @param	String		$step
	 * @return	Array
	 */
	public static function getStepConfig($step) {
		return TodoyuArray::assure(Todoyu::$CONFIG['INSTALLER']['steps'][$step]);
	}



	/**
	 * Process submitted data for a step. Call processing function
	 *
	 * @param	String		$step
	 * @param	Array		$data
	 * @return	Array
	 */
	private static function process($step, array $data = array()) {
		$stepConfig	= self::getStepConfig($step);

		if( TodoyuFunction::isFunctionReference($stepConfig['process']) ) {
			return TodoyuFunction::callUserFunction($stepConfig['process'], $data);
		} else {
			return array();
		}
	}



	/**
	 * Display step
	 *
	 * @param	String		$step
	 * @param	Array		$result
	 * @return	String
	 */
	private static function display($step, array $result = array(), array $postData = array()) {
		$stepConfig	= self::getStepConfig($step);
		$tmpl		= 'install/view/' . $stepConfig['tmpl'];

		if( TodoyuFunction::isFunctionReference($stepConfig['render']) ) {
			$data	= TodoyuFunction::callUserFunction($stepConfig['render'], $result);
		} else {
			$data	= array();
		}

		$data['progress']	= TodoyuInstallerRenderer::renderProgressWidget($step);
		$data['result']		= $result;
		$data['postData']	= $postData;

		return render($tmpl, $data);
	}



	/**
	 * Check if step if part of the update run
	 *
	 * @param	String		$step
	 * @return	Boolean
	 */
	public static function isUpdateStep($step) {
		return in_array($step, Todoyu::$CONFIG['INSTALLER']['update']);
	}



	/**
	 * Get steps of the current mode
	 *
	 * @return	Array
	 */
	public static function getModeSteps() {
		$type	= self::getMode();

		return TodoyuArray::assure(Todoyu::$CONFIG['INSTALLER'][$type]);
	}



	/**
	 * Get mode steps (update or install) with labels
	 *
	 * @return	Array
	 */
	public static function getStepsWithLabels() {
		$steps		= self::getModeSteps();
		$withLabels	= array();

		foreach($steps as $step) {
			$withLabels[$step] = Label('installer.' . $step . '.title');
		}

		return $withLabels;
	}


	
	/**
	 * Get locale options with localized labels
	 *
	 * @return Array
	 */
	public static function getAvailableLocaleOptions() {
		$locales	= TodoyuLocaleManager::getAvailableLocales();
		$options	= array();

		foreach($locales as $locale) {
			$options[] = array(
				'key'	=> $locale,
				'label'	=> TodoyuLanguage::getLabel('installer.locale.selectthislocale', $locale)
			);
		}

		return $options;
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
		return TodoyuInstallerManager::isDatabaseConfigured() || self::hasDoubleEnableFile();
	}

}
?>