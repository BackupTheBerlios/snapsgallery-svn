<?php
if (!empty($_GET['err'])) {
	$err = $_GET['err'];
} else {
	$err = '';
}
?>
			<h3><img src="icons/comments.png" alt="Comments" title="Comments" style="vertical-align: middle;" /> Comments</h3>
			<?php if (!empty($err)) { echo "\n".'<div class="box">'.$err.'</div><br />'."\n"; } ?>
			<div class="box">
				<script type="text/javascript">
					function cancel() {
						document.location.href = 'index.php?s=comments';
					}
					function comment(id) {
						window.open('cmtDetail.php?comment='+id, 'cmtDetail', 'width=330,height=240,scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no');
					}
				</script>
				<table cellpadding="2" cellspacing="0" border="0" style="width: 100%;">
<?php
if (empty($_GET['a']) && empty($_GET['comment'])) {
?>
					<tr><td class="adminTD" style="background: #009; color: #FFF; font-weight: bold; width: 10%;">Delete</td><td class="adminTD" style="background: #009; color: #FFF; font-weight: bold; width: 10%;">Move</td><td style="background: #009; color: #FFF; font-weight: bold; width: 80%;">Comment</td></tr>
<?php
	$result =& $db->query('SELECT * FROM '.TP.'comments');
	if (DB::isError($result)) {
		die($result->getMessage());
	}
	if ($result->numRows() > 0) {
		echo "\t\t\t\t";
		$bg = ' style="background: #CCC;"';
		while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$bg = ($bg == ' style="background: #CCC;"' ? '' : ' style="background: #CCC;"');
			echo "\t".'<tr><td class="adminTD"'.$bg.'><a href="index.php?s=comments&amp;a=delete&amp;comment='.$line['commentID'].'"><img src="icons/deletecomments.png" alt="Delete" title="Delete" /></a></td><td class="adminTD"'.$bg.'><a href="index.php?s=comments&amp;a=move&amp;comment='.$line['commentID'].'"><img src="icons/movecomments.png" alt="Move" title="Move" /></a></td><td'.$bg.'>'.substr($line['commentBody'], 0, 50).'... <a href="javascript:comment('.$line['commentID'].');">More</a></td></tr>'."\n\t\t\t\t";
		}
	} else {
		echo '<tr><td colspan="3" class="adminTD">There are no comments.</td></tr>';
	}
?>
</table>
<?php
} else {
	switch($_GET['a']) {
		case 'delete' :
			if (empty($_POST['submit'])) {
				$result =& $db->query('SELECT * FROM '.TP.'comments WHERE commentID = '.$_GET['comment']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$rslt =& $db->query('SELECT imageName FROM '.TP.'images WHERE imageID = '.$line['imageID']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$ln =& $rslt->fetchRow(DB_FETCHMODE_ASSOC);
				$out = "\t\t\t\t\t".'<form action="index.php?s=comments&amp;a=delete&amp;comment='.$_GET['comment'].'" method="post">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Delete Comment</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td colspan="2">Are you sure you want to delete this comment?<br /><br /><div style="margin: 0 20px; padding: 3px; border: 1px solid #666; background: #FFF; font-size: 11px;">Comment for: '.$ln['imageName'].'<br />'.$line['commentBody'].'<br />by: '.$line['commentName'].'</div><br /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_delete_on.png\';" onmouseout="this.src=\'icons/btn_delete.png\';" type="image" src="icons/btn_delete.png" name="submit" value="Delete" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="cancel(); return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				$result =& $db->query('DELETE FROM '.TP.'comments WHERE commentID = '.$_GET['comment']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$err = 'Comment Deleted.';
					echo '<script type="text/javascript">document.location.href = "index.php?s=comments&err='.$err.'";</script>';
				} else {
					$err = 'Comment Deletion Failed';
					echo '<script type="text/javascript">document.location.href = "index.php?s=comments&err='.$err.'";</script>';
				}
			}
			break;
		case 'move' :
			if (empty($_POST['submit'])) {
				$result =& $db->query('SELECT commentName, imageID FROM '.TP.'comments WHERE commentID = '.$_GET['comment']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$result =& $db->query('SELECT * FROM '.TP.'images WHERE imageID = '.$line['imageID']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$ln =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$curImage = $ln['imageName'];
				$result =& $db->query('SELECT * FROM '.TP.'images');
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$out = "\t\t\t\t\t".'<form action="index.php?s=comments&amp;a=move&amp;comment='.$_GET['comment'].'" method="post">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Move Comment</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Move From:</td><td>'.$curImage.'</td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Move To:</td><td><select name="toImage">';
				while ($ln =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
					$out .= '<option value="'.$ln['imageID'].'">'.$ln['imageName'].'</option>';
				}
				$out .= '</select></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_move_on.png\';" onmouseout="this.src=\'icons/btn_move.png\';" type="image" src="icons/btn_move.png" name="submit" value="Move" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="cancel(); return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				$result =& $db->query('UPDATE '.TP.'comments SET imageID = '.$_POST['toImage'].' WHERE commentID = '.$_GET['comment']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$err = 'Comment Moved!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=comments&err='.$err.'";</script>';
				} else {
					$err = 'Comment Move Failed.';
					echo '<script type="text/javascript">document.location.href = "index.php?s=comments&err='.$err.'";</script>';
				}
			}
			break;
		default :
			break;
	}
	echo $out."\t\t\t\t".'</table>'."\n";
}
?>			</div>