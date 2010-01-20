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
 * Form element for database relations (1:n n:n)
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_DatabaseRelation extends TodoyuFormElement {


	/**
	 * Constructor of the class
	 *
	 * @param	String		$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config  = array())	{
		parent::__construct('databaseRelation', $name, $fieldset, $config);
	}



	/**
	 * Set value
	 *
	 * @param	Array		$value
	 */
	public function setValue($value) {
		$records	= TodoyuArray::assure($value);

		parent::setValue($records);
	}



	/**
	 * Get record data
	 *
	 * @param	Integer		$index
	 * @return	Array
	 */
	public function getRecord($index) {
		$index	= intval($index);

		if( ! is_array($this->config['value'][$index]) ) {
			$this->config['value'][$index] = array();
		}

		return $this->config['value'][$index];
	}



	/**
	 * Get field data for rendering
	 *
	 * @return Array
	 */
	public function getData() {
		$data = parent::getData();

			// Records template data
		$data['records']	= $this->getRecordsTemplateData();

			// Records general information
		$data['fieldname']	= $this->getName();
		$data['formname']	= $this->getForm()->getName();
		$data['idRecord']	= $this->getForm()->getRecordID();

		return $data;
	}



	/**
	 * Render the field, including registered rendering hooks
	 *
	 * @param	Boolean	$odd
	 * @return	String
	 */
	public function render($odd = false) {

		return parent::render($odd);
	}



	/**
	 * Render new record without data
	 *
	 * @param	Integer		$index
	 * @return	String
	 */
	public function renderNewRecord($index = 0)	{
		$tmpl	= 'core/view/form/FormElement_DatabaseRelation_Record.tmpl';
		$data	= array();

			// Get record data
		$data['record']		= $this->getRecordTemplateData($index);

			// Records general information
		$data['fieldname']	= $this->getName();
		$data['idRecord']	= $this->getForm()->getRecordID();

		return render($tmpl, $data);
	}



	/**
	 * Render foreign record form
	 *
	 * @param	Index		$index
	 * @return	String
	 */
	protected function renderRecordForm($index)	{
		$xmlPath = $this->getRecordsFormXml();



			// Load form data
		$recordData	= $this->getRecord($index);
		$idRecord	= intval($recordData['id']);

			// Construct form object
		$recordForm	= TodoyuFormManager::getForm($xmlPath, $idRecord);

		$recordData	= TodoyuFormHook::callLoadData($xmlPath, $recordData, $idRecord);

		$formName	= $this->getForm()->getName() . '[' . $this->getName() . '][' . $index . ']';

			// Set form data
		$recordForm->setFormData($recordData);
		$recordForm->setVars(array('parent' => $this->getForm()->getRecordID()));
		$recordForm->setUseRecordID(false);
		$recordForm->setAttribute('noFormTag', true);
		$recordForm->setName($formName);

			// Evoke assigned validators
		if( $this->getForm()->getValidateForm() )	{
			$recordForm->isValid();
//			if( ! $form->isValid() )	{
//				$this->setErrorMessage( Label('form.field.hasError') );
//				$this->setErrorTrue();
//			}
		}

			// Render
		return $recordForm->render();
	}



	/**
	 * Get configuration array for foreign records
	 *
	 * @return	Array
	 */
	protected function getRecordsConfig() {
		return $this->getAttribute('foreignRecordConf');
	}



	/**
	 * Load foreign record from baserecord
	 *
	 * @param	Array	$data
	 * @return	Array
	 */
	protected function getRecords() {
		return TodoyuArray::assure($this->getValue());
	}



	/**
	 * Get template data for all records
	 *
	 * @return	Array
	 */
	protected function getRecordsTemplateData() {
		$records	= $this->getRecords();

		foreach($records as $index => $record) {
			$records[$index] = $this->getRecordTemplateData($index);
		}

		return $records;
	}



	/**
	 * Get path to record form xml
	 *
	 * @return	String
	 */
	protected function getRecordsFormXml() {
		$recordConfig = $this->getRecordsConfig();

		return $recordConfig['foreignformxml'];
	}



	/**
	 * Get record label defined by config
	 *
	 * @param	Index		$index
	 * @return	String
	 */
	protected function getRecordLabel($index) {
		$config	= $this->getRecordsConfig();
		$record	= $this->getRecord($index);
		$label	= '';
		$type	= $config['label']['@attributes']['type'];

			// Get label by type
		switch( $type ) {
			case 'function':
				$function	= $config['label']['function'];

				if( TodoyuDiv::isFunctionReference($function) ) {
					$label = TodoyuDiv::callUserFunction($function, $this, $record);
				}
				break;

			case 'field':
				$field	= $config['label']['field'];
				$label	= trim($record[$field]);
				break;
		}

			// If no label found, check if there is a noLabel tag
		if( empty($label) && ! empty($config['label']['noLabel']) ) {
			$label	= TodoyuDiv::getLabel($config['label']['noLabel']);
		}

			// If still no label found, get default "no label" tag
		if( empty($label) ) {
			$label = TodoyuLanguage::getLabel('form.databaserelation.nolabel');
		}

		return $label;
	}



	/**
	 * Get record with template data
	 *
	 * @param	Integer		$index
	 * @return	Array
	 */
	protected function getRecordTemplateData($index) {
		$record	= $this->getRecord($index);

		$record['_index'] 	= $index;
		$record['_label'] 	= $this->getRecordLabel($index);
		$record['_formHTML']= $this->renderRecordForm($index);

		return $record;
	}



	/**
	 * Validate required option
	 * If the field has no validators, but is required, we have to perfom an "required" check
	 * Because a databaseRelation can contain any kind of data, a custom validator function is required.
	 * The function has to be referenced in foreignRecordConf->validateRequired in the xml
	 *
	 * @return	Bool
	 */
	public function validateRequired() {
		$customValidator	= $this->config['foreignRecordConf']['validateRequired'];

		if( TodoyuDiv::isFunctionReference($customValidator) ) {
			$records	= $this->getRecords();
			$valid		= true;

			foreach($records as $record) {
				if( ! TodoyuDiv::callUserFunction($customValidator, $this, $record) ) {
					$valid = false;
					break;
				}
			}
		} else {
			$valid = sizeof($this->getRecords()) > 0;
		}

		return $valid;
	}

}

?>