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
 * 
 */
abstract class TodoyuExportBase {



	/**
	 * @var array
	 */
	protected $exportData = array();



	/**
	 * @var string
	 */
	protected	$filename	= '';



	/**
	 * @param  $exportData
	 * @param array $customConfig
	 * @return void
	 */
	public function __construct(array $exportData, array $customConfig = array())	{
		$this->exportData	= $exportData;

		$this->init($customConfig);
	}



	/**
	 * @abstract
	 * @param  $customConfig
	 * @return void
	 */
	protected abstract function init(array $customConfig);



	/**
	 * setter method for filename
	 *
	 * @param  $filename
	 * @return void
	 */
	public function setFilename($filename)	{
		$this->filename = $filename;
	}



	/**
	 * @abstract
	 * @return void
	 */
	public abstract function getContent();



	/**
	 * Sends the file to download
	 *
	 * @return void
	 */
	public function download($type, $filename = '')	{
		header( 'Content-Type: ' . $type );
		header( 'Content-Disposition: attachment; filename=' . ($filename ? $filename : $this->filename));
		header( 'Content-Description: csv File' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		echo $this->getContent();

		exit();
	}
}

?>