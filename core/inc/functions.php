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
 * Shortcut for TodoyuLanguage::getLabel()
 * Get the label in the current language
 *
 * @package		Todoyu
 * @subpackage 	Core
 * @param	String		$labelKey	e.g 'project.status.planning'
 * @param	String		$locale
 * @return	String
 */
function Label($labelKey, $locale = null) {
	return TodoyuLanguage::getLabel($labelKey, $locale);
}



/**
 * Find label. If plain is true, check for LLL, because also plaintext is allowed
 *
 * @param	String		$label		Label reference or plain text
 * @param	Bool		$plain		If true, label has to start with LLL: or will be interpreted as plaintext
 * @param	String		$locale		Forced locale
 * @return	String
 */
function findLabel($label, $plain = false, $language = null) {
	if( $plain ) {
		return TodoyuDiv::getLabel($label, $language);
	} else {
		return TodoyuLanguage::getLabel($label, $language);
	}
}



/**
 * Get person ID. If parameter is not set or 0, get the current person ID
 *
 * @param	Integer		$idPerson
 * @return	Integer
 */
function personid($idPerson = 0) {
	$idPerson = intval($idPerson);

	return $idPerson === 0 ? TodoyuAuth::getPersonID() : $idPerson;
}



/**
 * Shortcut to current person object
 *
 * @return	TodoyuPerson
 */
function person() {
	return TodoyuAuth::getPerson();
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
 * Check if a right is set (=allowed)
 *
 * @param	String		$extKey		Extension key
 * @param	String		$right		Right name
 * @return	Boolean
 */
function allowed($extKey, $right) {
	return TodoyuRightsManager::isAllowed($extKey, $right);
}



/**
 * Check if ALL given rights of an extension are allowed
 *
 * @param	String		$extKey			Extension key
 * @param	String		$rightsList		Comma seperated names of rights
 * @return	Bool
 */
function allowedAll($extKey, $rightsList) {
	$rights	= explode(',', $rightsList);

	foreach($rights as $right) {
		if( ! allowed($extKey, $right) ) {
			return false;
		}
	}

	return true;
}



/**
 * Check if ANY of the given rights of an extension is allowed
 *
 * @param	String		$extKey			Extension key
 * @param	String		$rightsList		Comma seperated names of rights
 * @return	Bool
 */
function allowedAny($extKey, $rightsList) {
	$rights	= explode(',', $rightsList);

	foreach($rights as $right) {
		if( allowed($extKey, $right) ) {
			return true;
		}
	}

	return false;
}



/**
 * Restrict current request to persons who have the right
 * Stop script if right is not set
 *
 * @param	String		$extKey
 * @param	String		$right
 */
function restrict($extKey, $right) {
	TodoyuRightsManager::restrict($extKey, $right);
}



/**
 * Restrict access to internal persons
 */
function restrictInternal() {
	TodoyuRightsManager::restrictInternal();
}



/**
 * Restrict (deny) access if none if the rights is allowed
 * If one right is allowed, do nothing
 *
 * @param	String		$extKey			Extension key
 * @param	String		$rightsList		Comma seperated names of rights
 */
function restrictIfNone($extKey, $rightsList) {
	$rights		= explode(',', $rightsList);
	$denyRight	= '';

	foreach($rights as $right) {
		if( allowed($extKey, $right) ) {
			return;
		} else {
			$denyRight = $right;
		}
	}

	deny($extKey, $denyRight);
}



/**
 * Deny access because of a missing right
 *
 * @param	String		$extKey
 * @param	String		$right
 */
function deny($extKey, $right) {
	TodoyuRightsManager::deny($extKey, $right);
}

?>