<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="snaps.css" />
{literal}
<script type="text/javascript">
function credits() {
	cb = document.getElementById('creditsBox');
	if (cb.style.display == 'none') {
		cb.style.display = 'block';
	} else {
		cb.style.display = 'none';
	}
}
</script>
{/literal}
</head>
{* If we have an action of login, focus on the username field *}
{if $action eq "login"}
<body onload="document.getElementById('username').focus();">
{else}
<body>
{/if}
	<div style="width: 700px; margin: 0 auto; text-align: center; background: #FFF; border: 1px solid #666;">
		<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
			<tr>
				<td style="text-align: left;"><h1><a href="{$smarty.server.PHP_SELF}">{$title}</a></h1></td>
				<td style="text-align: right; padding: 5px;"><a href="{$smarty.server.PHP_SELF}?action=login">Log In</a>{if $config.allowSubmit eq 1} {* If we allow submissions, show the Submit Picture link *} | <a href="{$smarty.server.PHP_SELF}?action=submit">Submit Picture</a></td>
			</tr>
		</table><br />
		{else}
				</td>
			</tr>
		</table><br />
		{/if}
		<div class="box" style="text-align: left;">
			<table cellpadding="3" cellspacing="0" border="0">
				<form enctype="multipart/form-data" action="{$smarty.server.PHP_SELF}?action=submit" method="post">
				<tr><td colspan="2"><h4 style="margin-top: 0;">Upload New Image</h4></td></tr>
				<tr><td style="text-align: right;">Your Name:</td><td><input type="text" size="30" name="upSubName" /></td></tr>
				<tr><td style="text-align: right;">Your Email:</td><td><input type="text" size="30" name="upSubEmail" /></td></tr>
				<tr><td style="text-align: right;">Image Name:</td><td><input type="text" size="30" name="upName" /></td></tr>
				<tr><td style="text-align: right; vertical-align: top;">Image Description:</td><td><textarea cols="50" rows="5" name="upDesc"></textarea></td></tr>
				<tr><td style="text-align: right;">File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="2000000" /><input name="userfile" type="file" /></td></tr>
				<tr><td style="text-align: right;"><input type="button" name="cancel" value="Cancel" onclick="document.location.href=\'index.php\'; return false;" /></td><td><input type="submit" name="submit" value="Add" /></td></tr>
				</form>
			</table>
		</div><br />
	</div>
<div class="snapsCopy">Powered by <a href="http://labs.sonicdesign.us/projects/Snaps!/">Snaps! Gallery</a> v{$config.version}<br />&copy;2005 The Sonic Group, LLC. All Rights Reserved.<br /><a href="javascript:credits();">Credits</a></div>
<div id="creditsBox" class="snapsCopy" style="display: none;"><p class="snapsNotes">This program was written by Dave Scott of <a href="http://www.thesonicgroup.us">The Sonic Group, LLC</a>. Snaps! is released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>. This program uses icons from <a href="http://www.everaldo.com">Everaldo Coelho</a> released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>, icons from <a href="http://www.icon-king.com">David Vignoni</a> released under the <a href="http://www.gnu.org/licenses/lgpl.html">GNU LGPL</a>, and the Universal PNG Enabler from <a href="http://dsandler.org/">Dan Sandler</a>, with code from <a href="http://www.youngpup.net/">Aaron Boodman</a> and <a href="http://www.allinthehead.com/">Drew McLellan</a>.</p></div>
</body>
</html>