<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

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
 * @param	Boolean			$plain		If true, the label needs a LLL: prefix to be recognized as label. Used if labelkeys and plaintext is possible
 * @param	String			$locale		locale (de,en,...)
 * @return	String
 */
function Dwoo_Plugin_Label_compile(Dwoo_Compiler $compiler, $key, $plain = false, $locale = null) {
	return 'findLabel(' . $key . ', ' . $plain . ', ' . $locale . ')';
//	return 'TodoyuLanguage::getLabel(' . $key . ',' . $locale . ')';
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
function Dwoo_Plugin_Workload_compile(Dwoo_Compiler $compiler, $workload) {
	return 'TodoyuTime::sec2hour(' . $workload . ')';
	//return TodoyuTime::sec2hour($workload);
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

	return $convertSpecialCharsOnly == true ? htmlspecialchars($content, ENT_QUOTES, 'UTF-8') : htmlentities($content, ENT_QUOTES, 'UTF-8');
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
 * Get formatted filesize
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param	Integer		$fileSize
 * @return	String
 */

function Dwoo_Plugin_filesize(Dwoo $dwoo, $fileSize) {
	return TodoyuString::formatSize($fileSize);
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
	return 'TodoyuString::crop(' . $string . ', ' . $maxLen . ', \'...\', ' . $dontSplitWords . ')';
}



/**
 * Render numeric value with (at least) two digits
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param 	String		$value
 */

function Dwoo_Plugin_twoDigits(Dwoo $dwoo, $value) {
	return ( intVal($value) < 10 ) ? ('0' . $value) : $value;
}



/**
 * Debug some variable inside a Dwoo template
 *
 * @package		Todoyu
 * @subpackage	Template
 *
 * @param 	Dwoo 		$dwoo
 * @param 	Mixed		$variable
 * @param 	Boolean		$phpFormat
 * @return	String
 */
function Dwoo_Plugin_debug(Dwoo $dwoo, $variable, $phpFormat = false) {
	if ( $phpFormat ) {
			// use PHP syntax formatting
		TodoyuDebug::printPHP($variable);
	} else {
			// simple print_r
		$debug	= '<pre style="z-index:200; background-color:#fff;">' . print_r($variable, true) . '</pre>';
	}

	return $debug;
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
 * Return odd / even by given index
 *
 * @package		Todoyu
 * @subpackage 	Template
 *
 * @param	Dwoo		$dwoo
 * @param	Integer		$index
 * @return	String
 */
function Dwoo_Plugin_odd_even(Dwoo $dwoo, $index) {
	return $index % 2 ? 'odd' : 'even';
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



/**
 * Clean bad tags from HTML code
 *
 * @param	Dwoo_Compiler 	$compiler		Dwoo compiler
 * @param	String			$html
 * @return	String
 */
function Dwoo_Plugin_cleanHtml_compile(Dwoo_Compiler $compiler, $html) {
	return 'TodoyuHtmlFilter::clean(' . $html . ')';
}



/**
 * Substitute URLs by hyperlinks
 *
 * @param 	Dwoo		$dwoo
 * @param	String		$text
 * @return	String
 */
function Dwoo_Plugin_linkUrls_compile(Dwoo_Compiler $compiler, $text) {
	return 'TodoyuString::replaceUrlWithLink(' . $text . ')';
}



/**
 * Button template
 *
 * @param 	Dwoo		$dwoo
 * @param	String		$label		Button text
 * @param	String		$onclick	onClick javaScript handler
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
		'title'	=> TodoyuString::getLabel($title),
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
	return '\'<h5>\' . htmlentities(TodoyuString::getLabel(' . $title . '), ENT_QUOTES, \'UTF-8\') . \'</h5>\'';
}



/**
 * Check whether right is given. Get function string to check this
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
 * Check if all given rights are allowed. Get function string to check this
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
 * Check whether any of the given rights are allowed. Get function string to check this
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
 * Check whether user has right, or given user ID is the current users ID. Get function string to check this
 *
 * @param	Dwoo_Compiler 	$compiler
 * @param	String			$ext
 * @param	String			$right
 * @param	Integer			$idPerson
 * @return	String
 */
function Dwoo_Plugin_allowedOrOwn_compile(Dwoo_Compiler $compiler, $ext, $right, $idPerson) {
	return 'TodoyuRightsManager::isAllowed(' . $ext . ',' . $right . ') || personid()==' . $idPerson;
}



/**
 * Check if user has right and given user ID is the current users ID
 * Get function string to check this
 *
 * @param	Dwoo_Compiler 	$compiler
 * @param	String			$ext
 * @param	String			$right
 * @param	Integer			$idPerson
 * @return	Boolean
 */
function Dwoo_Plugin_allowedAndOwn_compile(Dwoo_Compiler $compiler, $ext, $right, $idPerson) {
	return 'TodoyuRightsManager::isAllowed(' . $ext . ',' . $right . ') && personid()==' . $idPerson;
}



/**
 * Check if person is internal
 *
 * @param	Dwoo_Compiler	$compiler
 * @return	String		(Bool)
 */
function Dwoo_Plugin_isInternal_compile(Dwoo_Compiler $compiler) {
	return 'Todoyu::person()->isInternal()';
}



/**
 * Subtract given subtrahend from given minuend
 *
 * @param	Dwoo		$compiler
 * @param	Mixed		$minuend
 * @param	Mixed		$subtrahend
 * @return	Integer							difference
 */
function Dwoo_Plugin_subtract(Dwoo $dwoo, $minuend, $subtrahend) {
	$minuend	= floatval($minuend);
	$subtrahend	= floatval($subtrahend);

	return ($minuend - $subtrahend);
}



/**
 * Convert HTML code to text, keep as much format as possible
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$html
 * @return	String			Text version
 */
function Dwoo_Plugin_html2text_compile(Dwoo_Compiler $compiler, $html) {
	return 'TodoyuString::html2text(' . $html . ')';
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
function Dwoo_Plugin_select(Dwoo $dwoo, array $options, array $value = array(), $id = '', $name = '', $class = '', $size = 0, $multiple = false, $disabled = false, $onchange = '') {
	$tmpl	= 'core/view/select.tmpl';
	$data	= array(
		'htmlId'	=> $id,
		'htmlName'	=> $name,
		'class'		=> $class,
		'size'		=> $size == 0 ? sizeof($options) : $size,
		'multiple'	=> $multiple,
		'disabled'	=> $disabled,
		'onchange'	=> $onchange,
		'value'		=> $value,
		'options'	=> $options
	);

	if( $multiple !== true ) {
		$data['size'] = 1;
	}

	return render($tmpl, $data);
}



/**
 * Replace line breaks "\n" with ODT style line breaks
 *
 * @param	Dwoo_Compiler	$compiler
 * @param	String			$text
 * @return	String
 */
function Dwoo_Plugin_OdtLinebreaks_compile(Dwoo_Compiler $compiler, $text) {
	return 'str_replace("\n", \'<text:line-break/>\', ' . $text . ')';
}


/**
 * Replace spaces with &nbsp; entities
 * Prevent line breaks on spaces
 *
 * @param	Dwoo_Compiler 	$compiler
 * @param	String			$text
 * @return	String
 */
function Dwoo_Plugin_nobreak_compile(Dwoo_Compiler $compiler, $text) {
	return 'str_replace(\' \', \'&nbsp;\', ' . $text . ')';
}

?>