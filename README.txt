***************************************************************
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
***************************************************************

Contents
--------
1. Beta disclaimer
2. How to install todoyu beta2
3. How to upgrade from beta1 of todoyu to beta2 with the automatic database upgrader


1. Beta disclaimer
------------------
This is an beta version. Not for productive use!
Most of the bugs should be fixed, be we still need a lot of testing.
You're welcome to take a look, but you should not rely on any function,
classname or behaviour at this moment!


2. How to install todoyu beta2
------------------------------
* Copy all the files of the beta2 package to your webserver
* When loading todoyu in the browser, the todoyu installer will start automatically
* Follow the instructions of the installer


3. How to upgrade an existing beta1 installation of todoyu to beta2
------------------------------------------------------------------
* Remove the following beta2 files to keep your stored configuration:
	* "config/db.php"
	* "config/system.php"
* Delete all files and folders inside the "cache/" folder
* Overwrite all files of your beta1 installation with the beta2 files
* Make sure the file "install/ENABLE" exists, remove the file "install/_ENABLE"
* When loading todoyu in your browser, the upgrade tool of the installer will start automatically
* Follow the instructions of the updater
