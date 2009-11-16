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

	// Set session cookie HTTPonly
ini_set("session.cookie_httponly", 1);

	// Start session
session_start();


	// Init config array
if( ! is_array($CONFIG) ) {
	$CONFIG = array();
}


	// Define basic constants
require_once( dirname(dirname(__FILE__)) . '/config/constants.php' );


	// Add todoyu include path
set_include_path(get_include_path() . PATH_SEPARATOR . PATH);
	// Add PEAR to include path
set_include_path(get_include_path() . PATH_SEPARATOR . PATH_PEAR);




	// Load basic classes
require_once( PATH_LIB . '/php/cssmin.php' );
require_once( PATH_LIB . '/php/jsmin.php' );
require_once( PATH_CORE . '/Todoyu.class.php' );
require_once( PATH_CORE . '/TodoyuDatabase.class.php' );
require_once( PATH_CORE . '/TodoyuAuth.class.php' );
require_once( PATH_CORE . '/TodoyuBaseObject.class.php' );
require_once( PATH_CORE . '/TodoyuExtensions.class.php' );
require_once( PATH_CORE . '/TodoyuSessionManager.class.php' );
require_once( PATH_CORE . '/TodoyuLocale.class.php' );
require_once( PATH_CORE . '/TodoyuCache.class.php' );
require_once( PATH_CORE . '/TodoyuLogger.class.php' );
require_once( PATH_CORE . '/TodoyuRequest.class.php' );
require_once( PATH_CORE . '/TodoyuActionController.class.php' );
require_once( PATH_CORE . '/TodoyuActionDispatcher.class.php' );
require_once( PATH_CORE . '/TodoyuArray.class.php' );
require_once( PATH_CORE . '/TodoyuDiv.class.php' );
require_once( PATH_CORE . '/TodoyuPreferenceManager.class.php' );
require_once( PATH_CORE . '/TodoyuFileManager.class.php' );
require_once( PATH_CORE . '/TodoyuRightsManager.class.php' );
require_once( PATH_CORE . '/TodoyuHookManager.class.php' );

	// Include basic user classes
require_once( PATH_EXT .  '/user/model/TodoyuUser.class.php' );
require_once( PATH_EXT .  '/user/model/TodoyuUserManager.class.php' );
require_once( PATH_EXT .  '/user/model/TodoyuUserPreferences.class.php' );


	// Load development classes
require_once( PATH_CORE . '/TodoyuDebug.class.php' );
require_once( PATH_LIB . '/php/FirePHP/FirePHP.class.php' );


	// Load dwoo
require_once( PATH_LIB . '/php/dwoo/dwooAutoload.php' );


	// Register autoloader
spl_autoload_register( array('Todoyu', 'autoloader') );


	// Load global functions
require_once( PATH_CORE . '/inc/functions.php' );
require_once( PATH_CORE . '/lib/php/dwoo/plugins.php' );

	// Include strptime function if not defined on windows
if(!function_exists('strptime')) {
	require_once( PATH_CORE . '/inc/strptime.function.php' );
}



	// Load basic core config
require_once( PATH_CONFIG . '/config.php');
require_once( PATH_CONFIG . '/locale.php');
require_once( PATH_CONFIG . '/fe.php');
require_once( PATH_CONFIG . '/assets.php');
require_once( PATH_CONFIG . '/cache.php');
require_once( PATH_CONFIG . '/log.php');
require_once( PATH_CONFIG . '/colors.php');

	// Load local config
require_once( PATH_LOCALCONF . '/extensions.php');
require_once( PATH_LOCALCONF . '/db.php');
require_once( PATH_LOCALCONF . '/config.php');
require_once( PATH_LOCALCONF . '/system.php');


	// Add form include path
TodoyuExtensions::addIncludePath( PATH_CORE . '/form' );

	// Set default timezone
date_default_timezone_set($CONFIG['LOCALE']['defaultTimezone']);

	// Init basic classes
Todoyu::init();



	// Register core localization file
TodoyuLocale::register('core', PATH_CORE . '/locale/core.xml');
TodoyuLocale::register('dateformat', PATH_CORE . '/config/dateformat.xml');

	// Register static_... tables' localization files
TodoyuLocale::register('static_country', PATH_CORE . '/locale/static_country.xml');
TodoyuLocale::register('static_country_zone', PATH_CORE . '/locale/static_country_zone.xml');
TodoyuLocale::register('static_currency', PATH_CORE . '/locale/static_currency.xml');
TodoyuLocale::register('static_territory', PATH_CORE . '/locale/static_territory.xml');

	// Load extensions
require( PATH_CORE . '/inc/load_extensions.php' );

	// Custom config overrides
require_once( PATH_CONFIG . '/override.php');
require_once( PATH_LOCALCONF . '/override.php');

?>