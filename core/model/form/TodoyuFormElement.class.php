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
 * Base class for all form elements
 *
 * @package		Todoyu
 * @subpackage	Form
 */
abstract class TodoyuFormElement implements TodoyuFormElementInterface {

	/**
	 * Type of the element
	 *
	 * @var	String
	 */
	protected $type;

	/**
	 * Name of the form element
	 *
	 * @var	String
	 */
	protected $name;

	/**
	 * Parent fieldset
	 *
	 * @var	TodoyuFieldset
	 */
	protected $fieldset;

	/**
	 * Field configuration
	 *
	 * @var	Array
	 */
	public $config;


	/**
	 * Field error
	 *
	 * @var	Boolean
	 */
	protected $error = false;

	/**
	 * Field error message
	 *
	 * @var	String
	 */
	protected $errorMessage = '';



	/**
	 * Initialize form element
	 *
	 * @param	String		$type
	 * @param	String		$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	public function __construct($type, $name, TodoyuFieldset $fieldset, array $config = array()) {
		$this->type 	= $type;
		$this->name 	= $name;
		$this->fieldset = $fieldset;
		$this->config	= $config;

		$this->setAttribute('nodeAttributes', $this->getAttribute('@attributes'));
		$this->setAttribute('htmlId', $this->getForm()->makeID($this->name));

			// Parse labels of comment fields
		$this->setAfterFieldText($this->getAfterFieldText());
		$this->setBeforeFieldText($this->getBeforeFieldText());

		$this->init();

			// If default value is set in form xml, register it in form
		if( $this->hasAttribute('value') ) {
			$this->updateFormdata($this->getValue());
		}
	}



	/**
	 * Init after constructor
	 * Can be overridden in extended types
	 */
	protected function init() {

	}



	/**
	 * Remove the field from the form
	 */
	public function remove() {
		$this->fieldset->removeField($this->getName(), true);
	}



	/**
	 * Get template for this form element
	 *
	 * @return	String
	 */
	protected function getTemplate() {
		return TodoyuFormFactory::getTemplate($this->type);
	}



	/**
	 * Get form instance
	 *
	 * @return	TodoyuForm
	 */
	public final function getForm() {
		return $this->fieldset->getForm();
	}



	/**
	 * Get fieldset
	 *
	 * @return	TodoyuFieldset
	 */
	public final function getFieldset() {
		return $this->fieldset;
	}



	/**
	 * Alias for parent fieldset element
	 *
	 * @see		getFieldset()
	 * @return	TodoyuFieldset
	 */
	public final function getParent() {
		return $this->getFieldset();
	}



	/**
	 * Set parent fieldset. Only necessary when inserted into another form
	 *
	 * @param	TodoyuFieldset		$fieldset
	 */
	public final function setFieldset(TodoyuFieldset $fieldset) {
		$this->fieldset = $fieldset;
	}



	/**
	 * Get data to render the element
	 * A lot of config fields have to be processed and transformed, before
	 * the element can be rendered with its template
	 *
	 * @return	Array
	 */
	protected function getData() {
			// Parse all attributes with form data
		foreach($this->config as $key => $value) {
			if( ! is_array($value) ) {
				if( strpos($value, '#') !== false ) {
					$this->config[$key] = $this->getForm()->parseWithFormData($value);
				}
			}
		}

		$this->config['htmlId']			= $this->getForm()->makeID($this->name);
		$this->config['htmlName']		= $this->getForm()->makeName($this->name, $this->config['multiple']);
//		$this->config['label']			= $this->config['label'] ? TodoyuString::getLabel($this->config['label']) : '&nbsp;';
		$this->config['containerClass']	= 'type' . ucfirst($this->type) . ' fieldname' . ucfirst(str_replace('_', '', $this->name));
		$this->config['inputClass']		= $this->type;
		$this->config['required']		= $this->hasAttribute('required');
		$this->config['hasErrorClass']	= $this->hasAttribute('hasError') ? 'fieldHasError':'';
		$this->config['hasIconClass']	= $this->hasAttribute('hasIcon') ? 'hasIcon icon' . ucfirst($this->name):'';
		$this->config['wizard']			= $this->hasAttribute('wizard') ? $this->getWizardConfiguration() : false;
		$this->config['valueTemplate']	= $this->getValueForTemplate();

		return $this->config;
	}



	/**
	 * Get config value
	 *
	 * @param	String		$name
	 * @return	Mixed
	 */
	public function __get($name) {
		return $this->config[$name];
	}



