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

// Transform task billing types to ids

$billingtype = array(	'normal'			=> 1,
						'not_billed'		=> 2,
						'must_be_clarified'	=> 5,
						'bug'				=> 3,
						'feature_request'	=> 4);

foreach($billingtype as $billingText => $billingCode) {
	$table	= 'task';
	$where	= 'billing_type = \'' . $billingText .'\'';
	$update	= array('billing_type' => $billingCode);

	Todoyu::db()->doUpdate($table, $where, $update);
}

?>