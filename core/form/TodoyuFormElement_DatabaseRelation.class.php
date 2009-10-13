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
	 * Record config array
	 *
	 * @var	Array
	 */
	protected $recordConf = array();



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
	 * Check database form element values array being given
	 *
	 * @param	Integer	$index		numeric index of the databaseRelation field
	 * @return	Boolean
	 */
	public function getValueArr($index) {
		$values = $this->getAttribute('default');

		return is_array( $values[$index] ) ? $values[$index] : array();
	}



	/**
	 * Get record config
	 *
	 * @param	Integer	$index		numeric index of the databaseRelation field
	 * @return	Mixed
	 */
	public function getRecordConf($index)	{
		return $this->recordConf[$index];
	}



	/**
	 * Add record config attribute
	 *
	 * @param	Integer	$index		numeric index of the databaseRelation field
	 * @param	String	$name
	 * @param	Mixed	$value
	 */
	public function addRecordConfAttribute($index, $name, $value) {
		$this->recordConf[$index][$name]	= $value;
	}



	/**
	 * Returns data of databaseRelation field
	 *
	 * @return	Array
	 */
	public function getData() {
		$data = parent::getData();

		$data['foreignRecords']		= $this->loadForeignRecord( $data );
		$data['foreignRecordConf']	= $this->getAttribute('foreignRecordConf');
		$data['fieldname']			= $data['@attributes']['name'];
//		$data['formbasename']		= $this->getForm()->getAttribute('formbasename');
		$data['formname']			= $this->getForm()->getName();

		return $data;
	}



	/**
	 * Render the field, including registered rendering hooks
	 *
	 * @param	Boolean	$odd
	 * @return	String
	 */
	public function render($odd = false) {
		$tmpl			= $this->getTemplate();
		$data			= $this->getData();
		$data['odd']	= $odd;

		TodoyuDebug::printInFirebug($tmpl);

		$data['idRecord']	= $this->getForm()->getRecordID();

		$data['error'] = $this->error;
		$data['errorMessage'] = $this->errorMessage;

		$fieldHTML = render($tmpl, $data);

//		$foreignFormXML	= $data['foreignRecordConf']['foreignformxml'];
//		if ($foreignFormXML != '') {
//			$fieldHTML	= TodoyuFormHook::callDatabaseRelationFieldModifier($foreignFormXML, $fieldHTML, $data);
//		}

		return $fieldHTML;
	}



	/**
	 * Returns empty form of a new record
	 *
	 * @param	Array	$config
	 * @param	Integer	$index		numeric index of the databaseRelation field
	 * @return	String
	 */
	public function addNewRecord($index = 0)	{
		$data = $this->getData();

			// Set record offsets
		$this->handleRecord($data, $this->getAttribute('foreignRecordConf'), $index);

		$data['record']		= $this->getRecordConf($index);
		$data['key']		= $index;

		TodoyuDebug::printHtml($data, 'data');

		return render('core/view/form/FormElement_DatabaseRelation_Record.tmpl', $data);
	}



	/**
	 * Load foreign record from baserecord
	 *
	 * @param	Array	$data
	 * @return	Array
	 */
	protected function loadForeignRecord($data)	{
		$foreignRecords = array();

		$recordID = $this->getForm()->getRecordID();

		$foreignRecordConf = $this->getAttribute('foreignRecordConf');

		$records = $this->getValue();
		if(is_array($records))	{
			foreach($records as $index => $foreignRecord)	{
				$this->handleRecord($data, $foreignRecordConf, $index);
				$foreignRecords[$index] = $this->getRecordConf($index);
			}
		}

		return $foreignRecords;
	}



	/**
	 * Load form from foreign record.
	 *
	 * @param	Array	$config
	 * @param	Integer	$index		numeric index of the databaseRelation field
	 * @return	String
	 */
	protected function loadForeignRecordForm(array $config, $index)	{
		$xmlPath	= $config['foreignformxml'];

//		TodoyuDebug::printInFirebug($xmlPath, 'xmlPath');

		if ($xmlPath)	{
				// Construct form object
			$form	= new TodoyuForm( $xmlPath );

				// Load form data
			$formData	= $this->getValueArr($index);
			$editID		= $formData['id'];

			$form		= TodoyuFormHook::callBuildForm($xmlPath, $form, $index);
			$formData	= TodoyuFormHook::callLoadData($xmlPath, $formData, $editID);

//			$form->setAttribute('formbasename', $form->getName());

			$name	= $this->makeForeignRecordName($form, $index);
			$form['name'] = $name;

				// Set form data
			$form->setFormData($formData);
			$form->setRecordID($editID);
			$form->setAttribute('noFormTag', 1);

				// Evoke assigned validators
			if ( $this->getForm()->getValidateForm() )	{
				if( ! $form->isValid() )	{
					$this->setErrorMessage( Label('form.field.hasError') );
					$this->setErrorTrue();
				}
			}

				// Render
			return $form->render();

		} else {
			return 'ERROR: no form-XML defined';
		}
	}



	/**
	 * Get label of user function
	 *
	 * @param	Array	$conf		configuration array
	 * @param	Integer	$index		numeric index of the databaseRelation field
	 * @return	String
	 */
	protected function getForeignRecordLabel($conf, $index)	{
		if($conf['foreignLabelUserFunc'])	{
			$label = $this->callLabelUserFunc($conf['foreignLabelUserFunc'], $index);
		} else if($conf['foreignLabelField']) {
			$values = $this->getValueArr($index);
			$label	= $values[$conf['foreignLabelField']];
		}else {
			$label =  '[no label field defined]';
		}

		return strlen( trim($label) ) > 0 ? $label : TodoyuLocale::getLabel('form.databaserelation.nolabel');
	}



	/**
	 * If there is a userfunction configured for the label call it
	 *
	 * @param	String	$userFunc
	 * @param	Integer	$index		numeric index of the databaseRelation field
	 * @return	String
	 */
	protected function callLabelUserFunc($userFunc, $index)	{
		if( TodoyuDiv::isFunctionReference($userFunc) ) {
			return TodoyuDiv::callUserFunction($userFunc, $this, $this->getValueArr($index));
		} else {
			return false;
		}
	}



	/**
	 * Creates form name of foreign record
	 *
	 * @param	TodoyuForm	$form
	 * @param	Integer	$index		numeric index of the databaseRelation field
	 * @return	String
	 */
	protected function makeForeignRecordName(TodoyuForm $form, $index)	 {
		$localName = $this->getForm()->getName();

		return $localName . '[' . $form->getName() . '][' . $index . ']';
	}



	/**
	 * Set record offsets
	 *
	 * @param	Array	$data
	 * @param	Array	$foreignRecordConf
	 * @param	Integer	$index		numeric index of the databaseRelation field
	*/
	protected function handleRecord($data, $foreignRecordConf, $index)	{
		$this->addRecordConfAttribute($index, 'index', $data['default'][0]['id']);
		$this->addRecordConfAttribute($index, '_label', $this->getForeignRecordLabel($foreignRecordConf, $index));
		$this->addRecordConfAttribute($index, 'type', $data['nodeAttributes']['name']);
		$this->addRecordConfAttribute($index, 'formHTML', $this->loadForeignRecordForm($foreignRecordConf, $index));
	}
}

?>