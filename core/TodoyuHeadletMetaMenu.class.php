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
 * Meta menu headlet
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuHeadletMetaMenu extends TodoyuHeadlet {

	/**
	 * Headlet meta menu entries property
	 *
	 * @var	Array
	 */
	private $entries = array();



	/**
	 * Initialize headlet meta menu
	 *
	 */
	protected function init() {
		$this->setTemplate('core/view/headlet-metamenu.tmpl');

		$this->entries	= $this->getMenuEntries();

		$this->setData(array(
			'entries'	=> $this->entries
		));
	}



	/**
	 * Get headlet meta menu entries
	 *
	 * @return	Array
	 */
	private function getMenuEntries() {
		return TodoyuMetaMenuManager::getEntries();
	}



	/**
	 * Check headlet meta menu having entries ( amount > 0)
	 *
	 * @return	Boolean
	 */
	private function hasEntries() {
		return sizeof($this->entries) > 0;
	}



	/**
	 * Render headlet meta menu
	 *
	 * @return	String
	 */
	public function render() {
		if( $this->hasEntries() ) {
			return parent::render();
		} else {
			return '';
		}
	}

}

?>