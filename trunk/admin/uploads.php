<?php
/* Get error messages, if set */
if (!empty($_GET['err'])) {
	$err = $_GET['err'];
} else {
	$err = '';
}
?>
			<h3><img src="icons/uploads.png" alt="Uploads" title="Uploads" style="vertical-align: middle;" /> Uploads</h3>
			<?php if (!empty($err)) { echo "\n".'<div class="box">'.$err.'</div><br />'."\n"; } ?>
			<div class="box">
				<script type="text/javascript">
					function cancel() {
						document.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?s=uploads';
					}
					function picture(id) {
						window.open('picDetail.php?image='+id, 'picDetail', 'width=640,height=500,scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no');
					}
				</script>
				<table cellpadding="2" cellspacing="0" border="0" style="width: 100%;">
<?php
/* If we don't have an action, or an image */
if (empty($_GET['a']) && empty($_GET['image'])) {
?>
					<tr><td class="adminTD" style="background: #009; color: #FFF; font-weight: bold; width: 10%;">Edit</td><td class="adminTD" style="background: #009; color: #FFF; font-weight: bold; width: 10%;">Delete</td><td style="background: #009; color: #FFF; font-weight: bold; width: 10%; text-align: center;">Add</td><td style="background: #009; color: #FFF; font-weight: bold; width: 30%;">Image Name</td><td style="background: #009; color: #FFF; font-weight: bold; width: 40%;">Image Description</td></tr>
<?php
	/* Get uploads */
	$result =& $db->query('SELECT * FROM '.TP.'uploads');
	if (DB::isError($result)) {
		die($result->getMessage());
	}
	/* If we have uploads, display list */
	if ($result->numRows() > 0) {
		echo "\t\t\t\t";
		$bg = ' style="background: #CCC;"';
		while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$bg = ($bg == ' style="background: #CCC;"' ? '' : ' style="background: #CCC;"');
			echo "\t".'<tr><td class="adminTD"'.$bg.'><a href="index.php?s=uploads&amp;a=edit&amp;image='.$line['upID'].'"><img src="icons/editimage.png" alt="Edit" title="Edit" /></a></td><td class="adminTD"'.$bg.'><a href="index.php?s=uploads&amp;a=delete&amp;image='.$line['upID'].'"><img src="icons/deleteimage.png" alt="Delete" title="Delete" /></a></td><td class="adminTD"'.$bg.'><a href="index.php?s=uploads&amp;a=move&amp;image='.$line['upID'].'"><img src="icons/moveimage.png" alt="Add to Album" title="Add to Album" /></a></td><td'.$bg.'><a href="javascript:picture('.$line['upID'].');">'.$line['upName'].'</a></td><td'.$bg.' class="snapsNotes">'.$line['upDesc'].'</tr>'."\n\t\t\t\t";
		}
	} else {
		/* Otherwise, display error message */
		echo '<tr><td colspan="5" class="adminTD">There are no uploads.</td></tr>';
	}
?>
				</table>
