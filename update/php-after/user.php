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

	// Keep the link between person and user
$linkPersonUser	= array();



#### Copy data from "person" to new "ext_user_user" table

	// Get person data
$persons	= Todoyu::db()->getArray('*', 'person', '', '', 'uid');



	// Get person IDs
$personIDs	= array();
$userIDs	= array();
foreach($persons as $person) {
	$personIDs[]= $person['uid'];
	$userIDs[]	= $person['user'];
}



	// Get users
$users = Todoyu::db()->getArray(
	'*',	'user_backup',
	'uid IN (' . implode(',', $userIDs). ')',
	'',	'',	'',
	'uid'
);



	// Get linked workloads data
$workloads = Todoyu::db()->getArray(
	'*',	'person_dailyworkload',
	'person IN (' . implode(',', $personIDs). ')',
	'',	'',	'',
	'person'
);


$personWorkFunctions	=  Todoyu::db()->getArray(
	'*',
	'__backup_customer_person_mm', // (former 'customer_person_mm')
	'person IN (' . implode(',', $personIDs). ')',
	'',	'',	'',
	'person'
);



	// Create new users
foreach($persons as $person) {
	$linkPersonUser[$person['user']] = $person['uid'];
	$linkedWorkloads = $workloads[ $person['uid'] ];

	$table	= 'ext_user_user';
	$data	= array('id'						=> $person['uid'],
					'date_create'				=> $person['crdate'],
					'date_update'				=> $person['last_modified'],
					'deleted'					=> $person['deleted'],
					'gender'					=> $person['gender'],
					'firstname'					=> $person['firstname'],
					'lastname'					=> $person['lastname'],
					'birthday'					=> strtotime($person['dateofbirth']) > 0 ? date('Y-m-d', strtotime($person['dateofbirth'])) : '',
					'ext_resources_efficiency'	=> $person['efficiency'],

					'id_jobtype'				=> $personWorkFunctions[ $person['uid']]['workfunction'],

					'ext_resources_wl_mon_am'	=> $linkedWorkloads['dwlp_mo_am'],
					'ext_resources_wl_mon_pm'	=> $linkedWorkloads['dwlp_mo_pm'],
					'ext_resources_wl_tue_am'	=> $linkedWorkloads['dwlp_tu_am'],
					'ext_resources_wl_tue_pm'	=> $linkedWorkloads['dwlp_tu_pm'],
					'ext_resources_wl_wed_am'	=> $linkedWorkloads['dwlp_we_am'],
					'ext_resources_wl_wed_pm'	=> $linkedWorkloads['dwlp_we_pm'],
					'ext_resources_wl_thu_am'	=> $linkedWorkloads['dwlp_th_am'],
					'ext_resources_wl_thu_pm'	=> $linkedWorkloads['dwlp_th_pm'],
					'ext_resources_wl_fri_am'	=> $linkedWorkloads['dwlp_fr_am'],
					'ext_resources_wl_fri_pm'	=> $linkedWorkloads['dwlp_fr_pm'],
					'ext_resources_wl_sat_am'	=> $linkedWorkloads['dwlp_sa_am'],
					'ext_resources_wl_sat_pm'	=> $linkedWorkloads['dwlp_sa_pm'],
					'ext_resources_wl_sun_am'	=> $linkedWorkloads['dwlp_su_am'],
					'ext_resources_wl_sun_pm'	=> $linkedWorkloads['dwlp_su_pm'],

					'shortname'					=> $person['shortname'],
					'active'					=> 0
	);

	Todoyu::db()->doInsert($table, $data);
}




#### Copy data from "user_backup" to new "ext_user_user" table

	// Get user data
$fields	= 'uid, hidden, username, password, usergroup, external_staff';
$table	= 'user_backup';
$where	= '	username 	!= \'\' AND
			deleted		= 0';

$users	= Todoyu::db()->getArray($fields, $table, $where);


	// Update users
foreach($users as $user) {
	$idUser	= intval($linkPersonUser[$user['uid']]);

	if( $idPerson === 0 ) {
		continue;
	}

	$table	= 'ext_user_user';
	$where	= 'id = ' . $idUser;
	$data	= array('active'		=> $user['hidden'] == 0 ? 1 : 0,
					'username'		=> $user['username'],
					'password'		=> $user['password'],


					// USERTYPE_INTERNAL == 1
					// USERTYPE_EXTERNAL == 2

					'type'			=> ($user['usergroup'] == 1 || $user['usergroup'] == 2) ? 1 : 2
					);

	Todoyu::db()->doUpdate($table, $where, $data);


		// Add group relation
	$idGroup	= intval($user['usergroup']);
	if( $idGroup > 0 ) {
		$data	= array('id_user'	=> $idUser,
						'id_group'	=> $idGroup);

		Todoyu::db()->doInsert('ext_user_mm_user_group', $data);
	}
}



?>