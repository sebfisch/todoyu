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
 * Shortcut for TodoyuLocale::getLabel()
 * Get the label in the current language
 *
 * @package		Todoyu
 * @subpackage 	Core
 * @param	String		$labelKey	e.g 'project.status.planning'
 * @param	String		$locale
 * @return	String
 */
function Label($labelKey, $locale = null) {
	return TodoyuLocale::getLabel($labelKey, $locale);
}



/**
 * Find label. If plain is true, check for LLL, because also plaintext is allowed
 *
 * @param	String		$label		Label reference or plain text
 * @param	Bool		$plain		If true, label has to start with LLL: or will be interpreted as plaintext
 * @param	String		$locale		Forced locale
 * @return	String
 */
function findLabel($label, $plain = false, $locale = null) {
	if( $plain ) {
		return TodoyuDiv::getLabel($label, $locale);
	} else {
		return TodoyuLocale::getLabel($label, $locale);
	}
}



/**
 * Get user id. If parameter is not set or 0, get the current users id
 *
 * @param	Integer		$idUser
 * @return	Integer
 */
function userid($idUser = 0) {
	$idUser = intval($idUser);

	return $idUser === 0 ? TodoyuAuth::getUserID() : $idUser;
}



/**
 * Shortcut to current user object
 *
 * @return	User
 */
function user() {
	return TodoyuAuth::getUser();
}



/**
 * Render data with a template
 * Shortcut for Todoyu Todoyu::tmpl()->get(...);
 *
 * @param	String			$template		Path to template file (or a template object)
 * @param	Array			$data			Data for template rendering
 * @param	Dwoo_ICompiler 	$compiler		Custom compiler
 * @param	Boolean			$output			Output directly with echo
 * @return	String			Rendered template
 */
function render($template, $data = array(), $compiler = null, $output = false) {
	try {
		$content = Todoyu::tmpl()->get($template, $data, $compiler, $output);
	} catch(Dwoo_Exception $e) {
		TodoyuHeader::sendHeaderPlain();

		$trace	= $e->getTrace();

		echo "Dwoo Template Error: ({$e->getCode()})\n";
		echo "=================================================\n\n";
		echo "Error:		{$e->getMessage()}\n";
		echo "File:		{$trace[1]['file']} : {$trace[1]['line']}\n";
		echo "Template:	{$trace[1]['args'][0]}\n";

		exit();
	}

	return $content;
}



/**
 * Render notification
 *
 * @param	String	$text
 * @return	String	notification HTML
 */
function renderNotification($text) {
	$config	= array(
		'text'		=> $text
	);

	return render('core/view/notification.tmpl', $config);
}



/**
 * Check if a right is set (=allowed)
 *
 * @param	String		$extID
 * @param	String		$right
 * @return	Boolean
 */
function allowed($extKey, $right) {
	return TodoyuRightsManager::isAllowed($extKey, $right);
}



/**
 * Restrict current request to users who have the right
 * Stop script if right is not set
 *
 * @param	String		$extKey
 * @param	String		$right
 */
function restrict($extKey, $right) {
	allowed($extKey, $right) or die('No access');
}



/**
 * Add IE custom scripts to the browser (if its an IE)
 *
 */
function initIEcustomScripts() {
	if( TodoyuBrowserInfo::isIE() ) {
		$browserVersion	= TodoyuBrowserInfo::getMajorVersion();

		if( $browserVersion < 7 ) {
			$GLOBALS['CONFIG']['FE']['PAGE']['assets']['js'][] = array(
				'file'		=> 'core/assets/js/IEbelow7.js',
				'position'	=> 1000
			);

			$GLOBALS['CONFIG']['FE']['PAGE']['assets']['css'][] = array(
				'file'		=> 'core/assets/css/iebelow7.css',
				'position'	=> 1000
			);
		}
	}
}

?>