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

	// Activate extensions (deactivated during install)
Todoyu::$CONFIG['WITHOUT_EXTENSIONS'] = false;

	// Debugging
Todoyu::$CONFIG['DEBUG'] = true;

	// Error logging
Todoyu::$CONFIG['LOG']['active'] = array('FILE', 'FIREPHP');

	// Asset caching
Todoyu::$CONFIG['CACHE']['JS']['localize']	= false;
Todoyu::$CONFIG['CACHE']['JS']['merge']		= false;
Todoyu::$CONFIG['CACHE']['JS']['compress']	= false;

Todoyu::$CONFIG['CACHE']['CSS']['merge']	= false;
Todoyu::$CONFIG['CACHE']['CSS']['compress']	= false;

Todoyu::$CONFIG['LIST']['size']	= 30;

//TodoyuRightsManager::flushRights();

?>