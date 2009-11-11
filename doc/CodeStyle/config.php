<?php

/**
 * Flat Todoyu PHP config file
 * Config files always should be included into the global namespace
 */


	// Set a config variable for project extension
$CONFIG['EXT']['project']['somevar'] = 345;

	// Register a form hook (if you have a lot of form hooks, register them in your special config file hooks.php)
TodoyuFormHook::registerBuildForm('path/to/the/form/file.xml', 'SomeClass::myHandlerFunction', 120);


?>