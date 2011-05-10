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
 * Test for: TodoyuString
 *
 * @package		Todoyu
 * @subpackage	Core
 */
class TodoyuStringTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Array
	 */
	private $array;



	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

	}



	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}



	/**
	 * Test TodoyuString::isUTF8
	 */
	public function testIsUTF8() {
		$utf8		= 'スノーフレイクは、プレ';
		$ascii		= 'This is ASCII text';
		$iso8859_15	= 'Ich möchte Umlaute haben';

//		$this->assertTrue(TodoyuString::isUTF8($utf8));
//		$this->assertFalse(TodoyuString::isUTF8($ascii));
//		$this->assertFalse(TodoyuString::isUTF8($iso8859_15));



//		$utf8		= 'häöüllilu';
//		$nonUtf8	= 'Hallo';
//		$integer	= 6699;
//		$float		= 123.5;
//
//		$this->assertFalse(TodoyuString::isUTF8($nonUtf8));
//
//		$toUtf8	= utf8_encode($nonUtf8);
//		$this->assertTrue(TodoyuString::isUTF8($toUtf8));
//		$this->assertTrue($utf8);
//
//		$this->assertFalse(TodoyuString::isUTF8($integer) );
//		$this->assertFalse(TodoyuString::isUTF8($float) );
	}



	/**
	 * Test TodoyuString::convertToUTF8
	 *
	 * @todo Implement testConvertToUTF8().
	 */
	public function testConvertToUTF8() {
//		$string	= 'h��llilu';
//
//		$utf8	= TodoyuString::convertToUTF8($string);
//		$this->assertEquals(true, TodoyuString::isUTF8($utf8) );
	}



	/**
	 * Test TodoyuString::isValidEmail
	 *
	 * @todo Implement testIsValidEmail().
	 */
	public function testIsValidEmail() {
		$emailValid		= 'testmann@snowflake.ch';

		$invalid	= array(
			'test.@ann@snowflake.ch',
			'www.snowflake.ch',
			'http://www.snowflake.ch/',
			'h@ttp://www.snowflake.ch/',
			'@snowflake.ch',
			'www@snowflakech'
		);

		$this->assertEquals(true, TodoyuString::isValidEmail($emailValid));

		foreach($invalid as $emailInvalid) {
			$this->assertFalse(TodoyuString::isValidEmail($emailInvalid), 'email: ' . $emailInvalid );
		}
	}



	/**
	 * Test TodoyuString::crop
	 *
	 */
	public function testCrop() {
		$text	= 'Open source is a development method for software that harnesses the power of distributed peer review and transparency of process. The promise of open source is better quality, higher reliability, more flexibility, lower cost, and an end to predatory vendor lock-in.';
		$expect	= 'Open source is a:::';
		$cropped= TodoyuString::crop($text, 20, ':::');

		$this->assertEquals($expect, $cropped);

		$text	= 'スノーフレイクは、プレミアム・オープンソース・ソフトウェア（※）の実装（※）を手がける情報通信  ソリュー';
		$expect	= 'スノーフレイクは、プレミアム・オープンソ...';
		$cropped= TodoyuString::crop($text, 20);

		$this->assertEquals($expect, $cropped);
	}



	/**
	 * Test TodoyuString::wrap
	 *
	 */
	public function testWrap() {
		$text	= 'This is a Test';
		$wrap	= '<strong>|</strong>';
		$result	= TodoyuString::wrap($text, $wrap);
		$expect	= '<strong>This is a Test</strong>';

		$this->assertEquals($expect, $result);
	}



	/**
	 * Test TodoyuString::splitCamelCase
	 *
	 * @todo Implement testSplitCamelCase().
	 */
	public function testSplitCamelCase() {
		$camelCase	= 'TodoyuTaskAndProjectManagementSoftware';
		$parts		= TodoyuString::splitCamelCase($camelCase);

		$this->assertType('array', $parts);
		$this->assertEquals(6, sizeof($parts));
	}



	/**
	 * Test TodoyuString::html2text
	 *
	 * @todo Implement testHtml2text().
	 */
	public function testHtml2text() {
		$html_1		= '<strong>strong</strong>';
		$expect_1	= 'strong';
		$html_2		= 'line1<br>line2<br />line3';
		$expect_2	= "line1\nline2\nline3";
		$html_3		= '<ul><li>繁体字</li></ul>';
		$expect_3	= "繁体字";

		$result_1	= TodoyuString::html2text($html_1);
		$result_2	= TodoyuString::html2text($html_2, true);
		$result_3	= TodoyuString::html2text($html_3);

		$this->assertEquals($expect_1, $result_1);
		$this->assertEquals($expect_2, $result_2);
		$this->assertEquals($expect_3, $result_3);
	}



	/**
	 * Test TodoyuString::getSubstring
	 *
	 * @todo Implement testGetSubstring().
	 */
	public function testGetSubstring() {
		$text	= 'Open source is a development method for software that harnesses the power of distributed peer review and transparency of process. The promise of open source is better quality, higher reliability, more flexibility, lower cost, and an end to predatory vendor lock-in.';
		$sub	= TodoyuString::getSubstring($text, 'power');
		$expect	= 'that harnesses the power of distributed peer';

		$this->assertEquals($expect, $sub);
	}



	/**
	 * Test TodoyuString::generatePassword
	 *
	 * @todo Implement testGeneratePassword().
	 */
	public function testGeneratePassword() {
		$password	= TodoyuString::generatePassword();

		$this->assertEquals(8, strlen($password));

		$password	= TodoyuString::generatePassword(10, false, false);

		$this->assertEquals($password, strtolower($password));
		$this->assertNotRegExp('/\d/', $password);
	}



	/**
	 * Test TodoyuString::addToList
	 *
	 */
	public function testAddToList() {
		$list	= '1,2,3';

		$newList	= TodoyuString::addToList($list, 4);

		$this->assertEquals('1,2,3,4', $newList);
	}



	/**
	 * Test TodoyuString::isInList($item, $listString, $listSeparator = ',')
	 *
	 */
	public function testIsInList() {
		$list	= '3,test,345345.44,boarders,4,2,6,3';

		$this->assertTrue(TodoyuString::isInList(3, $list));
		$this->assertTrue(TodoyuString::isInList('test', $list));
		$this->assertTrue(TodoyuString::isInList(345345.44, $list));
		$this->assertFalse(TodoyuString::isInList(4444444, $list));
	}



	/**
	 * Test TodoyuString::formatSize($filesize, array $labels = null, $noLabel = false)
	 *
	 */
	public function testFormatSize() {
		$sizeB	= 756;			// 756 B
		$sizeKB	= 38242;		// 38.2 KB
		$sizeMB	= 34556789; 	// 34.5..MB
		$sizeGB	= 2560593443;	// 2.5 GB

		$expectB	= '756 B';
		$expectKB	= '37 KB';
		$expectMB	= '33 MB';
		$expectGB	= '2.4 GB';

		$formatB	= TodoyuString::formatSize($sizeB);
		$formatKB	= TodoyuString::formatSize($sizeKB);
		$formatMB	= TodoyuString::formatSize($sizeMB);
		$formatGB	= TodoyuString::formatSize($sizeGB);

		$this->assertEquals($expectB, $formatB);
		$this->assertEquals($expectKB, $formatKB);
		$this->assertEquals($expectMB, $formatMB);
		$this->assertEquals($expectGB, $formatGB);


		$noLabel	= TodoyuString::formatSize($sizeB, null, true);
		$expectNoL	= '756';

		$this->assertEquals($expectNoL, $noLabel);
	}

	public function testbr2nl() {
		$text	= 'this<br>string<br />contains<br >html<br/>linebreaks';
		$expect	= "this\nstring\ncontains\nhtml\nlinebreaks";
		$result	= TodoyuString::br2nl($text);

		$this->assertEquals($expect, $result);
	}


	public function testListUnique() {
		$list	= '1,2,3,3,4,5,1,2,3';
		$expect	= '1,2,3,4,5';
		$result	= TodoyuString::listUnique($list, ',');

		$this->assertEquals($expect, $result);
	}


	public function testWrapscript() {
		$script	= 'var x = 44;';
		$expect	= '<script language="javascript" type="text/javascript">' . $script . '</script>';
		$result	= TodoyuString::wrapscript($script);

		$this->assertEquals($expect, $result);
	}

	public function testMd5short() {
		$text	= 'this text will be hashed';
		$expect	= substr(md5($text), 0, 10);
		$result	= TodoyuString::md5short($text);

		$this->assertEquals($expect, $result);
	}


	public function testTrimExplode() {
		$text	= 'hello,   world,this  ,  text, will,be        ,trimmed';
		$expect	= array('hello', 'world', 'this', 'text', 'will', 'be', 'trimmed');
		$result	= TodoyuString::trimExplode(',', $text);

		$this->assertEquals($expect, $result);
	}


	public function testToPhpCodeString() {
		$var1	= 'already a string';
		$expect1= "'already a string'";
		$result1= TodoyuString::toPhpCodeString($var1);

		$var2	= 12345;
		$expect2= '12345';
		$result2= TodoyuString::toPhpCodeString($var2);

		$var3	= 123.45;
		$expect3= '123.45';
		$result3= TodoyuString::toPhpCodeString($var3);

		$var4	= array(1,2,3);
		$expect4= 'array(0=>1,1=>2,2=>3)';
		$result4= TodoyuString::toPhpCodeString($var4);

		$var5	= array('a' => 1, 'b' => 'test', 3 => 'xxx');
		$expect5= 'array(\'a\'=>1,\'b\'=>\'test\',3=>\'xxx\')';
		$result5= TodoyuString::toPhpCodeString($var5);

		$var5	= new stdClass();
		$var5->member	= 'tes\'t';
		$expect5= 'unserialize(stripslashes(\'O:8:\"stdClass\":1:{s:6:\"member\";s:5:\"tes\\\'t\";}\'))';
		$result5= TodoyuString::toPhpCodeString($var5);

		$this->assertEquals($expect1, $result1);
		$this->assertEquals($expect2, $result2);
		$this->assertEquals($expect3, $result3);
		$this->assertEquals($expect4, $result4);
		$this->assertEquals($expect5, $result5);
	}


	public function testBuildUrl() {
		$params	= array(
			'a'	=> 'alpha',
			'b'	=> 'beta',
			'g'	=> 'gamma'
		);
		$hash	= 'task-123';

		$result1	= TodoyuString::buildUrl($params, $hash);
		$result2	= TodoyuString::buildUrl($params, $hash, true);

		$expect1	= PATH_WEB . '/index.php?a=alpha&b=beta&g=gamma#task-123';
		$expect2	= SERVER_URL . PATH_WEB . '/index.php?a=alpha&b=beta&g=gamma#task-123';

		$this->assertEquals($expect1, $result1);
		$this->assertEquals($expect2, $result2);
	}

	public function testGetImgTag() {
		$src	= 'assets/test.png';
		$width	= 300;
		$height	= 200;
		$alt	= 'Alternative text';

		$expect	= '<img src="' . $src . '" width="' . $width . '" height="' . $height . '" alt="' . $alt . '" />';
		$result	= TodoyuString::getImgTag($src, $width, $height, $alt);

		$this->assertEquals($expect, $result);
	}


	public function testGetATag() {
		$url	= 'unit/test.html';
		$label	= 'Link Text';
		$expect	= '<a href="' . $url . '" target="_blank">' . $label . '</a>';
		$result	= TodoyuString::getATag($url, $label);

		$this->assertEquals($expect, $result);
	}


	public function testGetMailtoTag() {
		$email	= 'team@todoyu.com';
		$label	= 'Send message to todoyu team';
		$subject= 'Mail Subject';
		$content= 'Hello, I am a mail body';
		$cc		= 'sales@todoyu.com';

		$expect	= '<a href="mailto:' . $email . '?subject=' . urlencode($subject) . '&body=' . urlencode($content) . '&cc=' . $cc . '">' . $label . '</a>';
		$result	= TodoyuString::getMailtoTag($email, $label, false, $subject, $content, $cc);

		$this->assertEquals($expect, $result);
	}


	public function testBuildHtmlTag() {
		$tag	= 'a';
		$params	= array(
			'href'		=> 'test/url.html',
			'onclick'	=> 'doSomething()'
		);
		$content	= 'Click me';
		$expect	= '<a href="test/url.html" onclick="doSomething()">Click me</a>';
		$result	= TodoyuString::buildHtmlTag($tag, $params, $content);

		$this->assertEquals($expect, $result);
	}


	public function testCleanRteText() {
		$text1	= '<p>&nbsp;</p><p>Second paragraph</p>';
		$expect1= '<p>Second paragraph</p>';
		$result1= TodoyuString::cleanRTEText($text1);

		$text2	= "<pre>Preformatted text\nwith linebreaks and whitespaces                    </pre>";
		$expect2= 'Preformatted text<br />with linebreaks and whitespaces';
		$result2= TodoyuString::cleanRTEText($text2);

		$this->assertEquals($expect1, $result1);
		$this->assertEquals($expect2, $result2);
	}


	public function testExtractHttpHeaders() {
		$responseContent = "200\r\n"
						. "Date: Fri, 18 Mar 2011 16:07:16 GMT\r\n"
						. "Server: Apache/2.2.14 (Win32) DAV/2 mod_ssl/2.2.14 OpenSSL/0.9.8l mod_autoindex_color PHP/5.3.1 mod_apreq2-20090110/2.7.1 mod_perl/2.0.4 Perl/v5.10.1"
						. "\r\n\r\n"
						. "Response Content";

		$headers	= TodoyuString::extractHttpHeaders($responseContent);

		$this->assertEquals(200, $headers['status']);
		$this->assertEquals('Fri, 18 Mar 2011 16:07:16 GMT', $headers['Date']);
		$this->assertEquals('Apache/2.2.14 (Win32) DAV/2 mod_ssl/2.2.14 OpenSSL/0.9.8l mod_autoindex_color PHP/5.3.1 mod_apreq2-20090110/2.7.1 mod_perl/2.0.4 Perl/v5.10.1', $headers['Server']);
	}


	public function testExtractHeadersFromString() {
		$headerContent = "200\r\n"
						. "Date: Fri, 18 Mar 2011 16:07:16 GMT\r\n"
						. "Server: Apache/2.2.14 (Win32) DAV/2 mod_ssl/2.2.14 OpenSSL/0.9.8l mod_autoindex_color PHP/5.3.1 mod_apreq2-20090110/2.7.1 mod_perl/2.0.4 Perl/v5.10.1";

		$headers	= TodoyuString::extractHttpHeaders($headerContent);

		$this->assertEquals(200, $headers['status']);
		$this->assertEquals('Fri, 18 Mar 2011 16:07:16 GMT', $headers['Date']);
		$this->assertEquals('Apache/2.2.14 (Win32) DAV/2 mod_ssl/2.2.14 OpenSSL/0.9.8l mod_autoindex_color PHP/5.3.1 mod_apreq2-20090110/2.7.1 mod_perl/2.0.4 Perl/v5.10.1', $headers['Server']);
	}


	public function testgetversioninfo() {
		$version	= '2.3.43-alpha';
		$result		= TodoyuString::getVersionInfo($version);

		$this->assertEquals(2, $result['major']);
		$this->assertEquals(3, $result['minor']);
		$this->assertEquals(43, $result['revision']);
		$this->assertEquals('alpha', $result['status']);
	}



	public function testReplaceUrlWithLink() {
		$text	= 'This is plaintext with www.todoyu.com links in it. http://www.snowflake.ch You can also mail: team@todoyu.com';
		$expect	= 'This is plaintext with <a href="http://www.todoyu.com" target="_blank">www.todoyu.com</a> links in it. <a href="http://www.snowflake.ch" target="_blank">http://www.snowflake.ch</a> You can also mail: <a href="mailto:team@todoyu.com">team@todoyu.com</a>';

		$result	= TodoyuString::replaceUrlWithLink($text);

		$this->assertEquals($expect, $result);
	}


	public function testGetRangeString() {
		Todoyu::setLocale('en_GB');

			// 2 hours
		$dateStart1	= mktime(14, 0, 0, 1, 1, 2011);
		$dateEnd1	= mktime(16, 0, 0, 1, 1, 2011);
		$expect1	= '01/01/2011, 14:00 - 16:00 (02:00 Hours)';
		$result1	= TodoyuString::getRangeString($dateStart1, $dateEnd1);

		$this->assertEquals($expect1, $result1);

			// 2 days
		$dateStart2	= mktime(14, 0, 0, 1, 1, 2011);
		$dateEnd2	= mktime(16, 0, 0, 1, 2, 2011);
		$expect2	= '01/01/11 14:00 - 01/02/11 16:00 (2 Days)';
		$result2	= TodoyuString::getRangeString($dateStart2, $dateEnd2);

		$this->assertEquals($expect2, $result2);

			// 30 minutes
		$dateStart3	= mktime(14, 0, 0, 1, 1, 2011);
		$dateEnd3	= mktime(14, 30, 0, 1, 1, 2011);
		$expect3	= '01/01/2011, 14:00 - 14:30 (30 Minutes)';
		$result3	= TodoyuString::getRangeString($dateStart3, $dateEnd3);

		$this->assertEquals($expect3, $result3);
	}


	public function testExtracthttpstatuscode() {
		$test200	= 'HTTP/1.1 200 OK';
		$test404	= 'HTTP/1.1 404 Not Found';

		$result200	= TodoyuString::extractHttpStatusCode($test200);
		$result404	= TodoyuString::extractHttpStatusCode($test404);

		$this->assertEquals(200, $result200);
		$this->assertEquals(404, $result404);
	}

	public function testenableJsFunctionInJSON() {
		$array	= array(
			'func' => 'function(arg){return arg;}'
		);
		$json		= json_encode($array);
		$enabled	= TodoyuString::enableJsFunctionInJSON($json);
		$expected	= '{"func":function(arg){return arg;}}';

		$this->assertEquals($expected, $enabled);
	}


	public function testwrapwithtag() {
		$result	= TodoyuString::wrapWithTag('strong', 'Bold');
		$expect	= '<strong>Bold</strong>';

		$this->assertEquals($result, $expect);
	}


	public function testwraptodoyulink() {
		$result1	= TodoyuString::wrapTodoyuLink('Link', 'project', array('controller'=>'test'));
		$expect1	= '<a href="/todoyu_trunk/index.php?controller=test&ext=project">Link</a>';

		$this->assertEquals($expect1, $result1);

		$result2	= TodoyuString::wrapTodoyuLink('Link', 'project', array('controller'=>'test','action'=>'foo'), 'myHash', '_blank');
		$expect2	= '<a href="/todoyu_trunk/index.php?controller=test&action=foo&ext=project#myHash" target="_blank">Link</a>';

		$this->assertEquals($expect2, $result2);
	}





}

?>