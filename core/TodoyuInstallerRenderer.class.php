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
 * @subpackage	InstallerRenderer
 */
class TodoyuInstallerRenderer {

	/**
	 * Get render array of installation steps being under execution
	 *
	 * @return	Array
	 */
	public static function getProgressRenderData() {
		$step	= TodoyuInstaller::getStep();


			// Get only seqment (install / update) of installer steps containing the current step
		list($installerMode, $steps)	= TodoyuInstaller::getStepsSegment($curRenderStep);

		$progress 	= array(
			'steps'			=> array(),
			'installerMode'	=> $installerMode
		);

			// Render steps progression data
		$isPast			= 1;
		foreach($steps as $stepName => $stepData) {
			if ( $stepData['dontListProgress'] === true ) {
				break;
			}

			$isCurrent	= 0;
			if ( $curRenderStep === $stepName ) {
				$isCurrent	= 1;
				$isPast		= 0;
			}

			$progress['steps'][$stepName]	= array(
				'installerMode'	=> $installerMode,
				'label'			=> Label('LLL:installer.progress.label.' . $stepName),
				'curr'			=> $isCurrent,
				'past'			=> ($isPast && ! $isCurrent) ? 1 : 0,
			);
		}

		return $progress;
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
			'textclass'	=> 'text textInfo'
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
			'phpversion'=> $info['phpversion'],
			'files'		=> $info['files'],
			'stop'		=> $info['stop']
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
			'fields'	=> $result['fields']
		);

		if( $result['error'] ) {
			$data['text'] 		= $result['errorMessage'];
			$data['textClass'] 	= 'error';
		} else {
			$data['text'] 		= Label('dbconnection.text');
			$data['textClass'] 	= 'info';
		}

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
			'databases'	=> TodoyuDbAnalyzer::getDatabasesOnServer($dbConfig)
		);

		if( is_array($result['fields']) ) {
			$data['fields'] = $result['fields'];
		}

		if( $result['error'] === false ) {
			$data['text']		= Label('installer.dbselect.text.saved');
			$data['textClass']	= 'success';
		} else {
			$data['text']		= $result['errorMessage'];
			$data['textClass']	= 'error';
		}

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
			'text'			=> Label('installer.importTablesAndData'),
			'textClass'		=> 'info',
			'coreStructure'	=> TodoyuSQLManager::getCoreTablesFromFile(),
			'extStructure'	=> TodoyuSQLManager::getExtTablesFromFile()
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

		if( $result['error'] === true ) {
			$data['text']		= Label('installer.systemconfig.text.error');
			$data['textClass']	= 'error';
		}

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
		);

		if( $result['error'] === true ) {
			$data['text']		= Label('installer.adminpassword.error.changeAdminPassword');
			$data['textClass']	= 'error';

		}

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
			'title'	=> 'installer.update.title',
			'button'=> 'installer.update.button'
		);

		return $data;
	}



	/**
	 * Render detected and conducted mandatory version DB updates
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderUpdateToCurrentVersion(array $result) {
		$data	= array(
			'title'		=> 'installer.updatetocurrentversion.title',
			'button'	=> 'installer.updatetocurrentversion.button',
			'diff'		=> TodoyuSQLManager::getStructureDifferences()
		);

		if( sizeof($data['diff']['missingTables']) === 0 && sizeof($data['diff']['missingColumns']) === 0 && sizeof($data['diff']['changedColumns']) === 0) {
			$data['text']		= Label('installer.updatetocurrentversion.noupdates');
			$data['textClass']	= 'success';
			$data['noUpdates']	= true;
		}

		return $data;
	}



	public static function renderFinishUpdate(array $result) {
		$data	= array(
			'title'		=> 'installer.finishupdate.title',
			'button'	=> 'installer.finishupdate.button',
			'text'		=> Label('installer.finishupdate.text'),
			'textClass'	=> 'success'
		);

		return $data;
	}



	/**
	 * Render detected and conducted generic database updates
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderGenericDBupdates($nextStep, array $result) {
			// DB structure is NOT up-to-date! offer updating
		$data	= array(
			'title'			=> Label('LLL:installer.title.dbUpdateCheck'),
			'textclass'		=> 'text textInfo',
			'diffs'			=> TodoyuInstallerDbHelper::getDBstructureDiff(),
			'buttonLabel'	=> Label('LLL:installer.button.disableAndLogIn')
		);
		$tmpl	= 'install/view/11_genericupdates.tmpl';

		return render($tmpl, $data);
	}

}
?>