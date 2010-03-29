<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * TodoyuFieldset object for form
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFieldset implements ArrayAccess {

	/**
	 * Name of the fieldset
	 *
	 * @var	String
	 */
	private $name;

	/**
	 * Parent element of the fieldset. Can be the form or an other fieldset.
	 *
	 * @var	TodoyuFieldset
	 */
	private $parent;

	/**
	 * Attributes of the fieldset (like legend, class, etc)
	 *
	 * @var	Array
	 */
	private $attributes;

	/**
	 * Elements of the fieldsets. Can be a mix of fieldsets and FormElements
	 *
	 * @var	Array
	 */
	private $elements = array();



	/**
	 * Initialize a new fieldset.
	 *
	 * @param	TodoyuFieldset	$parent		Reference to parent element (fieldset or the form)
	 * @param	String		$name		Name of the fieldset to be accessed over $form->FIELDSETNAME->method()
	 */
	public function __construct($parent, $name) {
		$this->parent	= $parent;
		$this->name		= $name;
	}



	/**
	 * Get the form instance
	 *
	 * @return	TodoyuForm
	 */
	public function getForm() {
		return $this->getParent()->getForm();
	}



	/**
	 * Get parent element (fieldset or form)
	 *
	 * @return	TodoyuFieldset
	 */
	public function getParent() {
		return $this->parent;
	}



	/**
	 * Set fieldset parent
	 *
	 * @param	Object		$parent
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}



	/**
	 * Get fieldset name
	 *
	 * @return	String
	 */
	public function getName() {
		return $this->name;
	}



	/**
	 * Get field from the form
	 *
	 * @param	String		$name
	 * @return	TodoyuFormElement
	 */
	public function getField($name) {
		return $this->elements[$name];
	}



	/**
	 * Get a fieldset by name
	 *
	 * @param	String		$name
	 * @return	TodoyuFieldset
	 */
	public function getFieldset($name) {
		return $this->elements[$name];
	}



	/**
	 * Access elements in the fieldset over $form->FIELDSETNAME->ELEMENTNAME
	 *
	 * @param	String			$name		Name of the sub element
	 * @return	TodoyuFormElement
	 */
	public function __get($name) {
		return $this->elements[$name];
	}



	/**
	 * Delete an element in the fieldset
	 *
	 * @param	String		$name
	 */
	public function __unset($name) {
		unset($this->elements[$name]);
	}



	/**
	 * Set a fieldset attribute
	 *
	 * @param	String		$name
	 * @param	Mixed		$value
	 */
	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}



	/**
	 * Get a fieldset attribute
	 *
	 * @param	String		$name
	 * @return	Mixed
	 */
	public function getAttribute($name) {
		return $this->attributes[$name];
	}



	/**
	 * Set fieldset legend
	 *
	 * @param	String		$legend
	 */
	public function setLegend($legend) {
		$this->setAttribute('legend', $legend);
	}



	/**
	 * Set fieldset class(es)
	 *
	 * @param	String		$class
	 */
	public function setClass($class) {
		$this->setAttribute('class', $class);
	}



	/**
	 * Add a new fieldset to the fieldset
	 * Creates a new fieldset and adds it to the child list
	 * and return a reference to the fieldset
	 *
	 * @param	String			$name
	 * @param	TodoyuFieldset	$fieldset
	 * @return	TodoyuFieldset
	 */
	public function addFieldset($name, TodoyuFieldset $fieldset = null, $position = null) {
		if( is_null($fieldset) ) {
			$fieldset = new TodoyuFieldset($this, $name);
		}

			// Set fieldset parent
		$fieldset->setParent($this);

			// If no position given, append element
		if( is_null($position) ) {
			$this->elements[$name] = $fieldset;
		} else {
				// If position available, insert element at given positon
			$pos = explode(':', $position);

			$this->elements = TodoyuArray::insertElement($this->elements, $name, $fieldset, $pos[0], $pos[1]);
		}

		$this->getForm()->registerFieldset($name, $fieldset);

		return $fieldset;
	}



	/**
	 * Add the $field to the fieldset
	 *
	 * @param	String		$name			Name of the field
	 * @param	String		$field			Field object
	 * @param	String		$position		Insert position. Format: after:title, before:status
	 * @return	TodoyuFormElement
	 */
	public function addField($name, TodoyuFormElement $field, $position = null) {
			// Set the new parent fieldset
		$field->setFieldset($this);
		$field->setName($name);

			// If no position given, append element
		if( is_null($position) ) {
			$this->elements[$name] = $field;
		} else {
				// If position available, insert element at given positon
			$pos = explode(':', $position);

			$this->elements = TodoyuArray::insertElement($this->elements, $name, $field, $pos[0], $pos[1]);
		}

		$this->getForm()->registerField($name, $field);

		return $this->elements[$name];
	}



	/**
	 * Add all elements of a form to this fieldset
	 *
	 * @param	 $xmlPath		Path to sub form XML file
	 */
	public function addElementsFromXML($xmlPath, $position = null) {
		$xmlPath	= TodoyuFileManager::pathAbsolute($xmlPath);
		$form		= TodoyuFormManager::getForm($xmlPath);

		$fieldsets	= $form->getFieldsets();

		foreach($fieldsets as $fieldset) {
			$this->injectFieldset($fieldset, $position);

			$position = 'after:' . $fieldset->getName();
		}
	}



	/**
	 * Add elements from an other XML into the fieldset after the element named $name
	 *
	 * @see		$this->addElementsFromXML()
	 * @param	String		$xmlPath		Path to the xml file
	 * @param	String		$name			Name of the field to insert the elements after
	 */
	public function addElementsFromXMLAfter($xmlPath, $name) {
		$this->addElementsFromXML($xmlPath, 'after:' . $name);
	}



	/**
	 * Add elements from an other XML into the fieldset before the element named $name
	 *
	 * @see		$this->addElementsFromXML()
	 * @param	String		$xmlPath		Path to the xml file
	 * @param	String		$name			Name of the field to insert the elements before
	 */
	public function addElementsFromXMLBefore($xmlPath, $name) {
		$this->addElementsFromXML($xmlPath, 'before:' . $name);
	}



	/**
	 * Inject an existing fieldset into the form
	 *
	 * @param	TodoyuFieldset	$fieldset
	 * @return	TodoyuFieldset
	 */
	public function injectFieldset(TodoyuFieldset $fieldset, $position = null) {
		$fieldset->setParent($this);
		$fieldset->setFieldsToForm($this->getForm());

		return $this->addFieldset($fieldset->getName(), $fieldset, $position);
	}



	/**
	 * Add a field from custom config
	 *
	 * @param	String		$name		Fieldname
	 * @param	String		$type		Fieldtype
	 * @param	Array		$config		Field configuration
	 */
	public function addFieldElement($name, $type, array $config) {
		$field	= TodoyuFormFactory::createField($type, $name, $this, $config);

		return $this->addField($name, $field);
	}



	/**
	 * Remove a field (and cleanup field references)
	 *
	 * @param	String		$name		Fieldname
	 * @param	Boolean		$cleanup	Perform cleanup
	 */
	public function removeField($name, $cleanup = true) {
		unset($this->elements[$name]);

		if( $cleanup ) {
			$this->getForm()->removeField($name, false);
		}
	}



	/**
	 * Remove fieldset with all its elements
	 *
	 */
	public function remove() {
		$fieldNames	= $this->getFieldNames();

		foreach($fieldNames as $fieldName) {
			$this->getField($fieldName)->remove();
		}

		$this->getForm()->removeFieldset($this->getName());
	}



	/**
	 * Get field names
	 *
	 * @return	Array
	 */
	public function getFieldNames() {
		$fieldnames	= array();

		foreach($this->elements as $element) {
			if( $element instanceof TodoyuFormElement ) {
					// element is form element
				$fieldnames[] = $element->getName();
			} elseif( $element instanceof TodoyuFieldset ) {
					// element is sub fieldset
				$fieldnames = array_merge($fieldnames, $element->getFieldNames());
			}
		}

		return $fieldnames;
	}



	/**
	 * Get data for template rendering
	 *
	 * @return	Array
	 */
	protected function getData() {
		$this->setAttribute('htmlId', $this->getForm()->makeID($this->name, 'fieldset'));
		$this->setAttribute('name', $this->name);

		return $this->attributes;
	}



	/**
	 * Render fieldset with all its childelements
	 *
	 * @return	String
	 */
	public function render() {
		$template	= Todoyu::$CONFIG['FORM']['templates']['fieldset'];
		$data		= $this->getData();

		$data['content'] =  $this->renderElements();;

		return render($template, $data);
	}



	/**
	 * Render fieldset elements (without wrapping fieldset)
	 *
	 * @return	String
	 */
	public function renderElements() {
		$content	= '';

		$odd = true;
		foreach($this->elements as $name => $element) {
			if( $element instanceof TodoyuFormElementInterface ) {
				if( ! $element->isHidden() ) {
					$content .= $element->render($odd) . "\n";
				}
			} elseif( $element instanceof TodoyuFieldset ) {
				$content .= $element->render($odd) . "\n";
			}

			$odd = ! $odd;
		}

		return $content;
	}



	/**
	 * ArrayAccess: Check if an attribute is set: isset($fieldset['legend'])
	 *
	 * @param	String		$name
	 * @return	Boolean
	 */
	public function offsetExists($name) {
		return isset($this->attributes[$name]);
	}



	/**
	 * ArrayAccess: Get an attribute from the fieldset: echo $fieldset['legend']
	 *
	 * @param	String		$name
	 * @return	String
	 */
	public function offsetGet($name) {
		return $this->getAttribute($name);
	}



	/**
	 * ArrayAccess: Set an attribute: $fieldset['legend'] = 'New Legend'
	 *
	 * @param	String		$name
	 * @param	String		$value
	 */
	public function offsetSet($name, $value) {
		$this->setAttribute($name, $value);
	}



	/**
	 * ArrayAccess: Delete attribute: unset($fieldset['legend'])
	 *
	 * @param	String		$name
	 */
	public function offsetUnset($name) {
		unset($this->attributes[$name]);
	}



	/**
	 * Returns all subfieldsets
	 *
	 * @return	Array
	 */
	public function getFieldsets()	{
		$fieldsets = array();

		foreach($this->elements as $element)	{
			if( $element instanceof Fieldset )	{
				$fieldsets[] = $element;
			}
		}

		return $fieldsets;
	}



	/**
	 * Adds fields of a fieldset recursivly to the form
	 *
	 * @param TodoyuForm $form
	 */
	public function setFieldsToForm(TodoyuForm $form)	{
		foreach($this->elements as $element)	{
			if( $element instanceof TodoyuFormElement )	{
				$form->registerField($element->getName(), $element);
			} else if( $element instanceof TodoyuFieldset )	{
				$element->setFieldsToForm($form);
			}
		}
	}



	/**
	 * Bubble error
	 * Report a field error to its parent
	 *
	 * @param	TodoyuFormElement		$field
	 */
	public function bubbleError(TodoyuFormElement $field) {
		TodoyuDebug::printInFirebug($field->getName(), 'FIELDSET=' . $this->getName());
		$this->getParent()->bubbleError($field);
	}
}

?>