<?php
if (!empty($_GET['err'])) {
	$err = $_GET['err'];
} else {
	$err = '';
}
?>
			<h3><img src="icons/users.png" alt="Users" title="Users" style="vertical-align: middle;" /> Users</h3>
			<?php if (!empty($err)) { echo "\n".'<div class="box">'.$err.'</div><br />'."\n"; } ?>
			<div class="box">
				<script type="text/javascript">
					function cancel() {
						document.location.href = 'index.php?s=users';
					}
					function verifyPass() {
						p1 = document.getElementById('password').value;
						p2 = document.getElementById('password2').value;
						if (p1 == p2) {
							return true;
						} else {
							alert("Passwords do not match. Please correct this and try again.");
							return false;
						}
					}
				</script>
				<table cellpadding="2" cellspacing="0" border="0" style="width: 100%;">
<?php
if (empty($_GET['a']) && empty($_GET['user'])) {
?>
					<tr><td class="adminTD" style="background: #009; color: #FFF; font-weight: bold; width: 10%;">Edit</td><td class="adminTD" style="background: #009; color: #FFF; font-weight: bold; width: 10%;">Delete</td><td style="background: #009; color: #FFF; font-weight: bold; width: 80%;">User's Name</td></tr>
<?php
	$result =& $db->query('SELECT * FROM '.TP.'users');
	if (DB::isError($result)) {
		die($result->getMessage());
	}
	echo "\t\t\t\t";
	$bg = ' style="background: #CCC;"';
	while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
		$bg = ($bg == ' style="background: #CCC;"' ? '' : ' style="background: #CCC;"');
		echo "\t".'<tr><td class="adminTD"'.$bg.'><a href="index.php?s=users&amp;a=edit&amp;user='.$line['userID'].'"><img src="icons/edituser.png" alt="Edit" title="Edit" /></a></td><td class="adminTD"'.$bg.'><a href="index.php?s=users&amp;a=delete&amp;user='.$line['userID'].'"><img src="icons/deleteuser.png" alt="Delete" title="Delete" /></a></td><td'.$bg.'>'.$line['userFname'].' '.$line['userLname'].'</tr>'."\n\t\t\t\t";
	}
?>
</table>
				<br /><span style="padding: 5px; font-weight: bold;"><a href="index.php?s=users&amp;a=new"><img style="vertical-align: bottom;" src="icons/newuser.png" alt="New User" title="New User" /></a> Add New User</span>
