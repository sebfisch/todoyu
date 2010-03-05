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
 * Core asset configuration
 *
 * @package		Todoyu
 * @subpackage	Core
 */

$CONFIG['FE']['PAGE']['assets'] = array(
	'js' => array(
		array(
			'file'		=> 'lib/js/prototype.js',
			'position'	=> 1,
			'merge'		=> false,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/scriptaculous/scriptaculous.js',
			'position'	=> 2,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/scriptaculous/builder.js',
			'position'	=> 3,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/scriptaculous/effects.js',
			'position'	=> 3,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/scriptaculous/controls.js',
			'position'	=> 4,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/scriptaculous/dragdrop.js',
			'position'	=> 4,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/scriptaculous/slider.js',
			'position'	=> 4,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/tiny_mce/tiny_mce.js',
			'position'	=> 20,
			'merge'		=> false,
			'localize'	=> false,
			'compress'	=> false
		),
		array(
			'file'		=> 'lib/js/scal/javascripts/scal.js',
			'position'	=> 21,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/jscalendar/calendar.js',
			'position'	=> 22,
			'merge'		=> false,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/jscalendar/lang/calendar-de.js',
			'position'	=> 23,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> false
		),
		array(
			'file'		=> 'lib/js/jscalendar/calendar-setup.js',
			'position'	=> 24,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'lib/js/prototype-window/window.js',
			'position'	=> 25,
			'merge'		=> true,
			'localize'	=> false,
			'compress'	=> true
		),
		array(
			'file'		=> 'core/lib/js/prototype/prototype.js',
			'position'	=> 26
		),
		array(
			'file'		=> 'core/assets/js/Todoyu.js',
			'position'	=> 50
		),
		array(
			'file'		=> 'core/lib/js/prototype/Autocompleter.js',
			'position'	=> 51
		),
		array(
			'file'		=> 'core/assets/js/Ui.js',
			'position'	=> 52
		),
		array(
			'file'		=> 'core/assets/js/Notification.js',
			'position'	=> 53
		),
		array(
			'file'		=> 'core/assets/js/Popup.js',
			'position'	=> 54
		),
		array(
			'file'		=> 'core/assets/js/QuickInfo.js',
			'position'	=> 55
		),
		array(
			'file'		=> 'core/assets/js/Headlet.js',
			'position'	=> 55
		),
		array(
			'file'		=> 'core/assets/js/HeadletQuickCreate.js',
			'position'	=> 55
		),
		array(
			'file'		=> 'core/assets/js/HeadletAjaxLoader.js',
			'position'	=> 55
		),
		array(
			'file'		=> 'core/assets/js/AjaxResponders.js',
			'position'	=> 56
		),
		array(
			'file'		=> 'core/assets/js/AjaxReplacer.js',
			'position'	=> 57
		),
		array(
			'file'		=> 'core/assets/js/Ajax.js',
			'position'	=> 57
		),
		array(
			'file'		=> 'core/assets/js/Helper.js',
			'position'	=> 58
		),
		array(
			'file'		=> 'core/assets/js/Hook.js',
			'position'	=> 58
		),
		array(
			'file'		=> 'core/assets/js/Time.js',
			'position'	=> 59
		),
		array(
			'file'		=> 'core/assets/js/ContextMenu.js',
			'position'	=> 60
		),
		array(
			'file'		=> 'core/assets/js/ContextMenuTemplate.js',
			'position'	=> 61
		),
		array(
			'file'		=> 'core/assets/js/Tabs.js',
			'position'	=> 62
		),
		array(
			'file'		=> 'core/assets/js/PanelWidget.js',
			'position'	=> 63
		),
		array(
			'file'		=> 'core/assets/js/Pref.js',
			'position'	=> 64
		)	,
		array(
			'file'		=> 'core/assets/js/Form.js',
			'position'	=> 65
		),
		array(
			'file'		=> 'core/assets/js/Autocomplete.js',
			'position'	=> 66
		),
		array(
			'file'		=> 'core/assets/js/TimePicker.js',
			'position'	=> 67
		),
		array(
			'file'		=> 'core/assets/js/Listing.js',
			'position'	=> 68
		)
	),

	'css' => array(
		array(
			'file'		=> 'core/assets/css/base.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/layout.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/notification.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/navi.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/contextmenu.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/form.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/button.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/tab.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/toppanel.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/content.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/lib/js/jscalendar/jscalendar.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/lib/js/prototype-window/themes/todoyu.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/timepicker.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/list.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/listing.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/panel.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/quickinfo.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/headlet-ajaxloader.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/assets/css/headlet-quickcreate.css',
			'media'		=> 'all',
			'position'	=> 10
		)
	)
);


?>