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


	private $steps = array();


	/**
	 * @param	String		$name
	 */
	public function __construct($name) {
		$this->name	= $name;
	}


	protected function renderStep($stepName) {


		$step	= $this->getStep($stepName);

		return $step->renderContent();
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
		$step		= $this->getActiveStep();

		$tmpl	= 'core/view/wizard.tmpl';
		$data	= array(
			'wizardName'=> $this->name,
			'steps'		=> $this->getStepItems(),
			'stepName'	=> $step->getName(),
			'title'		=> $step->getLabel(),
			'content'	=> $step->renderContent(),
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

		if( ! isset($this->steps[$stepName]) ) {
			$stepConfig	= TodoyuWizardManager::getStep($this->name, $stepName);

			$class	= get_class($this) . 'Step' . ucfirst(strtolower($stepName));

			if( class_exists($class, true) ) {
				 $this->steps[$stepName] = new $class($this, $stepConfig);
			} else {
				throw new Exception('Wizard step class not found: ' . $class);
			}
		}

		return $this->steps[$stepName];
	}



	/**
	 * Get name of active step. Use parameter if step name was given
	 * @param null $stepName
	 * @return Mixed|null|void
	 */
	protected function getActiveStepName($stepName = null) {
		if( empty($stepName) ) {
			$stepName = TodoyuWizardManager::getCurrentStep($this->name);
		}
		if( empty($stepName) ) {
			$stepName =	$this->getFirstStep();
		}

		return $stepName;
	}


	/**
	 * @return	TodoyuWizardStep
	 */
	public function getActiveStep() {
		return $this->getStep($this->getActiveStepName());
	}



	/**
	 * Get label of active step
	 *
	 * @return	String
	 */
	public function getActiveStepLabel() {
		return $this->getActiveStep()->getLabel();
	}


	/**
	 * Change step into given direction (next/back)
	 *
	 * @param	String		$direction
	 * @return	String		Next step key
	 */
	public function goToStepInDirection($direction) {
		if( $direction === 'next' ) {
			return $this->goToNextStep();
		} elseif( $direction === 'back' ) {
			return $this->goToLastStep();
		} else {
			return $this->getActiveStepName();
		}
	}

	public function goToNextStep() {
		$nextStep	= $this->getNextStepName();

		TodoyuWizardManager::setCurrentStep($this->name, $nextStep);

		return $nextStep;
	}


	public function goToLastStep() {
		$lastStep	= $this->getLastStepName();

		TodoyuWizardManager::setCurrentStep($this->name, $lastStep);

		return $lastStep;
	}

	protected function getNextStep() {
		return $this->getStep($this->getNextStepName());
	}

	protected function getLastStep() {
		return $this->getStep($this->getLastStepName());
	}



	protected function getNextStepName() {
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






	protected function getLastStepName() {
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