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
 * Simple (non-block) Dwoo plugins
 *
 * @package		Todoyu
 * @subpackage	Template
 */



/**
 * Dwoo plugin function for label translation
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$key		label-key
 * @param	String			$locale		locale (de,en,...)
 * @return	String
 */
function Dwoo_Plugin_Label_compile(Dwoo_Compiler $compiler, $key, $plain = false, $locale = null) {
	return 'findLabel(' . $key . ', ' . $plain . ', ' . $locale . ')';
	//return 'TodoyuLanguage::getLabel(' . $key . ',' . $locale . ')';
}



/**
 * Dwoo plugin function for label translation with replacement of substring
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo		$dwoo
 * @param	String		$key		label-key
 * @param	String		$locale		locale (de,en,...)
 * @param	String		$needle
 * @param	String		$replacement
 * @return	String
 */

function Dwoo_Plugin_LabelReplace(Dwoo $dwoo, $key, $locale = null, $needle, $replacement) {
	$res = Label($key, $locale);
	if ( trim($res) != '') {
		$res	= str_replace($needle, $replacement, $res);
	} else {
		$res = 'Label not found: "' . $key . '" ';
	}

	return $res;
}



/**
 * Format the amount of workload (number of seconds to hours and minutes)
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param	Integer		$workload
 * @return	String
 */
function Dwoo_Plugin_Workload(Dwoo $dwoo, $workload) {
	return TodoyuTime::sec2hour($workload);
}



/**
 * Build a Todoyu link link ?ext=EXTNAME&action=ACTION
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param 	String		$extension
 * @param	String		$action
 * @param	String		$params
 * @return	String
 */
function Dwoo_Plugin_link(Dwoo $dwoo, $extension, $controller = 'ext', $params = '') {
	return '?ext=' . $extension . '&controller=' . $controller . $params;
}



/**
 * Include given file's content with special- or all applicable characters converted to HTML character entities
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param 	String		$file
 * @return	String
 */
function Dwoo_Plugin_includeEscaped(Dwoo $dwoo, $file, $convertSpecialCharsOnly = true) {
	require_once( PATH . '/lib/php/dwoo/plugins/builtin/functions/include.php' );

	$content	= Dwoo_Plugin_include($dwoo, $file);

	return $convertSpecialCharsOnly == true ? htmlspecialchars($content) : htmlentities($content);
}



/**
 * Check if a value (or a list of) exists in an array
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param	String		$value
 * @param	Array		$array
 * @return	Boolean
 */
function Dwoo_Plugin_inArray(Dwoo $dwoo, $value, $array) {
	if( ! is_array($value) ) {
		$value = explode(',', $value);
	}
	if( ! is_array($array) ) {
		$array = explode(',', $array);
	}

	$mix = array_intersect($value, $array);

	return sizeof($mix) > 0 ;
}



/**
 * Encode string for HTML output
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo_Compiler 	$compiler
 * @param 	String			$string
 * @return	String
 */
function Dwoo_Plugin_htmlencode_compile(Dwoo_Compiler $compiler, $string) {
	return 'htmlentities(' . $string . ', ENT_QUOTES, \'UTF-8\')';
}



/**
 * Format an integer to hours:minutes:seconds
 *
 * @param 	Dwoo 		$dwoo
 * @param	Integer		$seconds
 * @return	String
 */
function Dwoo_Plugin_HourMinSec(Dwoo $dwoo, $seconds) {
	return TodoyuTime::sec2time($seconds);
}



/**
 * Format an integer to hours:minutes:seconds
 *
 * @param 	Dwoo 		$dwoo
 * @param	Integer		$seconds
 * @return	String
 */

function Dwoo_Plugin_HourMin(Dwoo $dwoo, $seconds) {
	return TodoyuTime::sec2time($seconds);
}



/**
 * Check if an ID belongs to the current user
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo_Compiler 	$dwoo
 * @param	Integer			$idUser
 * @return	Boolean
 */

function Dwoo_Plugin_isUserID_compile(Dwoo_Compiler $dwoo, $idUser) {
	return 'userid() === intval(' . $idUser . ')';
}


/**
 * Get the ID of the currently logged-in user
 *
 * @param 	Dwoo 		$dwoo
 * @return	String
 *
 */
function Dwoo_Plugin_UserID(Dwoo $dwoo) {
	return userid();
}



