<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="snaps.css" />
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
		<div class="snapsCrumb">
			<div style="width: 300px; margin: 0 auto; text-align: center;">
				<form action="{$smarty.server.PHP_SELF}?action=login" method="post">
				<div style="height: 24px;"><span style="width: 100px; float: left; text-align: right;">Username:</span><span style="width: 190px; float: right; text-align: left;"><input type="text" name="username" id="username" size="15" /></span></div>
				<div style="height: 24px;"><span style="width: 100px; float: left; text-align: right;">Password:</span><span style="width: 190px; float: right; text-align: left;"><input type="password" name="password" id="password" size="15" /></span></div>
				<div style="height: 24px;"><span style="width: 100px; float: left; text-align: right;"><input style="font-size: 13px;" type="reset" name="reset" value="Clear" /></span><span style="width: 190px; float: right; text-align: left;"><input style="font-size: 13px;" type="submit" name="submit" value="Log In" /></span></div>
				</form>
			</div>
		</div><br />
	</div>
<div class="snapsCopy">Powered by <a href="http://labs.sonicdesign.us/projects/Snaps!/">Snaps!</a> v{$config.version}<p class="snapsNotes">This program uses icons from the <a href="http://www.kde.org">KDE</a> Project by <a href="http://www.everaldo.com">Everaldo Coelho</a> released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>, and the Universal PNG Enabler from <a href="http://dsandler.org/">Dan Sandler</a>, which incorporates code from <a href="http://www.youngpup.net/">Aaron Boodman</a> (inline) and <a href="http://www.allinthehead.com/">Drew McLellan</a> (background).</p></div>
</body>
</html>