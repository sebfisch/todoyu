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

	session_start();
	session_destroy();


	if( intval($_GET['start']) !== 1) {
		die('
			<html><head><title>Update</title></head><body>
				<a href="?start=1&dryrun=1">Test update starten (dry run) &gt;&gt;</a><br/>
				<a href="?start=1&dryrun=0">Update starten &gt;&gt;</a>
			</body></html>'
		);
	}


		// Change working directory to todoyu root
	chdir(dirname(dirname(__FILE__)));

	$CONFIG['NOAUTH'] = true;

	require('core/inc/global.php');

	$phpBefore	= array('taskstatus', 'projectstatus', 'taskbillingtype', 'acknowledged_tasks', 'ext_user_panelwidget');

	$sqlBefore	= array('user', 'task', 'project', 'portal_tab', 'address', 'address_type', 'bookmark', 'contactinfo', 'contactinfo_type', 'currency', 'customer', 'customer_address_mm', 'customer_contactinfo_mm', 'customer_customerrole_mm', 'customer_person_mm', 'customerrole', 'faq', 'faq_topic', 'fixed_project', 'fixed_task', 'granted_holiday', 'granted_holidays', 'history', 'invoice', 'invoice_approval', 'invoiceitem', 'invoicereminder', 'message', 'person_address_mm', 'person_contactinfo_mm', 'person_usergroup_mm', 'prepayment', 'project_company_mm', 'project_person_mm', 'projectrole', 'rate', 'rateset', 'recurrance', 'reductions', 'settlement_type', 'task_person_mm', 'taskasset', 'taskcomment', 'taskcomment_faq_mm', 'tasktype', 'timetracking', 'usergroup', 'workfunction', 'workinghours', 'ext_billing_type', 'filter', 'static_tables', 'ext_comment_feedback', 'portal_tab_filterset', 'ext_calendar', 'ext_calendar_holidayset');

	$phpAfter	= array('user', 'daily_workload', 'projectrole', 'acknowledged_after');

	$sqlAfter	= array('person', 'preference', 'right', 'log', 'acl', 'user_backup', 'hosting_all', 'person_dailyworkload', '_unused', 'static_data', 'ext_user_panelwidget', 'ext_filter_condition', 'cleanup');




	/*
		// Drop all tables
	$allTables	= Todoyu::db()->getTables();

	foreach($allTables as $tablename) {
		Todoyu::db()->query('DROP TABLE `' . $tablename . '`');
	}
	*/





//	Header::sendHeaderPlain();



	$out	= "Starting Update\n\n<pre>\n";


			// --------------------------- PHP BEFORE:

	$out	.= "PHP before:\n\n";

	foreach($phpBefore as $scriptName) {
		$scriptPath	= "update/php-before/$scriptName.php";

		if (file_exists($scriptPath)) {
			$out	.= '- ' . $scriptPath . "\n";
			if (intval($_GET['dryrun']) != 1)  {
				include( $scriptPath );
			}
		} else {
			$out	.= "\n" . 'FILE NOT FOUND: - ' . $scriptPath . "\n";
		}

	}

	flush();

			// --------------------------- SQL BEFORE:

	$out	.= "\n\nSQL before:\n";

	foreach($sqlBefore as $sqlFile) {
		$sqlFilePath	= 'update/sql-before/' . $sqlFile . '.sql';

		if (file_exists($sqlFilePath)) {
			$out	.= '- ' . $sqlFilePath . "\n";

			if (intval($_GET['dryrun']) != 1)  {
				$sqlCode= file_get_contents($sqlFilePath);
				$parts	= explode(';', $sqlCode);

				foreach($parts as $query) {
					if( trim($query) != '' ) {
						Todoyu::db()->query($query);
					}
				}
			}

		} else {
			$out	.= "\n" . 'FILE NOT FOUND: - ' . $sqlFilePath . "\n";
		}
	}



	flush();


			// --------------------------- PHP AFTER:

	$out	.= "\n\nPHP after:\n";

	foreach($phpAfter as $scriptName) {
		$scriptPath	= "update/php-after/$scriptName.php";

		if (file_exists($scriptPath)) {
			$out	.= '- ' . $scriptPath . "\n";

			if (intval($_GET['dryrun']) != 1)  {
				include($scriptPath);
			}
		} else {
			$out	.= "\n" . 'FILE NOT FOUND: - ' . $scriptPath . "\n";
		}
	}



//	$out	.= '- (inline) setting tasks acknowledged' . "\n\n";


	flush();


			// --------------------------- SQL AFTER:

	$out	.= "\n\nSQL after:\n";

	foreach($sqlAfter as $sqlFile) {
		$sqlPath	= 'update/sql-after/' . $sqlFile . '.sql';

		if (file_exists($sqlPath)) {
			$out	.= '- ' . $sqlPath . "\n";

			if (intval($_GET['dryrun']) != 1)  {
				$sqlCode= file_get_contents($sqlPath);

				$parts	= explode(';', $sqlCode);

				foreach($parts as $query) {
					if( trim($query) != '' ) {
						Todoyu::db()->query($query);
					}
				}
			}
		} else {
			$out	.= "\n" . 'FILE NOT FOUND: - ' . $sqlPath . "\n";
		}

	}


				// --------------------------- FINISHED

	$out	.= "\n" . '</pre>Finished.<br/<br/><a href="index.php">-&gt; Back to Start</a><br/>';

	echo $out;

?>