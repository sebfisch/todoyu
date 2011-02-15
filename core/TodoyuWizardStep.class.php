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
abstract class TodoyuWizardStep {

	protected $wizard;

	protected $config;

	protected $data = array();

	final public function __construct(TodoyuWizard $wizard, array $config) {
		$this->wizard	= $wizard;
		$this->config	= $config;
	}


	public function getName() {
		return $this->config['step'];
	}


	public function getLabel() {
		return Label($this->config['label']);
	}


	public function init() {

	}

	public function renderHelp() {
		return false;
	}


	abstract public function save(array $data);

	abstract public function render();

}

?>