<?php

	// This config file is loaded when everything is loaded and before the request is handled
	// Here you can override any extension configuration with your own setting


$CONFIG['EXT']['user']['isGoodPassword'] = array(
	'minLength'		=> 4,
	'hasNumbers'	=> false,
	'hasLowerCase'	=> false,
	'hasUpperCase'	=> false
);

?>