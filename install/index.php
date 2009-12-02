<?php

error_reporting(E_ALL ^ E_NOTICE);

	// Change current work directory to main directory to prevent path problems
chdir(dirname(dirname(__FILE__)));

ob_start();

	// Preinclude constants


	// Include global include file
require_once('core/inc/global.php');


	// Load default init script
require_once( PATH_CORE . '/inc/init.php');
require_once( PATH_CORE .'/inc/version.php');


require_once('install/model/TodoyuInstaller.class.php');

//ob_clean();


	// Check if ENABLE file is available. If not, stop here
if( ! is_file(PATH . '/install/ENABLE') ) {
//	@unlink(PATH . '/index.html');
//	echo SERVER_URL . '/index.php';
//	exit();
//	header("Location: ../index.php");
//	exit();
	die("File 'install/ENABLE' not found. Create it to access the installer");
}

if( $_GET['restart'] == 1 ) {
	TodoyuInstaller::setStep(0);
	header("Location: " . $_SERVER['SCRIPT_NAME']);
	exit();
}

	// If data has been submitted, process it
if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	$error = TodoyuInstaller::processStep($_POST);
}

	// Display step output
TodoyuInstaller::displayStep($error);

ob_end_flush();

?>