<?php
} else {
	switch($_GET['a']) {
		case 'edit' :
			if (empty($_POST['submit'])) {
				$result =& $db->query('SELECT * FROM '.TP.'users WHERE userID = '.$_GET['user']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$out = "\t\t\t\t\t".'<form action="index.php?s=users&amp;a=edit&amp;user='.$_GET['user'].'" method="post" onsubmit="return verifyPass();">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Edit User</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">First Name:</td><td><input type="text" size="30" name="userFname" value="'.$line['userFname'].'" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Last Name:</td><td><input type="text" size="30" name="userLname" value="'.$line['userLname'].'" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">E-Mail:</td><td><input type="text" size="30" name="userEmail" value="'.$line['userEmail'].'" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Username:</td><td><input type="text" size="30" name="username" value="'.$line['username'].'" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Password:</td><td><input type="password" size="10" id="password" name="password" value="" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Verify:</td><td><input type="password" size="10" id="password2" name="password2" value="" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_edit_on.png\';" onmouseout="this.src=\'icons/btn_edit.png\';" type="image" src="icons/btn_edit.png" name="submit" value="Edit" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="cancel(); return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				if (empty($_POST['password']) && empty($_POST['password2'])) {
					$result =& $db->query('UPDATE '.TP.'users SET userFname = "'.mysql_escape_string($_POST['userFname']).'", userLname = "'.mysql_escape_string($_POST['userLname']).'", userEmail = "'.mysql_escape_string($_POST['userEmail']).'", username = "'.mysql_escape_string($_POST['username']).'" WHERE userID = '.$_GET['user']);
				} else {
					$result =& $db->query('UPDATE '.TP.'users SET userFname = "'.mysql_escape_string($_POST['userFname']).'", userLname = "'.mysql_escape_string($_POST['userLname']).'", userEmail = "'.mysql_escape_string($_POST['userEmail']).'", username = "'.mysql_escape_string($_POST['username']).'", userpass = "'.md5($_POST['password']).'" WHERE userID = '.$_GET['user']);
				}
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$err = 'Edit Successful!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=users&err='.$err.'";</script>';
				} else {
					$err = 'Edit Failed!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=users&err='.$err.'";</script>';
				}
			}
			break;
		case 'new' :
			if (empty($_POST['submit'])) {
				$out = "\t\t\t\t\t".'<form action="index.php?s=users&amp;a=new" method="post" onsubmit="return verifyPass();">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Create New User</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">First Name:</td><td><input type="text" size="30" name="userFname" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Last Name:</td><td><input type="text" size="30" name="userLname" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">E-Mail:</td><td><input type="text" size="30" name="userEmail" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Username:</td><td><input type="text" size="30" name="username" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Password:</td><td><input type="password" size="10" id="password" name="password" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;">Verify:</td><td><input type="password" size="10" id="password2" name="password2" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_create_on.png\';" onmouseout="this.src=\'icons/btn_create.png\';" type="image" src="icons/btn_create.png" name="submit" value="Edit" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="cancel(); return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				$result =& $db->query('INSERT INTO '.TP.'users (userID, userFname, userLname, userEmail, username, userpass) VALUES ("", "'.mysql_escape_string($_POST['userFname']).'", "'.mysql_escape_string($_POST['userLname']).'", "'.mysql_escape_string($_POST['userEmail']).'", "'.mysql_escape_string($_POST['username']).'", "'.md5($_POST['password']).'")');
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$err = 'User Created!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=users&err='.$err.'";</script>';
				} else {
					$err = 'User Creation Failed!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=users&err='.$err.'";</script>';
				}
			}
			break;
		case 'delete' :
			if (empty($_POST['submit'])) {
				$result =& $db->query('SELECT * FROM '.TP.'users WHERE userID = '.$_GET['user']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
				$out = "\t\t\t\t\t".'<form action="index.php?s=users&amp;a=delete&amp;user='.$_GET['user'].'" method="post">'."\n";
				$out .= "\t\t\t\t\t".'<tr><td colspan="2"><h4 style="margin-top: 0;">Delete User</h4></td></tr>';
				$out .= "\t\t\t\t\t".'<tr><td colspan="2">Are you sure you want to delete: <span style="font-weight: bold;">'.$line['userFname'].' '.$line['userLname'].'</span>?</td></tr>'."\n";
				$out .= "\t\t\t\t\t".'<tr><td style="text-align: right;"><input onmouseover="this.src=\'icons/btn_delete_on.png\';" onmouseout="this.src=\'icons/btn_delete.png\';" type="image" src="icons/btn_delete.png" name="submit" value="Delete" /></td><td><input onmouseover="this.src=\'icons/btn_cancel_on.png\';" onmouseout="this.src=\'icons/btn_cancel.png\';" type="image" src="icons/btn_cancel.png" onclick="cancel(); return false;" /></td></tr>'."\n";
				$out .= "\t\t\t\t\t".'</form>'."\n";
			} else {
				$result =& $db->query('DELETE FROM '.TP.'users WHERE userID = '.$_GET['user']);
				if (DB::isError($result)) {
					die($result->getMessage());
				}
				if ($db->affectedRows() > 0) {
					$err = 'User Deleted!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=users&err='.$err.'";</script>';
				} else {
					$err = 'User Deletion Failed!';
					echo '<script type="text/javascript">document.location.href = "index.php?s=users&err='.$err.'";</script>';
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