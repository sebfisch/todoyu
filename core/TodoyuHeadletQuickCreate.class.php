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





		TodoyuDebug::printHtml($engines['all']);

//		$items	= array();
//
//		if( is_array($engines['primary']) ) {
//			$engines['primary']['class'];
//			$primary[]
//			$items[] = array(
//
//
//			);
//		}
//
//		TodoyuDebug::printHtml($engineItems);


		return array();
	}

}

?>