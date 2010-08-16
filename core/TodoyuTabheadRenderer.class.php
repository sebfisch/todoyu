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
 * Tabhead renderer
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuTabheadRenderer {

	/**
	 * Render tab
	 *
	 * @param	String	$id
	 * @param	String	$key
	 * @param	String	$class
	 * @param	String	$classKey
	 * @param	String	$active
	 * @param	String	$label
	 * @param 	String	$position
	 * @param 	Int		$taskamount
	 * @return	String
	 */
	public static function renderTab($htmlId, $class, $classKey, $active, $label, $position, $hasIcon = 0, $elementAmount = 0) {
		$tmpl	= 'core/view/tabhead.tmpl';
		$data	= array(
			'tab' => array(
				'htmlId'	=> $htmlId,
				'class'		=> $class,
				'classKey'	=> $classKey,
				'label'		=> $label,
				'position'	=> $position,
				'hasIcon'	=> $hasIcon,
				'elementAmount'=> $elementAmount
			),
			'active'	=> $active,
		);

		return render($tmpl, $data);
	}



	/**
	 * Render tabs. Parse labels
	 *
	 * @param	String	$name
	 * @param	Array	$tabs
	 * @param	String	$class
	 * @param	String	$jsHandler 
	 * @param	String	$active
	 * @return	String
	 */
	public static function renderTabs($name, array $tabs, $jsHandler, $active = '', $class = '') {
		foreach($tabs as $index => $tab) {
			$tabs[$index]['label'] = TodoyuString::getLabel($tab['label']);
		}

		if( $active === '' ) {
			$active = $tabs[0]['id'];
		}

		if( $class === '' ) {
			$class = $name;
		}

		$tmpl	= 'core/view/tabheads.tmpl';
		$data	= array(
			'name'		=> $name,
			'class'		=> $class,
			'jsHandler'	=> $jsHandler,
			'active'	=> $active,
			'tabs'		=> $tabs,
		);

		return render($tmpl, $data);
	}

}

?>