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
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

class TodoyuHeadletQuickCreate extends TodoyuHeadletTypeMenu {

	/**
	 * Initialize quick create headlet (set template, set initial data)
	 */
	protected function init() {
		$this->setJsHeadlet('Todoyu.Headlet.QuickCreate');

		TodoyuPage::addJsOnloadedFunction('Todoyu.Headlet.QuickCreate.init.bind(Todoyu.Headlet.QuickCreate)', 100);
	}



	/**
	 * Get menu items for headlet based on registered enginges
	 *
	 * @return	Array
	 */
	protected function getMenuItems() {
		$engines= TodoyuQuickCreateManager::getEngines();

			// If there's only one engine: remove primary (no need of primary entry when there's only a single one)
		if ( sizeof($engines['all']) === 1 ) {
			unset($engines['primary']);
		}

		$items	= array();
		if( is_array($engines['primary']) ) {
			array_unshift($engines['all'], $engines['primary']);
		}

		foreach($engines['all'] as $engine) {
			$item	= array(
				'id'	=> $engine['ext'] . '-' . $engine['type'],
				'class'	=> 'item' . ucfirst($engine['ext']) . ucfirst($engine['type']),
				'label'	=> $engine['label']
			);

			if( $engine['isPrimary'] ) {
				$item['class'] .= ' primary';
			}

			$items[] = $item;
		}

		return $items;
	}



	/**
	 * Get headlet label
	 *
	 * @return	String
	 */
	public function getLabel() {
		return Label('core.quickcreate.title');
	}



	/**
	 * Check if no items are available in the create menu
	 *
	 * @return	Bool
	 */
	public function isEmpty() {
		return sizeof($this->getMenuItems()) === 0;
	}

}

?>