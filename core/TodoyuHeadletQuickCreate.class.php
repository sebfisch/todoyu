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

class TodoyuHeadletQuickCreate extends TodoyuHeadlet {

	/**
	 * Initialize quick create headlet (set template, set initial data)
	 */
	protected function init() {
		$this->setTemplate('core/view/headlet-quickcreate.tmpl');

		$this->setData(array(
			'query'			=> $this->params['query'],
			'createModes'	=> TodoyuSearchManager::getSearchEngines()
		));
	}



	/**
	 * Render quick search headlet, have resp. JS being added
	 *
	 * @return	String
	 */
	public function render() {
		TodoyuPage::addJsOnloadedFunction('Todoyu.Ext.search.Headlet.QuickSearch.init.bind(Todoyu.Ext.search.Headlet.QuickSearch)');

		return parent::render();
	}

}

?>