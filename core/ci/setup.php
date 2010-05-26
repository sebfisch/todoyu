<?php

require_once(realpath(dirname(__FILE__) . '/../inc/global.php'));
require_once(PATH_CORE . '/inc/init.php');

TodoyuExtInstaller::installExtension('unittest');

echo "Unittest wurde installiert";

?>