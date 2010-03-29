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

/**
 * Dwoo plugin to restrict access to template parts
 *
 * @example		{restrict 'extkey' 'rightskey'}Restricted parts{else}Unrestricted{/restrict}
 *
 * @package		Todoyu
 * @subpackage	Template
 */
class Dwoo_Plugin_paging extends Dwoo_Block_Plugin implements Dwoo_ICompilable_Block {

	public static $cnt = 0;

	/**
	 * Initialize plugin
	 *
	 */
	public static function init($data, $item, $update, $size, $name, $offset, $total) {

	}



	/**
	 * Before processing the block content
	 *
	 * @param	Dwoo_Compiler	$compiler
	 * @param	Array			$params
	 * @param	String			$prepend		Unknown param
	 * @param	String			$append			Unknown param
	 * @param	String			$type			Unknown param
	 * @return	String
	 */
	public static function preProcessing(Dwoo_Compiler $compiler, array $params, $prepend, $append, $type) {
		return '';
	}



	/**
	 * After processing the block. Create compiled code for template which wraps the processed content
	 *
	 * @param	Dwoo_Compiler	$compiler
	 * @param	Array			$params
	 * @param	String			$prepend		Unknown param
	 * @param	String			$append			Unknown param
	 * @param	String			$type			Unknown param
	 * @return	String
	 */
	public static function postProcessing(Dwoo_Compiler $compiler, array $params, $prepend, $append, $content) {
		$params = $compiler->getCompiledParams($params);
		$name	= $params['name'];

		$cnt	= self::$cnt++;

			// Start pre PHP
		$pre	= Dwoo_Compiler::PHP_OPEN . "\n";

			// Get records data in temp variable
		$pre	.= '$_fh' . $cnt . '_data = ' . $params['data'] . ';' . "\n";

			// Define foreach config
		$pre 	.= '$this->globals["foreach"]['.$name.'] = array'."\n(";
		$pre 	.= "\t".'"index"		=> 0,' . "\n";
		$pre 	.= "\t".'"iteration"	=> 1,' . "\n";
		$pre 	.= "\t".'"first"		=> true,' . "\n";
		$pre 	.= "\t".'"last"		=> sizeof($_fh' . $cnt . '_data)===0,' . "\n";
		$pre 	.= "\t".'"show"		=> $this->isArray($_fh'.$cnt.'_data, true),' . "\n";
		$pre 	.= "\t".'"total"		=> $this->isArray($_fh'.$cnt.'_data) ? count($_fh'.$cnt.'_data) : 0,' . "\n";
		$pre 	.= "\t".'"paging"	=>  array(' . "\n";
		$pre 	.= "\t\t".'"rows"	=>  intval(' . $params['total'] . '),' . "\n";
		$pre 	.= "\t\t".'"pages"	=> ceil(intval(' . $params['total'] . ')/intval(' . $params['size'] . ')),' . "\n";
		$pre 	.= "\t\t".'"page"	=> intval(' . $params['offset'] . ') === 0 ? 1 : floor((intval(' . $params['offset'] . ')/intval(' . $params['size'] . ')))+1,' . "\n";
		$pre 	.= "\t\t".'"link"	=> str_replace(\'"\', \'\\\'\', \'Todoyu.Paging.update(' . $name . ', #OFFSET#)\')' . "\n";
		$pre	.= "\t)\n";
		$pre	.= ");\n";

		// intval($params['offset']) === 0

			// Make a shortcut to the config
		$pre	.= '$_fh'.$cnt.'_glob =& $this->globals["foreach"]['.$name.'];' . "\n";

			// Add foreach() function
		$pre	.= 'foreach($_fh' . $cnt . '_data as $this->scope[' . $params['item'] . ']){' . "\n";

			// Add iteration updates
		$pre 	.= "/* -- foreach start output */\n";
		$pre	.= "\n\t\t".'$_fh'.$cnt.'_glob["first"] = ($_fh'.$cnt.'_glob["index"] === 0);';
		$pre	.= "\n\t\t".'$_fh'.$cnt.'_glob["last"] = ($_fh'.$cnt.'_glob["iteration"] === $_fh'.$cnt.'_glob["total"]);';
		$pre	.= Dwoo_Compiler::PHP_CLOSE;


			// Start post PHP
		$post 	.= Dwoo_Compiler::PHP_OPEN . "\n";
			// Add iteration updates
		$post	.="\n\t\t".'$_fh'.$cnt.'_glob["index"]+=1;';
		$post	.="\n\t\t".'$_fh'.$cnt.'_glob["iteration"]+=1;';
			// End foreach() function
		$post 	.= "/* -- foreach end output */\n";
		$post 	.= "}";
		$post	.= Dwoo_Compiler::PHP_CLOSE;

			// Add javascript init
		$post	.= '<script>Todoyu.Paging.init(' . $name . ', ' . Dwoo_Compiler::PHP_OPEN . ' echo ' . ($params['offset']) . '; ' . Dwoo_Compiler::PHP_CLOSE . ', ' . trim($params['update']) . ');</script>';

		return $pre . $content . $post;
	}

}

?>