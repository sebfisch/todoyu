<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * Core asset configuration
 *
 * @package		Todoyu
 * @subpackage	Core
 */

Todoyu::$CONFIG['FE']['PAGE']['assets'] = array(
	'js' => array(
		array(
			'file'		=> 'lib/js/prototype.js',
			'position'	=> 1,
			'merge'		=> false,
			'localize'	=> false,
			'compress'	=> true
		),
//		array(
//			'file'		=> 'lib/js/scriptaculous/scriptaculous.js',
//			'position'	=> 2,
//			'merge'		=> true,
//			'localize'	=> false,
//			'compress'	=> true
//		),
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

/**
 * Note: JSCalendar lang file is added at end of initialization
 */
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
			'file'		=> 'core/asset/js/Todoyu.js',
			'position'	=> 50
		),
		array(
			'file'		=> 'core/asset/js/Ext.js',
			'position'	=> 51
		),
		array(
			'file'		=> 'core/asset/js/Autocompleter.js',
			'position'	=> 51
		),
		array(
			'file'		=> 'core/asset/js/Ui.js',
			'position'	=> 52
		),
		array(
			'file'		=> 'core/asset/js/Notification.js',
			'position'	=> 53
		),
		array(
			'file'		=> 'core/asset/js/Popup.js',
			'position'	=> 54
		),
		array(
			'file'		=> 'core/asset/js/Popups.js',
			'position'	=> 54
		),
		array(
			'file'		=> 'core/asset/js/QuickInfo.js',
			'position'	=> 55
		),
		array(
			'file'		=> 'core/asset/js/SortablePanelList.js',
			'position'	=> 55
		),
		array(
			'file'		=> 'core/asset/js/Ajax.js',
			'position'	=> 56
		),
		array(
			'file'		=> 'core/asset/js/AjaxResponders.js',
			'position'	=> 57
		),
		array(
			'file'		=> 'core/asset/js/AjaxReplacer.js',
			'position'	=> 57
		),
		array(
			'file'		=> 'core/asset/js/Helper.js',
			'position'	=> 58
		),
		array(
			'file'		=> 'core/asset/js/Hook.js',
			'position'	=> 58
		),
		array(
			'file'		=> 'core/asset/js/Time.js',
			'position'	=> 59
		),
		array(
			'file'		=> 'core/asset/js/DateField.js',
			'position'	=> 59
		),
		array(
			'file'		=> 'core/asset/js/ContextMenu.js',
			'position'	=> 60
		),
		array(
			'file'		=> 'core/asset/js/ContextMenuTemplate.js',
			'position'	=> 61
		),
		array(
			'file'		=> 'core/asset/js/Tabs.js',
			'position'	=> 62
		),
		array(
			'file'		=> 'core/asset/js/PanelWidget.js',
			'position'	=> 63
		),
		array(
			'file'		=> 'core/asset/js/Pref.js',
			'position'	=> 64
		)	,
		array(
			'file'		=> 'core/asset/js/Form.js',
			'position'	=> 65
		),
		array(
			'file'		=> 'core/asset/js/Validate.js',
			'position'	=> 66
		),
		array(
			'file'		=> 'core/asset/js/Autocomplete.js',
			'position'	=> 67
		),
		array(
			'file'		=> 'core/asset/js/TimePicker.js',
			'position'	=> 68
		),
		array(
			'file'		=> 'core/asset/js/Listing.js',
			'position'	=> 69
		),
		array(
			'file'		=> 'core/asset/js/Cookie.js',
			'position'	=> 70
		),
		array(
			'file'		=> 'core/asset/js/DelayedTextObserver.js',
			'position'	=> 71
		),
		array(
			'file'		=> 'core/asset/js/PanelWidgetStatusSelector.js',
			'position'	=> 72
		),
		array(
			'file'		=> 'core/asset/js/LoaderBox.js',
			'position'	=> 73
		),
		array(
			'file'		=> 'core/asset/js/ProgressBox.js',
			'position'	=> 74
		),
		array(
			'file'		=> 'core/asset/js/Highcharts.js',
			'position'	=> 74
		),
		array(
			'file'		=> 'core/asset/js/OverflowWindow.js',
			'position'	=> 75
		),
		array(
			'file'		=> 'core/asset/js/Timerange.js',
			'position'	=> 76
		),
		array(
			'file'		=> 'core/asset/js/ItemList.js',
			'position'	=> 77
		),
		array(
			'file'		=> 'core/asset/js/FieldList.js',
			'position'	=> 78
		),
		array(
			'file'		=> 'core/asset/js/SelectMulti.js',
			'position'	=> 79
		),
		array(
			'file'		=> 'core/asset/js/AutocompleterMulti.js',
			'position'	=> 80
		),
		array(
			'file'		=> 'core/asset/js/Wizard.js',
			'position'	=> 81
		),
		array(
			'file'		=> 'core/asset/js/AutoExtendingList.js',
			'position'	=> 82
		),
		array(
			'file'		=> 'core/asset/js/Headlets.js',
			'position'	=> 90
		),
		array(
			'file'		=> 'core/asset/js/Headlet.js',
			'position'	=> 90
		),
		array(
			'file'		=> 'core/asset/js/HeadletQuickCreate.js',
			'position'	=> 91
		),
		array(
			'file'		=> 'core/asset/js/HeadletAbout.js',
			'position'	=> 91
		),
		array(
			'file'		=> 'core/asset/js/HeadletAjaxLoader.js',
			'position'	=> 91
		),
		array(
			'file'		=> 'core/asset/js/PanelWidgetSearchList.js',
			'position'	=> 91
		)
	),

	'css' => array(
		array(
			'file'		=> 'core/asset/css/base.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/layout.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/notification.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/navi.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/contextmenu.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/form.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/button.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/tab.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/toppanel.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/headlet.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/content.css',
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
			'file'		=> 'core/asset/css/timepicker.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/list.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/listing.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/panel.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/sortable-panel-list.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/quickinfo.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/headlet-ajaxloader.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/headlet-quickcreate.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/headlet-about.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/loader-box.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/progress-box.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/overflow-window.css',
			'media'		=> 'all',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/timerange.css',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/inline-filters.css',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/wizard.css',
			'position'	=> 10
		),
		array(
			'file'		=> 'core/asset/css/panelwidget-searchlist.css',
			'position'	=> 10
		)
	)
);

?>