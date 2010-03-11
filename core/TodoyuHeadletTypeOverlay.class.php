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
 * Abstract headlet overlay class
 *
 * @package		Todoyu
 * @subpackage	Core
 * @abstract
 */
abstract class TodoyuHeadletTypeOverlay extends TodoyuHeadlet {

	protected $type = 'overlay';

	protected function initType() {
		//$this->setTemplate('core/view/headlet-type-overlay.tmpl');

		$this->addButtonClass('headletTypeOverlay');
	}

	abstract protected function renderOverlayContent();

	public function render() {
		$this->data['content'] = $this->renderOverlayContent();

		return parent::render();
	}

}

?>