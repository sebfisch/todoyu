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
 * Form manager
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuFormManager {

	/**
	 * Create a form object, set record ID and call buildForm hook
	 *
	 * @param	String		$xmlPath
	 * @param	Integer		$idRecord
	 * @return	TodoyuForm
	 */
	public static function getForm($xmlPath, $idRecord = 0) {
		$form	= new TodoyuForm($xmlPath);

		$form->setRecordID($idRecord);

		$form	= TodoyuFormHook::callBuildForm($xmlPath, $form, $idRecord);

		return $form;
	}


	/**
	 * Render a subrecord of a database relation field
	 *
	 * @param	String		$xmlPath
	 * @param	String		$fieldName
	 * @param	String		$formName
	 * @param	Integer		$index
	 * @param	Integer		$idRecord
	 * @param	Array		$data
	 * @return	String
	 */
	public static function renderSubformRecord($xmlPath, $fieldName, $formName, $index = 0, $idRecord = 0, array $data = array()) {
		$index		= intval($index);
		$idRecord	= intval($idRecord);

			// Make form object
		$form 	= self::getForm($xmlPath, $index);

			// Load (/preset) form data
		$data	= TodoyuFormHook::callLoadData($xmlPath, $data, $index);

			// Set form data
		$form->setFormData($data);
		$form->setRecordID($idRecord);

		$field			= $form->getField($fieldName);
		$form['name']	= $formName;

		return $field->renderNewRecord($index);
	}

}

?>