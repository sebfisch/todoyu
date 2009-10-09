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
			'merge'		=> false,
			'localize'	=> false,
			'compress'	=> false
		),
		array(
			'file'		=> 'lib/js/tiny_mce/tiny_mce.js',
			'position'	=> 20,
			'merge'		=> true,
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
			'file'		=> 'core/assets/js/Todoyu.js',
			'position'	=> 50
		),
		array(
			'file'		=> 'core/assets/js/extend/prototype.js',
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
			'file'		=> 'core/assets/js/AjaxResponders.js',
			'position'	=> 55
		),
		array(
			'file'		=> 'core/assets/js/AjaxReplacer.js',
			'position'	=> 56
		),
		array(
			'file'		=> 'core/assets/js/Helper.js',
			'position'	=> 57
		),
		array(
			'file'		=> 'core/assets/js/Hook.js',
			'position'	=> 57
		),
		array(
			'file'		=> 'core/assets/js/Time.js',
			'position'	=> 58
		),
		array(
			'file'		=> 'core/assets/js/ContextMenu.js',
			'position'	=> 59
		),
		array(
			'file'		=> 'core/assets/js/ContextMenuTemplate.js',
			'position'	=> 60
		),
		array(
			'file'		=> 'core/assets/js/Tabs.js',
			'position'	=> 61
		),
		array(
			'file'		=> 'core/assets/js/PanelWidget.js',
			'position'	=> 62
		),
		array(
			'file'		=> 'core/assets/js/Pref.js',
			'position'	=> 63
		)	,
		array(
			'file'		=> 'core/assets/js/Form.js',
			'position'	=> 64
		),
		array(
			'file'		=> 'core/assets/js/Autocomplete.js',
			'position'	=> 65
		),
		array(
			'file'		=> 'core/assets/js/DurationPicker.js',
			'position'	=> 66
		)
	),

	'css' => array(
//		array(
//			'file'		=> 'core/assets/css/old/contentstyle.css',
//			'media'		=> 'all',
//			'position'	=> 1
//		),
//		array(
//			'file'		=> 'core/assets/css/old/staticcontentstyle.css',
//			'media'		=> 'all',
//			'position'	=> 1
//		),
//		array(
//			'file'		=> 'core/assets/css/old/staticstyle.css',
//			'media'		=> 'all',
//			'position'	=> 1
//		),
//		array(
//			'file'		=> 'core/assets/css/old/style.css',
//			'media'		=> 'all',
//			'position'	=> 1
//		),
//		array(
//			'file'		=> 'core/assets/css/old/formstyle.css',
//			'media'		=> 'all',
//			'position'	=> 1
//		),
//		array(
//			'file'		=> 'core/assets/css/old/resourceplanning.css',
//			'media'		=> 'all',
//			'position'	=> 1
//		),
//		array(
//			'file'		=> 'core/assets/css/old/staticformstyle.css',
//			'media'		=> 'all',
//			'position'	=> 1
//		),
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
			'file'		=> 'core/assets/css/durationpicker.css',
			'media'		=> 'all',
			'position'	=> 10
		)
//		array(
//			'file'		=> 'lib/js/jscal2/css/jscal2.css',
//			'media'		=> 'all',
//			'position'	=> 10
//		),
//		array(
//			'file'		=> 'lib/js/jscal2/css/border-radius.css',
//			'media'		=> 'all',
//			'position'	=> 11
//		),
//		array(
//			'file'		=> 'lib/js/jscal2/css/steel/steel.css',
//			'media'		=> 'all',
//			'position'	=> 12
//		)
	)
);


?>