	/**
	 * Set config value
	 *
	 * @param	String		$name
	 * @param	Mixed		$value
	 */
	public function __set($name, $value) {
		$this->setAttribute($name, $value);
	}



	/**
	 * Set config value
	 *
	 * @param	String		$name
	 * @param	Mixed		$value
	 */
	public function setAttribute($name, $value) {
		$this->config[$name] = $value;
	}



	/**
	 * Set form element label
	 *
	 * @param	String		$label
	 */
	public function setLabel($label) {
		$this->setAttribute('label', $label);
	}



	/**
	 * Set required flag
	 *
	 * @param	Boolean		$required
	 */
	public function setRequired($required = true) {
		$this->setAttribute('required', $required?true:false);
	}



	/**
	 * Remove option with given value from options array of config (of <select> element)
	 *
	 * @param	Mixed	$value
	 */
	public function removeOptionByValue($value) {
		$this->removeOptionsByValues(array($value));
	}



	/**
	 * Remove options with any of the given values from options array of config (of <select> element)
	 *
	 * @param	Array	$values
	 */
	public function removeOptionsByValues(array $values) {
		$options		= $this->config['options'];
		$cleanOptions	= array();

		foreach($options as $key => $option) {
			if( ! in_array($option['value'], $values) ) {
				$cleanOptions[$key]	= $option;
			}
		}

		$this->setAttribute('options', $cleanOptions);
	}



	/**
	 * Add user validator to given form element (field)
	 *
	 * @param	Array		$validatorConfig
	 */
	public function addUserValidator(array $validatorConfig) {
			// Get validator
		$validator	= $this->getAttribute('validate');
		$userValidator	= $validator['user'];

			// Ensure listed attributes are an array, so new ones are addable
		$isAttributesArray = TodoyuArray::getFirstKey($userValidator) !== '@attributes';
		if( ! $isAttributesArray ) {
			$attributes		= $userValidator;
			$userValidator	= array('0'	=> $attributes);
		}
			// Add new validator
		$userValidator[]= $validatorConfig;

		$validator['user']	= $userValidator;
		$this->setAttribute('validate', $validator);
	}



	/**
	 * Get config value
	 *
	 * @param	String		$name
	 * @return	Mixed
	 */
	public function getAttribute($name) {
		return $this->config[$name];
	}



	/**
	 * Check if an attribute is set
	 *
	 * @param	String		$name
	 * @return	Boolean
	 */
	public function hasAttribute($name) {
		return isset($this->config[$name]);
	}



	/**
	 * Get form element field name
	 *
	 * @return 	String
	 */
	public function getName() {
		return $this->name;
	}



	/**
	 * Get absolute name of the field
	 * Based on the position: fieldset-subfieldset-fieldname
	 *
	 * @return	String
	 */
	public function getAbsoluteName() {
		return $this->getFieldset()->getAbsoluteName() . '-' . $this->name;
	}



	/**
	 * Get form element label
	 *
	 * @return unknown
	 */
	public function getLabel() {
		return $this->getAttribute('label');
	}



	/**
	 * Get type of form element
	 *
	 * @return	String
	 */
	public function getType() {
		return $this->type;
	}



	/**
	 * Get field value ('attribute') of form element
	 *
	 * @return	Mixed
	 */
	public function getValue() {
		return $this->getAttribute('value');
	}



	/**
	 * Set field value ('attribute')
	 *
	 * @param	Mixed		$value
	 * @param	Boolean		$updateForm		Update the form. Can be false if the form already has the value
	 */
	public function setValue($value, $updateForm = true) {
		$this->setAttribute('value', $value);

		if( $updateForm ) {
			$this->updateFormData($value);
		}
	}



	/**
	 * Set a new name for the field
	 *
	 * @param	String		$name
	 */
	public function setName($name) {
		$this->name	= $name;
	}



	/**
	 * Get value for template display
	 * Override this function in custom field type if special rendering necessary
	 *
	 * @return	String
	 */
	public function getValueForTemplate() {
		return $this->getValue();
	}



	/**
	 * Update form data for field
	 *
	 * @param	Mixed		$value
	 */
	protected function updateFormdata($value) {
		$this->getForm()->setFieldFormData($this->getName(), $value);
	}



	/**
	 * Get HTML ID of field
	 *
	 * @return	String
	 */
	public function getHtmlID() {
		return $this->getForm()->makeID($this->getName());
	}



