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
 * Setup installer steps order
 *
 * @package		Todoyu
 * @subpackage	Installer
 */

$CONFIG['INSTALLER']['steps'] = array(
	0 => array(
		'name'			=> 'welcome',
//		'processAction'	=> 'start',
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderWelcome',
		'nextStepNum'	=> 1,
	),
	1 => array(
		'name'			=> 'servercheck',
//		'processAction'	=> 'servercheck',
			// Check server
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderServercheck',
		'nextStepNum'	=> 2,
	),
	2 => array(
		'name'			=> 'dbconnection',
//		'processAction'	=> 'dbconnection',
			// Check DB connection
		'processFuncRef'=> 'TodoyuInstaller::checkDbConnection',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbConnection',
		'nextStepNum'	=> 3,
	),
	3 => array(
		'name'			=> 'dbselect',
//		'processAction'	=> 'dbselect',
			// Add DB, save DB config
		'processFuncRef'=> 'TodoyuInstaller::dbSelect',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbSelect',
		'nextStepNum'	=> 4,
	),
	4 => array(
		'name'			=> 'importstatic',
//		'processAction'	=> 'importstatic',
			// Import static DB data
		'processFuncRef'=> 'TodoyuInstallerDbHelper::importStaticData',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderImportStatic',
		'nextStepNum'	=> 5,
	),
	5 => array(
		'name'			=> 'config',
//		'processAction'	=> 'config',
			// Update system config file (/config/system.php)
		'processFuncRef'=> 'TodoyuInstaller::tryUpdateConfig',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderConfig',
		'nextStepNum'	=> 6,
	),
	6 => array(
		'name'			=> 'setadminpassword',
//		'processAction'	=> 'setadminpassword',
			// Validate password, store admin user and password in DB (table 'ext_user_user')
		'processFuncRef'=> 'TodoyuInstaller::setAdminPassword',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderAdminPassword',
		'nextStepNum'	=> 7,
	),
	7 => array(
		'name'			=> 'finish',
//		'processAction'	=> 'finish',
			// Finish installer: deactivate, reinit step, go to todoyu login page
		'processFuncRef'=> false, //'TodoyuInstaller::finish',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderFinish',
		'nextStepNum'	=> 100,
	),

		// Update steps
	8 => array(
		'name'			=> 'welcometoupdate',
//		'processAction'	=> 'welcometoupdate',
			// Welcome to version updates screen
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderWelcomeToUpdate',
		'nextStepNum'	=> 9,
	),
	9 => array(
		'name'			=> 'updatebeta1tobeta2',
//		'processAction'	=> 'updatebeta1tobeta2',
			// Update beta1 to beta2, have mandatory updates be carried out
		'processFuncRef'=> 'TodoyuInstaller::updatebeta1tobeta2',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDBstructureCheck',
		'nextStepNum'	=> 10,
	),
	10 => array(
		'name'			=> 'dbstructurecheck',
//		'processAction'	=> 'dbstructurecheck',
		'processFuncRef'	=> '',
			// Check for changes in 'tables.sql' files against DB
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDBstructureCheck',
		'nextStepNum'	=> 11,
	),
	11 => array(
		'name'			=> 'finishupdate',
//		'processAction'	=> 'finishupdate',
		'processFuncRef'=> 'TodoyuInstaller::finishUpdate',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderUpdateFinished',
		'nextStepNum'	=> 7,
	),

	100	=> array(
		'name'			=> 'exit',
//		'processAction'	=> 'exit',
		'processFuncRef'=> 'TodoyuInstaller::finish',
		'renderFuncRef'	=> false, //'TodoyuInstallerRenderer::renderUpdateFinished',
		'nextStepNum'	=> 0,
	)
);

?>