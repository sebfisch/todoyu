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
 * Database updates from beta1 to beta2
 *
 */

$query	= '	SELECT * FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME IN (\'ext_filter_set\', \'ext_filter_condition\')';

$hasRes	= Todoyu::db()->queryHasResult($query);

	// If not done yet
if ($hasRes) {
		// Do updates now
	$query	= file_get_contents( PATH . '/install/db/update_beta1_to_beta2.sql');

	$queries	= explode(';', $query);
	foreach($queries as $query) {
		Todoyu::db()->query( $query );
	}
}

?>