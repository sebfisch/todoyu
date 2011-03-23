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
 * class for radio boxes
 *
 * @package 	Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_Radio extends TodoyuFormElement {

	/**
	 * Constructor of the class
	 *
	 * @param	String		$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	function __construct($name, TodoyuFieldset $fieldset, array $config  = array()) {
		parent::__construct('radio', $name, $fieldset, $config);

//		if( ! $this->isLazyInit() ) {
//			$this->init();
//		}
	}



	/**
	 * Init
	 */
	protected function init() {
		if( is_array($this->config['source']) ) {
			$type	= $this->config['source']['@attributes']['type'];
			$source	= $this->config['source'];

			switch( $type ) {
				case 'list':
					$this->initSourceList($source);
					break;


				case 'function':
					$this->initSourceFunction($source);
					break;


				case 'sql':
					$this->initSourceSql($source);
					break;


				default:
					die("Unknown source tpye");
					break;
			}
		}
	}



	/**
	 * Init options from a XML list
	 *
	 * @param	Array		$source
	 */
	protected function initSourceList(array $source) {
		if( is_array($source['option']) ) {
			foreach($source['option'] as $option) {
				$this->addOption($option['value'], $option['label'], $option['checked']);
			}
		}
	}



	/**
	 * Load select options from database
	 *
	 * @param	Array		$source
	 */
	protected function initSourceSql(array $source) {
		$data	= Todoyu::db()->getArray(
			$source['fields'],
			$source['tables'],
			$source['where'],
			$source['group'],
			$source['order'],
			$source['limit']
		);

			// Key for label and value
		$valueKey	= $source['value'];
		$labelKey	= $source['label'];

			// Set flag
		$useValueFunc = false;
		$useLabelFunc = false;

		if( strstr($valueKey, '::') !== false ) {
			$valueFunc = explode('::', $valueKey);
			if( method_exists($valueFunc[0], $valueFunc[1]) ) {
				$useValueFunc = true;
			}
		}

		if( strstr($labelKey, '::') !== false ) {
			$labelFunc = explode('::', $labelKey);
			if( method_exists($labelFunc[0], $labelFunc[1]) ) {
				$useLabelFunc = true;
			}
		}

		foreach( $data as $option ) {
			$value	= $useValueFunc ? call_user_func($valueFunc, $this, $option) : $option[$valueKey];
			$label	= $useLabelFunc ? call_user_func($labelFunc, $this, $option) : $option[$labelKey];

			$this->addOption($value, $label);
		}
	}



	/**
	 * Initialize source function
	 *
	 * @param	Array	$source
	 */
	protected function initSourceFunction(array $source) {
		$funcRef	= explode('::', $source['function']);

		if( sizeof($funcRef) == 2 ) {
			$options	= call_user_func($funcRef, $this->getForm());

			foreach($options as $option) {
				$this->addOption($option['value'], $option['label'], $option['checked']);
			}
		}
	}



	/**
	 * Detect if lazy init is defined (grab data when form is rendered)
	 *
	 * @return	Boolean
	 */
	protected function isLazyInit() {
		return array_key_exists('lazyInit', $this->config['source']);
	}



	/**
	 * Get all options
	 *
	 * @return	Array
	 */
	public function getOptions() {
		return $this->get('options');
	}



	/**
	 * Add a new option at the end of the list
	 *
	 * @param	String		$value
	 * @param	String		$label
	 * @param	Boolean		$checked
	 * @param	Boolean		$disabled
	 */
	public function addOption($value, $label, $checked = false, $disabled = false) {
		$this->config['options'][] = array(
			'value'		=> $value,
			'label'		=> label($label),
			'checked'	=> $checked,
			'disabled'	=> $disabled
		);

		if( $checked ) {
			$this->addCheckedValue($value);
		}
	}



	/**
	 * Set an option. The (first) option with the same value will be replaced.
	 * If no option with this value exists, a new options will be added
	 *
	 * @param	String		$value
	 * @param	String		$label
	 * @param	Boolean		$checked
	 * @param	Boolean		$disabled
	 */
	public function setOption($value, $label, $checked = false, $disabled = false) {
		$index = $this->getOptionIndexByValue($value);

		if( $index === false ) {
			$this->addOption($value, $label, $checked, $disabled);
		} else {
			$this->config['options'][$index] =  array(
				'value'		=> $value,
				'label'		=> $label,
				'checked'	=> $checked
			);
		}
	}



	/**
	 * Get the index of the option by its value
	 *
	 * @param	String		$value
	 * @return	Integer		Or false if not found
	 */
	protected function getOptionIndexByValue($value) {
		$optionIndex = false;

		foreach( $this->config['options'] as $index => $option ) {
			if( $option['value'] == $value ) {
				$optionIndex = $index;
				break;
			}
		}

		return $optionIndex;
	}



	/**
	 * Get selected value
	 *
	 * @return	Mixed		An array or a string
	 */
	public function getValue() {
		return $this->config['checked'];
	}



	/**
	 * Set selected value
	 *
	 * @param	Mixed		$value		An array or a string
	 */
	public function setValue($value) {
		$this->config['checked'] = $value;
	}



	/**
	 * Add value to selected-values list
	 *
	 * @param	String		$value
	 */
	public function addCheckedValue($value) {
		$this->setValue( TodoyuString::addToList($this->getValue(), $value, ',', true) );
	}



	/**
	 * Get data for template rendering
	 *
	 * @return	Array
	 */
	protected function getData() {
		if( $this->isLazyInit() ) {
			$this->init();
		}

		return parent::getData();
	}


}

?>