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
 * @subpackage	Core
 */
abstract class TodoyuWizard {

	/**
	 * Wizard name
	 *
	 * @var	String
	 */
	private $name;


	/**
	 * @param	String		$name
	 */
	public function __construct($name) {
		$this->name	= $name;
	}


	protected function renderStep($stepName) {


		$step	= $this->getStep($stepName);

		return $step->render();
	}


	/**
	 * Save
	 * @param  $stepName
	 * @param array $data
	 * @return void
	 */
	public function save($stepName, array $data) {
		return $this->getStep($stepName)->save($data);
	}


	/**
	 * Render step of the wizard
	 *
	 * @param	String		$stepName
	 * @return 	String
	 */
	public function render($stepName) {
		$stepName	= $this->getActiveStepName($stepName);
		$step		= $this->getStep($stepName);

		$tmpl	= 'core/view/wizard.tmpl';
		$data	= array(
			'wizardName'=> $this->name,
			'stepName'	=> $stepName,
			'steps'		=> $this->getStepItems(),
			'title'		=> $step->getLabel(),
			'content'	=> $step->render(),
			'help'		=> $step->renderHelp()
		);

		return render($tmpl, $data);
	}



	/**
	 * Get all step items with rendered label
	 *
	 * @return	Array
	 */
	protected function getStepItems() {
		$steps	= TodoyuWizardManager::getSteps($this->name);

//		foreach($steps as $index => $step) {
//			$steps[$index]['label'] = Label($step['label']);
//		}

		return $steps;
	}


	/**
	 * Get step object
	 *
	 * @param	String		$step
	 * @return	TodoyuWizardStep
	 */
	protected function getStep($stepName) {
		$stepName	= $this->getActiveStepName($stepName);
		$stepConfig	= TodoyuWizardManager::getStep($this->name, $stepName);

		$class	= get_class($this) . 'Step' . ucfirst(strtolower($stepName));

		if( class_exists($class, true) ) {
			return new $class($this, $stepConfig);
		} else {
			throw new Exception('Wizard step class not found: ' . $class);
		}
	}


	protected function getActiveStepName($stepName = null) {
		if( empty($stepName) ) {
			$stepName = TodoyuWizardManager::getCurrentStep($this->name);
		}
		if( empty($stepName) ) {
			$stepName =	$this->getFirstStep();
		}

		return $stepName;
	}


	public function goToStepInDirection($direction) {
		return $direction === 'next' ? $this->goToNextStep() : $this->goToLastStep();
	}

	public function goToNextStep() {
		$nextStep	= $this->getNextStep();

		TodoyuWizardManager::setCurrentStep($this->name, $nextStep);

		return $nextStep;
	}


	public function goToLastStep() {
		$lastStep	= $this->getLastStep();

		TodoyuWizardManager::setCurrentStep($this->name, $lastStep);

		return $lastStep;
	}



	protected function getNextStep() {
		$activeStep	= $this->getActiveStepName();

		$steps		= TodoyuWizardManager::getSteps($this->name);
		$found		= false;

		foreach($steps as $step) {
			if( $found ) {
				return $step['step'];
			}
			if( $step['step'] == $activeStep ) {
				$found = true;
			}
		}

		return false;
	}


	protected function getLastStep() {
		$activeStep	= $this->getActiveStepName();

		$steps		= TodoyuWizardManager::getSteps($this->name);
		$last		= $steps[0]['step'];

		foreach($steps as $step) {
			if( $step['step'] == $activeStep ) {
				return $last;
			}

			$last = $step['step'];
		}

		return false;
	}


	abstract protected function getFirstStep();

}

?>