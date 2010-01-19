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
 * Setup installer steps (installation, updating) order
 *
 * @package		Todoyu
 * @subpackage	Installer
 */

$CONFIG['INSTALLER']['steps'] = array(
		// Installation steps
	'welcome' => array(
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderWelcome',
		'nextStep'		=> 'servercheck',
	),
	'servercheck' => array(
			// Check server compatibility
		'processFuncRef'=> 'TodoyuInstaller::checkServerCompatibility',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderServercheck',
		'nextStep'		=> 'dbconnection',
	),
	'dbconnection' => array(
			// Configure DB connection details
		'processFuncRef'=> 'TodoyuInstaller::checkDbConnection',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbConnection',
		'nextStep'		=> 'dbselect',
	),
	'dbselect' => array(
			// Configure to select existing or create new DB. Save DB connection data
		'processFuncRef'=> 'TodoyuInstaller::storeDbConfig',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbSelect',
		'nextStep'		=> 'staticdata',
	),
	'staticdata' => array(
			// Preview static data, than import it
		'processFuncRef'=> 'TodoyuInstaller::importStaticData',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderImportStaticData',
		'nextStep'		=> 'systemconfig',
	),
	'systemconfig' => array(
			// Update system config file (/config/system.php)
		'processFuncRef'=> 'TodoyuInstaller::updateSytemConfig',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderSytemConfig',
		'nextStep'		=> 'setadminpassword',
	),
	'setadminpassword' => array(
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderAdminPassword',
		'nextStep'		=> 'saveadminpassword',
	),
	'saveadminpassword' => array(
		'processFuncRef'	=> 'TodoyuInstaller::saveadminpassword',
		'renderFuncRef'		=> 'TodoyuInstallerRenderer::renderFinish',
		'nextStep'			=> 'exit',
		'dontListProgress'	=> true
	),
	'exit'	=> array(
		'processFuncRef'	=> 'TodoyuInstaller::finish',
		'renderFuncRef'		=> false,
		'nextStep'			=> '',
		'dontListProgress'	=> true
	),



		// ------------------------ Update steps ---------------------
	'welcometoupdate' => array(
		'processFuncRef'=> false,
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderWelcomeToUpdate',
		'nextStep'		=> 'updatetocurrentversion',
	),
	'updatetocurrentversion' => array(
		'processFuncRef'=> 'TodoyuInstaller::updateToCurrentVersion',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDBstructureCheck',
		'nextStep'		=> 'dbstructurecheck',
	),
	'dbstructurecheck' => array(
		'processFuncRef'	=> '',
			// Check for changes in 'tables.sql' files against DB
		'renderFuncRef'		=> 'TodoyuInstallerRenderer::renderDBstructureCheck',
		'nextStep'			=> 'finishupdate',
	),
	'finishupdate' => array(
		'processFuncRef'=> 'TodoyuInstaller::finishUpdate',
			// processing autoforwards to next step
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderUpdateFinished',
		'nextStep'		=> 'exitUpdate',
	),
	'exitUpdate'	=> array(
		'processFuncRef'	=> 'TodoyuInstaller::finish',
		'renderFuncRef'		=> false,
		'nextStep'			=> '',
		'dontListProgress'	=> true
	)
);

?>