/**
 * Get the username to an user ID
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param	Integer		$idUser
 * @return	String
 */

function Dwoo_Plugin_UserName(Dwoo $dwoo, $idUser) {
	$idUser	= intval($idUser);

	return TodoyuUserManager::getUser($idUser)->getFullName(true);
}



/**
 * Get the shortname of the currently logged-in user
 *
 * @param 	Dwoo 		$dwoo
 * @return	String
 *
 */
function Dwoo_Plugin_UserShortname(Dwoo $dwoo, $idUser) {
	$idUser	= intval( $idUser );

	return TodoyuUserManager::getUser($idUser)->getShortname();
}



/**
 * Get formatted filesize
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param	Integer		$filesize
 * @return	String
 */

function Dwoo_Plugin_filesize(Dwoo $dwoo, $filesize) {
	return TodoyuDiv::formatSize($filesize);
}



/**
 * Quicklink to user information
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo 		$dwoo
 * @param	Integer		$idUser
 * @return	String
 */

function Dwoo_Plugin_UserQuicklink(Dwoo $dwoo, $idUser) {
	return 'alert(\'Quicklink: \' + ' . $idUser . ')';
}



/**
 * Limit string length to given length
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param 	String		$string
 * @param 	Integer		$maxLen
 */

function Dwoo_Plugin_cropText_compile(Dwoo_Compiler $compiler, $string, $maxLen, $dontSplitWords = true) {
	return 'TodoyuDiv::cropText(' . $string . ', ' . $maxLen . ', \'...\', ' . $dontSplitWords . ')';
}



/**
 * Render numberic value with (at least) two digits
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param 	String		$value
 */

function Dwoo_Plugin_twoDigits(Dwoo $dwoo, $value) {
	return intVal($value) < 10 ? ('0' . $value) : $value;
}



/**
 * Debug some variable inside a Dwoo template
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param 	Mixed		$variable
 * @return	String
 */
function Dwoo_Plugin_debug(Dwoo $dwoo, $variable) {
	return '<pre style="z-index:200; background-color:#fff;">' . print_r($variable, true) . '</pre>';
}



/**
 * View some variable (from inside a Dwoo template) in firebug
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param 	Mixed		$variable
 * @return	String
 */
function Dwoo_Plugin_firebug(Dwoo $dwoo, $variable) {
	TodoyuDebug::printInFirebug($variable);
}



/**
 * returns odd even by given index
 *
 * @package		Todoyu
 * @subpackage 	Template
 *
 * @param Dwoo $dwoo
 * @param int $index
 * @return stringg
 */
function Dwoo_Plugin_odd_even(Dwoo $dwoo, $index)	{
	return $index%2 ? 'odd':'even';
}



/**
 * Special Todoyu date format. Format a date based on registered key in the core
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param	Dwoo_Compiler 	$compiler		Dwoo compiler
 * @param	Integer			$timestamp		Timestamp to format
 * @param	String			$formatName		Format name
 * @return	String
 */
function Dwoo_Plugin_dateFormat_compile(Dwoo_Compiler $compiler, $timestamp, $formatName) {
	return 'TodoyuTime::format(' . $timestamp . ', ' . $formatName . ')';
}



function Dwoo_Plugin_cleanHtml_compile(Dwoo_Compiler $compiler, $html) {
	return 'TodoyuHtmlFilter::clean(' . $html . ')';
}



/**
 * Button template
 *
 * @param 	Dwoo		$dwoo
 * @param	String		$label		Button text
 * @param	String		$onclick	Onclick javascript handler
 * @param	String		$class		CSS class
 * @param	Integer		$id			HTML id
 * @return	String
 */
function Dwoo_Plugin_Button(Dwoo $dwoo, $label = '', $onclick = '', $class ='', $id = '', $title = '', $type = '') {
	$tmpl	= 'core/view/button.tmpl';
	$data	= array(
		'label'		=> $label,
		'onclick'	=> $onclick,
		'class'		=> $class,
		'id'		=> $id,
		'title'		=> $title,
		'type'		=> $type
	);

	return render($tmpl, $data);
}



/**
 * Header template
 *
 * @param 	Dwoo		$dwoo
 * @param	String		$title
 * @param	String		$class
 * @return	String
 */
