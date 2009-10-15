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

TodoyuLocale::register('form', PATH_CORE . '/locale/form.xml');



$CONFIG['FORM']['templates'] = array(
	'form'		=> 'core/view/form/Form.tmpl',
	'fieldset'	=> 'core/view/form/Fieldset.tmpl',
	'formelement'=>'core/view/form/FormElement.tmpl',
	'hidden'	=> 'core/view/form/HiddenField.tmpl'
);

$CONFIG['FORM']['TYPES']['textinput'] = array(
	'class'		=> 'TodoyuFormElement_Textinput',
	'template'	=> 'core/view/form/FormElement_Textinput.tmpl'
);

$CONFIG['FORM']['TYPES']['select'] = array(
	'class'		=> 'TodoyuFormElement_Select',
	'template'	=> 'core/view/form/FormElement_Select.tmpl'
);

$CONFIG['FORM']['TYPES']['radio'] = array(
	'class'		=> 'TodoyuFormElement_Radio',
	'template'	=> 'core/view/form/FormElement_Radio.tmpl'
);

$CONFIG['FORM']['TYPES']['selectgrouped'] = array(
	'class'		=> 'TodoyuFormElement_SelectGrouped',
	'template'	=> 'core/view/form/FormElement_SelectGrouped.tmpl'
);

$CONFIG['FORM']['TYPES']['textarea'] = array(
	'class'		=> 'TodoyuFormElement_Textarea',
	'template'	=> 'core/view/form/FormElement_Textarea.tmpl'
);

$CONFIG['FORM']['TYPES']['checkbox'] = array(
	'class'		=> 'TodoyuFormElement_Checkbox',
	'template'	=> 'core/view/form/FormElement_Checkbox.tmpl'
);

$CONFIG['FORM']['TYPES']['dateinput'] = array(
	'class'		=> 'TodoyuFormElement_Dateinput',
	'template'	=> 'core/view/form/FormElement_Dateinput.tmpl'
);

$CONFIG['FORM']['TYPES']['datetimeinput'] = array(
	'class'		=> 'TodoyuFormElement_DateTimeInput',
	'template'	=> 'core/view/form/FormElement_Dateinput.tmpl'
);

$CONFIG['FORM']['TYPES']['button'] = array(
	'class'		=> 'TodoyuFormElement_Button',
	'template'	=> 'core/view/form/FormElement_Button.tmpl'
);

$CONFIG['FORM']['TYPES']['timeinput'] = array(
	'class'		=> 'TodoyuFormElement_Timeinput',
	'template'	=> 'core/view/form/FormElement_Timeinput.tmpl'
);

$CONFIG['FORM']['TYPES']['duration'] = array(
	'class'		=> 'TodoyuFormElement_Duration',
	'template'	=> 'core/view/form/FormElement_Duration.tmpl'
);

$CONFIG['FORM']['TYPES']['RTE'] = array(
	'class'		=> 'TodoyuFormElement_RTE',
	'template'	=> 'core/view/form/FormElement_RTE.tmpl'
);

$CONFIG['FORM']['TYPES']['upload'] = array(
	'class'		=> 'TodoyuFormElement_Upload',
	'template'	=> 'core/view/form/FormElement_Upload.tmpl'
);

$CONFIG['FORM']['TYPES']['textinputAC'] = array(
	'class'		=> 'TodoyuFormElement_TextinputAC',
	'template'	=> 'core/view/form/FormElement_TextinputAC.tmpl'
);

$CONFIG['FORM']['TYPES']['databaseRelation'] = array(
	'class'		=> 'TodoyuFormElement_DatabaseRelation',
	'template'	=> 'core/view/form/FormElement_DatabaseRelation.tmpl'
);

$CONFIG['FORM']['TYPES']['comment'] = array(
	'class'		=> 'TodoyuFormElement_Comment',
	'template'	=> 'core/view/form/FormElement_Comment.tmpl'
);

?>