<?php
session_start();
/*
* Include the DB class from PEAR for database access.
* You must have PEAR and the DB package installed to
* use sNaps!. If you need instructions for installing PEAR
* and the DB package, please see http://pear.php.net
*/
require_once('DB.php');

/*
* Include the configuration
*/
require_once('../Snaps!.config.php');

/* Connect to the database */
$db =& DB::connect($dsn);
if (DB::isError($db)) {
    die($db->getMessage());
}

/* Retrieve configuration information from the database */
$result =& $db->query('SELECT * FROM '.TP.'config');
if (DB::isError($result)) {
	die($result->getMessage());
}
while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
	$config[$line['var']] = $line['val'];
}
/* Check to make sure we have a valid administrator session */
if ($_SESSION['loggedIn']) {
	/* Get the image ID */
	if (!empty($_GET['image'])) {
		$img = $_GET['image'];
	} else {
		$img = '';
	}
	/* If we have an image ID */
	if (!empty($img)) {
		/* Get and display the image's information */
		$result =& $db->query('SELECT * FROM '.TP.'uploads WHERE upID = '.$img);
		if (DB::isError($result)) {
			die($result->getMessage());
		}
		$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
		$out = $line['upName'].'<br /><br />Submitted by: <a href="mailto:'.$line['upSubEmail'].'">'.$line['upSubName'].'</a> on '.date("m-d-Y", $line['upCreated']).'<br /><br /><img src="../Snaps!.image.php?image='.str_replace(" ", "%20", $config['absPath'].$config['uploadsPath'].$line['upFilename']).'&size=500">';
	} else {
		/* Otherise, print an error message */
		$out = 'Error: Invalid Upload ID';
	}
?>
<html>
<head>
<title>Snaps! Gallery Admin :: Upload Detail</title>
<link rel="stylesheet" type="text/css" href="admin.css" />
</head>
<body>
<div style="background: #FFF; width: 590px; padding: 5px; border: 1px solid #666;">
<span style="display: block; background: #009; padding: 5px; color: #FFF; font-weight: bold;">Upload Detail</span><br />
<?php echo $out; ?>
</div><br />
<div style="background: #FFF; width: 60px; float: right; text-align: center; padding: 5px; border: 1px solid #666;">
<a href="javascript:window.close();">Close</a>
</div>
</body>
</html>
<?php
} else {
	/* Otherwise, fail. */
	die('Security violation.');
}
?>