	/**
	 * Get data of field to store in the database
	 *
	 * @return	String
	 */
	public function getStorageData() {
		if( $this->isNoStorageField() ) {
			return false;
		} else {
			return $this->getValue();
		}
	}



	/**
	 * Check if field is hidden (not displayed when fieldset is rendered)
	 *
	 * @return	String
	 */
	public function isHidden() {
		return $this->hasAttribute('hide');
	}



	/**
	 * Check if field is marked as no storage. If true,
	 * the field will not be stored in the database
	 *
	 * @return	Boolean
	 */
	public function isNoStorageField() {
		return $this->hasAttribute('noStorage');
	}



	/**
	 * Check if field is valid
	 *
	 * @return	Boolean
	 */
	public final function isValid() {
		$validations 	= $this->getValidations();
		$formData		= $this->getForm()->getFormData();

			// Loop over all validators
		foreach($validations as $validatorName => $validatorConfigs) {
				// If multiple validators with the same name are defined,
				// they are stored in an array, loop over them
			if( !is_string($validatorConfigs) && isset($validatorConfigs['0']) ) {
					// Loop over all instances of a validator type
				foreach($validatorConfigs as $validatorConfig) {
					$result	= $this->runValidator($validatorName, $validatorConfig, $formData);

					if( $result === false ) {
						return false;
					}
				}
			} else {
					// A validator type was only used once for this field, run normal
				$validatorConfigs	= TodoyuArray::assure($validatorConfigs, true);
				$result	= $this->runValidator($validatorName, $validatorConfigs, $formData);

				if( $result === false ) {
					return false;
				}
			}
		}

			// Check for required
		if( $this->isRequired() && ! $this->isRequiredNoCheck() ) {
			if( ! $this->validateRequired() ) {
				$this->setErrorTrue();
				//$this->bubbleError($this);

				if( ! empty($this->config['required']['@attributes']['label']) ) {
					$this->setErrorMessage($this->config['required']['@attributes']['label']);
				} else {
					$this->setErrorMessage('LLL:core.form.field.isrequired');
				}

				return false;
			}
		}

		return true;
	}



	/**
	 * Run the validator
	 *
	 * @param	String		$validatorName
	 * @param	Array		$validatorConfig
	 * @return	Boolean
	 */
	private final function runValidator($validatorName, array $validatorConfig) {
		$isValid = TodoyuFormValidator::validate($validatorName, $this->getStorageData(), $validatorConfig, $this, $this->getForm()->getFormData());

			// If validation failed, set error message
		if( $isValid === false ) {
			$this->setErrorTrue();

				// If error message not already set by function, check config or use default
			if( empty($this->errorMessage) ) {

				if( isset($validatorConfig['@attributes']['msg']) ) {
					$this->setErrorMessage(Todoyu::Label($validatorConfig['@attributes']['msg']));
				} else {
					$this->setErrorMessage('LLL:core.form.field.hasError');
				}
			}

			return false;
		}

		return true;
	}



	/**
	 * Set error message of form element
	 *
	 * @param	String	$message
	 */
	public function setErrorMessage($message) {
		$this->error		= true;
		$this->errorMessage = $message;
	}



	/**
	 * Get field error message (can be an empty if no error occured)
	 *
	 * @return	String
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}



	/**
	 * Set error status of form element true
	 */
	protected function setErrorTrue() {
		$this->error = true;
	}



	/**
	 * Bubble error
	 * Report a field error to its parent
	 *
	 * @param	TodoyuFormElement		$field
	 */
	public function bubbleError(TodoyuFormElement $field) {
//		TodoyuDebug::printInFirebug($field->getName(), 'FIELD=' . $this->getName());

		$this->setErrorTrue();
		$this->getFieldset()->bubbleError($field);
	}



	/**
	 * Check whether form element has error status ($this->error == true)
	 *
	 * @return	Boolean
	 */
	protected function hasErrorStatus() {
		return $this->error === true;
	}



	/**
	 * Check whether form element has validations assigned
	 *
	 * @return	Boolean
	 */
	public function hasValidations() {
		return sizeof($this->getValidations()) > 0;
	}



	/**
	 * Get field validations
	 *
	 * @return	Array
	 */
	public function getValidations() {
		return is_array($this->config['validate']) ? $this->config['validate'] : array();
	}



	/**
	 * Check if field is required
	 *
	 * @return	Boolean
	 */
	public function isRequired() {
		return $this->hasAttribute('required');
	}



