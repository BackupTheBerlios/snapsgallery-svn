<?php
/* Include the functions */
require_once('Snaps!.index.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title><?php echo $title; ?></title>
<link rel="stylesheet" type="text/css" href="snaps.css" />
</head>
<body<?php if ($action == 'login') { echo ' onload="document.getElementById(\'username\').focus();"'; } ?>>
	<div style="width: 700px; margin: 0 auto; text-align: center; background: #FFF; border: 1px solid #666;">
	<?php echo $out; ?>
	</div>
<div class="snapsCopy">Powered by <a href="http://labs.sonicdesign.us/projects/Snaps!/">Snaps!</a> v<?php echo $config['version']; ?><p class="snapsNotes">This program uses icons from the <a href="http://www.kde.org">KDE</a> Project by <a href="http://www.everaldo.com">Everaldo Coelho</a> released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>, and the Universal PNG Enabler from <a href="http://dsandler.org/">Dan Sandler</a>, which incorporates code from <a href="http://www.youngpup.net/">Aaron Boodman</a> (inline) and <a href="http://www.allinthehead.com/">Drew McLellan</a> (background).</p></div>
</body>
</html>