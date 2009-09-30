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

@ini_set('zend_monitor.enable', 0);
if(@function_exists('output_cache_disable')) {
	@output_cache_disable();
}
if(isset($_GET['debugger_connect']) && $_GET['debugger_connect'] == 1) {
	if(function_exists('debugger_connect'))  {
		debugger_connect();
		exit();
	} else {
		echo "No connector is installed.";
	}
}
?>
