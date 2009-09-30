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

$fields		= '*';
$table		= 'person_dailyworkload';

$workloads	= Todoyu::db()->getArray($fields, $table);

foreach($workloads as $workload) {
	$data	= array('ext_resources_wl_mon_am'	=> $workload['dwlp_mo_am'],
					'ext_resources_wl_mon_pm'	=> $workload['dwlp_mo_pm'],
					'ext_resources_wl_tue_am'	=> $workload['dwlp_tu_am'],
					'ext_resources_wl_tue_pm'	=> $workload['dwlp_tu_pm'],
					'ext_resources_wl_wed_am'	=> $workload['dwlp_we_am'],
					'ext_resources_wl_wed_pm'	=> $workload['dwlp_we_pm'],
					'ext_resources_wl_thu_am'	=> $workload['dwlp_th_am'],
					'ext_resources_wl_thu_pm'	=> $workload['dwlp_th_pm'],
					'ext_resources_wl_fri_am'	=> $workload['dwlp_fr_am'],
					'ext_resources_wl_fri_pm'	=> $workload['dwlp_fr_pm'],
					'ext_resources_wl_sat_am'	=> $workload['dwlp_sa_am'],
					'ext_resources_wl_sat_pm'	=> $workload['dwlp_sa_pm'],
					'ext_resources_wl_sun_am'	=> $workload['dwlp_su_am'],
					'ext_resources_wl_sun_pm'	=> $workload['dwlp_su_pm']
					);

	$table	= 'ext_user_user';
	$where	= 'id = ' . intval($workload['person']);

	Todoyu::db()->doUpdate($table, $where, $data);
}

?>