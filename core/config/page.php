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
 * Core config for page rendering
 *
 * @package		Todoyu
 * @subpackage	Core
 */

	// Add "TODOYU" tab
if( TodoyuAuth::isLoggedIn() ) {
	TodoyuFrontend::addMenuEntry('todoyu', 'LLL:core.tab.todoyu.label', 'javascript:void(0)', 300);

	TodoyuPage::addJsOnloadedFunction('Todoyu.QuickInfo.init.bind(Todoyu.QuickInfo)', 10);

		// Register ajax loader headlet which indicated acitve ajax requests
	TodoyuHeadManager::addHeadlet('TodoyuHeadletAjaxLoader', 0);
	TodoyuHeadManager::addHeadlet('TodoyuHeadletQuickCreate', 50);


		// Register meta menu headlet
//	TodoyuHeadManager::addHeadlet('TodoyuHeadletMetaMenu', 80);

		// Generate colors css and sprite
		// Moved to calendar ext controller
	//TodoyuColors::generate();
}


?>