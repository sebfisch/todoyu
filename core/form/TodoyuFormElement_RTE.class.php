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
 * FormElement: RichTextEditor (tinyMCE)
 *
 * Richtext editor
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_RTE extends TodoyuFormElement_Textarea {

	/**
	 * Initialize RTE form element
	 *
	 * @param	String		$name
	 * @param	TodoyuFieldset	$fieldset
	 * @param	Array		$config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		TodoyuFormElement::__construct('RTE', $name, $fieldset, $config);
	}



	/**
	 * Build RTE initialisation javaScript code to convert the textarea into a RTE
	 * when displayed on the page
	 *
	 * @return	String
	 */
	private function buildRTEjs() {
		$options	= array(
			'mode'				=> 'exact',
			'elements'			=> $this->getHtmlID(),
			'theme'				=> 'simple',
			'content_css'		=> 'core/assets/css/tinymce.css',
			'valid_elements'	=> 'strong,em,p,br,u,strike,ol,ul,li,a,pre'
		);

			// Load config
		if( is_array($this->config['tinymce']) ) {
			foreach($this->config['tinymce'] as $name => $value) {
				$options[$name] = $value;
			}
		}

			// Generate config code
		$jsCode	= "tinyMCE.init({\n";
		$tmpOpt	= array();

		foreach($options as $name => $value) {
			$tmpOpt[] = $name . ' : "' . $value . '"';
		}

		$jsCode .= implode(",\n", $tmpOpt) . "\n});\n";
		$jsCode .= 'Todoyu.Ui.initRTEfocus(\'' . $options['elements'] . '\');';

		return $jsCode;
	}



	/**
	 * Get rich text editor (tinyMCE) config
	 *
	 * @param	String	$name
	 * @return	Array
	 */
	private function getRTEconfig($name) {
		$defaults	= array('theme'	=> 'simple');
		$config		= false;

		if( isset($this->config['tinymce']['theme']) ) {
			$config = $this->config['tinymce']['theme'];
		} elseif( array_key_exists($name, $defaults) ) {
			$config = $defaults[$name];
		}

		return $config;
	}



	/**
	 * Get field data
	 *
	 * @return	Array
	 */
	public function getData() {
		$data	= parent::getData();

		$data['rteJs'] = $this->buildRTEjs();

		return $data;
	}


	
	/**
	 * Set RTE text. Removed <pre> tags (copy from email programs) and adds <br> tags for the newlines in <pre>
	 *
	 * @param	String		$value
	 * @param	Boolean		$updateForm
	 */
	public function setValue($value, $updateForm = true) {
		$value	= TodoyuString::cleanRTEText($value);

		parent::setValue($value, $updateForm);
	}



	/**
	 * Check if field is valid for required flag
	 *
	 * @return	Boolean
	 */
	public function validateRequired() {
		return TodoyuValidator::isNotEmpty($this->getValue());
	}

}

?>