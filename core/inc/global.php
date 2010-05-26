<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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

	// Set session cookie HTTPonly
@ini_set('session.cookie_httponly', 1);
	// Ignore errors of type notice
error_reporting(E_ALL ^ E_NOTICE);

	// Start session
session_start();


	// Define basic constants
require_once( dirname(dirname(__FILE__)) . '/config/constants.php' );

	// Store session modification timestamp
if ( ! isset($_SESSION['mtime']) ) {
	$_SESSION['mtime']	= NOW;
}

	// Add todoyu include path
set_include_path(get_include_path() . PATH_SEPARATOR . PATH);
	// Add PEAR to include path
set_include_path(get_include_path() . PATH_SEPARATOR . PATH_PEAR);



	// Load basic classes
require_once( PATH_CORE . '/Todoyu.class.php' );
require_once( PATH_CORE . '/TodoyuDatabase.class.php' );
require_once( PATH_CORE . '/TodoyuAuth.class.php' );
require_once( PATH_CORE . '/TodoyuBaseObject.class.php' );
require_once( PATH_CORE . '/TodoyuExtensions.class.php' );
require_once( PATH_CORE . '/TodoyuSession.class.php' );
require_once( PATH_CORE . '/TodoyuLanguage.class.php' );
require_once( PATH_CORE . '/TodoyuCache.class.php' );
require_once( PATH_CORE . '/TodoyuLogger.class.php' );
require_once( PATH_CORE . '/TodoyuRequest.class.php' );
require_once( PATH_CORE . '/TodoyuActionController.class.php' );
require_once( PATH_CORE . '/TodoyuActionDispatcher.class.php' );
require_once( PATH_CORE . '/TodoyuArray.class.php' );
require_once( PATH_CORE . '/TodoyuPreferenceManager.class.php' );
require_once( PATH_CORE . '/TodoyuFileManager.class.php' );

require_once( PATH_CORE . '/TodoyuRightsManager.class.php' );

require_once( PATH_CORE . '/TodoyuHookManager.class.php' );
require_once( PATH_CORE . '/TodoyuHeader.class.php' );
require_once( PATH_CORE . '/TodoyuPanelWidgetManager.class.php' );
require_once( PATH_CORE . '/TodoyuContextMenuManager.class.php' );
require_once( PATH_CORE . '/TodoyuErrorHandler.class.php' );

	// Include basic person classes
require_once( PATH_EXT .  '/contact/model/TodoyuPerson.class.php' );
require_once( PATH_EXT .  '/contact/model/TodoyuPersonManager.class.php' );
require_once( PATH_EXT .  '/contact/model/TodoyuContactPreferences.class.php' );

	// Load development classes
require_once( PATH_CORE . '/TodoyuDebug.class.php' );
require_once( PATH_LIB . '/php/FirePHP/FirePHP.class.php' );

	// Load dwoo
require_once( PATH_LIB . '/php/dwoo/dwooAutoload.php' );

	// Load CSS and JS minimizer
require_once( PATH_LIB . '/php/cssmin.php' );
require_once( PATH_LIB . '/php/jsmin.php' );

	// Register autoloader
spl_autoload_register( array('Todoyu', 'autoloader') );

	// Register error handler
//set_error_handler(array('TodoyuErrorHandler', 'handleError'));

	// Load global functions
require_once( PATH_CORE . '/inc/version.php' );
require_once( PATH_CORE . '/inc/functions.php' );
require_once( PATH_CORE . '/lib/php/dwoo/plugins.php' );
require_once( PATH_CORE . '/lib/php/dwoo/Dwoo_Plugin_restrict.php' );
require_once( PATH_CORE . '/lib/php/dwoo/Dwoo_Plugin_restrictIfNone.php' );
require_once( PATH_CORE . '/lib/php/dwoo/Dwoo_Plugin_restrictOrOwn.php' );
require_once( PATH_CORE . '/lib/php/dwoo/Dwoo_Plugin_restrictInternal.php' );
require_once( PATH_CORE . '/lib/php/dwoo/Dwoo_Plugin_listing.php' );

	// Include strptime function if not defined on windows
if( ! function_exists('strptime') ) {
	require_once( PATH_CORE . '/inc/strptime.function.php' );
}



	// Load basic core config
require_once( PATH_CONFIG . '/config.php');
require_once( PATH_CONFIG . '/locales.php');
require_once( PATH_CONFIG . '/language.php');
require_once( PATH_CONFIG . '/fe.php');
require_once( PATH_CONFIG . '/assets.php');
require_once( PATH_CONFIG . '/cache.php');
require_once( PATH_CONFIG . '/log.php');
require_once( PATH_CONFIG . '/colors.php');


	// Load local config
require_once( PATH_LOCALCONF . '/db.php');
require_once( PATH_LOCALCONF . '/config.php');
require_once( PATH_LOCALCONF . '/system.php');
require_once( PATH_LOCALCONF . '/extensions.php');
require_once( PATH_LOCALCONF . '/extconf.php');


	// Load contact extension (needed to initialize todoyu)
require_once( PATH_EXT . '/contact/ext.php' );

?>