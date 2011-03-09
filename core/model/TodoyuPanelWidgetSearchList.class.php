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
 * [Enter Class Description]
 *
 * @package		Todoyu
 * @subpackage	Core
 */
abstract class TodoyuPanelWidgetSearchList extends TodoyuPanelWidget {

	protected $jsObject;


	public function renderContent($listOnly = false) {
		$this->addClass('searchList');

		$tmpl	= $this->getTemplate();
		$data	= $this->getTemplateData();

		$data['listOnly']	= $listOnly;

		if( ! TodoyuRequest::isAjaxRequest() ) {
			TodoyuPage::addJsOnloadedFunction('function(){new ' . $this->jsObject . '(\'' . htmlentities($this->getSearchText()) . '\');}');
		}

		return render($tmpl, $data);
	}


	public function renderList() {
		return $this->renderContent(true);
	}


	protected function getTemplate() {
		return 'core/view/panelwidget-searchlist.tmpl';
	}


	protected function getTemplateData() {
		$data = array(
			'searchForm'=> $this->renderSearchForm(),
			'items'		=> $this->getItems(),
			'id'		=> $this->getID()
		);

		return $data;
	}



	/**
	 * Render filter form
	 *
	 * @return	String
	 */
	protected function renderSearchForm() {
		$xmlPath= 'core/config/form/panelwidget-searchlist.xml';
		$form	= TodoyuFormManager::getForm($xmlPath);
		$data	= array(
			'search'	=> $this->getSearchText()
		);

		$form->setName($this->getID());
		$form->setFormData($data);
		$form->setUseRecordID(false);
		$form->setAttribute('class', 'searchList');

		return $form->render();
	}


	protected function setJsObject($jsObject) {
		$this->jsObject = $jsObject;
	}


	protected function getExtID() {
		return TodoyuExtensions::getExtID($this->get('ext'));
	}

	public function saveSearchText($search) {
		$pref	= 'panelwidgetsearchlist-' . $this->getID() . '-search';
		$search	= trim($search);

		return TodoyuPreferenceManager::savePreference($this->getExtID(), $pref, $search, 0, true);
	}


	public function getSearchText() {
		$pref	= 'panelwidgetsearchlist-' . $this->getID() . '-search';

		return trim(TodoyuPreferenceManager::getPreference($this->getExtID(), $pref));
	}

	protected abstract function getItems();

}

?>