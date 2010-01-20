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
	public static function getProgressRenderData($nextStep, $curRenderStep) {
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
	public static function renderWelcome($nextStep, array $result) {
		$data	= array(
			'title'				=> Label('LLL:installer.title.welcome'),
			'textclass'			=> 'text textInfo',
			'buttonLabel'		=> Label('LLL:installer.button.iAcceptLicense'),
			'next'				=> true,
			'progress'			=> self::getProgressRenderData($nextStep, 'welcome'),
			'nextStep'			=> $nextStep
		);
		$tmpl	= 'install/view/01_license.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render server check (correct PHP version and writable files, folders) screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderServercheck($nextStep, array $result) {
		$data	= array(
			'title'				=> Label('LLL:installer.title.serverCheck'),
			'phpversionStatus'	=> $result['versionStatus'],
			'phpversion'		=> '(' . Label('LLL:installer.yourVersion') . PHP_VERSION . ')',
			'writable'			=> $result['writableStatuses'],
			'next'				=> $result['error'] === false,
			'buttonLabel'		=> Label('LLL:installer.button.configureDatabase'),
			'progress'			=> self::getProgressRenderData($nextStep, 'servercheck'),
			'nextStep'			=> $nextStep
		);
		$tmpl	= 'install/view/02_servercheck.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Check DB connection details. This step repeats itself on connection failure.
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderDbConnection($nextStep, array $result) {
		$error	= $result['error'];

			// Render connection data form
		$data	= array(
			'title'			=> Label('LLL:installer.title.setupDatabaseServerConnection'),
			'fields'		=> $result['db'],
			'next'			=> true,
			'nextStep'		=> $result['nextStep'],
			'progress'		=> self::getProgressRenderData($nextStep, 'dbconnection'),
			'buttonLabel'	=> Label('LLL:installer.button.testConnection'),
		);

			// Prosseccing received connection data, did it succeed?
		if( is_array($result['db']) && $error !== false ) {
			$data['textClass']	= 'text textError';
			$data['hasError']	= 1;
			$data['text']		= Label('LLL:installer.error') . ': ' . $error;
			$data['buttonLabel']= Label('LLL:installer.button.checkConnectionData');
		}

		$tmpl	= 'install/view/03_dbconnection.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render DB select screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderDbSelect($nextStep, array $result)	{
		$dbConfig	= $_SESSION['todoyuinstaller']['db'];

		$data	= array(
			'title'			=> Label('LLL:installer.title.setupDatabase'),
			'next'			=> true,
			'nextStep'		=> $nextStep,
			'progress'			=> self::getProgressRenderData($nextStep, 'dbselect'),
			'buttonLabel'	=> Label('LLL:installer.button.saveDatabaseSetup')
		);

		if( is_array($dbConfig) ) {
			try {
				$data['options'] = TodoyuDbAnalyzer::getAvailableDatabases($dbConfig);
			} catch(Exception $e) {
				$data['textclass']	= 'text textError';
				$data['text']		= Label('LLL:installer.error') . ': ' . $error;
			}
		}

		$tmpl	= 'install/view/04_dbselect.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render import static DB data screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderImportStaticData($nextStep, array $result) {
		$data	= array(
			'title'			=> Label('LLL:installer.title.importStaticData'),
			'text'			=> Label('LLL:installer.dbConnectionStored') . ' ' . Label('LLL:installer.importStaticData'),
			'textClass'		=> 'text textSuccess',
			'coreStructure'	=> TodoyuInstallerDbHelper::getCoreDBstructures(),
			'extStructure'	=> TodoyuInstallerDbHelper::getExtDBstructures(),
			'buttonLabel'	=> Label('LLL:installer.button.importStaticData'),
			'next'			=> true,
			'nextStep'		=> $nextStep,
			'progress'			=> self::getProgressRenderData($nextStep, 'staticdata'),
		);
		$tmpl	= 'install/view/05_importstatic.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render system config setup (name, email, primary language)
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderSytemConfig($nextStep, array $result) {
		$data	= array(
			'title'			=> Label('LLL:installer.title.systemConfig'),
			'text'			=> $error ? $error : '',
			'textClass'		=> $error ? 'text textError' : '',
			'nextStep'		=> $nextStep,
			'progress'		=> self::getProgressRenderData($nextStep, 'systemconfig'),
			'next'			=> true,
			'buttonLabel'	=> Label('LLL:installer.button.saveConfiguration'),
		);

		$tmpl	= 'install/view/06_config.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render admin password change screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderAdminPassword($nextStep, array $result) {
		$data	= array(
			'title'			=> Label('LLL:installer.changeAdminPassword'),
			'text'			=> strlen($error) > 0 ? $error : Label('LLL:installer.error.changeAdminPassword'),
			'textClass'		=> strlen($error) > 0 ?  'text textError' : 'text textInfo',
			'nextStep'		=> $nextStep,
			'progress'		=> self::getProgressRenderData($nextStep, 'setadminpassword'),
			'next'			=> true,
			'buttonLabel'	=> Label('LLL:installer.button.changeAdminPassword'),
		);

		$tmpl	= 'install/view/07_adminpassword.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render finishing screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderFinish($nextStep, array $result) {
		$data	= array(
			'title'			=> Label('LLL:installer.title.finished'),
			'text'			=> Label('LLL:installer.title.finished'),
			'textClass'		=> 'text textSuccess',
			'nextStep'		=> $nextStep,
			'progress'		=> self::getProgressRenderData($nextStep, 'exit'),
			'next'			=> true,
			'buttonLabel'	=> Label('LLL:installer.button.disableAndLogIn')
		);
		$tmpl	= 'install/view/08_finish.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render updater welcome screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderWelcomeToUpdate($nextStep, array $result) {
		$data	= array(
			'title'			=> Label('LLL:installer.title.welcomeToUpdate'),
			'sqlUpdates'	=> TodoyuInstaller::getRequiredVersionUpdates(),
			'nextStep'		=> $nextStep,
			'progress'		=> self::getProgressRenderData($nextStep, 'welcometoupdate'),
			'next'			=> true,
			'buttonLabel'	=> Label('LLL:installer.button.dbUpdate')
		);

		$tmpl	= 'install/view/09_welcometoupdate.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render welcome screen
	 *
	 * @param	String	$nextStep
	 * @param	Array	$result
	 * @return	String
	 */
	public static function renderDBstructureCheck($nextStep, array $result) {
			// DB structure is NOT up-to-date! offer updating
		$data	= array(
			'title'			=> Label('LLL:installer.title.dbUpdateCheck'),
			'textclass'		=> 'text textInfo',
			'diffs'			=> TodoyuInstallerDbHelper::getDBstructureDiff(),
			'nextStep'		=> $nextStep,
			'progress'		=> self::getProgressRenderData($nextStep, 'updatetocurrentversion'),
			'next'			=> true,
			'buttonLabel'	=> Label('LLL:installer.button.checkExtensions')
		);
		$tmpl	= 'install/view/10_dbchanges.tmpl';

		return render($tmpl, $data);
	}



   /**
 	* Render update finished screen
 	*
 	* @param	String	$nextStep
	* @param	Array	$result
	* @return	String
 	*/
	public static function renderUpdateFinished($nextStep, array $result) {
		$data	= array(
			'title'			=> Label('LLL:installer.title.updateFinished'),
			'text'			=> Label('LLL:installer.title.updateFinished'),
			'textClass'		=> 'text textSuccess',
			'nextStep'		=> $nextStep,
			'progress'		=> self::getProgressRenderData($nextStep, 'finishupdate'),
			'next'			=> true,
			'buttonLabel'	=> Label('LLL:installer.button.disableAndLogIn')
		);

		$tmpl	= 'install/view/11_finishupdate.tmpl';
		return render($tmpl, $data);
	}

}
?>