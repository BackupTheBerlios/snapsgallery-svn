<?php
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