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

/** Abstract headlet base class
 *
 * @package		Todoyu
 * @subpackage	Core
 */

abstract class TodoyuHeadlet implements TodoyuHeadletInterface {

	/**
	 * Headlet template file
	 *
	 * @var	String
	 */
	protected $template	= '';

	/**
	 * Headlet render data
	 *
	 * @var	Array
	 */
	protected $data		= array();

	/**
	 * Request parameters
	 *
	 * @var	Array
	 */
	protected $params	= array();


	/**
	 * Headlet constructor which calls the init function
	 * if defined
	 * @param	Array		$params		Request parameters
	 * @final
	 */
	public final function __construct(array $params = array()) {
		$this->init();
	}



	/**
	 * Init function for panel widget, alternative for constructor
	 *
	 */
	protected function init() {
		// Dummy, override in extended headlet
	}



	/**
	 * Set headlet template
	 *
	 * @param	String		$template
	 */
	protected function setTemplate($template) {
		$this->template = $template;
	}



	/**
	 * Set headlet render data
	 *
	 * @param	Array		$data
	 */
	protected function setData(array $data) {
		$this->data = $data;
	}



	/**
	 * Get current area ID
	 *
	 * @return	Integer
	 */
	protected function getAreaID() {
		return Todoyu::getArea();
	}



	/**
	 * Get current area key
	 *
	 * @return	String
	 */
	protected function getAreaKey() {
		return Todoyu::getAreaKey();
	}



	/**
	 * Render headlet
	 *
	 * @return	String
	 */
	public function render() {
		$this->data['id'] = strtolower(str_replace('Todoyu','',get_class($this)));

		return render($this->template, $this->data);
	}

}


?>