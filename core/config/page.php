<?php

if( TodoyuAuth::isLoggedIn() ) {
	TodoyuFrontend::addMenuEntry('todoyu', 'LLL:core.tab.todoyu.label', 'javascript:void(0)', 300);
}

?>