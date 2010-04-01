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
	$pathConfig		= 'config' . DIRECTORY_SEPARATOR;
	$pathConfigForm	= $pathConfig . 'form' . DIRECTORY_SEPARATOR;
	$pathConfigDb	= $pathConfig . 'db' . DIRECTORY_SEPARATOR;
	$pathConfigFormAdmin	= $pathConfigForm . 'admin' . DIRECTORY_SEPARATOR;
	$pathLocale		= 'locale' . DIRECTORY_SEPARATOR;
	$pathAssetsCss	= 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR;
	$pathAssetsImg	= 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
	$pathAssetsJs	= 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;
	$pathController	= 'controller' . DIRECTORY_SEPARATOR;
	$pathModel		= 'model' . DIRECTORY_SEPARATOR;
	$pathView		= 'view' . DIRECTORY_SEPARATOR;
	$pathViewPanelwidgets	= $pathView . 'panelwidgets' . DIRECTORY_SEPARATOR;
	$pathConfig		= 'config' . DIRECTORY_SEPARATOR;
	$pathInstall	= PATH . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR;

	Todoyu::$CONFIG['INSTALLER']['oldFiles']	= array(
			// Folders to be deleted with all their contents
		'deleteFolders'	=> array(
			PATH_EXT . DIRECTORY_SEPARATOR . 'dev',
			PATH_EXT . DIRECTORY_SEPARATOR . 'user',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'content',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'tabs',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'tree',
		),

		'deleteFolderContents'	=> array(
			PATH_CACHE . DIRECTORY_SEPARATOR . 'css',
			PATH_CACHE . DIRECTORY_SEPARATOR . 'img',
			PATH_CACHE . DIRECTORY_SEPARATOR . 'js',
			PATH_CACHE . DIRECTORY_SEPARATOR . 'language',
			PATH_CACHE . DIRECTORY_SEPARATOR . 'output',
			PATH_CACHE . DIRECTORY_SEPARATOR . 'tmpl',
		),

			// Files to be deleted
		'deleteFiles'	=> array(
			PATH_CORE . DIRECTORY_SEPARATOR . 'TodoyuDiv.class.php',
			PATH_CORE . DIRECTORY_SEPARATOR . 'TodoyuHeadletManager.class.php',
			PATH_CORE . DIRECTORY_SEPARATOR . 'TodoyuHeadletMetaMenu.class.php',
			PATH_CORE . DIRECTORY_SEPARATOR . 'TodoyuHeadletRenderer.class.php',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsCss . 'paging.css',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_button.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_button_small.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_footer.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_headerline.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_headers.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_metamenu_li.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_tab_header.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'body_bg.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'col1_r13.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'col3_bg_r13.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'col3_r13.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'colF_r13.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'dottedline_hor.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'dottedline_ver.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'duration_wizard.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'footer_bg.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'ico_toggle.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'ico_toggle_select.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'icons_dev.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'panel_footer.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'panel_header.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'shadow01.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'sprite-icons.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'sprite-notify.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'sprite-toggle.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'subNav_a_act_bg.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'subNav__bg.png',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathAssetsJs . 'Paging.js',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathView . 'headlet-ajaxloader.tmpl',
			PATH_CORE . DIRECTORY_SEPARATOR . $pathView . 'headlet-metamenu.tmpl',
			PATH_EXT_ADMIN . DIRECTORY_SEPARATOR . $pathConfig . 'rights.xml',
			PATH_EXT_ADMIN . DIRECTORY_SEPARATOR . $pathLocale . 'rights.xml',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathAssetsCss . 'panelwidget-quickevent.css',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathAssetsImg . 'icons_old.png',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathAssetsJs . 'PanelWidgetQuickEvent.js',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathAssetsJs . 'Quickinfo.js',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathConfigForm . 'event-user.xml',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathConfigForm . 'quickevent.xml',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathController . 'TodoyuCalendarQuickeventActionController.class.php',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuCalendarQuickinfo.class.php',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuPanelWidgetQuickEvent.class.php',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathView . 'quickinfo-event.tmpl',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathView . 'quickinfo-holiday.tmpl',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathViewPanelwidgets . 'panelwidget-quickevent.tmpl',
			PATH_EXT_CALENDAR . DIRECTORY_SEPARATOR . $pathConfig . 'table.php',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathAssetsCss . 'panelwidget-quickcontact.css',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathAssetsJs . 'PanelWidgetQuickContact.js',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathConfigForm . 'company-user.xml',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathConfigForm . 'user-company.xml',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathConfigFormAdmin . 'customerrole.xml',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathLocale . 'panelwidget-quickcontact.xml',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuPanelWidgetQuickContact.class.php',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathView . 'info-user.tmpl',
			PATH_EXT_CONTACT . DIRECTORY_SEPARATOR . $pathView . 'panelwidget-quickcontact.tmpl',
			PATH_EXT_LOGINPAGE . DIRECTORY_SEPARATOR . $pathAssetsCss . 'panelwidget-loginhints.css',
			PATH_EXT_LOGINPAGE . DIRECTORY_SEPARATOR . $pathAssetsImg . 'panelwidget-loginhints.png',
			PATH_EXT_LOGINPAGE . DIRECTORY_SEPARATOR . $pathLocale . 'panelwidget-loginhints.xml',
			PATH_EXT_LOGINPAGE . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuPanelWidgetLoginHints.class.php',
			PATH_EXT_LOGINPAGE . DIRECTORY_SEPARATOR . $pathView . 'panelwidget-loginhints.tmpl',
			PATH_EXT_PORTAL . DIRECTORY_SEPARATOR . $pathAssetsJs . 'Task.js',
			PATH_EXT_PORTAL . DIRECTORY_SEPARATOR . $pathConfig . 'rights.php',
			PATH_EXT_PORTAL . DIRECTORY_SEPARATOR . $pathController . 'TodoyuPortalTaskActionController.class.php',
			PATH_EXT_PORTAL . DIRECTORY_SEPARATOR . $pathView . 'panelwidget-quicktask.tmpl',
			PATH_EXT_PORTAL . DIRECTORY_SEPARATOR . $pathView . 'panelwidget-quicktaskform.tmpl',
			PATH_EXT_PORTAL . DIRECTORY_SEPARATOR . $pathView . 'task-header.tmpl',
			PATH_EXT_PORTAL . DIRECTORY_SEPARATOR . $pathView . 'tasklist-header.tmpl',
			PATH_EXT_PORTAL . DIRECTORY_SEPARATOR . $pathView . 'tasklist.tmpl',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathAssetsCss . 'panelwidget-projecttree.css',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathAssetsCss . 'panelwidget-quickproject.css',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathAssetsJs . 'PanelWidgetProjectTree.js',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathAssetsJs . 'PanelWidgetProjectTreeFilter.js',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathAssetsJs . 'PanelWidgetQuickProject.js',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathConfigForm . 'panelwidget-projecttree-filter.xml',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathConfigForm . 'project-user.xml',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathConfigFormAdmin . 'userrole.xml',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathController . 'TodoyuProjectPanelwidgetprojecttreeActionController.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathController . 'TodoyuProjectProjectformActionController.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR .  $pathController . 'TodoyuProjectSubtasksActionController.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathController . 'TodoyuProjectTabActionController.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuPanelWidgetProjectTree.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuPanelWidgetQuickProject.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuProjectDataSource.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuProjectSearchRenderer.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuTaskSearchRenderer.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuUserrole.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathModel . 'TodoyuUserroleManager.class.php',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathView . 'formelement-projectusers.tmpl',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathView . 'project-assignedusers.tmpl',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathViewPanelwidgets . 'panelwidget-projecttree-filter.tmpl',
			PATH_EXT_PROJECT . DIRECTORY_SEPARATOR . $pathViewPanelwidgets . 'panelwidget-projecttree-tree.tmpl',
			PATH_EXT_SEARCH . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_search_headerline.png',
			PATH_EXT_SEARCH . DIRECTORY_SEPARATOR . $pathView . 'filterwidgets' . DIRECTORY_SEPARATOR . 'filterwidget-projectrole.tmpl',
			PATH_EXT_SYSMANAGER . DIRECTORY_SEPARATOR . $pathView . 'groupselector.tmpl',
			PATH_EXT_SYSMANAGER . DIRECTORY_SEPARATOR . $pathView . 'rights-not-available.tmpl',
			PATH_EXT_SYSMANAGER . DIRECTORY_SEPARATOR . $pathView . 'rightseditor.tmpl',
			PATH_EXT_TIMETRACKING . DIRECTORY_SEPARATOR . $pathAssetsImg . 'bg_headlettimetracking.png',
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