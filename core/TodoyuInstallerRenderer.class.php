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
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Installer
 *
 * @package		Todoyu
 * @subpackage	Installer
 */
class TodoyuInstallerRenderer {

	/**
	 * Render progress panel widget listing all steps of installation
	 *
	 * @param	String		$step
	 * @return	String
	 */
	public static function renderProgressWidget($step) {
		$tmpl	= 'install/view/panelwidget-progress.tmpl';
		$data	= array(
			'steps'	=> TodoyuInstaller::getStepsWithLabels(),
			'step'	=> TodoyuInstaller::getStep(),
			'title'	=> Label('installer.type.' . TodoyuInstaller::getMode())
		);

		return render($tmpl, $data);
	}



	/**
	 * Render installer step: select installer language (preset for system language)
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderLocale(array $result) {
		$data	= array(
			'title'			=> 'installer.locale.title',
			'button'		=> false,
			'text'			=> Label('installer.locale.text'),
			'textClass'		=> 'text textInfo',
			'locales'		=> TodoyuInstaller::getAvailableLocaleOptions(),
			'userLocale'	=> TodoyuBrowserInfo::getBrowserLocale(),
		);
		
		return $data;
	}



	/**
	 * Render installer step: todoyu end user license agreement
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderLicense(array $result) {
		$data	= array(
			'title'		=> 'installer.license.title',
			'button'	=> 'installer.license.button',
			'text'		=> Label('installer.license.text'),
			'textClass'	=> 'text textInfo'
		);

		return $data;
	}



	/**
	 * Render installer step: Server Check (correct PHP version and writable files, folders)
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderServerCheck(array $result) {
		$info	= TodoyuInstallerManager::checkServer();

		$data	= array(
			'title'		=> 'installer.servercheck.title',
			'button'	=> 'installer.servercheck.button',
			'info'		=> array(
				'phpversion'=> $info['phpversion'],
				'files'		=> $info['files'],
				'stop'		=> $info['stop']
			)
		);

			// Get result of server check, set response display
		if( $info['stop'] === false ) {
			$data['text'] 		= Label('installer.servercheck.ready');
			$data['textClass'] 	= 'success';
		} else {
			$data['text'] 		= Label('installer.servercheck.NotReady');
			$data['textClass'] 	= 'error';
		}

		return $data;
	}



	/**
	 * Render installer step: Setup Database Connection (check DB connection details, this step repeats itself on connection failure)
	 *
	 * @param	Array	$result
	 * @return	Array
	 */
	public static function renderDbConnection(array $result) {
		$data	= array(
			'title'		=> 'installer.dbconnection.title',
			'button'	=> 'installer.dbconnection.button',
			'text'		=> Label('installer.dbconnection.text'),
			'textClass'	=> 'info'
		);

		return $data;
	}



	/**
	 * Render installer step: Select Database
	 *
	 * @param	Array	$result
	 * @return	Array
	 */
	public static function renderDbSelect(array $result) {
		$dbConfig	= TodoyuSession::get('installer/db');
		$databases	= TodoyuDbAnalyzer::getDatabasesOnServer($dbConfig);
		$dbOptions	= array();
		$dbConf		= $dbConfig;

			// Prerender database selection options 
		foreach($databases as $database) {
			$dbConf['database']	= $database;
			$tables				= TodoyuDbAnalyzer::getDatabaseTables($dbConf);
			$dbOptions[] = array(
				'database'	=> $database,
				'tables'	=> $tables,
				'size'		=> sizeof($tables)
			);
		}
			// Setup rendering data
		$data	= array(
			'title'		=> 'installer.dbselect.title',
			'button'	=> 'installer.dbselect.button',
			'databases'	=> $dbOptions,
			'text'		=> Label('installer.dbselect.text'),
			'textClass'	=> 'info'
		);

		return $data;
	}



	/**
	 * Render installer step: Import Database Tables
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderImportDbTables(array $result) {
		$data	= array(
			'title'			=> 'installer.importtables.title',
			'button'		=> 'installer.importtables.button',
			'coreStructure'	=> TodoyuSQLManager::getCoreTablesFromFile(),
			'extStructure'	=> TodoyuSQLManager::getExtTablesFromFile(),
			'text'			=> Label('installer.importtables.text'),
			'textClass'		=> 'info'
		);

		return $data;
	}



	/**
	 * Render installer step: System Configuration Setup (name, email, primary language)
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderSystemConfig(array $result) {
		$data	= array(
			'title'			=> 'installer.systemconfig.title',
			'button'		=> 'installer.systemconfig.button',
			'userLocale'	=> TodoyuSession::get('installer/locale'),
			'locales'		=> TodoyuLocaleManager::getLocaleOptions(),
			'timezones'		=> TodoyuDatasource::getTimezones(),
			'text'			=> Label('installer.systemconfig.text'),
			'textClass'		=> 'info'
		);

		return $data;
	}



	/**
	 * Render installer step: Create Administrator Account
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderAdminAccount(array $result) {		
		$data	= array(
			'title'		=> 'installer.adminaccount.title',
			'button'	=> 'installer.adminaccount.button',
			'email'		=> TodoyuSession::get('installer/systememail'),
			'text'		=> Label('installer.adminaccount.text'),
			'textClass'	=> 'info'
		);

		return $data;
	}



	/**
	 * Render installer step: Import Demo Data
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderImportDemoData(array $result) {
		$data	= array(
			'title'		=> 'installer.importdemodata.title',
			'button'	=> 'installer.importdemodata.button',
			'text'		=> Label('installer.importdemodata.text'),
			'textClass'	=> 'info'
		);

		return $data;
	}



	/**
	 * Render installer step: Finish Installation
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderFinish(array $result) {
		$data	= array(
			'title'		=> 'installer.finish.title',
			'button'	=> 'installer.finish.button',
			'text'		=> Label('installer.finish.text'),
			'textClass'	=> 'success'
		);

		return $data;
	}





	######## UPDATE ##############




	/**
	 * Render updater welcome screen
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderUpdate(array $result) {
		$data	= array(
			'title'			=> 'installer.update.title',
			'button'		=> 'installer.update.button',
			'text'			=> Label('installer.update.title'),
			'textClass'		=> 'info'
		);

		return $data;
	}



	/**
	 * Render detected and conducted mandatory version DB updates
	 *
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderUpdateToCurrentVersion(array $result) {
		$data	= array(
			'title'		=> 'installer.updatetocurrentversion.title',
			'button'	=> 'installer.updatetocurrentversion.button',
			'diff'		=> TodoyuSQLManager::getStructureDifferences()
		);

		if( sizeof($data['diff']['missingTables']) === 0 && sizeof($data['diff']['missingColumns']) === 0 && sizeof($data['diff']['changedColumns']) === 0 && sizeof($data['diff']['missingKeys']) === 0 ) {
			$data['text']		= Label('installer.updatetocurrentversion.noupdates');
			$data['textClass']	= 'success';
			$data['button']		= 'installer.updatetocurrentversion.button.noupdates';
			$data['noUpdates']	= true;
		} else {
			$data['text']		= Label('installer.updatetocurrentversion.updates');
			$data['textClass']	= 'info';
		}

		return $data;
	}



	/**
	 * Render update finish screen
	 *
	 * @param	Array		$result
	 * @return	Array
	 */

	public static function renderFinishUpdate(array $result) {
		$data	= array(
			'title'		=> 'installer.finish.title',
			'button'	=> 'installer.finish.button',
			'text'		=> Label('installer.finishupdate.text'),
			'textClass'	=> 'success'
		);

		return $data;
	}
}
?>