function Dwoo_Plugin_Header(Dwoo $dwoo, $title, $class = '') {
	$tmpl	= 'core/view/headerLine.tmpl';
	$data	= array(
		'title'	=> Label($title),
		'class'	=> $class
	);

	return render($tmpl, $data);
}



/**
 * Build page content title
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$title
 * @return	String
 */
function Dwoo_Plugin_Title_compile(Dwoo_Compiler $compiler, $title) {
	return '\'<h5>\' . htmlentities(TodoyuDiv::getLabel(' . $title . ') ) . \'</h5>\'';
}



/**
 * Index letter generator
 * Generates letters from A-Z with links the the anchors
 *
 * @param	Dwoo		$dwoo
 * @param	Array		$records		Record array the list is based on
 * @param	String		$field			Field to check if a record with this letter exists
 * @param	String		$indexName		Name of the anchor (default is index)
 * @return	String
 */
function Dwoo_Plugin_IndexLetters(Dwoo $dwoo, array $records, $field, $indexName = 'index') {
	$letters = array();

	for($i = 65; $i < 91; $i++) {
		$letters[chr($i)] = false;
	}

	foreach($records as $record) {
		$key = strtoupper(substr($record[$field], 0, 1));
		$letters[$key] = true;
	}

	$tmpl	= 'core/view/indexLetters.tmpl';
	$data	= array(
		'letters'	=> $letters,
		'indexName'	=> $indexName
	);

	return render($tmpl, $data);
}


/**
 * Check if right is allowed
 * Get function string to check this
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$ext
 * @param	String			$right
 * @return	String
 */
function Dwoo_Plugin_allowed_compile(Dwoo_Compiler $compiler, $ext, $right) {
	return 'TodoyuRightsManager::isAllowed(' . $ext . ',' . $right . ')';
}



/**
 * Check if all given rights are allowed
 * Get function string to check this
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$ext
 * @param	String			$rightsList
 * @return	String
 */
function Dwoo_Plugin_allowedAll_compile(Dwoo_Compiler $compiler, $ext, $rightsList) {
	return 'allowedAll(' . $ext . ',' . $rightsList . ')';
}



/**
 * Check if any given rights are allowed
 * Get function string to check this
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$ext
 * @param	String			$rightsList
 * @return	String
 */
function Dwoo_Plugin_allowedAny_compile(Dwoo_Compiler $compiler, $ext, $rightsList) {
	return 'allowedAny(' . $ext . ',' . $rightsList . ')';
}



/**
 * Check if user has right, or given user ID is the current users ID
 * Get function string to check this
 *
 * @param	Dwoo_Compiler 	$compiler
 * @param	String			$ext
 * @param	String			$right
 * @param	Integer			$idUser
 * @return	String
 */
function Dwoo_Plugin_allowedOrOwn_compile(Dwoo_Compiler $compiler, $ext, $right, $idUser) {
	return 'TodoyuRightsManager::isAllowed(' . $ext . ',' . $right . ') || userid()==' . $idUser;
}



/**
 * Convert HTML code to text, keep as much format as possible
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$html
 * @return	String			Text version
 */
function Dwoo_Plugin_html2text_compile(Dwoo_Compiler $compiler, $html) {
	return 'TodoyuDiv::html2text(' . $html . ')';
}



/**
 * Render select element with options
 *
 * @param	Dwoo 		$dwoo
 * @param	String		$id		HTML id
 * @param	String		$name	HTML name
 * @param	String		$class
 * @param	Integer		$size
 * @param	Boolean		$multiple
 * @param	Boolean		$disabled
 * @param	String		$onchange
 * @param	Array		$options
 * @param	Array		$value		Array to allow for multi selection
 * @return	String
 */
function Dwoo_Plugin_select(Dwoo $dwoo, $id = '', $name = '', $class = '', $size = 0, $multiple = false, $disabled = false, $onchange = '', array $options = array(), array $value = array()) {
	$tmpl	= 'core/view/select.tmpl';
	$data	= array(
		'htmlId'	=> $id,
		'htmlName'	=> $name,
		'class'		=> $class,
		'size'		=> $size,
		'multiple'	=> $multiple,
		'disabled'	=> $disabled,
		'onchange'	=> $onchange,
		'value'		=> $value,
		'options'	=> $options,
	);

	return render($tmpl, $data);
}

?>