<?php
session_start();
if (!file_exists('install/')) {
/*
* Include the DB class from PEAR for database access.
* You must have PEAR and the DB package installed to
* use Snaps!. If you need instructions for installing PEAR
* and the DB package, please see http://pear.php.net
*/
require_once('DB.php');

/*
* Include the Snaps! configuration file
*/
require_once('Snaps!.config.php');

/* Connect to the database */
$db =& DB::connect($dsn);
if (DB::isError($db)) {
    die($db->getMessage());
}

/* Include the Snaps! functions */
require_once('./Snaps!.functions.php');

/* Check and get the URL variables */
if (!empty($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = '';
}

if (!empty($_GET['album'])) {
	$album = $_GET['album'];
} else {
	$album = '';
}

if (!empty($_GET['image'])) {
	$image = $_GET['image'];
} else {
	$image = '';
}

if (!empty($_GET['start'])) {
	$start = $_GET['start'];
} else {
	$start = '0';
}

/* If we have actions set, perform appropriate section */
if (!empty($action)) {
	$out = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;"><tr><td style="text-align: left;"><h1><a href="'.$_SERVER['PHP_SELF'].'">'.$title.'</a></h1></td><td style="text-align: right; padding: 5px;"><a href="'.$_SERVER['PHP_SELF'].'?action=login">Log In</a>';
	if ($config['allowSubmit'] == 1) {
		$out .= ' | <a href="'.$_SERVER['PHP_SELF'].'?action=submit">Submit Picture</a></td></tr></table><br />';
	} else {
		$out .= '</td></tr></table><br />';
	}
	switch($action) {
		case 'login' :
			if (empty($_POST['submit'])) {
				$out .= '<div style="clear: right; width: 300px; height: 100px; margin: 0 auto;"><form action="'.$_SERVER['PHP_SELF'].'?action=login" method="post">';
				$out .= '<div style="height: 24px;"><span style="width: 100px; float: left; text-align: right;">Username:</span><span style="width: 190px; float: right; text-align: left;"><input type="text" name="username" id="username" size="15" /></span></div>';
				$out .= '<div style="height: 24px;"><span style="width: 100px; float: left; text-align: right;">Password:</span><span style="width: 190px; float: right; text-align: left;"><input type="password" name="password" id="password" size="15" /></span></div>';
				$out .= '<div style="height: 24px;"><span style="width: 100px; float: left; text-align: right;"><input style="font-size: 13px;" type="reset" name="reset" value="Clear" /></span><span style="width: 190px; float: right; text-align: left;"><input style="font-size: 13px;" type="submit" name="submit" value="Log In" /></span></div>';
				$out .= '</form></div>';
			} else {
				$un = $_POST['username'];
				$ps = md5($_POST['password']);
				$result =& $db->query('SELECT * FROM '.TP.'users WHERE username = \''.$un.'\' AND userpass = \''.$ps.'\'');
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$logged = $result->numRows();
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				if ($logged == 0) {
					$out .= '<div style="clear: both; width: 300px; height: 100px; margin: 0 auto; color: #F00;">Username or Password incorrect.</div>';
				} else {
					$_SESSION['name'] = $line['userFname'].' '.$line['userLname'];
					$_SESSION['userID'] = $line['userID'];
					$_SESSION['loggedIn'] = TRUE;
					$result =& $db->query('UPDATE '.TP.'users SET userLastLogin = UNIX_TIMESTAMP() WHERE userID = '.$_SESSION['userID']);
					if (DB::isError($result)) {
						die($result->getMessage());
					}
					header("Location: admin/index.php");
				}
			}
			break;
		case 'submit' :
			if ($config['allowSubmit'] == 1) {
				if (empty($_GET['err'])) {
					if (empty($_POST['submit'])) {
						$out .= '<div class="box" style="text-align: left;">'."\n\t\t\t\t".'<table cellpadding="3" cellspacing="0" border="0">'."\n";
						$out .= "\t\t\t\t\t".'<form enctype="multipart/form-data" action="index.php?action=submit" method="post">'."\n";
						$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Upload New Image</h4></td></tr>';
						$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Your Name:</td><td><input type="text" size="30" name="upSubName" /></td></tr>'."\n";
						$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Your Email:</td><td><input type="text" size="30" name="upSubEmail" /></td></tr>'."\n";
						$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Image Name:</td><td><input type="text" size="30" name="upName" /></td></tr>'."\n";
						$out .= "\t\t\t\t\t".'<tr><td style="text-align: right; vertical-align: top;">Image Description:</td><td><textarea cols="50" rows="5" name="upDesc"></textarea></td></tr>'."\n";
						$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="2000000" /><input name="userfile" type="file" /></td></tr>'."\n";
						$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input type="button" name="cancel" value="Cancel" onclick="document.location.href=\'index.php\'; return false;" /></td><td><input type="submit" name="submit" value="Add" /></td></tr>'."\n";
						$out .= "\t\t\t\t\t".'</form>'."\n\t\t\t\t".'</table>'."\n".'</div><br />'."\n";
					} else {
						$uploaddir = $config['absPath'].$config['uploadsPath'];
						$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
						if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
							$result =& $db->query('INSERT INTO '.TP.'uploads (upID, upSubName, upSubEmail, upName, upDesc, upFilename, upCreated) VALUES ("", "'.mysql_escape_string($_POST['upSubName']).'", "'.mysql_escape_string($_POST['upSubEmail']).'", "'.mysql_escape_string($_POST['upName']).'", "'.mysql_escape_string($_POST['upDesc']).'", "'.basename($_FILES['userfile']['name']).'", UNIX_TIMESTAMP())');
							if (DB::isError($result)) {
								die($result->getMessage());
							}
							if ($db->affectedRows() > 0) {
								$err = 'Image successfully uploaded. The administrator will review your submission.';
								echo '<script type="text/javascript">document.location.href = "index.php?action=submit&err='.$err.'";</script>';
							} else {
								$err = 'Image successfully uploaded, but could not be added to database.';
								echo '<script type="text/javascript">document.location.href = "index.php?action=submit&err='.$err.'";</script>';
							}
						} else {
							$err = 'Possible file upload attack!';
							echo '<script type="text/javascript">document.location.href = "index.php?action=submit&err='.$err.'";</script>';
						}
					}
				} else {
					$out .= "\n".'<div class="box" style="clear: both; text-align: left;">'.$_GET['err'].'</div><br />'."\n";
				}
			} else {
			$out .= '<div class="box" style="text-align: left;">Image Submission has been disabled by the administrator.</div><br />';
			}
			break;
		default :
			die('Security violation.<br />Your IP and the time and date this occurred have been logged.');
			break;
	}
/* otherwise, we check for viewing of list, album, or image */
} else if (!empty($_POST['submit'])) {
	switch($_POST['submit']) {
		case 'Post Comment' :
			if ($config['allowComment'] == 1) {
				$out = comment('create', $image, $album);
			} else {
				$out = '<div class="box" style="text-align: left;">Commenting has been disabled by the administrator.</div><br />';
			}
			break;
		default :
			break;
	}
} else {
	if (empty($album)) {
		$out = albumList();
	} else if (!empty($album) && empty($image)) {
		$out = album('view', $album);
	} else {
		$out = image('view', $album, $image);
	}
}
} else {
	die('<html><head><title>Snaps! Gallery :: Security Violation</title></head><body><div style="width: 600px; margin: 0 auto; padding: 5px; text-align: center; background: #EEE; border: 1px solid #666;"><h2 style="text-align: left; font-family: Verdana, Arial, Helvetica, sans-serif; padding: 5px; margin: 0;">Security Violation!</h2><p style="text-align: left; font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold;">If you are seeing this message, one of the following has occured:</p><p style="padding-left: 10px; color: #F00; text-align: left; font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold;">&bull; Snaps! has not been installed<br />&bull; You have not deleted the "install" directory after installing Snaps!<br />&bull; A security breach</p></div><br /></body></html>');
}

?>