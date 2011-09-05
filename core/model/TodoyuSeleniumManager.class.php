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
 * [Add class description]
 *
 * @package		Todoyu
 * @subpackage	[Subpackage]
 */
class TodoyuSeleniumManager {

	private static $pathMasterFile = 'cache/cli/selenium.html';

	public static function mergeAllTests() {
		$allTests	= self::getAllTests();

		self::writeMasterTestFile($allTests);
	}


	private static function getAllTests() {
		$extKeys	= TodoyuExtensions::getInstalledExtKeys();
		$tests		= array();

		$tests['core']	= self::getCoreTests();

		foreach($extKeys as $extKey) {
			$tests[$extKey] = self::getExtensionTests($extKey);
		}

		return $tests;
	}


	private static function writeMasterTestFile($allTests) {
		$tmpl	= 'core/view/selenium-master.tmpl';
		$data	= array(
			'allTests'	=> $allTests
		);
		$content= Todoyu::render($tmpl, $data);

		TodoyuFileManager::saveFileContent(self::$pathMasterFile, $content);
	}

	private static function getExtensionTests($extKey) {
		$testSuite	= TodoyuExtensions::getExtPath($extKey, 'test/selenium/index.html');

		return self::getTestsFromSuiteFile($testSuite);
	}


	private static function getCoreTests() {
		$testSuite	= 'core/test/selenium/index.html';

		return self::getTestsFromSuiteFile($testSuite);
	}

	private static function getTestsFromSuiteFile($pathSuiteFile) {
		$pathSuiteFile	= TodoyuFileManager::pathAbsolute($pathSuiteFile);
		$tests			= array();
		$basePath		= dirname($pathSuiteFile);

		if( is_file($pathSuiteFile) ) {
			$content	= file_get_contents($pathSuiteFile);
			$tests		= self::extractTests($content);
		}

		foreach($tests as $index => $test) {
			$testPath	= TodoyuFileManager::pathWeb(realpath($basePath . DIR_SEP . $test['path']));

			if( is_file($testPath) ) {
				$tests[$index]['path'] = $testPath;
			} else {
				unset($tests[$index]);
			}
		}

		return $tests;
	}


	private static function extractTests($testSuiteHtml) {
		$pattern	= '/<tr><td><a href="([^"]*)">(.*?)<\/a><\/td><\/tr>/';
		$tests		= array();

		preg_match_all($pattern, $testSuiteHtml, $matches);

		foreach($matches[1] as $index => $testPath) {
			$tests[] = array(
				'path'	=> $testPath,
				'name'	=> $matches[2][$index]
			);
		}

		return $tests;
	}
}

?>