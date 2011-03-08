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
 * Panelwidget interface
 *
 * @package		Todoyu
 * @subpackage	Core
 */
interface TodoyuPanelWidgetIf {

	/**
	 * Constructor
	 *
	 * @param	Array	$config
	 * @param	Array	$params
	 * @param 	Integer	$idArea
	 */
	public function __construct(array $config, array $params = array(), $idArea = 0);



	/**
	 * Render panel widget
	 */
	public function render();



	/**
	 * Render panel widget content
	 */
	public function renderContent();

}

?>