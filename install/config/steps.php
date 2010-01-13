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
		// ------------- Installer steps -------------
	0 => array(
		'name'			=> 'welcome',
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderWelcome',
		'nextStepNum'	=> 1,
	),
	1 => array(
		'name'			=> 'servercheck',
			// Check server
		'processFuncRef'=> 'false',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderServercheck',
		'nextStepNum'	=> 2,
	),
	2 => array(
		'name'			=> 'dbconnectionconfig',
			// Configure DB connection details
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbConnectionConfig',
		'nextStepNum'	=> 21,
	),

	21 => array(
		'name'			=> 'dbconnectioncheck',
			// DB connection and check, store to session if connection OK, repeat on failure
		'processFuncRef'=> 'TodoyuInstaller::checkDbConnection',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbConnectionCheck',
			// If check failed: this step repeats itself
			// If check verified: save and autoforward to next step
		'nextStepNum'	=> 3,
	),

	3 => array(
		'name'			=> 'dbselect',
			// Save DB connection data.	Select DB
		'processFuncRef'=> 'TodoyuInstaller::dbSelect',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbSelect',
		'nextStepNum'	=> 31,
	),

	31	=> array(
			'name'			=> 'savedbselect',
			'processFuncRef'=> 'TodoyuInstaller::saveDbSelect',
			'renderFuncRef'	=> false,
				// No output, processing forwards to next step automaticly
			'nextStepNum'	=> 4,
	),

	4 => array(
		'name'			=> 'importstatic',
			// Save DB selection, Import static DB data
		'processFuncRef'=> 'TodoyuInstaller::SaveDbAndimportStaticData',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderImportStatic',
		'nextStepNum'	=> 5,
	),
	5 => array(
		'name'			=> 'config',
			// Update system config file (/config/system.php)
		'processFuncRef'=> 'TodoyuInstaller::tryUpdateConfig',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderConfig',
		'nextStepNum'	=> 6,
	),
	6 => array(
		'name'			=> 'setadminpassword',
			// Validate password, store admin user and password in DB (table 'ext_user_user')
		'processFuncRef'=> 'TodoyuInstaller::setAdminPassword',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderAdminPassword',
		'nextStepNum'	=> 7,
	),
	7 => array(
		'name'			=> 'finish',
			// Finish installer: deactivate, reinit step, go to todoyu login page
		'processFuncRef'=> false, //'TodoyuInstaller::finish',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderFinish',
		'nextStepNum'	=> 100,
	),

		// ------------- Update steps -------------
	8 => array(
		'name'			=> 'welcometoupdate',
			// Welcome to version updates screen
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderWelcomeToUpdate',
		'nextStepNum'	=> 9,
	),
	9 => array(
		'name'			=> 'updatetocurrentversion',
			// Update beta1 to beta2, have mandatory updates be carried out
		'processFuncRef'=> 'TodoyuInstaller::updateToCurrentVersion',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDBstructureCheck',
		'nextStepNum'	=> 10,
	),
	10 => array(
		'name'			=> 'dbstructurecheck',
		'processFuncRef'	=> '',
			// Check for changes in 'tables.sql' files against DB
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDBstructureCheck',
		'nextStepNum'	=> 11,
	),
	11 => array(
		'name'			=> 'finishupdate',
		'processFuncRef'=> 'TodoyuInstaller::finishUpdate',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderUpdateFinished',
		'nextStepNum'	=> 7,
	),

		// ------------- Exit installer and go to todoyu login page -------------
	100	=> array(
		'name'			=> 'exit',
		'processFuncRef'=> 'TodoyuInstaller::finish',
		'renderFuncRef'	=> false,
		'nextStepNum'	=> 0,
	)
);

?>