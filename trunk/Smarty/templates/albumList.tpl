<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="snaps.css" />
<script type="text/javascript" src="iepngfix.js"></script>
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
<table cellpadding="0" cellspacing="10" border="0" style="width: 100%;">
		{if is_array($data)}
	<tr>
{* This is a loop that gives us 3 columns of albums *}
{* The data returned by the albumList() is an array that contains (in this order):
album ID [0], album Name [1], album Description [2], album image count [3], and album last modified timestamp [4]
for each album -> $data[element] references the album, and $data[element][0-4] references each of the
pieces of information listed above. *}
{assign var="col" value="0"}
{assign var="count" value="1"}
{section name=c loop=$data}
	{assign var="count" value="`$count+1`"}
{/section}
{section name=element loop=$count start=1}
{if $col == 3}
			</tr>
			<tr>
{assign var="col" value="0"}
{/if}
				<td class="snapsTable"><a href="{$smarty.server.PHP_SELF}?album={$data[element][0]}">{$data[element][6]}</a><br /><a href="{$smarty.server.PHP_SELF}?album={$data[element][0]}">{$data[element][1]}</a><br />{$data[element][2]}<br /><div class="snapsNotes">({$data[element][3]} items in this album)<br />Last modified: {$data[element][4]|date_format:"%m-%d-%Y"}</div></td>
{assign var="col" value="`$col+1`"}
{/section}
{assign var="remainder" value="`3-$col`"}
{section name=emptyElement loop=$remainder}
				<td class="snapsTable">&nbsp;</td>
{/section}
			</tr>
{else}
			<tr><td class="snapsTable">{$data}</td></tr>
{/if}
		</table>
		<div class="snapsCrumb" style="text-align: right;">{$data[1][5]}</div><br />
	</div>
<div class="snapsCopy">Powered by <a href="http://labs.sonicdesign.us/projects/Snaps!/">Snaps! Gallery</a> v{$config.version}<br />&copy;2005 The Sonic Group, LLC. All Rights Reserved.<br /><a href="javascript:credits();">Credits</a></div>
<div id="creditsBox" class="snapsCopy" style="display: none;"><p class="snapsNotes">This program was written by Dave Scott of <a href="http://www.thesonicgroup.us">The Sonic Group, LLC</a>. Snaps! is released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>. This program uses icons from <a href="http://www.everaldo.com">Everaldo Coelho</a> released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>, icons from <a href="http://www.icon-king.com">David Vignoni</a> released under the <a href="http://www.gnu.org/licenses/lgpl.html">GNU LGPL</a>, and the Universal PNG Enabler from <a href="http://dsandler.org/">Dan Sandler</a>, with code from <a href="http://www.youngpup.net/">Aaron Boodman</a> and <a href="http://www.allinthehead.com/">Drew McLellan</a>.</p></div>
</body>
</html>