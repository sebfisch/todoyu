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
 * Core config for page rendering
 *
 * @package		Todoyu
 * @subpackage	Core
 */

if( TodoyuAuth::isLoggedIn() ) {
	TodoyuPage::addJsOnloadedFunction('Todoyu.QuickInfo.init.bind(Todoyu.QuickInfo)', 10);

		// Register AJAX loader headlet which indicated acitve ajax requests
	TodoyuHeadManager::addHeadlet('TodoyuHeadletAjaxLoader', 0);
	TodoyuHeadManager::addHeadlet('TodoyuHeadletAbout', 10);
	TodoyuHeadManager::addHeadlet('TodoyuHeadletQuickCreate', 50);

	TodoyuPage::addJsOnloadedFunction('Todoyu.DateField.init.bind(Todoyu.DateField, \'' . TodoyuLanguage::getLabel('dateformat.datetime')	. '\')');

		// Generate colors css and sprite
		// Moved to calendar ext controller
	//TodoyuColors::generate();
}

?>