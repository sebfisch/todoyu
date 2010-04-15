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

	// Disable notices
error_reporting(E_ALL ^ E_NOTICE);

	// Add core path to autoloader include paths
Todoyu::$CONFIG['AUTOLOAD'] = array(PATH_CORE, PATH_CORE . DIR_SEP . 'controller');


	// Todoyu session config
Todoyu::$CONFIG['SESSION']		= array(
	'key'	=> 'TODOYU'
);

	// Template (dwoo) path config
Todoyu::$CONFIG['TEMPLATE']		= array(
	'compile'	=> PATH_CACHE . DIR_SEP . 'tmpl' . DIR_SEP . 'compile',
	'cache'		=> PATH_CACHE . DIR_SEP . 'tmpl' . DIR_SEP . 'cache',
);

	// Bad tags which are encoded by the HtmlFilter
Todoyu::$CONFIG['SECURITY']['badHtmlTags'] = array('script', 'iframe', 'input', 'textarea', 'select', 'form');


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
Todoyu::$CONFIG['SYSTEM']['timezone']	= 'Europe/Zurich';

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

	// Flags to prevent normal initialisation
Todoyu::$CONFIG['WITHOUT_EXTENSIONS']	= false;
Todoyu::$CONFIG['NO_INIT']				= false;


?>