<?php
} else {
	/* Otherwise, handle the action */
	switch($_GET['a']) {
		case 'edit' :
			/* If the form has not been submitted */
			if (empty($_POST['submit'])) {
				/* Get the upload's information, display edit form */
				$result =& $db->query('SELECT * FROM '.TP.'uploads WHERE upID = '.$_GET['image']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$out = "\t\t\t\t\t".'<form action="index.php?s=uploads&amp;a=edit&amp;image='.$_GET['image'].'" method="post">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Edit Uploaded Image</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Image Name:</td><td><input type="text" size="30" name="upName" value="'.$line['upName'].'" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right; vertical-align: top;">Image Description:</td><td><textarea cols="50" rows="5" name="upDesc">'.$line['upDesc'].'</textarea></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_edit_on.png\';" onmouseout="this.src=\'icons/btn_edit.png\';" type="image" src="icons/btn_edit.png" name="submit" value="Edit" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="cancel(); return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				/* Otherwise, update the image, print messages */
				$result =& $db->query('UPDATE '.TP.'uploads SET upName = "'.mysql_escape_string($_POST['upName']).'", upDesc = "'.mysql_escape_string($_POST['upDesc']).'" WHERE upID = '.$_GET['image']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$err = 'Edit Successful!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
				} else {
					$err = 'Edit Failed!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
				}
			}
			break;
		case 'delete' :
			/* If the form has not been submitted */
			if (empty($_POST['submit'])) {
				/* Get the image's information */
				$result =& $db->query('SELECT * FROM '.TP.'uploads WHERE upID = '.$_GET['image']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$img = $config['absPath'].$config['uploadsPath'].$line['upFilename'];
				/* If the file exists, display the confirmation form */
				if (file_exists($img)) {
					$out = "\t\t\t\t\t".'<form action="index.php?s=uploads&amp;a=delete&amp;image='.$_GET['image'].'" method="post">'."\n";
					$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Delete Uploaded Image ('.$line['upName'].')</h4></td></tr>';
					$out .= "\t\t\t\t\t".'<tr><td colspan="2">Are you sure you want to delete: <span style="font-weight: bold;">'.$line['upName'].'</span>?</td></tr>'."\n";
					$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_delete_on.png\';" onmouseout="this.src=\'icons/btn_delete.png\';" type="image" src="icons/btn_delete.png" name="submit" value="Delete" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="cancel(); return false;" /></td></tr>'."\n";
					$out .= "\t\t\t\t\t".'</form>'."\n";
				} else {
					/* Otherwise, print error message */
					$err = 'File does not exist!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
				}
			} else {
				/* Otherwise, delete the image fomr the database, and physical file from uploads folder, print messages */
				$result =& $db->query('SELECT * FROM '.TP.'uploads WHERE upID = '.$_GET['image']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$img = $config['absPath'].$config['uploadsPath'].$line['upFilename'];
				$result =& $db->query('DELETE FROM snaps_uploads WHERE upID = '.$_GET['image']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					if (unlink($img)) {
						$err = 'Image Deleted.';
						echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
					} else {
						$err = 'Image deleted from database, but could not delete image file. Please remove "'.$img.'" manually.';
						echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
					}
				} else {
					$err = 'Image could not be deleted.';
					echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
				}
			}
			break;
		case 'move' :
			/* If the form has not been submitted */
			if (empty($_POST['submit'])) {
				/* Get the image's information and list of albums, display move/add to album form */
				$result =& $db->query('SELECT * FROM '.TP.'uploads WHERE upID = '.$_GET['image']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$result =& $db->query('SELECT * FROM '.TP.'albums');
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$out = "\t\t\t\t\t".'<form action="index.php?s=uploads&amp;a=move&amp;image='.$_GET['image'].'" method="post">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Add Image ('.$line['upName'].') to Album</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Add To:</td><td><select name="toAlbum">';
				while ($ln =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
					$out .= '<option value="'.$ln['albumID'].'">'.$ln['albumName'].'</option>';
				}
				$out .= '</select></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_add_on.png\';" onmouseout="this.src=\'icons/btn_add.png\';" type="image" src="icons/btn_add.png" name="submit" value="Add" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="cancel(); return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				/* Otherwise, get image's information, update album image count, add the image to database, move physical image, delete from uploads table, print messages */
				$result =& $db->query('SELECT * FROM '.TP.'uploads WHERE upID = '.$_GET['image']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$src_img = $config['absPath'].$config['uploadsPath'].$line['upFilename'];
				$dst_img = $config['absPath'].$config['albumsPath'].$_POST['toAlbum'].'/'.$line['upFilename'];
				$result =& $db->query('UPDATE '.TP.'albums SET albumCount = albumCount+1, albumModified = UNIX_TIMESTAMP() WHERE albumID = '.$_POST['toAlbum']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$result =& $db->query('INSERT INTO '.TP.'images (`imageID`, `albumID`, `imageName`, `imageDesc`, `imageFilename`, `imageSubName`, `imageSubEmail`, `imageCreated`, `imageModified`, `imageViews`) VALUES ("", "'.$_POST['toAlbum'].'", "'.$line['upName'].'", "'.$line['upDesc'].'", "'.$line['upFilename'].'", "'.$line['upSubName'].'", "'.$line['upSubEmail'].'", "'.$line['upCreated'].'", UNIX_TIMESTAMP(), 0)');
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					if (rename($src_img, $dst_img)) {
						$result =& $db->query('DELETE FROM '.TP.'uploads WHERE upID = '.$_GET['image']);
						if (DB::isError($result)) {
							die($result->getMessage());
						}
						$err = 'Successfully added image.';
						echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
					} else {
						$err = 'Added image to database, but physical move failed. Please move "'.$src_img.'" to "'.$dst_img.'".';
						echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
					}
				} else {
					$err = 'Add failed. Please make sure the albums directories are chmod 755 or 777.';
					echo '<script type="text/javascript">document.location.href = "index.php?s=uploads&err='.$err.'";</script>';
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