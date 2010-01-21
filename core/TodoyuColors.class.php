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
 * Colors
 *
 * @package		Todoyu
 * @subpackage	Colors
 * @see 		config\colors.php
 */
class TodoyuColors {

	/**
	 * Generate css and img file for user colors
	 *
	 */
	public static function generate() {
		$fileCSS	= PATH_CACHE . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'colors.css';
		$fileIMG	= PATH_CACHE . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'colors.png';

			// Generate css file if it not exists
		if( ! is_file($fileCSS) ) {
			self::generateCSS($fileCSS);
		}

			// Generate png file if it not exists
		if( ! is_file($fileIMG) ) {
			self::generateIMG($fileIMG);
		}

		TodoyuPage::addStylesheet($fileCSS, 'all', 1, false, false);
	}



	/**
	 * Render Color CSS. If not stored up-to-date yet: save and have CSS-sprite be generated as well
	 *
	 */
	private static function generateCSS($fileCSS) {
			// Get configured colors
		$colors	= TodoyuArray::assure($GLOBALS['CONFIG']['COLORS']);
			// Render CSS file content
		$css	= '/* colors.css - Enumerated colors to be used for visual differenciation of elements */' . "\n";

		foreach($colors as $num => $rgb) {
			$inverse	= self::invert($rgb);
			$fade		= self::fade($rgb, 65);

			$css	.= '.enumColBG' . $num . ' { background-color:' . $rgb . ' !important; }' . "\n";
			$css	.= '.enumColBGFade' . $num . ' { background-color:' . $fade . ' !important; }' . "\n";
			$css	.= '.enumColFont' . $num . ' { color:' . $rgb . ' !important; }' . "\n";
			$css	.= '.enumColFontFade' . $num . ' { color:' . $fade . ' !important; }' . "\n";
			$css	.= '.enumColBgFg' . $num . ' { background-color:' . $rgb . ' !important; color:' . $inverse . ' !important; }' . "\n";
			$css	.= '.enumColFgBg' . $num . ' { background-color:' . $inverse . ' !important; color:' . $rgb . ' !important; }' . "\n";
			$css	.= '.enumColFgBg' . $num . ' { background-color:' . $inverse . ' !important; color:' . $rgb . ' !important; }' . "\n";
			$css	.= '.enumColBor' . $num . ' { border-color:' . $rgb . ' !important; }' . "\n";
			$css	.= '.enumColBorFade' . $num . ' { border-color:' . $fade . ' !important; }' . "\n";
			$css	.= '.enumColBorLef' . $num . ' { border-left-color:' . $rgb . ' !important; }' . "\n";
			$css	.= '.enumColBorRig' . $num . ' { border-right-color:' . $rgb . ' !important; }' . "\n";
			$css	.= '.enumColBorTop' . $num . ' { border-top-color:' . $rgb . ' !important; }' . "\n";
			$css	.= '.enumColBorBot' . $num . ' { border-bottom-color:' . $rgb . ' !important; }' . "\n";
			$css	.= 'option.enumColOptionLeftIcon' . $num . ' { background:url(\'../img/' . basename($fileIMG) . '\') no-repeat -8px -' . ($num * 16) . 'px !important; padding:0 0 0 12px; }' . "\n";
		}

			// Save CSS file
		TodoyuFileManager::makeDirDeep(dirname($fileCSS));
		file_put_contents($fileCSS, $css);

		$pathWeb	= TodoyuFileManager::pathWeb($fileCSS);

			// Register 'colors.css' to page
		TodoyuPage::addStylesheet($pathWeb, 'all', 200, false, false);
	}



	/**
	 * Render CSS sprite of colors declared in $GLOBALS['CONFIG']['COLORS'], 16 x 16 each
	 *
	 */
	private static function generateIMG($fileIMG) {
		$colors	= TodoyuArray::assure($GLOBALS['CONFIG']['COLORS']);
		$img	= imagecreate(16, sizeof($colors)*16);

		foreach($colors as $num => $rgb) {
			$red	= hexdec( substr($rgb, 1, 2) );
			$green	= hexdec( substr($rgb, 3, 2) );
			$blue	= hexdec( substr($rgb, 5, 2) );

			$color = ImageColorAllocate($img, $red, $green, $blue);

			imagefilledrectangle($img, 0, $num * 16, 16, $num * 16 + 16, $color);
		}

		imagepng($img, $fileIMG);
	}



	/**
	 * Generates the faded color of an original
	 *
	 * @param      String      $color          Hexadecimal color value
	 * @param      Integer     $percentage     Percentage to fade
	 * @return     String      New hexadecimal color value
	 *
	 * @author	Thomas Imboden <timboden@snowflake.ch>
	 */
	private static function fade($color, $percentage) {
		$percentage = 100 - $percentage;
		$rgbValues = array_map( 'hexDec', str_split( ltrim($color, '#'), 2 ) );

		for ($i = 0, $len = count($rgbValues); $i < $len; $i++) {
			$rgbValues[$i] = decHex( floor($rgbValues[$i] + (255 - $rgbValues[$i]) * ($percentage / 100) ) );
		}

		return '#' . implode('', $rgbValues);
	}



	/**
	 * Calculate complementary color to given RBG color
	 *
	 * @param	String	$color
	 * @return	String
	 *
	 */
	private static function invert($color) {
		$color = str_replace('#', '', $color);
	    if (strlen($color) != 6){
	    		return '#000000';
	   	}

	    $rgb = '';
	    for ($x = 0; $x < 3; $x++) {
	        $c = 255 - hexdec( substr($color, (2 * $x), 2) );
	        $c = ($c < 0) ? 0 : dechex( $c );
	        $rgb .= ( strlen($c) < 2 ) ? '0' . $c : $c;
	    }

	    return '#' . $rgb;
	}



	/**
	 * Checks brightness of a color and returns either black or white
	 *
	 * @param 	String		$color: Color in hex
	 * @return	String
	 * @author	Thomas Imboden <thimboden@snowflake.ch>
	 */
	private static function getBestReadableContrastTextColor($color) {
		$color 	= trim( str_replace('#', '', $color) );

		$c_r	= hexdec(substr($color, 0, 2));
		$c_g	= hexdec(substr($color, 2, 2));
		$c_b	= hexdec(substr($color, 4, 2));

		$brightnessLevel	=  (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;

		return $brightnessLevel < 110 ? '#FFFFFF' : '#000000';
	}



	/**
	 * Returns color array by given color id
	 *
	 * @param	Integer	$idColor
	 * @return	Array
	 */
	public static function getColorArray($idColor)	{
		$idColor	= intval($idColor);

		$idColor	= self::getColorID( $idColor );
		$rgb		= self::getColorRGB( $idColor );

		$color = array(
			'id'		=> $idColor,
			'border'	=> $rgb,
			'text'		=> self::getBestReadableContrastTextColor( $rgb ),
			'faded'		=> self::fade($rgb, 65),
		);

		return $color;
	}



	/**
	 * Returns the id of a color by its position in the config array
	 *
	 * @param	Integer	$position
	 * @return	Integer
	 */
	private static function getColorRGB($position)	{
		$position = intval($position);

		$rgb	= $GLOBALS['CONFIG']['COLORS'][	$position ];

		return $rgb;
	}



	/**
	 * gets id of color
	 *
	 * @param	Integer	$position
	 * @return	Integer
	 */
	private static function getColorID($position)	{
		$position = intval($position);

		$numOfColors = count($GLOBALS['CONFIG']['COLORS']);

		if( $position > $numOfColors-1 )	{
			$position = $position - ($position - ($position % ($numOfColors)));
		}

		return $position;
	}
}

?>