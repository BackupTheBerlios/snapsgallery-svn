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

/* Include the Snaps! functions */
require_once('./Snaps!.functions.php');

/* Include Smarty class */
define('SMARTY_DIR', $config['absPath'].'Smarty/libs/');
include(SMARTY_DIR.'Smarty.class.php');

/* Instantiate Smarty and set output values */
$smarty = new Smarty;
$smarty->template_dir = SMARTY_DIR.'../templates/';
$smarty->compile_dir = SMARTY_DIR.'../templates_c/';
$smarty->config_dir = SMARTY_DIR.'../config/';
$smarty->cache_dir = SMARTY_DIR.'../cache/';

$smarty->assign('title', $title);
$smarty->assign('action', $action);
$smarty->assign('config', $config);
if (!empty($action)) {
	switch($action) {
		case 'login' :
			if (empty($_POST['submit'])) {
				$smarty->display('login.tpl');
			} else {
				$result =& $db->query('SELECT * FROM '.TP.'users WHERE username = \''.$_POST['username'].'\' AND userpass = \''.md5($_POST['password']).'\'');
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				if ($result->numRows() == 0) {
					$smarty->assign('error', 'Username or Password incorrect.');
					$smarty->display('login.tpl');
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
						$smarty->display('upload.tpl');
					} else {
						$uploaddir = $config['absPath'].$config['uploadsPath'];
						$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
						if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
							$result =& $db->query('INSERT INTO '.TP.'uploads (upID, upSubName, upSubEmail, upName, upDesc, upFilename, upCreated) VALUES ("", "'.mysql_escape_string($_POST['upSubName']).'", "'.mysql_escape_string($_POST['upSubEmail']).'", "'.mysql_escape_string($_POST['upName']).'", "'.mysql_escape_string($_POST['upDesc']).'", "'.basename($_FILES['userfile']['name']).'", UNIX_TIMESTAMP())');
							if (DB::isError($result)) {
								die($result->getMessage());
							}
							if ($db->affectedRows() > 0) {
								$smarty->assign('etype', 'success');
								$smarty->assign('error', 'Image successfully uploaded. The administrator will review your submission.');
								$smarty->display('message.tpl');
							} else {
								$smarty->assign('etype', 'partial');
								$smarty->assign('error', 'Image successfully uploaded, but could not be added to database.');
								$smarty->display('message.tpl');
							}
						} else {
							$smarty->assign('etype', 'error');
							$smarty->assign('error', 'Possible file upload attack!');
							$smarty->display('message.tpl');
						}
					}
				}
			} else {
				$smarty->assign('etype', 'error');
				$smarty->assign('error', 'Image Submission has been disabled by the administrator.');
				$smarty->display('message.tpl');
			}
			break;
	}
} else if (!empty($_POST['submit'])) {
	if ($_POST['submit'] == 'Post Comment') {
		if ($config['allowComment'] == 1) {
			If (!empty($_POST['commentName']) && !empty($_POST['commentBody'])) {
				$smarty->assign('comment', comment('create', $image, $album));
				$smarty->display('cMessage.tpl');
			} else {
				$smarty->assign('etype', 'error');
				$smarty->assign('error', 'You must provide at least your name and a comment.');
				$smarty->display('message.tpl');
			}
		} else {
			$smarty->assign('etype', 'error');
			$smarty->assign('error', 'Commenting has been disabled by the administrator.');
			$smarty->display('message.tpl');
		}
	}
} else {
	if (empty($album)) {
		$smarty->assign('data', albumList());
		$smarty->display('albumList.tpl');
	} else {
		if (empty($image)) {
			$smarty->assign('data', album($album));
			$smarty->display('album.tpl');
		} else {
			$smarty->assign('data', image($album, $image));
			$smarty->assign('comment', comment('view', $image, $album));
			$smarty->display('image.tpl');
		}
	}
}
} else {
	die('<html><head><title>Snaps! Gallery :: Security Violation</title></head><body><div style="width: 600px; margin: 0 auto; padding: 5px; text-align: center; background: #EEE; border: 1px solid #666;"><h2 style="text-align: left; font-family: Verdana, Arial, Helvetica, sans-serif; padding: 5px; margin: 0;">Security Violation!</h2><p style="text-align: left; font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold;">If you are seeing this message, one of the following has occured:</p><p style="padding-left: 10px; color: #F00; text-align: left; font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold;">&bull; Snaps! has not been installed<br />&bull; You have not deleted the "install" directory after installing Snaps!<br />&bull; A security breach</p></div><br /></body></html>');
}
?>