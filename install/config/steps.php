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
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderWelcome',
	),
	1 => array(
		'name'			=> 'servercheck',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderServercheck',
	),
	2 => array(
		'name'			=> 'dbconnection',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbConnection',
	),
	3 => array(
		'name'			=> 'dbselect',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDbSelect',
	),
	4 => array(
		'name'			=> 'importstatic',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderImportStatic',
	),
	5 => array(
		'name'			=> 'config',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderConfig',
	),
	6 => array(
		'name'			=> 'setadminpassword',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderAdminPassword',
	),
	7 => array(
		'name'			=> 'finish',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderFinish',
	),
	8 => array(
		'name'			=> 'welcometoupdate',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderWelcomeToUpdate',
	),
	9 => array(
		'name'			=> 'updatebeta1tobeta2',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDBstructureCheck',
	),
	10 => array(
		'name'			=> 'dbstructurecheck',
			// check for changes in 'tables.sql' files against DB
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderDBstructureCheck',
	),
	11 => array(
		'name'			=> 'finishupdate',
		'renderFuncRef'	=> 'TodoyuInstallerRenderer::renderUpdateFinished',
	)
);

?>