	/**
	 * @return Check if field required has a noCheck option
	 */
	public function isRequiredNoCheck() {
		return isset($this->config['required']['noCheck']);
	}



	/**
	 * Validate required field value being given
	 *
	 * @return	Boolean
	 */
	public function validateRequired() {
		return $this->getValue() ? true : false;
	}



	/**
	 * Render form element
	 *
	 * @param	Boolean	$odd		Odd or even?
	 * @return	String
	 */
	public function render($odd = false) {
		$tmpl	= $this->getTemplate();
		$data	= $this->getData();

		$data['odd'] 			= $odd ? true : false;
		$data['error'] 			= $this->hasErrorStatus();
		$data['errorMessage'] 	= $this->getErrorMessage();

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Returns Form field wizard configurations
	 *
	 * @return	Array
	 */
	public function getWizardConfiguration() {
		$xmlConfig		= $this->getAttribute('wizard');
		$name			= $xmlConfig['@attributes']['name'];
		$wizardConfig	= TodoyuCreateWizardManager::getWizard($name);

		$wizardConfig['record']	= intval($this->getForm()->getRecordID());

		if( $wizardConfig['title'] ) {
			$wizardConfig['title'] = Todoyu::Label($wizardConfig['title']);
		}

		if( $wizardConfig['displayCondition'] ) {
			$showWizard	= TodoyuFunction::callUserFunctionArray($wizardConfig['displayCondition'], $this, $wizardConfig);

			if( $showWizard === false ) {
				return false;
			}
		}

		if( is_array($wizardConfig['restrict']) ) {
			$allowed	= false;

			foreach($wizardConfig['restrict'] as $restriction) {
				if( Todoyu::allowed($restriction[0], $restriction[1]) ) {
					$allowed = true;
					break;
				}
			}

			if( ! $allowed ) {
				return false;
			}
		}

		$jsParams	= $wizardConfig;

		unset($jsParams['restrict']);
		unset($jsParams['displayCondition']);
		unset($jsParams['htmlClass']);

		$wizardConfig['jsParams']	= $jsParams;

		return $wizardConfig;
	}



	/**
	 * Enable a form field
	 *
	 */
	public function enable() {
		unset($this->config['disabled']);
	}



	/**
	 * Disable a form field
	 *
	 */
	public function disable() {
		$this->config['disabled'] = true;
	}



	/**
	 * Check if a form field is disabled
	 *
	 * @return	Boolean
	 */
	public function isDisabled() {
		return !empty($this->config['disabled']);
	}



	/**
	 * Set the 'after field' text
	 *
	 * @param	String		$text
	 */
	public function setAfterFieldText($text) {
		$this->setAttribute('textAfterField', Todoyu::Label($text));
	}



	/**
	 * Get the 'after field' text
	 *
	 * @return	String
	 */
	public function getAfterFieldText() {
		return trim($this->getAttribute('textAfterField'));
	}



	/**
	 * Add text to the 'after field' text
	 *
	 * @param	String		$text
	 * @param	String		$glue
	 */
	public function addAfterFieldText($text, $glue = '<br />') {
		$current	= $this->getAfterFieldText();
		$text		= Todoyu::Label($text);

		if( $current === '' ) {
			$this->setAfterFieldText($text);
		} else {
			$this->setAfterFieldText($current . $glue . $text);
		}
	}



	/**
	 * Set the 'before field' text
	 *
	 * @param	String		$text
	 */
	public function setBeforeFieldText($text) {
		$this->setAttribute('textBeforeField', Todoyu::Label($text));
	}



	/**
	 * Get the 'before field' text
	 *
	 * @return	String
	 */
	public function getBeforeFieldText() {
		return trim($this->getAttribute('textBeforeField'));
	}



	/**
	 * Add text to the 'before field' text
	 *
	 * @param	String		$text
	 * @param	String		$glue
	 */
	public function addBeforeFieldText($text, $glue = '<br />') {
		$current	= $this->getBeforeFieldText();

		if( $current === '' ) {
			$this->setBeforeFieldText($text);
		} else {
			$this->setBeforeFieldText($current . $glue . $text);
		}
	}



	/**
	 * Get names of parent fieldsets
	 *
	 * @return	Array
	 */
	public function getParentFieldsetNames() {
		$parentFieldsets	= $this->getFieldset()->getParentFieldsetNames();
		$parentFieldsets[]	= $this->getFieldset()->getName();

		return $parentFieldsets;
	}

}

?>