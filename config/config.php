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

ini_set('eaccelerator.enable', 0);


	// Activate extensions (deactivated during install)
$CONFIG['WITHOUT_EXTENSIONS'] = false;

	// Debuging
$CONFIG['DEBUG'] = true;

	// Error logging
$CONFIG['LOG']['active'] = array('FILE', 'FIREPHP');

	// Asset caching
$CONFIG['CACHE']['JS']['localize']	= true;
$CONFIG['CACHE']['JS']['merge']		= false;
$CONFIG['CACHE']['JS']['compress']	= false;

$CONFIG['CACHE']['CSS']['merge']	= false;
$CONFIG['CACHE']['CSS']['compress']	= false;

$CONFIG['LIST']['size']	= 30;

//TodoyuRightsManager::flushRights();

?>