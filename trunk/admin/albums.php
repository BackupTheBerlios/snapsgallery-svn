<?php
if (!empty($_GET['err'])) {
	$err = $_GET['err'];
} else {
	$err = '';
}
?>
			<h3><img src="icons/albums.png" alt="Albums" title="Albums" style="vertical-align: middle;" /> Albums</h3>
			<?php if (!empty($err)) { echo "\n".'<div class="box">'.$err.'</div><br />'."\n"; } ?>
			<div class="box">
				<script type="text/javascript">
					function cancel() {
						document.location.href = 'index.php?s=albums';
					}
				</script>
				<table cellpadding="2" cellspacing="0" border="0" style="width: 100%;">
<?php
if (empty($_GET['a']) && empty($_GET['album'])) {
?>
					<tr><td class="adminTD" style="background: #009; color: #FFF; font-weight: bold; width: 10%;">Edit</td><td class="adminTD" style="background: #009; color: #FFF; font-weight: bold; width: 10%;">Delete</td><td style="background: #009; color: #FFF; font-weight: bold; width: 80%;">Album</td></tr>
<?php
	$result =& $db->query('SELECT * FROM '.TP.'albums');
	if (DB::isError($result)) {
		die($result->getMessage());
	}
	if ($result->numRows() > 0) {
		echo "\t\t\t\t";
		$bg = ' style="background: #CCC;"';
		while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$bg = ($bg == ' style="background: #CCC;"' ? '' : ' style="background: #CCC;"');
			echo "\t".'<tr><td class="adminTD"'.$bg.'><a href="index.php?s=albums&amp;a=edit&amp;album='.$line['albumID'].'"><img src="icons/editalbum.png" alt="Edit" title="Edit" /></a></td><td class="adminTD"'.$bg.'><a href="index.php?s=albums&amp;a=delete&amp;album='.$line['albumID'].'"><img src="icons/deletealbum.png" alt="Delete" title="Delete" /></a></td><td'.$bg.'>'.$line['albumName'].'</td></tr>'."\n\t\t\t\t";
		}
	} else {
		echo '<tr><td colspan="3" class="adminTD">There are no albums.</td></tr>';
	}
?>
</table>
				<br /><span style="padding: 5px; font-weight: bold;"><a href="index.php?s=albums&amp;a=new"><img style="vertical-align: bottom;" src="icons/newalbum.png" alt="New Album" title="New Album" /></a> Create New Album</span>
