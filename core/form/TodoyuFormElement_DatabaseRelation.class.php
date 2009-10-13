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
		$records	= TodoyuDiv::assureArray($value);

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

		if( ! is_array($this->config['default'][$index]) ) {
			$this->config['default'][$index] = array();
		}

		return $this->config['default'][$index];
	}



	/**
	 * Get field data for rendering
	 *
	 * @return Array
	 */
	public function getData() {
		$data = parent::getData();

		$data['records']	= $this->getRecordsTemplateData();
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

//		$tmpl			= $this->getTemplate();
//		$data			= $this->getData();
//		$data['odd']	= $odd;
//
//		$data['error'] = $this->error;
//		$data['errorMessage'] = $this->errorMessage;
//
//		$fieldHTML = render($tmpl, $data);
//
////		$foreignFormXML	= $data['foreignRecordConf']['foreignformxml'];
////		if ($foreignFormXML != '') {
////			$fieldHTML	= TodoyuFormHook::callDatabaseRelationFieldModifier($foreignFormXML, $fieldHTML, $data);
////		}
//
//		return $fieldHTML;
	}



	/**
	 * Render new record without data
	 *
	 * @param	Integer		$index
	 * @return	String
	 */
	public function addNewRecord($index = 0)	{
		$tmpl	= 'core/view/form/FormElement_DatabaseRelation_Record.tmpl';
		$data	= array();

		$data['record']		= $this->getRecordTemplateData($index);
		$data['key']		= $this->getName();
		$data['idRecord']	= $this->getForm()->getRecordID();

		return render($tmpl, $data);
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
		return TodoyuDiv::assureArray($this->getValue());
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
	 * Render foreign record form
	 *
	 * @param	Index		$index
	 * @return	String
	 */
	protected function renderRecordForm($index)	{
		$xmlPath = $this->getRecordsFormXml();

			// Construct form object
		$recordForm	= new TodoyuForm($xmlPath);

			// Load form data
		$recordData	= $this->getRecord($index);
		$idRecord	= intval($recordData['id']);

		$recordForm	= TodoyuFormHook::callBuildForm($xmlPath, $recordForm, $idRecord);
		$recordData	= TodoyuFormHook::callLoadData($xmlPath, $recordData, $idRecord);

		$formName	= $this->getForm()->getName() . '[' . $recordForm->getName() . '][' . $index . ']';

			// Set form data
		$recordForm->setFormData($recordData);
		$recordForm->setRecordID($idRecord);
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
	 * Get record label defined by config
	 *
	 * @param	Index		$index
	 * @return	String
	 */
	protected function getRecordLabel($index)	{
		$conf	= $this->getRecordsConfig();
		$record	= $this->getRecord($index);
		$label	= '';

		if( TodoyuDiv::isFunctionReference($conf['foreignLabelUserFunc']) )	{
			$label = TodoyuDiv::callUserFunction($conf['foreignLabelUserFunc'], $this, $record);
		} elseif( ! empty($conf['foreignLabelField']) ) {
			if( ! empty($record[$conf['foreignLabelField']]) ) {
				$label	= $record[$conf['foreignLabelField']];
			}
		} else {
			$label =  '[no label field defined]';
		}

		if( $label === '' ) {
			$label = TodoyuLocale::getLabel('form.databaserelation.nolabel');
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
		$record		= $this->getRecord($index);

		$record['index'] 	= $index;
		$record['_label'] 	= $this->getRecordLabel($index);
		$record['type'] 	= $this->getName();
		$record['formHTML']	= $this->renderRecordForm($index);

		return $record;
	}

}

?>