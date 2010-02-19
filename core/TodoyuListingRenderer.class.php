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
 * Listing renderer
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuListingRenderer {

	/**
	 * Render listing for configuration
	 *
	 * @param	String		$ext
	 * @param	String		$name
	 * @param	Integer		$offset
	 * @param	String		$searchWord
	 * @return	String
	 */
	public static function render($ext, $name, $offset = 0, $searchWord = '') {
		$config		= TodoyuListingManager::getConfig($ext, $name);
		$offset		= intval($offset);
		$size		= intval($config['size']);
		$searchWord	= trim($searchWord);

			// Get default size if not set
		if( $size === 0 ) {
			$size = $GLOBALS['CONFIG']['LIST']['size'];
		}

			// Disable paging if searching
		if( $searchWord !== '' ) {
			$size	= 100;
			$offset	= 0;
		}

		$listData	= TodoyuDiv::callUserFunction($config['dataFunc'], $size, $offset, $searchWord);
		$totalRows	= intval($listData['total']);

		$tmpl	= 'core/view/listing.tmpl';
		$data	= array(
			'ext'		=> $ext,
			'name'		=> $name,
			'config'	=> $config,
			'rows'		=> $listData['rows'],
			'offset'	=> $offset,
			'total'		=> $totalRows,
			'size'		=> $size,
			'page'		=> $offset === 0 ? 1 : ($offset / $size) + 1,
			'pages'		=> ceil($totalRows / $size),
			'noPaging'	=> $searchWord !== '',
			'nextPos'	=> $offset + $size,
		);

		return render($tmpl, $data);
	}

}

?>