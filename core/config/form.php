<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * Core config for forms: locales, templates
 *
 * @package		Todoyu
 * @subpackage	Core
 */



	/**
	 * Add basic form type configuration
	 * - template
	 * - class
	 */

Todoyu::$CONFIG['FORM']['templates'] = array(
	'form'		=> 'core/view/form/Form.tmpl',
	'fieldset'	=> 'core/view/form/Fieldset.tmpl',
	'formelement'=>'core/view/form/FormElement.tmpl',
	'hidden'	=> 'core/view/form/HiddenField.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['text'] = array(
	'class'		=> 'TodoyuFormElement_Text',
	'template'	=> 'core/view/form/FormElement_Text.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['select'] = array(
	'class'		=> 'TodoyuFormElement_Select',
	'template'	=> 'core/view/form/FormElement_Select.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['radio'] = array(
	'class'		=> 'TodoyuFormElement_Radio',
	'template'	=> 'core/view/form/FormElement_Radio.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['selectgrouped'] = array(
	'class'		=> 'TodoyuFormElement_SelectGrouped',
	'template'	=> 'core/view/form/FormElement_SelectGrouped.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['textarea'] = array(
	'class'		=> 'TodoyuFormElement_Textarea',
	'template'	=> 'core/view/form/FormElement_Textarea.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['checkbox'] = array(
	'class'		=> 'TodoyuFormElement_Checkbox',
	'template'	=> 'core/view/form/FormElement_Checkbox.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['date'] = array(
	'class'		=> 'TodoyuFormElement_Date',
	'template'	=> 'core/view/form/FormElement_Date.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['datetime'] = array(
	'class'		=> 'TodoyuFormElement_DateTime',
	'template'	=> 'core/view/form/FormElement_Date.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['button'] = array(
	'class'		=> 'TodoyuFormElement_Button',
	'template'	=> 'core/view/form/FormElement_Button.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['saveButton'] = array(
	'class'		=> 'TodoyuFormElement_SaveButton',
	'template'	=> 'core/view/form/FormElement_Button.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['cancelButton'] = array(
	'class'		=> 'TodoyuFormElement_CancelButton',
	'template'	=> 'core/view/form/FormElement_Button.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['expandAllButton'] = array(
	'class'		=> 'TodoyuFormElement_ExpandAllButton',
	'template'	=> 'core/view/form/FormElement_Button.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['time'] = array(
	'class'		=> 'TodoyuFormElement_Time',
	'template'	=> 'core/view/form/FormElement_Time.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['duration'] = array(
	'class'		=> 'TodoyuFormElement_Duration',
	'template'	=> 'core/view/form/FormElement_Duration.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['RTE'] = array(
	'class'		=> 'TodoyuFormElement_RTE',
	'template'	=> 'core/view/form/FormElement_RTE.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['upload'] = array(
	'class'		=> 'TodoyuFormElement_Upload',
	'template'	=> 'core/view/form/FormElement_Upload.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['textAC'] = array(
	'class'		=> 'TodoyuFormElement_TextAC',
	'template'	=> 'core/view/form/FormElement_TextAC.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['databaseRelation'] = array(
	'class'		=> 'TodoyuFormElement_DatabaseRelation',
	'template'	=> 'core/view/form/FormElement_DatabaseRelation.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['comment'] = array(
	'class'		=> 'TodoyuFormElement_Comment',
	'template'	=> 'core/view/form/FormElement_Comment.tmpl'
);

Todoyu::$CONFIG['FORM']['TYPES']['selectIcon'] = array(
	'class'		=> 'TodoyuFormElement_SelectIcon',
	'template'	=> 'core/view/form/FormElement_SelectIcon.tmpl'
);

?>