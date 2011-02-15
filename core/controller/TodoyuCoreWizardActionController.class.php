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
 * Core Action Controller
 * About
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuCoreWizardActionController extends TodoyuActionController {

	/**
	 * Render about window
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function loadAction(array $params) {
		$wizardName	= trim($params['wizard']);
		$step		= trim($params['step']);

		$wizard		= TodoyuWizardManager::getWizard($wizardName);

		return $wizard->render($step);
	}


	public function saveAction(array $params) {
		$wizardName	= trim($params['wizard']);
		$step		= trim($params['step']);
		$direction	= trim($params['direction']);
		$data		= TodoyuArray::assure($params['data']);

		$wizard		= TodoyuWizardManager::getWizard($wizardName);

		if( $wizard->save($step, $data) ) {
			$step	= $wizard->goToStepInDirection($direction);

//			TodoyuDebug::printInFireBug($step, 'next step');
		} else {
//			TodoyuDebug::printInFireBug('failed, render again the same');
		}

		return $wizard->render($step);
	}

}

?>
