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
 * Abstract headlet base class
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

	protected $buttonAttributes	= array();


	protected $jsHeadlet= 'xxx';




	/**
	 * Headlet constructor which calls the init function
	 *
	 * @final
	 */
	public final function __construct() {
		$this->addButtonClass('button');

		$this->initType();
		$this->init();
	}


	protected final function setJsHeadlet($jsHeadlet) {
		$this->jsHeadlet = $jsHeadlet;
	}



	/**
	 * Init function for type
	 *
	 */
	protected function initType() {
		// Dummy, override in headlet type class
	}


	/**
	 * Init function for panel widget, alternative for constructor
	 *
	 */
	protected function init() {
		// Dummy, override in headlet class
	}



	/**
	 * Get headlet name
	 *
	 * @return	String
	 */
	public function getName() {
		return str_replace('TodoyuHeadlet', '', get_class($this));
	}



	/**
	 * Get headlet ID (for HTML)
	 *
	 * @return	String
	 */
	public function getID() {
		return 'headlet-' . strtolower($this->getName());
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
	 * Add attribute for the button
	 *
	 * @param	String		$name
	 * @param	String		$value
	 */
	protected function addButtonAttribute($name, $value) {
		$this->buttonAttributes[$name][] = $value;
	}


	protected function addButtonClass($class) {
		$this->addButtonAttribute('class', trim($class) . ' ');
	}


	/**
	 * Get button attributes
	 *
	 * @return	Array
	 */
	protected function getButtonAttributes() {
		$attributes	= array();

		foreach($this->buttonAttributes as $name => $values) {
			$attributes[$name] = implode('', $values);
		}

		return $attributes;
	}



	/**
	 * Render headlet
	 *
	 * @return	String
	 */
	public function render() {
		$this->data['id'] = 'headlet-' . strtolower(str_replace('TodoyuHeadlet', '', get_class($this)));


		$this->data['buttonAttributes']	= $this->getButtonAttributes();

		TodoyuPage::addJsOnloadedFunction('Todoyu.Headlet.add.bind(Todoyu.Headlet, \'' . $this->getName() . '\', ' . $this->jsHeadlet . ')');


//		TodoyuDebug::printHtml($this->getButtonAttributes());

		return render($this->template, $this->data);
	}

}


?>