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
 * Language management for todoyu
 *
 * @deprecated
 * @package			Todoyu
 * @subpackage		Core
 */

class TodoyuLanguage {

	/**
	 * Backwards compatibility wrapper for TodoyuLanguage::register
	 * Please use TodoyuLabelManager::register now!
	 *
	 * @param	String		$fileKey			Filekey used as prefix of the labels
	 * @param	String		$absPathToFile		Absolute path to the locallang XML file
	 */
	public static function register($identifier, $absPathToFile) {
			// Extract extKey from path
		$absPathToFile	= TodoyuFileManager::pathAbsolute($absPathToFile);
		$parts			= explode(DIR_SEP . 'ext' . DIR_SEP, $absPathToFile);
		$parts			= explode(DIR_SEP, $parts[1]);
		$extKey			= $parts[0];

			// Extract file from path to file
		$file	= array_pop(explode(DIR_SEP, $absPathToFile));

		TodoyuLabelManager::register($identifier, $extKey, $file);
	}

}

?>