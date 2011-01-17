<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * Abstract headlet menu class
 *
 * @package		Todoyu
 * @subpackage	Core
 * @abstract
 */
abstract class TodoyuHeadletTypeMenu extends TodoyuHeadlet {

	protected $type = 'menu';

	protected function initType() {
		$this->addButtonClass('headletTypeMenu');
	}

	abstract protected function getMenuItems();


	private function renderMenuItems() {
		$items	= $this->getMenuItems();

		$tmpl	= 'core/view/headlet-menu.tmpl';
		$data	= array(
			'id'	=> $this->getID(),// 'xxx',
			'items'	=> $items
		);

		return render($tmpl, $data);
	}


	public function render() {
		$this->data['content'] = $this->renderMenuItems();

		return parent::render();
	}
}

?>