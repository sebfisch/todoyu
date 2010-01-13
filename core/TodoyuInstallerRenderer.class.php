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
	 * Render welcome screen
	 *
	 * @param	String	$error
	 * @return	String
	 */
	public static function renderWelcome($nextStep, $error = '') {
		$data	= array(
			'title'			=> 'Welcome',
			'textclass'		=> 'text textInfo',
			'buttonLabel'	=> 'Check server compatibility',
			'next'			=> true,
			'nextStep'		=> $nextStep
		);
		$tmpl	= 'install/view/01_welcome.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render server check (correct PHP version and writable files, folders) screen
	 *
	 * @param	String	$error
	 * @return	String
	 */
	public static function renderServercheck($nextStep, $error = '') {
		$versionStatus	= TodoyuInstaller::getPhpVersionStatus();
		$next			= 	$versionStatus == 'OK' ? true : false;

		list($writableStatuses, $writablePathsOk)	= TodoyuInstaller::getWritableStatuses();
		$next	= ( $writablePathsOk	== true ) ? $next : false;

		$data	= array(
			'title'				=> 'Server check',
			'phpversionStatus'	=> $versionStatus,
			'phpversion'		=> '(Your version: ' . PHP_VERSION . ')',
			'writable'			=> $writableStatuses,
			'next'				=> $next,
			'buttonLabel'		=> 'Configure database',
			'nextStep'			=> $nextStep
		);
		$tmpl	= 'install/view/02_servercheck.tmpl';

		return render($tmpl, $data);
	}


	/**
	 * Render DB connection details form
	 *
	 * @param String	$nextStep
	 * @param String	$error
	 */
	public static function renderDbConnectionConfig($nextStep, $error) {
		$data	= array(
			'title'				=> 'Setup Database Server Connection',
			'next'				=> true,
			'buttonLabel'		=> 'Test connection',
			'nextStep'			=> $nextStep,
		);

		$tmpl	= 'install/view/03_dbconnection.tmpl';

		return render($tmpl, $data);
	}


	/**
	 * Check DB connection details. This step repeats itself on connection failure.
	 *
	 * @param String	$nextStep
	 * @param String	$error
	 */
	public static function renderDbConnectionCheck($nextStep, $error) {
		$dbData	= $_SESSION['todoyuinstaller']['db'];
//		TodoyuDebug::printHtml($dbData);

		$currentStepName	= TodoyuInstallerStepManager::getCurrentStepName();

		$data	= array(
			'title'			=> 'Setup Database Server Connection',
			'fields'		=> $dbData,
			'next'			=> true,
			'buttonLabel'	=> 'Test connection',
			'nextStep'		=> $currentStepName
		);

			// Received connection data, test-connect
		if( is_array($dbData) && array_key_exists('server', $dbData) ) {
			$data['connectionHasBeenChecked']	= true;
			$connectionOk	= true;
			try {
				TodoyuDbAnalyzer::checkDbConnection($dbData);
			} catch(Exception $e) {
					// Connecting failed
				$data['textClass']	= 'text textError';
				$data['text']		= 'Error: ' . $e->getMessage();
				$data['hasError']	= 1;
				$data['buttonLabel']= 'Check connection data';
				$data['nextStep']	= $currentStepName;

				$connectionOk	= false;
			}

			if ( $connectionOk ) {
				TodoyuInstallerStepManager::jumpToNextStep();
			}

		}
		$tmpl	= 'install/view/03_dbconnection.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render DB select screen
	 *
	 * @param	String	$error
	 * @return	String
	 */
	public static function renderDbSelect($nextStep, $error = '')	{
		$dbData	= $_SESSION['todoyuinstaller']['db'];

		$data	= array(
			'title'			=> 'Setup Database',
			'next'			=> true,
			'nextStep'		=> $nextStep,
			'buttonLabel'	=> 'Save Database Setup'
		);

		if( is_array($dbData) ) {
			try {
				$data['options'] = TodoyuDbAnalyzer::getAvailableDatabases($dbData);
			} catch(Exception $e) {
				$data['textclass'] = 'text textError';
				$data['text'] = 'Error: ' . $e->getMessage();
			}

			if( strlen($error) > 0 )	{
				$data['textclass'] = 'text textError';
				$data['text'] = 'Error: ' . $error;
			}
		}

		$tmpl	= 'install/view/04_dbselect.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render import static DB data screen
	 *
	 * @param	String	$error
	 * @return	String
	 */
	public static function renderImportStatic($nextStep, $error = '') {
		$data	= array(
			'title'			=> 'Import static data',
			'text'			=> 'DB connection is stored, import static data now.',
			'textClass'		=> 'text textSuccess',
			'coreStructure'	=> TodoyuInstallerDbHelper::getCoreDBstructures(),
			'extStructure'	=> TodoyuInstallerDbHelper::getExtDBstructures(),
			'buttonLabel'	=> 'Import static database data',
			'next'			=> true,
			'nextStep'		=> $nextStep
		);
		$tmpl	= 'install/view/05_importstatic.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render config screen
	 *
	 * @param	String	$error
	 * @return	String
	 */
	public static function renderConfig($nextStep, $error = '') {
		$data	= array(
			'title'			=> 'System config',
			'text'			=> $error ? $error : '',
			'textClass'		=> $error ? 'text textError' : '',
			'nextStep'		=> $nextStep,
			'next'			=> true,
			'buttonLabel'	=> 'Save configuration'
		);

		$tmpl	= 'install/view/06_config.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render admin password change screen
	 *
	 * @param	String	$error
	 * @return	String
	 */
	public static function renderAdminPassword($nextStep, $error = '') {
		$data	= array(
			'title'			=> 'Change admin password',
			'text'			=> strlen($error) > 0 ? $error : 'Change admin password for your security!',
			'textClass'		=> strlen($error) > 0 ?  'text textError' : 'text textInfo',
			'nextStep'		=> $nextStep,
			'next'			=> true,
			'buttonLabel'	=> 'Change admin password'
		);

		$tmpl	= 'install/view/07_adminpassword.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render finishing screen
	 *
	 * @param	String	$error
	 * @return	String
	 */
	public static function renderFinish($nextStep, $error = '') {
		$data	= array(
			'title'			=> 'Installation finished',
			'text'			=> 'Installation finished',
			'textClass'		=> 'text textSuccess',
			'nextStep'		=> $nextStep,
			'next'			=> true,
			'buttonLabel'	=> 'Disable installer and go to login'
		);
		$tmpl	= 'install/view/08_finish.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render updater welcome screen
	 *
	 * @param 	String	$error
	 * @return	String
	 */
	public static function renderWelcomeToUpdate($nextStep, $error = '') {
		$data	= array(
			'title'			=> 'Welcome to datebase update',
			'sqlUpdates'	=> TodoyuInstaller::getRequiredVersionUpdates(),
			'nextStep'		=> $nextStep,
			'next'			=> true,
			'buttonLabel'	=> 'Perform DB updates'
		);

		$tmpl	= 'install/view/09_welcometoupdate.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render welcome screen
	 *
	 * @param	String	$error
	 * @return	String
	 */
	public static function renderDBstructureCheck($nextStep, $error = '') {
			// DB structure is NOT up-to-date! offer updating
		$data	= array(
			'title'			=> 'Database update check',
			'textclass'		=> 'text textInfo',
			'diffs'			=> TodoyuInstallerDbHelper::getDBstructureDiff(),
			'nextStep'		=> $nextStep,
			'next'			=> true,
			'buttonLabel'	=> 'Finish update'
		);
		$tmpl	= 'install/view/10_dbchanges.tmpl';

		return render($tmpl, $data);
	}



   /**
 	* Render update finished screen
 	*
 	* @param	String	$error
 	* @return	String
 	*/
	public static function renderUpdateFinished($nextStep, $error = '') {
		$data	= array(
			'title'			=> 'Update finished',
			'text'			=> 'Update finished',
			'textClass'		=> 'text textSuccess',
			'nextStep'		=> $nextStep,
			'next'			=> true,
			'buttonLabel'	=> 'Disable installer and go to login'
		);

		$tmpl	= 'install/view/11_finishupdate.tmpl';
		return render($tmpl, $data);
	}

}
?>