<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Setup installer steps (installation, updating) order
 *
 * @package		Todoyu
 * @subpackage	Installer
 */


Todoyu::$CONFIG['INSTALLER']['install'] = array(
	'install',
	'servercheck',
	'dbconnection',
	'dbselect',
	'importtables',
	'systemconfig',
	'adminpassword',
	'demodata',
	'finish'
);

Todoyu::$CONFIG['INSTALLER']['update'] = array(
	'update',
	'updateconfigfiles',
	'updatetocurrentversion',
	'finishupdate'
);


Todoyu::$CONFIG['INSTALLER']['steps'] = array(
		// Installation steps
	'install' => array(
		'process'	=> 'TodoyuInstallerManager::processInstall',
		'render'	=> 'TodoyuInstallerRenderer::renderInstall',
		'tmpl'		=> '01_install.tmpl'
	),
	'servercheck' => array(
			// Check server compatibility
		'process'	=> 'TodoyuInstallerManager::processServercheck',
		'render'	=> 'TodoyuInstallerRenderer::renderServercheck',
		'tmpl'		=> '02_servercheck.tmpl',
		'fileCheck'	=> array(
			'files',
			'config',
			'cache/tmpl/compile',
			'config/db.php',
			'config/extensions.php',
			'config/extconf.php',
			'index.html'
		)
	),
	'dbconnection' => array(
			// Configure DB connection details
		'process'	=> 'TodoyuInstallerManager::processDbconnection',
		'render'	=> 'TodoyuInstallerRenderer::renderDbConnection',
		'tmpl'		=> '03_dbconnection.tmpl'
	),
	'dbselect' => array(
			// Configure to select existing or create new DB. Save DB connection data
		'process'	=> 'TodoyuInstallerManager::processDbSelect',
		'render'	=> 'TodoyuInstallerRenderer::renderDbSelect',
		'tmpl'		=> '04_dbselect.tmpl'
	),
	'importtables' => array(
			// Preview static data, than import it
		'process'	=> 'TodoyuInstallerManager::proccessImportTables',
		'render'	=> 'TodoyuInstallerRenderer::renderImportTables',
		'tmpl'		=> '05_importtables.tmpl'
	),
	'systemconfig' => array(
			// Update system config file (/config/system.php)
		'process'	=> 'TodoyuInstallerManager::procesSystemConfig',
		'render'	=> 'TodoyuInstallerRenderer::renderSytemConfig',
		'tmpl'		=> '06_systemconfig.tmpl'
	),
	'adminpassword' => array(
		'process'	=> 'TodoyuInstallerManager::processAdminPassword',
		'render'	=> 'TodoyuInstallerRenderer::renderAdminPassword',
		'tmpl'		=> '07_adminpassword.tmpl'
	),
	'demodata'		=> array(
		'process'	=> 'TodoyuInstallerManager::processDemoData',
		'render'	=> 'TodoyuInstallerRenderer::renderDemoData',
		'tmpl'		=> 'xx_demodata.tmpl'
	),
	'finish' => array(
		'process'	=> 'TodoyuInstallerManager::processFinish',
		'render'	=> 'TodoyuInstallerRenderer::renderFinish',
		'tmpl'		=> '08_finish.tmpl'
	),



		// ------------------------ Update steps ---------------------
	'update' => array(
		'process'	=> 'TodoyuInstallerManager::processUpdate',
		'render'	=> 'TodoyuInstallerRenderer::renderUpdate',
		'tmpl'		=> '09_update.tmpl'
	),
	'updateconfigfiles'	=> array(
		'process'	=> 'TodoyuInstallerManager::processConfigFileCheck',
		'render'	=> 'TodoyuInstallerRenderer::renderUpdate',
		'tmpl'		=> '13_update.tmpl'
	),
	'updatetocurrentversion' => array(
			// Mandatory version updates
		'process'	=> 'TodoyuInstallerManager::processUpdateToCurrentVersion',
		'render'	=> 'TodoyuInstallerRenderer::renderUpdateToCurrentVersion',
		'tmpl'		=> '10_updatetocurrentversion.tmpl',
	),
	'finishupdate' => array(
		'process'	=> 'TodoyuInstallerManager::processFinishUpdate',
		'render'	=> 'TodoyuInstallerRenderer::renderFinishUpdate',
		'tmpl'		=> '12_finishupdate.tmpl'
	)
);

?>