<?php
/*
*	File:				Snaps!.config.php
*	Description:	Contains configuration information for the gallery
*	Copyright:	©2004 The Sonic Group, LLC
*	Website:		http://labs.sonicdesign.us/projects/Snaps!/
*
*	This program is free software; you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation; either version 2 of the License, or
*	(at your option) any later version.
*/

/*
* Set up the DSN for DB
* Format: protocol://username:password@hostname_or_IP/databaseName
* See the documentation for the DB package for supported protocols and options
*/
$dsn = 'mysql://%dbUser%:%dbPass%@%dbHost%/%dbName%';

/*
* Set the gallery title
*/
$title = '%title%';

/*
* Set up the Table Prefix
*/
define('TP', '%tblPrefix%');

?>