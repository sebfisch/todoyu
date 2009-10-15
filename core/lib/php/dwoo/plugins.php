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
	//return 'TodoyuLocale::getLabel(' . $key . ',' . $locale . ')';
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
 * checks if $value is an array-key of $array
 *
 * @package Todoyu
 * @subpackage Template
 *
 * @param	Dwoo 	$dwoo
 * @param	Mixed	$value
 * @param 	Array	$array
 * @return	Boolean
 */
function Dwoo_Plugin_arrayKeyExists(Dwoo $dwoo, $value, $array)	{
	if(!is_array($array))	{
		return false;
	}

	return array_key_exists($value, $array);
}



/**
 * Encode string for html output
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
 * Get Day.month.Year hours:minutes from given unix timestamp (seconds)
 *
 * @param 	Dwoo 		$dwoo
 * @param	Integer		$timestamp
 * @return	String
 */
function Dwoo_Plugin_date_dmyHi(Dwoo $dwoo, $timestamp) {

	return date('d.m.y H.i', $timestamp);
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
 * @param	Dwoo 		$dwoo
 * @param	Integer		$idUser
 * @return	Boolean
 */

function Dwoo_Plugin_isUserID(Dwoo $dwoo, $idUser) {
	$idUser	= intval($idUser);

	return $idUser > 0 && userid() === $idUser;
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
	$user	= TodoyuUserManager::getUser($idUser);

	return $user->getFullName(true);
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
	$user	= TodoyuUserManager::getUser($idUser);

	return $user->getShortname();
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

function Dwoo_Plugin_limit_strlen(Dwoo $dwoo, $string, $maxLen, $append = '') {
	return strlen($string) <= $maxLen ? $string : ( substr($string, 0, $maxLen) . $append );
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
 * @param 	String		$extension
 * @param	String		$action
 * @param	String		$params
 * @return	String
 */

function Dwoo_Plugin_debug(Dwoo $dwoo, $variable) {
	return '<pre style="z-index:200; background-color:#fff;">' . print_r($variable, true) . '</pre>';
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


function Dwoo_Plugin_Button(Dwoo $dwoo, $label = '', $onclick = '', $class ='', $id = '') {
	$tmpl	= 'core/view/button.tmpl';
	$data	= array(
		'label'		=> $label,
		'onclick'	=> $onclick,
		'class'		=> $class,
		'id'		=> $id
	);

	return render($tmpl, $data);
}


function Dwoo_Plugin_Header(Dwoo $dwoo, $title, $class = '') {
	$tmpl	= 'core/view/headerLine.tmpl';
	$data	= array(
		'title'	=> TodoyuDiv::getLabel($title),
		'class'	=> $class
	);

	return render($tmpl, $data);
}



?>