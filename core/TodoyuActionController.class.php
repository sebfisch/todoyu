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
 * Base action controller which will be extended by every action controller
 * of the extensions
 *
 * @package		Todoyu
 * @subpackage	Core
 * @abstract
 */

abstract class TodoyuActionController {
	/**
	 * Request parameters
	 *
	 * @var	Array
	 */
	protected $params;
	
	
	
	/**
	 * Constructor
	 * Set params and call init function
	 *
	 * @param	 Array		$params
	 */
	public final function __construct(array $params) {
		$this->params	= $params;
		
		$this->init($params);
	}
	
	
	
	/**
	 * Destructor is unused at the moment
	 *
	 */
	public function __destruct() {
		
	}
	
	
	
	/**
	 * Init function
	 * Can be overriden in extended class. Can be used as constructor alternative
	 *
	 * @param	Array		$params
	 */
	public function init(array $params) {
		// Override for custom init
	}
	
	
	
	/**
	 * Run requested action
	 * If requested action is not defined in current object, check for _unknowAction.
	 * If _unknownAction() is defined, call it. First parameter is original action name, second are all parameters
	 *
	 * @param	String		$action
	 * @return	String
	 */
	public final function runAction($action = 'default') {
		$result	= false;
		
		if( $this->isAction($action) ) {
			$method	= $this->getActionMethodName($action);
			$result	= call_user_func(array($this, $method), $this->params);
		} elseif( method_exists($this, '_unknownAction') ) {
			$result	= call_user_func(array($this, '_unknownAction'), $action, $this->params);
		} else {
			throw new TodoyuControllerException(EXT, get_class($this), $action, 'Action "' . $action . '" not found in ' . get_class($this));
			$result	= false;
		}
		
		return $result;
	}
	
	
	
	/**
	 * Get method name for the action
	 *
	 * @param	String		$action
	 * @return	String
	 */
	protected function getActionMethodName($action) {
		return strtolower($action) . 'Action';
	}
	
	
	
	/**
	 * Check if action exists (method is defined)
	 *
	 * @param	String		$action
	 * @return	Bool
	 */
	public function isAction($action) {
		$funcName	= self::getActionMethodName($action);

		return method_exists($this, $funcName);
	}
	
	
	
	/**
	 * Dummy default action
	 * If controller is called without an action and defaultAction is not defined in
	 * extended object, print error
	 *
	 * @param	Array		$params
	 */
	public function defaultAction($params = array()) {
		die("THERE IS NO DEFAULT ACTION DEFINED");
	}
	
}

?>