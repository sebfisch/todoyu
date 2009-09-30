<?php

	// Change current work directory to main directory to prevent path problems
chdir(dirname(dirname(__FILE__)));

ob_start();

	// Include global include file
require_once('core/inc/global.php');
require_once('install/model/TodoyuInstaller.class.php');

//ob_clean();


	// Check if ENABLE file is available. If not, stop here
if( ! is_file(PATH . '/install/ENABLE') ) {
	die("File 'install/ENABLE' not found. Create it to access the installer");
}

if( $_GET['restart'] == 1 ) {
	TodoyuInstaller::setStep(0);
	header("Location: " . $_SERVER['SCRIPT_NAME']);
	exit();
}

	// If data has been submitted, process it
if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	TodoyuInstaller::processStep($_POST);
}

	// Display step output
TodoyuInstaller::displayStep();

ob_end_flush();

?>