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



TODOYU INSTALLER README.TXT	- Steps to take when...
---------------------------------------------------

1. You generally want to run the installer:
	* Make sure the file "ENABLE" exists (rename or remove "_ENABLE")
	* See 4.

2. The database config fails and/or needs to be reconfigured:
	* See 1.
	* Make sure $CONFIG['DB']['autoconnect'] in config/db.php is set to false
	* Remove todoyu login- and session- cookies which would otherwise tell the system init that you're logged in already
	* See 4.

3. You updated an installation and need to run the database updater feature:
	* See 1.
	* See 4.

4. The URL to manually restart the installer is:
	http://localhost/todoyu/install/index.php?restart=1 (make sure you change http://localhost/todoyu/ to your hostname and todoyu path)