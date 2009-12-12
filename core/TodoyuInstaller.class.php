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
class TodoyuInstaller {

	/**
	 * Available steps
	 *
	 * @var	Array
	 */
	private static $steps = array(
//		'dbstructurecheck',
		'welcome',
		'servercheck',
		'dbconnection',
		'dbselect',
		'importstatic',
		'config',
		'setadminpassword',
		'finish'
	);



	/**
	 * Get current step
	 *
	 * @return	Integer
	 */
	public static function getStep() {
		return intval($_SESSION['todoyuinstaller']['step']);
	}



	/**
	 * Set current step
	 *
	 * @param	Integer		$step
	 */
	public static function setStep($step) {
		$_SESSION['todoyuinstaller']['step'] = intval($step);
	}



	/**
	 * Display output for current step
	 *
	 *	@param	String	$error
	 */
	public static function displayStep($error) {
		$stepNr	= self::getStep();
		$step	= self::$steps[$stepNr];

		switch($step) {
			case 'welcome':
				echo TodoyuInstallerRenderer::renderWelcome($error);
				break;

			case 'servercheck':
				echo TodoyuInstallerRenderer::renderServercheck($error);
				break;

			case 'dbconnection':
				echo TodoyuInstallerRenderer::renderDbConnection($error);
				break;

			case 'dbselect':
				echo TodoyuInstallerRenderer::renderDbSelect($error);
				break;

			case 'importstatic':
				echo TodoyuInstallerRenderer::renderImportStatic($error);
				break;

			case 'config':
				echo TodoyuInstallerRenderer::renderConfig($error);
				break;

			case 'setadminpassword':
				echo TodoyuInstallerRenderer::renderAdminPassword($error);
				break;

			case 'finish':
				echo TodoyuInstallerRenderer::renderFinish($error);
				break;

			case 'dbstructurecheck':
				echo TodoyuInstallerRenderer::renderDBstructureCheck($error);
				break;
		}
	}



	/**
	 * Process current step data
	 *
	 *	@param	Array		$data
	 *	@return	String
	 */
	public static function processStep($data) {
		$action	= $data['action'];

		switch($action) {
			case 'start':
				self::setStep(1);
				break;

			case 'servercheck':
				self::setStep(2);
				break;

			case 'dbconnection':
				$_SESSION['todoyuinstaller']['db'] = $data;
				try {
					TodoyuDbAnalyzer::checkDbConnection($data);
					self::setStep(3);
				} catch(Exception $e) {
					$error = $e->getMessage();
				}
				break;

			case 'dbselect':
				try {
					TodoyuInstallerDbHelper::addDatabase();
					self::setStep(4);
					TodoyuInstallerDbHelper::saveDbConfigInFile();
				} catch(Exception $e)	{
					$error = $e->getMessage();
				}
				break;

			case 'importstatic':
				TodoyuInstallerDbHelper::importStaticData();
				self::setStep(5);
				break;

			case 'config':
				try {
					self::updateConfig($data);
					self::setStep(6);
				} catch (Exception $e)	{
					$error = $e->getMessage();
				}
				break;

				break;

			case 'setadminpassword':
				try {
					TodoyuInstallerDbHelper::updateAdminPassword($data['password'], $data['password_confirm']);
					self::setStep(7);
				} catch(Exception $e)	{
					$error = $e->getMessage();
				}
				break;

			case 'finish':
				self::finish();
				self::setStep(0);
				break;
		}

		return $error;
	}



	/**
	 * Update config
	 *
	 *	@param	Array	$data
	 */
	private static function updateConfig($data) {
		if( ! (strlen($data['email']) > 0 && strlen($data['systemname']) > 0) )	{
			throw new Exception('Please set an email address and a system name');
		}

		$data	= array(
			'config'		=> $data,
			'encryptionKey'	=> TodoyuInstaller::makeEncryptionKey()
		);
		$tmpl	= 'install/view/system.php.tmpl';

		$config	= render($tmpl, $data);
		$code	= '<?php' . $config . '?>';
		$file	= PATH . '/config/system.php';

		file_put_contents($file, $code);
	}



	/**
	 * Finish installer steps
	 * Rename ENABLE file to disable the installer
	 */
	private static function finish() {
		$old	= PATH . '/install/ENABLE';
		$new	= PATH . '/install/_ENABLE';

		rename($old, $new);

		if( file_exists(PATH . '/index.html') )	{
			unlink(PATH . '/index.html');
		}

		self::setStep(0);

		header('Location: ' . dirname(SERVER_URL));
		exit();
	}



	/**
	 * Generate encryption key
	 *
	 * @return	String
	 */
	private static function makeEncryptionKey() {
		return str_replace('=', '', base64_encode(md5(NOW . serialize($_SERVER) . session_id() . rand(1000, 30000))));
	}

}
?>