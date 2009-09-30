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

// Transform text values of status to constant numbers

$taskStatus = array('accepted'		=> STATUS_ACCEPTED,
					'cleared'		=> STATUS_CLEARED,
					'confirmation'	=> STATUS_CONFIRM,
					'customer'		=> STATUS_CUSTOMER,
					'done'			=> STATUS_DONE,
					'inprogress'	=> STATUS_PROGRESS,
					'open'			=> STATUS_OPEN,
					'planing'		=> STATUS_PLANNING,
					'rejected'		=> STATUS_REJECTED);

foreach($taskStatus as $statusText => $statusCode) {
	$table	= 'task';
	$where	= 'status = \'' . $statusText .'\'';
	$update	= array('status' => $statusCode);

	Todoyu::db()->doUpdate($table, $where, $update);
}

?>