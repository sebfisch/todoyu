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
Todoyu::$CONFIG['AUTOLOAD'] = array(PATH_CORE);


	// Todoyu session config
Todoyu::$CONFIG['SESSION']		= array(
	'key'	=> 'TODOYU'
);

	// Template (dwoo) path config
Todoyu::$CONFIG['TEMPLATE']		= array(
	'compile'	=> PATH_CACHE . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . 'compile',
	'cache'		=> PATH_CACHE . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . 'cache',
);

	// Bad tags which are encoded by the HtmlFilter
Todoyu::$CONFIG['SECURITY']['badHtmlTags'] = array('script', 'iframe', 'input', 'textarea', 'select', 'form');


	// Initialize metamenu registration array
Todoyu::$CONFIG['MetaMenu']	= array();

	// Set (not) allowed paths for TodoyuFileManager::sendFile()
Todoyu::$CONFIG['sendFile']['allow']	= array(PATH_FILES);
Todoyu::$CONFIG['sendFile']['disallow']	= array();



Todoyu::$CONFIG['AUTH']['loginCookieName']	= 'todoyulogin';

Todoyu::$CONFIG['EXT_REQUEST_HANDLER'] = array();

Todoyu::$CONFIG['CHMOD'] = array(
	'file'	=> 0775,
	'folder'=> 0775
);

	// Add IE scripts hook to page
TodoyuHookManager::registerHook('core', 'renderPage', 'TodoyuPageAssetManager::addInternetExplorerAssets');

	// Localization defaults
Todoyu::$CONFIG['SYSTEM']['language']	= 'en';
Todoyu::$CONFIG['SYSTEM']['locale']		= 'en_US';
	// Default timezone
Todoyu::$CONFIG['LOCALE']['defaultTimezone']= 'Europe/Zurich';

	// List size for paging
Todoyu::$CONFIG['LIST']['size']	= 30;


TodoyuHookManager::registerHook('core', 'onload', 'TodoyuRequest::setDefaultRequestVarsHook', 10);
TodoyuHookManager::registerHook('core', 'onload', 'TodoyuCookieLogin::tryCookieLogin', 20);
TodoyuHookManager::registerHook('core', 'onload', 'TodoyuAuth::checkLoginStatus', 1000);


Todoyu::$CONFIG['goodPassword'] = array(
	'minLength'		=> 8,
	'hasNumbers'	=> true,
	'hasLowerCase'	=> true,
	'hasUpperCase'	=> true
);

Todoyu::$CONFIG['CREATE'] = array(
	'engines'	=> array()
);

?>