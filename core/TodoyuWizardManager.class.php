<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuWizardManager {

	/**
	 * Wizard configurations
	 *
	 * @var	Array
	 */
	private static $steps	= array();


	private static $wizards	= array();



	/**
	 * Add a wizard
	 *
	 * @param	String		$name
	 * @param	String		$class
	 */
	public static function addWizard($name, $class) {
		self::$wizards[$name] = $class;
	}



	/**
	 *
	 * @param	String			$name
	 * @return	TodoyuWizard
	 */
	public static function getWizard($name) {
		$class	= self::$wizards[$name];

		return new $class();
	}



	/**
	 * Add wizard step
	 *
	 * @param	String		$wizardName
	 * @param	Array		$stepConfig
	 */
	public static function addStep($wizardName, array $stepConfig) {
		self::$steps[$wizardName][$stepConfig['step']] = $stepConfig;
	}




	/**
	 * Get single wizard step
	 *
	 * @param	String		$wizardName
	 * @param	String		$stepName
	 * @return	Array
	 */
	public static function getStep($wizardName, $stepName) {
		return TodoyuArray::assure(self::$steps[$wizardName][$stepName]);
	}



	/**
	 * Get all steps of a wizard
	 *
	 * @param	String		$wizardName
	 * @return	Array
	 */
	public static function getSteps($wizardName) {
		return TodoyuArray::sortByLabel(TodoyuArray::assure(self::$steps[$wizardName], 'position'));
	}


	public static function setCurrentStep($wizardName, $stepName) {
		TodoyuSession::set('wizard/' . $wizardName, $stepName);
	}

	public static function getCurrentStep($wizardName) {
		return TodoyuSession::get('wizard/' . $wizardName);
	}

}

?>