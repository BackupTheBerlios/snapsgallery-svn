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
<div class="snapsCrumb">{$data[1][7]}</div>
		<table cellpadding="0" cellspacing="10" border="0" style="width: 100%;">
			<tr>
{* The data returned by the image() is an array that contains (in this order):
image name [0], image filename [1], image description [2], image created timestamp [3], image modified timestamp [4], album ID [5], and image [6]
$data[1][0-6] references each of the pieces of information listed above. 
The array also contains 2 special elements -  $data[1][7] which contains the breadcrumb navigation for the top of the album page, and
$data[1][8] contains the comments and comment form. *}
				<td class="snapsTable"><a href="{$config.albumsPath}{$data[1][5]}/{$data[1][1]}">{$data[1][6]}</a></td>
			</tr>
			<tr>
				<td class="snapsTable">{$data[1][2]}<br /><div class="snapsNotes">Created: {$data[1][3]|date_format:"%m-%d-%Y"}<br />Last Modified: {$data[1][4]|date_format:"%m-%d-%Y"}</div></td>
			</tr>
		</table>
		<div class="snapsCrumb">
			<h3>Comments</h3>
{if $config.allowComment eq 1}
{if is_array($comment)}
			<table cellpadding="0" cellspacing="10" border="0" style="width: 100%;">
{assign var="count" value="1"}
{section name=c loop=$comment}
	{assign var="count" value="`$count+1`"}
{/section}
{section name=el loop=$count  start=1}
				<tr>
					<td class="snapsTable" style="text-align: left;"><span class="snapsNotes">By: <a href="mailto:{$comment[el][1]}">{$comment[el][0]}</a><br />Posted: {$comment[el][3]|date_format:"%m-%d-%Y @ %I:%M:%S %p"}</span><br /><br />{$comment[el][2]}</td>
				</tr>
{/section}
			</table>
{else}
			{$comment}
{/if}
			<form name="comment" action="{$smarty.server.PHP_SELF}?album={$data[1][5]}&amp;image={$data[1][8]}" method="post">
			<h3>Post a comment</h3>
			<div style="width: 500px; height: 250px;">
				<div style="clear: both; height: 24px;"><span style="width: 100px; float: left; text-align: right;">Name:</span><span style="width: 390px; float: right; text-align: left;"><input type="text" name="commentName" id="commentName" size="30" /></span></div>
				<div style="clear: both; height: 24px;"><span style="width: 100px; float: left; text-align: right;">*Email:</span><span style="width: 390px; float: right; text-align: left;"><input type="text" name="commentEmail" id="commentEmail" size="30" /></span></div>
				<div style="clear: both;"><span style="width: 100px; float: left; text-align: right;">Comment:</span><span style="width: 390px; float: right; text-align: left;"><textarea cols="60" rows="10" name="commentBody" id="commentBody"></textarea></span></div>
				<div style="clear: both; padding-top: 10px; height: 30px;"><span style="width: 100px; float: left; text-align: right;">&nbsp;</span><span style="width: 390px; float: right;"><input type="reset" name="reset" value="Clear" /> <input type="submit" name="submit" value="Post Comment" /></span></div>
				<div style="clear: both; padding-top: 10px; height: 30px; font-size: 9px;">*Your email will be obfuscated to protect against spam harvesters.</div>
			</div>
			</form>
{else}
			{$comment}
{/if}
		</div><br />
	</div>
<div class="snapsCopy">Powered by <a href="http://labs.sonicdesign.us/projects/Snaps!/">Snaps!</a> v{$config.version}<p class="snapsNotes">This program uses icons from the <a href="http://www.kde.org">KDE</a> Project by <a href="http://www.everaldo.com">Everaldo Coelho</a> released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU GPL</a>, and the Universal PNG Enabler from <a href="http://dsandler.org/">Dan Sandler</a>, which incorporates code from <a href="http://www.youngpup.net/">Aaron Boodman</a> (inline) and <a href="http://www.allinthehead.com/">Drew McLellan</a> (background).</p></div>
</body>
</html>