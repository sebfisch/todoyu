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
 * Select form element
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_SelectGrouped extends TodoyuFormElement_Select {


	/**
	 * Initialize
	 *
	 * @param	String		$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		TodoyuFormElement::__construct('selectgrouped', $name, $fieldset, $config);

		if( ! $this->isLazyInit() ) {
			$this->init();
		}
	}



	/**
	 * Init select groups' options from function reference
	 *
	 * @param	Array	$source
	 */
	protected function initSourceFunction(array $source) {
		$funcRef	= explode('::', $source['function']);

		switch( sizeof($funcRef) ) {
				// funcRef is built like class::function
			case 2:
				$groups	= call_user_func($funcRef, $this);
				foreach($groups as $group => $options) {
					foreach($options as $option) {
						$this->addOption($group, $option['value'], $option['label'], $option['selected'], $option['disabled']);
					}
				}
				break;


				// funcRef is built like class::function::param, param is e.g the field ID
			case 3:
				Todoyu::log('Non standard 3 parts select source function: ' . $source['function'], LOG_LEVEL_NOTICE);
				$funcParam	= $funcRef[2];
				array_pop($funcRef);
				$groups	= call_user_func($funcRef, $this->getForm()->getFormData(), $funcParam);
				foreach($options as $option) {
					$this->addOption($group, $option['value'], $option['label'], $option['selected'], $option['disabled']);
				}
				break;
		}
	}



	/**
	 * Add a new option at the end of the list
	 *
	 * @param	String		$value
	 * @param	String		$label
	 */
	public function addOption($group, $value, $label, $selected = false, $disabled = false) {
		$this->config['options'][$group][] = array(
			'value'		=> $value,
			'label'		=> $label,
			'disabled'	=> $disabled
		);

		if( $selected ) {
			$this->addSelectedValue($value);
		}
	}


}

?>