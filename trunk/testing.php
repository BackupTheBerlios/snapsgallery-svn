<?php
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

/* Include Smarty class */
define('SMARTY_DIR', 'c:\htdocs\projects\Snaps!\trunk\Smarty\libs\\');
include(SMARTY_DIR.'Smarty.class.php');

/* Instantiate Smarty and set output values */
$smarty = new Smarty;
$smarty->template_dir = SMARTY_DIR.'..\templates/';
$smarty->compile_dir = SMARTY_DIR.'..\templates_c/';
$smarty->config_dir = SMARTY_DIR.'..\config/';
$smarty->cache_dir = SMARTY_DIR.'..\cache/';

$smarty->assign('title', $title);
$smarty->assign('action', $_GET['action']);
$smarty->assign('data', albumList());
$smarty->assign('config', $config);
$smarty->display('albumList.tpl');
?>