<?php
} else {
	switch($_GET['a']) {
		case 'edit' :
			if (empty($_POST['submit'])) {
				$result =& $db->query('SELECT * FROM '.TP.'albums WHERE albumID = '.$_GET['album']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$out = "\t\t\t\t\t".'<form action="index.php?s=albums&amp;a=edit&amp;album='.$_GET['album'].'" method="post">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Edit Album</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Album Name:</td><td><input type="text" size="30" name="albumName" value="'.$line['albumName'].'" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right; vertical-align: top;">Album Description:</td><td><textarea cols="50" rows="5" name="albumDesc">'.$line['albumDesc'].'</textarea></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_edit_on.png\';" onmouseout="this.src=\'icons/btn_edit.png\';" type="image" src="icons/btn_edit.png" name="submit" value="Edit" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="document.location.href=\'index.php?s=albums\'; return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				$result =& $db->query('UPDATE '.TP.'albums SET albumName = "'.mysql_escape_string($_POST['albumName']).'", albumDesc = "'.mysql_escape_string($_POST['albumDesc']).'", albumModified = UNIX_TIMESTAMP() WHERE albumID = '.$_GET['album']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$err = 'Edit Successful!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=albums&err='.$err.'";</script>';
				} else {
					$err = 'Edit Failed!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=albums&err='.$err.'";</script>';
				}
			}
			break;
		case 'new' :
			if (empty($_POST['submit'])) {
				$out = "\t\t\t\t\t".'<form action="index.php?s=albums&amp;a=new" method="post">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Create New Album</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Album Name:</td><td><input type="text" size="30" name="albumName" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right; vertical-align: top;">Album Description:</td><td><textarea cols="50" rows="5" name="albumDesc"></textarea></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_create_on.png\';" onmouseout="this.src=\'icons/btn_create.png\';" type="image" src="icons/btn_create.png" name="submit" value="Create" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="document.location.href=\'index.php?s=albums\'; return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				$result =& $db->query('INSERT INTO '.TP.'albums (albumID, albumName, albumDesc, albumCount, albumCreated, albumModified) VALUES ("", "'.mysql_escape_string($_POST['albumName']).'", "'.mysql_escape_string($_POST['albumDesc']).'", "", UNIX_TIMESTAMP(), UNIX_TIMESTAMP())');
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$result =& $db->query('SELECT albumID FROM '.TP.'albums WHERE albumName = "'.mysql_escape_string($_POST['albumName']).'" AND albumDesc = "'.mysql_escape_string($_POST['albumDesc']).'"');
					if (DB::isError($result)) {
						die($result->getMessage());
					}
					$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
					if (mkdir($config['absPath'].$config['albumsPath'].$line['albumID'])) {
						$err = 'Album Created!';
						echo '<script type="text/javascript">document.location.href = "index.php?s=albums&err='.$err.'";</script>';
					} else {
						$err = 'Album created in database, but folder creation failed. Please create a folder called "'.$line['albumID'].'" (without quotes) before adding images to this album.';
						echo '<script type="text/javascript">document.location.href = "index.php?s=albums&err='.$err.'";</script>';
					}
				} else {
					$err = 'Album Creation Failed!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=albums&err='.$err.'";</script>';
				}
			}
			break;
		case 'delete' :
			if (empty($_POST['submit'])) {
				$result =& $db->query('SELECT * FROM '.TP.'albums WHERE albumID = '.$_GET['album']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$albumDir = $config['absPath'].$config['albumsPath'].$line['albumID'];
				$files = 0;
				if (file_exists($albumDir)) {
					$dir = dir($albumDir);
					while (false !== $entry = $dir->read()) {
						if ($entry == '.' || $entry == '..') {
							continue;
						}
						$files++;
					}
					if ($files == 0) {
						$out = "\t\t\t\t\t".'<form action="index.php?s=albums&amp;a=delete&amp;album='.$_GET['album'].'" method="post">'."\n";
						$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Delete Album</h4></td></tr>';
						$out .= "\t\t\t\t\t".'<tr><td colspan="2">Are you sure you want to delete: <span style="font-weight: bold;">'.$line['albumName'].'</span>?</td></tr>'."\n";
						$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_delete_on.png\';" onmouseout="this.src=\'icons/btn_delete.png\';" type="image" src="icons/btn_delete.png" name="submit" value="Delete" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="document.location.href=\'index.php?s=albums\'; return false;" /></td></tr>'."\n";
						$out .= "\t\t\t\t\t".'</form>'."\n";
					} else {
						$out = "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Delete Album</h4></td></tr>';
						$out .= "\t\t\t\t\t".'<tr><td colspan="2">Cannot delete <span style="font-weight: bold;">'.$line['albumName'].'</span> because it contains images. Please remove the images first.</td></tr>';
					}
				}
			} else {
				$result =& $db->query('DELETE FROM '.TP.'albums WHERE albumID = '.$_GET['album']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$albumDir = $config['absPath'].$config['albumsPath'].$_GET['album'];
					if (rmdir($albumDir)) {
						$err = 'Album deleted.';
						echo '<script type="text/javascript">document.location.href = "index.php?s=albums&err='.$err.'";</script>';
					} else {
						$err = 'Album deleted from database, but could not delete album folder. Please remove "'.$albumDir.'" manually.';
						echo '<script type="text/javascript">document.location.href = "index.php?s=albums&err='.$err.'";</script>';
					}
				} else {
					$err = 'Album could not be deleted.';
					echo '<script type="text/javascript">document.location.href = "index.php?s=albums&err='.$err.'";</script>';
				}
			}
			break;
		default :
			break;
	}
	echo $out."\t\t\t\t".'</table>'."\n";
}
?>
			</div>