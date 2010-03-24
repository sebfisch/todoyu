<?php

	// This config file is loaded when everything is loaded and before the request is handled
	// Here you can override any extension configuration with your own setting


Todoyu::$CONFIG['goodPassword'] = array(
	'minLength'		=> 4,
	'hasNumbers'	=> false,
	'hasLowerCase'	=> false,
	'hasUpperCase'	=> false
);

?>