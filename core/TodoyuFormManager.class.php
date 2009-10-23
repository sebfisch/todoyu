<?php


class TodoyuFormManager {

	public static function renderSubformRecord($xmlPath, $fieldName, $formName, $index = 0, $idRecord = 0, array $data = array()) {
		$index		= intval($index);
		$idRecord	= intval($idRecord);

			// Make form object
		$form 	= new TodoyuForm($xmlPath);
		$form	= TodoyuFormHook::callBuildForm($xmlPath, $form, $index);

			// Load (/preset) form data
		$data	= TodoyuFormHook::callLoadData($xmlPath, $data, $index);

			// Set form data
		$form->setFormData($data);
		$form->setRecordID($idRecord);

		$field			= $form->getField($fieldName);
		$form['name']	= $formName;

		return $field->renderNewRecord($idRecord);
	}


}

?>