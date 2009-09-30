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
 * Layout rendering
 *
 * @package		Todoyu
 * @subpackage	Core
 */

class TodoyuLayoutRenderer {

	/**
	 * Render all navigations
	 *
	 * @return	String
	 */
	public static function renderNavigation() {
		$tmpl	= 'core/view/navi.tmpl';

		$data	= array(
			'navigation'=> TodoyuFrontend::getMenuEntries(),
			'active'	=> TodoyuFrontend::getActiveTab()
		);

		return render($tmpl, $data);
	}



	/**
	 * Render the main tabs
	 *
	 * @return	String
	 */
	public static function renderMainTabs() {
		$tabs	= TodoyuFrontend::getTabs();

		$tmpl	= 'core/view/maintabs.tmpl';
		$data	= array('tabs'	=> $tabs);

		return render($tmpl, $data);
	}



	/**
	 * Render the submenu
	 *
	 * @param	String		$key
	 * @return	String
	 */
	public static function renderSubmenu($key) {
		$submenu= TodoyuFrontend::getSubmenuTabs($key);
		$content= '';

		if( sizeof($submenu) > 0 ) {
			$tmpl	= 'core/view/submenu.tmpl';
			$data	= TodoyuFrontend::getSubmenuTabs($key);

			$content = render($tmpl, $data);
		}

		return $content;
	}



	/**
	 * Render ajax loader headlet
	 *
	 * @return	String
	 */
	public static function renderAjaxLoaderHeadlet() {
		$tmpl	= 'core/view/headlet-ajaxloader.tmpl';
		$data	= array(
			'id'	=> 'ajaxloader'
		);

		return render($tmpl, $data);
	}

}


?>