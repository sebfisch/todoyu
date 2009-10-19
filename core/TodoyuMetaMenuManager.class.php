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
 * Manager for the meta menu
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuMetaMenuManager {

	/**
	 * Add a menu entry (or override an existing by key)
	 *
	 * @param	String		$key			Entry key (used as ID and class)
	 * @param	String		$label			Label (text or locale reference)
	 * @param	Integer		$position		Position index
	 * @param	String		$href			href tag value
	 * @param	String		$onClick		onclick tag value
	 * @param	String		$onMouseOver	onmouseover tag value
	 * @param	String		$onMouseOut		onmouseout tag value
	 */
	public static function addEntry($key, $label, $position = 100, $href = '', $onClick = '', $onMouseOver = '', $onMouseOut = '') {
		$GLOBALS['CONFIG']['MetaMenu'][$key] = array(
			'key'		=> $key,
			'label'		=> TodoyuDiv::getLabel($label),
			'position'	=> intval($position),
			'href'		=> $href == '' ? 'javascript:void(0)' : $href,
			'onClick'	=> $onClick,
			'onMouseOver'=>$onMouseOver,
			'onMouseOut'=> $onMouseOut
		);
	}



	/**
	 * Get registered menu entries sorted
	 *
	 * @return	Array
	 */
	public static function getEntries() {
		if( ! is_array($GLOBALS['CONFIG']['MetaMenu']) ) {
			$GLOBALS['CONFIG']['MetaMenu'] = array();
		}

		foreach($GLOBALS['CONFIG']['MetaMenu'] as $key => $entry) {
			if( $entry['href'] === '' ) {
				$GLOBALS['CONFIG']['MetaMenu']['href'] = 'javascript:void(0)';
			}
		}

		return TodoyuArray::sortByLabel($GLOBALS['CONFIG']['MetaMenu'], 'position');
	}

}

?>