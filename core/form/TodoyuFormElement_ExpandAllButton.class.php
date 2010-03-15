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
 * FormElement: Expand all
 *
 * Button which expands all configured external records
 *
 * @package		Todoyu
 * @subpackage	Form
 */
class TodoyuFormElement_ExpandAllButton extends TodoyuFormElement_Button {

	/**
	 * Create button element
	 *
	 * @param	String		$name		Button name
	 * @param	TodoyuFieldset	$fieldset	Parent fieldset
	 * @param	Array		$config		Button config
	 */
	public function __construct($name, TodoyuFieldset $fieldset, array $config = array()) {
		TodoyuFormElement::__construct('expandAllButton', $name, $fieldset, $config);
	}


	/**
	 * Init: Set default values for expandAll button
	 *
	 */
	protected function init() {
		$this->setType('button');

		if( ! $this->hasAttribute('text') ) {
			$this->setAttribute('text', 'form.databaserelation.showAllRecords');
		}
		if( ! $this->hasAttribute('class') ) {
			$this->setAttribute('class', 'expandAll');
		}

		$fields	= TodoyuArray::trimExplode(',', $this->getAttribute('fields'), true);
		$list	= TodoyuArray::implodeQuoted($fields, ',');

		$this->setAttribute('onclick', 'Todoyu.Form.expandForeignRecords([' . $list . '])');

		parent::init();
	}

}

?>