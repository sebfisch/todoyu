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
 * Add extra assets for browsers below ie7
 * @todo	Use hook functions to allow others to hook in here
 */

	// Add form include path
Todoyu::addIncludePath( PATH_CORE . '/form' );
	// Add document include path
Todoyu::addIncludePath( PATH_CORE . '/document');

	// Init basic classes
if( Todoyu::$CONFIG['INIT'] ) {
	Todoyu::init();
}


	// Register core localization file
TodoyuLabelManager::registerCore('core', 'core.xml');
TodoyuLabelManager::registerCore('date', 'date.xml');
TodoyuLabelManager::registerCore('file', 'file.xml');
TodoyuLabelManager::registerCore('form', 'form.xml');
TodoyuLabelManager::registerCore('locale', 'locale.xml');
TodoyuLabelManager::registerCore('dateformat', 'dateformat.xml');

	// Register static_... tables' localization files
TodoyuLabelManager::registerCore('static_country', 'static_country.xml');
TodoyuLabelManager::registerCore('static_country_zone', 'static_country_zone.xml');
TodoyuLabelManager::registerCore('static_territory', 'static_territory.xml');
TodoyuLabelManager::registerCore('static_language', 'static_language.xml');

	// Add all paths of installed extensions to autoload
TodoyuExtensions::addAllExtensionAutoloadPaths();

	// Custom config overrides
require_once( PATH_LOCALCONF . '/override.php');

?>