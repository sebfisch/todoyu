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

	// Disable notices
error_reporting(E_ALL ^ E_NOTICE);

	// Add core path to autoloader include paths
$CONFIG['AUTOLOAD'] = array(PATH_CORE);


	// Todoyu session config
$CONFIG['SESSION']		= array(
	'key'	=> 'TODOYU'
);

	// Template (dwoo) path config
$CONFIG['TEMPLATE']		= array(
	'compile'	=> PATH_CACHE . '/tmpl/compile',
	'cache'		=> PATH_CACHE . '/tmpl/cache'
);

	// Bad tags which are encoded by the HtmlFilter
$CONFIG['SECURITY']['badHtmlTags'] = array('script', 'iframe', 'input', 'textarea', 'select', 'form');

	// Initialize headlet registration array
$CONFIG['HEADLETS']	= array();

	// Initialize metamenu registration array
$CONFIG['MetaMenu']	= array();

	// Set (not) allowed paths for TodoyuDiv::sendFile()
$CONFIG['sendFile']['allow']	= array(PATH_FILES);
$CONFIG['sendFile']['disallow']	= array();

	// Register ajax loader headlet which indicated acitve ajax requests
TodoyuHeadletManager::registerRight('TodoyuHeadletAjaxLoader', 150);
	// Register meta menu headlet
TodoyuHeadletManager::registerRight('TodoyuHeadletMetaMenu', 80);

$CONFIG['AUTH']['loginCookieName']	= 'todoyulogin';

$CONFIG['SYSTEM']['language']	= 'en';

$CONFIG['EXT_REQUEST_HANDLER'] = array();

TodoyuHookManager::registerHook('core', 'onload', 'TodoyuRequest::setDefaultRequestVarsHook', 10);
TodoyuHookManager::registerHook('core', 'onload', 'TodoyuCookieLogin::tryCookieLogin', 20);
TodoyuHookManager::registerHook('core', 'onload', 'TodoyuAuth::checkLoginStatus', 1000);

?>