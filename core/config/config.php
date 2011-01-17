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

	// Disable notices
error_reporting(E_ALL ^ E_NOTICE);

Todoyu::$CONFIG['DEBUG'] = false;

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

	// Add URL substitution to text auto-linkage
TodoyuHookManager::registerHook('core', 'substituteLinkableElements', 'TodoyuString::replaceUrlWithLink');

	// Add IE scripts hook to page
TodoyuHookManager::registerHook('core', 'renderPage', 'TodoyuPageAssetManager::addInternetExplorerAssets');

	// Localization defaults
Todoyu::$CONFIG['SYSTEM']['locale']		= 'en_GB';
Todoyu::$CONFIG['SYSTEM']['timezone']	= 'Europe/Zurich';

	// List size for paging
Todoyu::$CONFIG['LIST']['size']	= 30;


	// Add core onLoad hooks
TodoyuHookManager::registerHook('core', 'requestVars', 'TodoyuRequest::hookSetDefaultRequestVars', 10);
TodoyuHookManager::registerHook('core', 'requestVars', 'TodoyuCookieLogin::hookTryCookieLogin', 20);
TodoyuHookManager::registerHook('core', 'requestVars', 'TodoyuAuth::hookSendNotLoggedInForAjaxRequest', 30);
TodoyuHookManager::registerHook('core', 'requestVars', 'TodoyuAuth::hookRedirectToLoginIfNotLoggedIn', 1000);



	// Setup password requirements
Todoyu::$CONFIG['goodPassword'] = array(
	'minLength'			=> 8,
	'hasLowerCase'		=> true,
	'hasUpperCase'		=> true,
	'hasNumbers'		=> true,
	'hasSpecialChars'	=> false,
);

Todoyu::$CONFIG['CREATE'] = array(
	'engines'	=> array()
);

	// Enable todoyu initialization
Todoyu::$CONFIG['INIT']	= true;

Todoyu::$CONFIG['CHECK_DENIED_RIGHTS']	= false;


	// Export Config
Todoyu::$CONFIG['EXPORT']['CSV']	= array(
	'delimiter'			=> ';',				// field delimiter
	'enclosure'			=> '"',				// field enclosure (wrap for fields)
	'charset'			=> 'utf-8',			// charset of the file
	'useTableHeaders'	=> true				// print headers in the file?
);

?>