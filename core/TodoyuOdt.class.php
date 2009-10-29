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
 * This class handles odt files
 *
 */
class TodoyuOdt {



	/**
	 * Cache folder chache/odt/<given_ext_name>
	 *
	 * @var	String
	 */
	protected $tmpDir;



	/**
	 * Path of the temporary unzipped odt data
	 *
	 * @var	String
	 */
	protected $tmpOdtDir;



	/**
	 * Temporary generated odt-file
	 *
	 * @var	String
	 */
	protected $tmpOdtFile;



	/**
	 * Given extension name
	 *
	 * @var	String
	 */
	protected $ext;



	/**
	 * Constructor of the class
	 *
	 * @param	String	$ext
	 * @param	String	$newFileName
	 */
	public function __construct($ext = 'unknown', $newFileName = 'unknown.odt')	{
		$this->tmpDir		= PATH_CACHE.'/odt';
		$this->ext			= $ext;
		$this->newFileName 	= $newFileName;

		$this->init();
	}



	/**
	 * Initializes the class
	 *
	 */
	private function init()	{
		if(!is_dir($this->tmpDir))	{
			mkdir($this->tmpDir);
		}

		$this->exec('chmod -R 777 '.PATH_CACHE.'/odt/');

		$this->tmpDir	.=	'/'.$this->ext;

		if(!is_dir($this->tmpDir))	{
			mkdir($this->tmpDir);
		}
	}



	/**
	 * Opens the given template file.
	 * creates the unzipped folder in the cache of not exists
	 *
	 * @throws	Exception
	 *
	 * @param	String		$templateFile
	 */
	public function openFromTemplate($templateFile)	{
		if(! is_file($templateFile))	{
			throw new Exception('Templatefile '.$templateFile.' not found!');
		}

		$unzippedDir = $this->tmpDir.'/'.$this->getTemplateFileName($templateFile).'-unzipped';

		if(! is_dir($unzippedDir)	)	{
			mkdir($unzippedDir);
			$this->extractFromZip($unzippedDir, $templateFile);
		}

		if(! $this->tmpOdtDir)	$this->tmpOdtDir = $this->tmpDir.'/'.substr(md5(mt_rand().microtime()), 0, 15);

		$command = 'cp -R \''.$unzippedDir.'\' \''.$this->tmpOdtDir.'\'';
		$this->exec($command);
	}


	/**
	 *	Replaces markers from markersarray
	 * 	-> arraykey		=> markername
	 * 	-> arrayvalue	=> markervalue
	 *
	 *	@param	Array	$markersArray
	 */
	public function	replaceMarkers(array $markersArray)	{
		$content = file_get_contents($this->tmpOdtDir.'/content.xml');

		foreach($markersArray as $markerName => $valueToReplace)	{
			$value = nl2br($value);
			$value = preg_replace('/[\n\s]*\<br \/\>[\n\s]*/',']]><text:line-break/><![CDATA[', trim($value));
			$value = str_replace('&nbsp;',' ',$value);

			$content = str_replace('<text:placeholder text:placeholder-type="text">&lt;'.$markerName.'&gt;</text:placeholder>','<![CDATA['.$value.']]>',$content);
		}

		file_put_contents($this->tmpOdtDir.'/content.xml', $content);
	}



	/**
	 * Downloads the current odt-file
	 *
	 */
	public function download()	{
		header('Content-Type: application/vnd.oasis.opendocument.text');
		header('Content-Disposition: attachment; filename='. $this->newFileName);
		header('Pragma: no-cache');
		header('Expires: 0');

		echo $this->getContent();

		$this->close();

		exit();
	}


	/**
	 * Returns the file name of the template file
	 *
	 * @param	String	$tmplFile
	 * @return	String
	 */
	protected function getTemplateFileName($tmplFile)	{
		$tmplFileArr = explode('/', $tmplFile);
		return array_pop($tmplFileArr);
	}



	/**
	 * Creates the temporary file and compresses the odt-folder again
	 *
	 * @param	String	$odtPath
	 */
	protected function write($odtPath=null) {
		if(! $this->tmpOdtFile) $this->tmpOdtFile = mb_substr($this->tmpOdtDir, 0, -1).'.odt';

		$this->compressToZip($this->tmpOdtFile, $this->tmpOdtDir);

		if($odtPath) {
			$command = 'cp '.$this->tmpOdtFile.' '.$odtPath;
			$this->exec($command);
		}
	}



	/**
	 * Returns the content of the generated odt-file
	 *
	 * @return	String
	 */
	protected function getContent()	{
		if(! $this->tmpOdtFile)	{
			$this->write();
		}

		return file_get_contents($this->tmpOdtFile);
	}



	/**
	 * removes the temporary added folders and files
	 *
	 */
	protected function close()	{
		$command = 'rm -R ' . $this->tmpOdtDir;
		$this->exec($command);
		$command = 'rm '. $this->tmpOdtFile;
		$this->exec($command);
	}



	/**
	 *
	 * Compresses files from source to destination
	 *
	 *
	 * @param	String	$destination
	 * @param	String	$source
	 */
	protected function compressToZip($destination, $source)	{
		if(is_dir($source))	{
			$command = 'cd \''. $source . '\' && zip -r \''. $destination . '\' *';
			$this->exec($command);
		}
	}



	/**
	 * Extreacts source to destination-folder
	 *
	 * @param	String	$destination
	 * @param	String	$source
	 */
	protected function extractFromZip($destination, $source)	{
		if(is_file($source) && is_dir($destination))	{
			$command = 'unzip \'' . $source . '\' -d \'' . $destination. '\'';
			$this->exec($command);
		}
	}


	/**
	 * Proceeds the given command
	 *
	 * @param	String	$command
	 */
	protected function exec($command)	{
		exec($command);
	}
}
?>