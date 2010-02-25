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
 * Installer
 *
 * @package		Todoyu
 * @subpackage	Installer
 */
class TodoyuInstallerRenderer {

	/**
	 * Render progress panel widget
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
	 * Render welcome screen (license agreement)
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderInstall(array $result) {
		$data	= array(
			'title'		=> 'installer.install.title',
			'button'	=> 'installer.install.button',
			'text'		=> Label('installer.install.text'),
			'textClass'	=> 'text textInfo'
		);

		return $data;
	}



	/**
	 * Render server check (correct PHP version and writable files, folders) screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderServercheck(array $result) {
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
	 * Check DB connection details. This step repeats itself on connection failure.
	 *
	 * @param	Array	$result
	 * @return	Array
	 */
	public static function renderDbConnection(array $result) {
			// Render connection data form
		$data	= array(
			'title'		=> 'installer.dbconnection.title',
			'button'	=> 'installer.dbconnection.button',
			'text'		=> Label('installer.dbconnection.text'),
			'textClass'	=> 'info'
		);

		return $data;
	}



	/**
	 * Render DB select screen
	 *
	 * @param	Array	$result
	 * @return	Array
	 */
	public static function renderDbSelect(array $result)	{
		$dbConfig	= TodoyuSession::get('installer/db');

		$data	= array(
			'title'		=> 'installer.dbselect.title',
			'button'	=> 'installer.dbselect.button',
			'databases'	=> TodoyuDbAnalyzer::getDatabasesOnServer($dbConfig),
			'text'		=> Label('installer.dbselect.text'),
			'textClass'	=> 'info'
		);

		return $data;
	}



	/**
	 * Render import static DB data screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderImportTables(array $result) {
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
	 * Render system config setup (name, email, primary language)
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderSytemConfig(array $result) {
		$data	= array(
			'title'			=> 'installer.systemconfig.title',
			'button'		=> 'installer.systemconfig.button',
			'languages'		=> TodoyuLanguageManager::getAvailableLanguages(),
			'userLanguage'	=> TodoyuBrowserInfo::getBrowserLanguage(),
			'locales'		=> TodoyuLocaleManager::getLocaleOptions(),
			'userLocale'	=> TodoyuLocaleManager::getBrowserLocale(),
			'text'			=> Label('installer.systemconfig.text'),
			'textClass'		=> 'info'
		);

		return $data;
	}



	/**
	 * Render admin password change screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderAdminPassword(array $result) {
		$data	= array(
			'title'		=> 'installer.adminpassword.title',
			'button'	=> 'installer.adminpassword.button',
			'text'		=> Label('installer.adminpassword.text'),
			'textClass'	=> 'info'
		);

		return $data;
	}


	public static function renderDemoData(array $result) {
		$data	= array(
			'title'		=> 'installer.demodata.title',
			'button'	=> 'installer.demodata.button',
			'text'		=> Label('installer.demodata.text'),
			'textClass'	=> 'info'
		);

		return $data;
	}



	/**
	 * Render finishing screen
	 *
	 * @param	String	$nextStep
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
			'title'		=> 'installer.update.title',
			'button'	=> 'installer.update.button',
			'text'		=> Label('installer.update.title'),
			'textClass'	=> 'info'
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
			'title'		=> 'installer.finishupdate.title',
			'button'	=> 'installer.finishupdate.button',
			'text'		=> Label('installer.finishupdate.text'),
			'textClass'	=> 'success'
		);

		return $data;
	}
}
?>