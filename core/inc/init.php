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
 * Add extra assets for browsers below ie7
 * @todo	Use hook functions to allow others to hook in here
 */

if( TodoyuBrowserInfo::isIE() ) {
	$browserVersion	= TodoyuBrowserInfo::getMajorVersion();

	if( $browserVersion < 7 ) {
		$CONFIG['FE']['PAGE']['assets']['js'][] = array(
			'file'		=> 'core/assets/js/IEbelow7.js',
			'position'	=> 1000
		);

		$CONFIG['FE']['PAGE']['assets']['css'][] = array(
			'file'		=> 'core/assets/css/iebelow7.css',
			'position'	=> 1000
		);
	}
}

TodoyuHookManager::registerHook('core', 'onload', 'TodoyuRequest::setDefaultRequestVarsHook', 10);
TodoyuHookManager::registerHook('core', 'onload', 'TodoyuCookieLogin::tryCookieLogin', 20);
TodoyuHookManager::registerHook('core', 'onload', 'TodoyuAuth::checkLoginStatus', 1000);

?>