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
{if is_array($data)}
<div class="snapsCrumb">{$data[1][9]}</div>
{else}
<div class="snapsCrumb"><a href="{$smarty.server.PHP_SELF}">Album List</a></div>
{/if}
		<table cellpadding="0" cellspacing="10" border="0" style="width: 100%;">
		{if is_array($data)}
	<tr>
{* This is a loop that gives us 3 columns of images *}
{* The data returned by the album() is an array that contains (in this order):
image link [0], image Name [1], image [2], image description [3], image created timestamp [4], image modified timestamp [5], image view count [6], and number of comments [7]
for each image -> $data[element] references the image, and $data[element][0-7] references each of the pieces of information listed above. 
The array also contains 2 special elements - $data[1][8] contains the links for paginating the records, if there are more than the values configured
(9 per page by default), and $data[1][9] which contains the breadcrumb navigation for the top of the album page. *}
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
				<td class="snapsTable"><a href="{$smarty.server.PHP_SELF}{$data[element][0]}">{$data[element][2]}</a><br /><a href="{$smarty.server.PHP_SELF}{$data[element][0]}">{$data[element][1]}</a><br /><br /><div class="snapsNotes"><span style="font-style: oblique;">Viewed {$data[element][6]} times<br />{$data[element][7]}<br /></span>Created: {$data[element][4]|date_format:"%m-%d-%Y"}<br />Last modified: {$data[element][5]|date_format:"%m-%d-%Y"}</div></td>
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
{if is_array($data)}
		<div class="snapsCrumb" style="text-align: right;">{$data[1][8]}</div><br />
{/if}
	</div>
<div class="snapsCopy">Powered by <a href="http://labs.sonicdesign.us/projects/Snaps!/">Snaps!</a> v{$config.version}<p class="snapsNotes">This program uses icons from the <a href="http://www.kde.org">KDE</a> Project by <a href="http://www.everaldo.com">Everaldo Coelho</a> released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>, and the Universal PNG Enabler from <a href="http://dsandler.org/">Dan Sandler</a>, which incorporates code from <a href="http://www.youngpup.net/">Aaron Boodman</a> (inline) and <a href="http://www.allinthehead.com/">Drew McLellan</a> (background).</p></div>
</body>
</html>