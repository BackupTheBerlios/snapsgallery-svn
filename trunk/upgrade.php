<?php

/*
* Snaps! 1.4.4 Upgrade Script
*/

$err = '';
define('STR_OK', '<img src="button_ok.png" alt="OK" title="OK" />');
define('STR_ERR', '<img src="button_error.png" alt="Error" title="Error" />');
define('STR_WARN', '<img src="button_warning.png" alt="Warning" title="Warning" />');

if (!empty($_POST['upgrade'])) {
	include('DB.php');
	include('Snaps!.config.php');
	/* Connect to the database */
	$db =& DB::connect($dsn);
	if (DB::isError($db)) {
		die($db->getMessage());
	}
	$result =& $db->query('REPLACE INTO '.TP.'config (var, val) VALUES ("version", "1.4.4")');
	if (DB::isError($result)) {
		die($result->getMessage());
	}
	if ($result->numRows() == 0) {
		$err = 'Error upgrading Snaps! installation.';
	} else {
		$err = 'Upgrade successful!';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>Snaps! Gallery Upgrade</title>
<script type="text/javascript" src="iepngfix.js"></script>
<link rel="stylesheet" type="text/css" href="snaps.css" />
<style type="text/css">
img {
	vertical-align: middle;
}

.btn {
	color: #000;
	text-decoration: none;
	background: #BBB;
	font-weight: bold;
	padding: 3px;
	border: 2px outset #BBB;
}

.btn:hover {
	color: #FFF;
	text-decoration: underline;
}

td {
	padding: 6px;
}

.box {
	background: #EEE;
	padding: 4px;
	border: 1px solid #666;
}
</style>
</head>
<body>
<div style="width: 700px; margin: 0 auto; text-align: center; background: #FFF; border: 1px solid #666;">
	<h1 style="float: left;"><img src="images/snaps_logo.png" alt="Snaps! Gallery" title="Snaps! Gallery" /></h1>
	<div style="clear: both; text-align: left; padding: 10px;">
	<h3>Snaps! Upgrade - 1.4.x to 1.4.4</h3>
		<div class="box">
			<div style="text-align: center; width: 400px; margin: 0 auto;">
				<?php	if (empty($err)) { ?>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><input type="submit" name="upgrade" value="Perform Upgrade" /></form>
				<?php	} else {
								echo $err;
							}
				?>
			</div>
		</div>
	</div>
</div>
<div class="snapsCopy">Powered by <a href="http://labs.sonicdesign.us/projects/Snaps!/">Snaps! Gallery</a> v1.4.4<p class="snapsNotes">This program uses icons from the <a href="http://www.kde.org">KDE</a> Project by <a href="http://www.everaldo.com">Everaldo Coelho</a> released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>, and the Universal PNG Enabler from <a href="http://dsandler.org/">Dan Sandler</a>, which incorporates code from <a href="http://www.youngpup.net/">Aaron Boodman</a> (inline) and <a href="http://www.allinthehead.com/">Drew McLellan</a> (background).</p></div>
</body>
</html>