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

	// Insert project roles
$projectroles	= array(
	array(
		'id'		=> 1,
		'rolekey'	=> 'projectleader',
		'title'		=> 'project.userrrole.projectleader'
	),
	array(
		'id'		=> 2,
		'rolekey'	=> 'projectleader_ext',
		'title'		=> 'project.userrrole.projectleader_ext'
	),
	array(
		'id'		=> 3,
		'rolekey'	=> 'projectsupervisor',
		'title'		=> 'project.userrrole.projectsupervisor'
	),
	array(
		'id'		=> 4,
		'rolekey'	=> 'employee',
		'title'		=> 'project.userrrole.employee'
	),
	array(
		'id'	=> 5,
		'rolekey'	=> 'employee_ext',
		'title'	=> 'project.userrrole.employee_ext'
	),
	array(
		'id'		=> 100,
		'rolekey'	=> 'billingreference',
		'title'		=> 'billing.projectrole.billingreference'
	),
	array(
		'id'		=> 101,
		'rolekey'	=> 'hostingresponsible',
		'title'		=> 'hosting.projectrole.hostingresponsible'
	),
	array(
		'id'		=> 102,
		'rolekey'	=> 'programmer',
		'title'		=> 'snowflake.projectrole.programmer'
	),
	array(
		'id'		=> 103,
		'rolekey'	=> 'designer',
		'title'		=> 'snowflake.projectrole.designer'
	)
);

$table	= 'ext_project_userrole';

foreach($projectroles as $projectrole) {
	Todoyu::db()->doInsert($table, $projectrole);
}



	// Update current values in link table
$data	= array('id_userrole' => 'id_userrole+1000');
$where	= '1';
$table	= 'ext_project_mm_project_user';

Todoyu::db()->doUpdate($table, $where, $data, array('id_userrole'));


	// Update to new values
$valueLink	= array(
	1	=> 1,
	2	=> 102,
	3	=> 2,
	4 	=> 101,
	5	=> 5,
	6	=> 103,
	7	=> 3,
	8	=> 100
);

foreach($valueLink as $oldID => $newID) {
	$table	= 'ext_project_mm_project_user';
	$data	= array('id_userrole' => $newID);
	$where	= 'id_userrole = ' . ($oldID+1000);

	Todoyu::db()->doUpdate($table, $where, $data);
}


?>