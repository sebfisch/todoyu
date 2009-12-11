<?php

error_reporting(E_ALL ^ E_NOTICE);

	// Change current work directory to main directory to prevent path problems
chdir(dirname(dirname(__FILE__)));

ob_start();

	// Include global include file
require_once('core/inc/global.php');

	// Load default init script
require_once( PATH_CORE . '/inc/init.php');
require_once( PATH_CORE .'/inc/version.php');

	// Check if _ENABLE file is available (installer has finished). Redirect to login
if( is_file(PATH . '/install/_ENABLE') ) {
	@unlink(PATH . '/index.html');
	header("Location: ../index.php");
	exit();
}

if( $_GET['restart'] == 1 ) {
	TodoyuInstaller::setStep(0);
	header('Location: ' . $_SERVER['SCRIPT_NAME']);
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