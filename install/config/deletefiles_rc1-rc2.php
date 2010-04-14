<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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

		// Sub paths
	$pathConfig		= 'config' . DIR_SEP;
	$pathConfigForm	= $pathConfig . 'form' . DIR_SEP;
	$pathConfigDb	= $pathConfig . 'db' . DIR_SEP;
	$pathConfigFormAdmin	= $pathConfigForm . 'admin' . DIR_SEP;
	$pathLocale		= 		'locale' . DIR_SEP;
	$pathAssetsCss	= 		'assets' . DIR_SEP . 'css' . DIR_SEP;
	$pathAssetsImg	= 		'assets' . DIR_SEP . 'img' . DIR_SEP;
	$pathAssetsJs	= 		'assets' . DIR_SEP . 'js' . DIR_SEP;
	$pathController			= 'controller' . DIR_SEP;
	$pathModel				= 'model' . DIR_SEP;
	$pathView				= 'view' . DIR_SEP;
	$pathViewPanelwidgets	= $pathView . 'panelwidgets' . DIR_SEP;
	$pathConfig				= 'config' . DIR_SEP;
	$pathInstall			= PATH . DIR_SEP . 'install' . DIR_SEP;

	Todoyu::$CONFIG['INSTALLER']['oldFiles']	= array(
			// Folders to be deleted with all their contents
		'deleteFolders'	=> array(
			PATH_EXT . DIR_SEP . 'dev',
			PATH_EXT . DIR_SEP . 'user',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'content',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'tabs',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'tree',
		),

		'deleteFolderContents'	=> array(
			PATH_CACHE . DIR_SEP . 'css',
			PATH_CACHE . DIR_SEP . 'img',
			PATH_CACHE . DIR_SEP . 'js',
			PATH_CACHE . DIR_SEP . 'language',
			PATH_CACHE . DIR_SEP . 'output',
			PATH_CACHE . DIR_SEP . 'tmpl',
		),

			// Files to be deleted
		'deleteFiles'	=> array(
			PATH_CORE . DIR_SEP . 'TodoyuDiv.class.php',
			PATH_CORE . DIR_SEP . 'TodoyuHeadletManager.class.php',
			PATH_CORE . DIR_SEP . 'TodoyuHeadletMetaMenu.class.php',
			PATH_CORE . DIR_SEP . 'TodoyuHeadletRenderer.class.php',
			PATH_CORE . DIR_SEP . $pathAssetsCss . 'paging.css',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'bg_button.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'bg_button_small.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'bg_footer.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'bg_headerline.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'bg_headers.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'bg_metamenu_li.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'bg_tab_header.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'body_bg.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'col1_r13.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'col3_bg_r13.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'col3_r13.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'colF_r13.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'dottedline_hor.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'dottedline_ver.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'duration_wizard.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'footer_bg.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'ico_toggle.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'ico_toggle_select.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'icons_dev.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'panel_footer.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'panel_header.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'shadow01.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'sprite-icons.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'sprite-notify.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'sprite-toggle.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'subNav_a_act_bg.png',
			PATH_CORE . DIR_SEP . $pathAssetsImg . 'subNav__bg.png',
			PATH_CORE . DIR_SEP . $pathAssetsJs . 'Paging.js',
			PATH_CORE . DIR_SEP . $pathView . 'headlet-ajaxloader.tmpl',
			PATH_CORE . DIR_SEP . $pathView . 'headlet-metamenu.tmpl',
			PATH_EXT_ADMIN . DIR_SEP . $pathConfig . 'rights.xml',
			PATH_EXT_ADMIN . DIR_SEP . $pathLocale . 'rights.xml',
			PATH_EXT_CALENDAR . DIR_SEP . $pathAssetsCss . 'panelwidget-quickevent.css',
			PATH_EXT_CALENDAR . DIR_SEP . $pathAssetsImg . 'icons_old.png',
			PATH_EXT_CALENDAR . DIR_SEP . $pathAssetsJs . 'PanelWidgetQuickEvent.js',
			PATH_EXT_CALENDAR . DIR_SEP . $pathAssetsJs . 'Quickinfo.js',
			PATH_EXT_CALENDAR . DIR_SEP . $pathConfigForm . 'event-user.xml',
			PATH_EXT_CALENDAR . DIR_SEP . $pathConfigForm . 'quickevent.xml',
			PATH_EXT_CALENDAR . DIR_SEP . $pathController . 'TodoyuCalendarQuickeventActionController.class.php',
			PATH_EXT_CALENDAR . DIR_SEP . $pathModel . 'TodoyuCalendarQuickinfo.class.php',
			PATH_EXT_CALENDAR . DIR_SEP . $pathModel . 'TodoyuPanelWidgetQuickEvent.class.php',
			PATH_EXT_CALENDAR . DIR_SEP . $pathView . 'quickinfo-event.tmpl',
			PATH_EXT_CALENDAR . DIR_SEP . $pathView . 'quickinfo-holiday.tmpl',
			PATH_EXT_CALENDAR . DIR_SEP . $pathViewPanelwidgets . 'panelwidget-quickevent.tmpl',
			PATH_EXT_CALENDAR . DIR_SEP . $pathConfig . 'table.php',
			PATH_EXT_CONTACT . DIR_SEP . $pathAssetsCss . 'panelwidget-quickcontact.css',
			PATH_EXT_CONTACT . DIR_SEP . $pathAssetsJs . 'PanelWidgetQuickContact.js',
			PATH_EXT_CONTACT . DIR_SEP . $pathConfigForm . 'company-user.xml',
			PATH_EXT_CONTACT . DIR_SEP . $pathConfigForm . 'user-company.xml',
			PATH_EXT_CONTACT . DIR_SEP . $pathConfigFormAdmin . 'customerrole.xml',
			PATH_EXT_CONTACT . DIR_SEP . $pathLocale . 'panelwidget-quickcontact.xml',
			PATH_EXT_CONTACT . DIR_SEP . $pathModel . 'TodoyuPanelWidgetQuickContact.class.php',
			PATH_EXT_CONTACT . DIR_SEP . $pathView . 'info-user.tmpl',
			PATH_EXT_CONTACT . DIR_SEP . $pathView . 'panelwidget-quickcontact.tmpl',
			PATH_EXT_LOGINPAGE . DIR_SEP . $pathAssetsCss . 'panelwidget-loginhints.css',
			PATH_EXT_LOGINPAGE . DIR_SEP . $pathAssetsImg . 'panelwidget-loginhints.png',
			PATH_EXT_LOGINPAGE . DIR_SEP . $pathLocale . 'panelwidget-loginhints.xml',
			PATH_EXT_LOGINPAGE . DIR_SEP . $pathModel . 'TodoyuPanelWidgetLoginHints.class.php',
			PATH_EXT_LOGINPAGE . DIR_SEP . $pathView . 'panelwidget-loginhints.tmpl',
			PATH_EXT_PORTAL . DIR_SEP . $pathAssetsJs . 'Task.js',
			PATH_EXT_PORTAL . DIR_SEP . $pathConfig . 'rights.php',
			PATH_EXT_PORTAL . DIR_SEP . $pathController . 'TodoyuPortalTaskActionController.class.php',
			PATH_EXT_PORTAL . DIR_SEP . $pathView . 'panelwidget-quicktask.tmpl',
			PATH_EXT_PORTAL . DIR_SEP . $pathView . 'panelwidget-quicktaskform.tmpl',
			PATH_EXT_PORTAL . DIR_SEP . $pathView . 'task-header.tmpl',
			PATH_EXT_PORTAL . DIR_SEP . $pathView . 'tasklist-header.tmpl',
			PATH_EXT_PORTAL . DIR_SEP . $pathView . 'tasklist.tmpl',
			PATH_EXT_PROJECT . DIR_SEP . $pathAssetsCss . 'panelwidget-projecttree.css',
			PATH_EXT_PROJECT . DIR_SEP . $pathAssetsCss . 'panelwidget-quickproject.css',
			PATH_EXT_PROJECT . DIR_SEP . $pathAssetsJs . 'PanelWidgetProjectTree.js',
			PATH_EXT_PROJECT . DIR_SEP . $pathAssetsJs . 'PanelWidgetProjectTreeFilter.js',
			PATH_EXT_PROJECT . DIR_SEP . $pathAssetsJs . 'PanelWidgetQuickProject.js',
			PATH_EXT_PROJECT . DIR_SEP . $pathConfigForm . 'panelwidget-projecttree-filter.xml',
			PATH_EXT_PROJECT . DIR_SEP . $pathConfigForm . 'project-user.xml',
			PATH_EXT_PROJECT . DIR_SEP . $pathConfigFormAdmin . 'userrole.xml',
			PATH_EXT_PROJECT . DIR_SEP . $pathController . 'TodoyuProjectPanelwidgetprojecttreeActionController.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathController . 'TodoyuProjectProjectformActionController.class.php',
			PATH_EXT_PROJECT . DIR_SEP .  $pathController . 'TodoyuProjectSubtasksActionController.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathController . 'TodoyuProjectTabActionController.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathModel . 'TodoyuPanelWidgetProjectTree.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathModel . 'TodoyuPanelWidgetQuickProject.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathModel . 'TodoyuProjectDataSource.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathModel . 'TodoyuProjectSearchRenderer.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathModel . 'TodoyuTaskSearchRenderer.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathModel . 'TodoyuUserrole.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathModel . 'TodoyuUserroleManager.class.php',
			PATH_EXT_PROJECT . DIR_SEP . $pathView . 'formelement-projectusers.tmpl',
			PATH_EXT_PROJECT . DIR_SEP . $pathView . 'project-assignedusers.tmpl',
			PATH_EXT_PROJECT . DIR_SEP . $pathViewPanelwidgets . 'panelwidget-projecttree-filter.tmpl',
			PATH_EXT_PROJECT . DIR_SEP . $pathViewPanelwidgets . 'panelwidget-projecttree-tree.tmpl',
			PATH_EXT_SEARCH . DIR_SEP . $pathAssetsImg . 'bg_search_headerline.png',
			PATH_EXT_SEARCH . DIR_SEP . $pathView . 'filterwidgets' . DIR_SEP . 'filterwidget-projectrole.tmpl',
			PATH_EXT_SYSMANAGER . DIR_SEP . $pathView . 'groupselector.tmpl',
			PATH_EXT_SYSMANAGER . DIR_SEP . $pathView . 'rights-not-available.tmpl',
			PATH_EXT_SYSMANAGER . DIR_SEP . $pathView . 'rightseditor.tmpl',
			PATH_EXT_TIMETRACKING . DIR_SEP . $pathAssetsImg . 'bg_headlettimetracking.png',
			$pathInstall . $pathAssetsImg . 'bg_button.png',
			$pathInstall . $pathAssetsImg . 'bg_headerline.png',
			$pathInstall . $pathAssetsImg . 'col3_r13.png',
			$pathInstall . $pathAssetsImg . 'panel_footer.png',
			$pathInstall . $pathConfigDb . 'db_data.sql',
			$pathInstall . $pathConfigDb . 'db_structure.sql',
			$pathInstall . $pathConfigDb . 'update_beta1_to_beta2.sql',
			$pathInstall . $pathConfigDb . 'update_beta2_to_beta3.sql',
			$pathInstall . $pathConfigDb . 'update_beta3_to_rc1.sql',
		)
	);

?>