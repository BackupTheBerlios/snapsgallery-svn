<?php

/* sNaps! Gallery Functions */
/* ©2004 The Sonic Group, LLC */

/* Retrieve configuration information from the database */
$result =& $db->query('SELECT * FROM '.TP.'config');
if (DB::isError($result)) {
	die($result->getMessage());
}
while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
	$config[$line['var']] = $line['val'];
}

/**
* Function: pageHeader() - creates the page header
*
* @access public
* @var string $title - the title of the gallery
*/
function pageHeader($title) {
	global $config;
	$out = '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%;"><tr><td style="text-align: left;"><h1><a href="'.$_SERVER['PHP_SELF'].'">'.$title.'</a></h1></td><td style="text-align: right; padding: 5px;"><a href="'.$_SERVER['PHP_SELF'].'?action=login">Log In</a>';
	if ($config['allowSubmit'] == 1) {
		$out .= ' | <a href="'.$_SERVER['PHP_SELF'].'?action=submit">Submit Picture</a></td></tr></table><br />';
	} else {
		$out .= '</td></tr></table><br />';
	}
	return $out;
}
/**
* Function: crumb() - creates the breadcrumb navigation
*
* @access public
* @var string $index - the index page
* @var string/array $album - if album is the current page, it is a string with the album's name - if an image is the current page, it is an array with the albumID and it's name
* @var string $imgName - the image being viewed
*/
function crumb($index, $album, $imgName) {
	global $title;
	/* If no image name */
	if (empty($imgName)) {
		/* and no album name, we are on the album list page */
		if (empty($album)) {
			$bc = 'Album List';
		/* if we have an album name, we are on the image list page */
		} else {
			$bc = '<div class="snapsCrumb"><a href="'.$_SERVER['PHP_SELF'].'">Album List</a> -&gt; '.$album.'</div>';
		}
	/* otherwise, we are viewing an image */
	} else {
		$bc = '<div class="snapsCrumb"><a href="'.$_SERVER['PHP_SELF'].'">Album List</a> -&gt; <a href="'.$_SERVER['PHP_SELF'].'?album='.$album[0].'">'.$album[1].'</a> - &gt; '.$imgName.'</div>';
	}
	return $bc;
}
/**
* Function: makePagination() - creates page links
*
* @access public
* @var integer $albumID - the album to paginate
*/
function makePagination($albumID) {
	global $db, $config, $start;
	/* Get the total number of images in this album */
	$result =& $db->query('SELECT * FROM '.TP.'images WHERE albumID ='.$albumID);
	if (DB::isError($result)) {
		die($result->getMessage());
	}
	$numImages = $result->numRows();
	$out = 'Page: ';
	/* Figure out number of pages we should have */
	$totalPages = ceil($numImages/$config['imagesPP']);
	/* Figure out what page we are on */
	$current = $start/$config['imagesPP']+1;
	for ($i = 1; $i <= $totalPages; $i++) {
		/* If this is the current page, echo a non-link */
		if ($i == $current) {
			$out .=  '<span style="font-weight: bold;">[ '.$i.' ]</span>&nbsp;';
		/* otherwise, echo a link to the next page */
		} else {
			/* If $i is not 1 (i.e. the first page), the link is echoed with a start variable */
			if ($i != 1) {
				$out .= '<a href="'.$_SERVER['PHP_SELF'].'?album='.$albumID.'&amp;start='.$config['imagesPP']*($i-1).'">'.$i.'</a>&nbsp;';
			/* otherwise, the link is echoed as the first page (i.e. no start) */
			} else {
				$out .= '<a href="'.$_SERVER['PHP_SELF'].'?album='.$albumID.'">'.$i.'</a>&nbsp;';
			}
		}
	}
	return $out;
}
/**
* Function: albumList() - lists albums (index page)
*
* @access public
* @var string $title - the title of the gallery
*/
function albumList($title = 'Snaps! Gallery') {
	global $db;
	/* Get the number of albums */
	$result =& $db->query('SELECT * FROM '.TP.'albums');
	if (DB::isError($result)) {
		die($result->getMessage());
	}
	$numAlbums = $result->numRows();
	/* If we have albums, get their information */
	if ($numAlbums > 0) {
		$i = 1;
		while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$album[$i][0] =  '<a href="'.$_SERVER['PHP_SELF'].'?album='.$line['albumID'].'">';
			$album[$i][1] = $line['albumName'];
			$album[$i][2] = $line['albumDesc'];
			$album[$i][3] = $line['albumCount'];
			$album[$i][4] = $line['albumModified'];
			$i++;
		}
		/* Create the output page */
		$out = pageHeader($title);
		$out .= makeTable($album);
	/* otherwise, we have no albums */
	} else {
		$out = pageHeader($title).'<div>There are no albums yet!</div><br /><br />';
	}
	return $out;
}
/**
* Function: album() - views, creates, and deletes albums
*
* @access public
* @var string $action - view, create, or delete - defaults to view
* @var integer $albumID - ID of album to display
*/
function album($action = 'view', $albumID) {
	global $db, $title, $config, $start;
	switch($action) {
		/* if we are viewing an album */
		case 'view' :
			/* Get the album name */
			$result =& $db->query('SELECT * FROM '.TP.'albums WHERE albumID = '.$albumID);
			if (DB::isError($result)) {
				die($result->getMessage());
			}
			$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
			$albumTitle = $line['albumName'];
			$result =& $db->query('SELECT * FROM '.TP.'images');
			if (DB::isError($result)) {
				die($result->getMessage());
			}
			$numImages = $result->numRows();
			/* Get the images in the album */
			$result =& $db->query('SELECT * FROM '.TP.'images WHERE albumID = '.$albumID.' LIMIT '.$start.','.$config['imagesPP']);
			if (DB::isError($result)) {
				die($result->getMessage());
			}
			$albumImages = $result->numRows();
			/* If we have images, get their information */
			if ($albumImages > 0) {
				$i = 1;
				while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
					$image[$i][0] =  '<a href="'.$_SERVER['PHP_SELF'].'?album='.$albumID.'&amp;image='.$line['imageID'].'">';
					$image[$i][1] = $line['imageName'];
					if ($config['enableCache'] == 1) {
						$image[$i][2] = getImage($line['albumID'], $line['imageFilename'], $line['imageName'], 100, 'cache');
					} else {
						$image[$i][2] = getImage($line['albumID'], $line['imageFilename'], $line['imageName'], 100, 'dynamic');
					}
					$image[$i][3] = $line['imageDesc'];
					$image[$i][4] = $line['imageCreated'];
					$image[$i][5] = $line['imageModified'];
					$image[$i][6] = $line['imageViews'];
					if ($config['allowComment'] == 1) {
						/* Get number of comments for each image */
						$rslt =& $db->query('SELECT COUNT(*) FROM '.TP.'comments WHERE imageID = '.$line['imageID']);
						if (DB::isError($rslt)) {
							die($rslt->getMessage());
						}
						$ln =& $rslt->fetchRow(DB_FETCHMODE_ASSOC);
						$image[$i][8] = ($ln['COUNT(*)'] > 1) ? $ln['COUNT(*)'].' comments' : (($ln['COUNT(*)'] == 1) ? $ln['COUNT(*)'].' comment' : 'No comments');
					}
					$i++;
				}
				/* Create the output page */
				$out = pageHeader($title);
				$out .= crumb('index', $albumTitle, '');
				$out .= makeTable($image, 'album');
				if ($numImages > $config['imagesPP']) {
					$out .= '<div class="snapsCrumb" style="text-align: right;">'.makePagination($albumID).'</div><br /.>';
				} else {
					$out .= '<div class="snapsCrumb" style="text-align: right;">Page 1 of 1</div><br />';
				}
			/* otherwise, we have no images */
			} else {
				$out = pageHeader($title).crumb('index', $albumTitle, '').'<div style="margin-top: 10px;">There are no images in this album yet!</div><br /><br />';
			}
			return $out;
			break;
		case 'create' :
			break;
		case 'delete' :
			break;
		default:
			break;
	}
}
/**
* Function: image() - views, resizes, and deletes images
*
* @access public
* @var string $action - view or delete - defaults to view
* @var integer $albumID - ID of album picture belongs to
* @var integer $imgID - ID of image to work with
*/
function image($action = 'view', $albumID, $imgID) {
	global $db, $title, $config;
	switch($action) {
		case 'view' :
			/* Get album name */
			$result =& $db->query('SELECT * FROM '.TP.'albums WHERE albumID = '.$albumID);
			if (DB::isError($result)) {
				die($result->getMessage());
			}
			$line =& $result->fetchRow(DB_FETCHMODE_ASSOC);
			$albumTitle[0] = $albumID;
			$albumTitle[1] = $line['albumName'];
			/* Update number of views for this image */
			$result =& $db->query('UPDATE '.TP.'images SET imageViews = imageViews+1 WHERE albumID = '.$albumID.' AND imageID = '.$imgID);
			if (DB::isError($result)) {
				die($result->getMessage());
			}
			/* Get the image's information */
			$result =& $db->query('SELECT * FROM '.TP.'images WHERE albumID = '.$albumID.' AND imageID = '.$imgID);
			if (DB::isError($result)) {
				die($result->getMessage());
			}
			$i = 1;
			while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$image[$i][0] = $line['imageName'];
				$image[$i][1] = $line['imageFilename'];
				$image[$i][2] = $line['imageDesc'];
				$image[$i][3] = $line['imageCreated'];
				$image[$i][4] = $line['imageModified'];
				$image[$i][5] = $line['albumID'];
				if ($config['enableCache'] == 1) {
					$image[$i][6] = getImage($line['albumID'], $line['imageFilename'], $line['imageName'], 600, 'cache');
				} else {
					$image[$i][6] = getImage($line['albumID'], $line['imageFilename'], $line['imageName'], 600, 'dynamic');
				}
				$i++;
			}
			/* Create the output page */
			$out = pageHeader($title);
			$out .= crumb('index', $albumTitle, $image[1][0]);
			$out .= makeTable($image, 'image', $size = 600);
			if ($config['allowComment'] == 1) {
				$out .= '<div class="snapsCrumb"><h3>Comments</h3>';
				$out .= comment('view', $imgID, $albumID);
				$out .= '</div><br />';
			}
			return $out;
			break;
		case 'delete' :
			break;
		default :
			break;
	}
}
/**
* Function: comment() - view, create, and delete/moderate comments - called from image()
*
* @access private
* @var string $action - view, create, or delete - defaults to view
* @var integer $imgID - ID of image to view, add, delete/moderate comments for
* @var integer $albID - ID of album image belongs to
*/
function comment($action = 'view', $imgID, $albID) {
	global $db, $title;
	switch($action) {
		case 'view' :
			/* Get all the comments for this image */
			$result =& $db->query('SELECT * FROM '.TP.'comments WHERE imageID = '.$imgID);
			if (DB::isError($result)) {
				die($result->getMessage());
			}
			/* Get number of comments */
			$numComments = $result->numRows();
			/* If we have comments, get their information */
			if ($numComments > 0) {
				$i = 1;
				while ($line =& $result->fetchRow(DB_FETCHMODE_ASSOC)) {
					$comment[$i][0] = $line['commentName'];
					$comment[$i][1] = $line['commentEmail'];
					$comment[$i][2] = $line['commentBody'];
					$comment[$i][3] = $line['commentCreated'];
					$i++;
				}
				/* assemble the comments table and submission form */
				$out = makeTable($comment, 'comment');
				$out .= makeForm('comment');
			/* otherwise, there are no comments - assemble the submission form */
			} else {
				$out = 'There are no comments for this image.<br />';
				$out .= makeForm('comment');
			}
			return $out;
			break;
		case 'create' :
			$result =& $db->query("INSERT INTO `".TP."comments` ( `commentID` , `imageID` , `commentName` , `commentEmail` , `commentBody` , `commentCreated` ) VALUES ('', '".$imgID."', '".mysql_escape_string($_POST['commentName'])."', '".mysql_escape_string($_POST['commentEmail'])."', '".mysql_escape_string($_POST['commentBody'])."', UNIX_TIMESTAMP())");
			if (DB::isError($result)) {
				die($result->getMessage());
			}
			if ($db->affectedRows() > 0) {
				$out = pageHeader($title);
				$out .= '<div class="snapsNotes" style="clear: both;"><span style="color: #090; font-weight: bold;">Thank you for your comment.</span><br /><br /><a href="index.php?album='.$albID.'&amp;image='.$imgID.'">Back to the image</a>.</div><br />';
			} else {
				$out = pageHeader($title);
				$out .= '<div class="snapsNotes" style="clear: both;"><span style="color: #900; font-weight: bold;">There was a problem adding your comment.</span><br /><br /><a href="index.php?album='.$albID.'&amp;image='.$imgID.'">Back to the image</a>.</div><br />';
			}
			return $out;
			break;
		case 'delete' :
			break;
		default :
			break;
	}
}
/**
* Function: getImage() - returns image source if file exists, caches then returns image source if file does not exist
*
* @access private
* @var integer $albumID - ID of album picture belongs to
* @var string $imgFilename - image filename to work with
* @var string $imgName - name of image
* @var integer $size - thumbnail size
* @var string $mode - image generation mode (cache or dynamic)
*/
function getImage($albumID, $imgFilename, $imgName, $size, $mode) {
	global $db, $config;
	switch($mode) {
		case 'cache' :
			$outPath = $config['absPath'].$config['cachePath'];
			$outFilename = md5($imgFilename.'-'.$size).'.jpg';
			if (file_exists($outPath.$outFilename)) {
				return '<img src="'.$config['cachePath'].$outFilename.'" alt="'.$imgName.'" title="'.$imgName.'" />';
			} else {
				$filename = $config['absPath'].$config['albumsPath'].$albumID.'/'.$imgFilename;
				include('Snaps!.image.php');
				return '<img src="'.$config['cachePath'].$outFilename.'" alt="'.$imgName.'" title="'.$imgName.'" />';
			}
			break;
		case 'dynamic' :
			$filename = $config['albumsPath'].$albumID.'/'.$imgFilename;
			return '<img src="Snaps!.image.php?filename='.$filename.'&amp;size='.$size.'" alt="'.$imgName.'" title="'.$imgName.'" />';
			break;
	}
}
/**
* Function: makeTable() - assemble and return a table filled with data
*
* @access private
* @var array $data - information to be outputted - multi-dimensional array containing links, names, and extra data
* @var string $type - type of table - index, album, image, or comment
* @var integer $size - maximum width/height of thumbnail images - defaults to small thumbnails limit
*/
function makeTable($data, $type = 'index', $size = 100) {
	global $config;
	switch($type) {
		case 'index' :
			$out = "\n".'<table cellpadding="0" cellspacing="10" border="0" style="width: 100%;">'."\n\t".'<tr>'."\n";
			for ($i = 1; $i < count($data)+1; $i++) {
				$out .= "\t\t".'<td class="snapsTable">'.$data[$i][0].'<img src="images/album.png" alt="'.$data[$i][1].'" title="'.$data[$i][1].'" /></a><br />'.$data[$i][0].$data[$i][1].'</a><br />'.$data[$i][2].'<br /><div class="snapsNotes">('.$data[$i][3].' items in this album)<br />Last modified: '.date("m-d-Y", $data[$i][4]).'</div></td>'."\n";
				if ($i % 3 == 0) {
					if (!($i ==  count($data))) {
						$out .= "\t".'</tr>'."\n\t".'<tr>'."\n";
					}
				}
			}
			$out .= "\t".'</tr>'."\n".'</table>'."\n";
			break;
		case 'album' :
			$out = "\n".'<table cellpadding="0" cellspacing="10" border="0" style="width: 100%;">'."\n\t".'<tr>'."\n";
			for ($i = 1; $i < count($data)+1; $i++) {
				$comment = (!empty($data[$i][8])) ? $data[$i][8].'<br />' : '';
				$out .= "\t\t".'<td class="snapsTable">'.$data[$i][0].$data[$i][2].'</a><br />'.$data[$i][0].$data[$i][1].'</a><br /><br /><div class="snapsNotes"><span style="font-style: oblique;">Viewed '.$data[$i][6].' times<br />'.$comment.'</span>Created: '.date("m-d-Y", $data[$i][4]).'<br />Last modified: '.date("m-d-Y", $data[$i][5]).'</div></td>'."\n";
				if ($i % 3 == 0) {
					if (!($i ==  count($data))) {
						$out .= "\t".'</tr>'."\n\t".'<tr>'."\n";
					}
				}
			}
			$out .= "\t".'</tr>'."\n".'</table>'."\n";
			break;
		case 'image' :
			$out = "\n".'<table cellpadding="0" cellspacing="10" border="0" style="width: 100%;">'."\n\t".'<tr>'."\n";
			$out .= "\t\t".'<td class="snapsTable"><a href="'.$config['albumsPath'].$data[1][5].'/'.$data[1][1].'">'.$data[1][6].'</a></td>'."\n";
			$out .= "\t".'<tr>'."\n\t\t".'<td class="snapsTable">'.$data[1][2].'<br /><div class="snapsNotes">Created: '.date("m-d-Y", $data[1][3]).'<br />Last Modified: '.date("m-d-Y", $data[1][4]).'</div></td>'."\n";
			$out .= "\t".'</tr>'."\n".'</table>'."\n";
			break;
		case 'comment' :
			$out = "\n".'<table cellpadding="0" cellspacing="10" border="0" style="width: 100%;">'."\n\t";
			for ($i = 1; $i < count($data)+1; $i++) {
				$out .= '<tr>'."\n\t\t".'<td class="snapsTable" style="text-align: left;"><span class="snapsNotes">By: <a href="mailto:'.emailOB($data[$i][1]).'">'.$data[$i][0].'</a><br />Posted: '.date("m-d-Y @ g:i:s a", $data[$i][3]).'</span><br /><br />'.$data[$i][2].'</td>'."\n\t".'</tr>';
			}
			$out .= "\n".'</table>'."\n";
		default :
			break;
	}
	return $out;
}
/**
* Function: makeForm() - assemble and return a form
*
* @access private
* @var string $type - type of form - comment or upload
*/
function makeForm($type = 'comment') {
	global $album, $image;
	switch($type) {
		case 'comment' :
			$form = "\n".'<form name="comment" action="'.$_SERVER['PHP_SELF'].'?album='.$album.'&amp;image='.$image.'" method="post">'."\n";
			$form .= '<h3>Post a comment</h3>'."\n\t".'<div style="width: 500px; height: 250px;">'."\n\t\t";
			$form .= '<div style="clear: both; height: 24px;"><span style="width: 100px; float: left; text-align: right;">Name:</span><span style="width: 390px; float: right; text-align: left;"><input type="text" name="commentName" id="commentName" size="30" /></span></div>';
			$form .= "\n\t\t".'<div style="clear: both; height: 24px;"><span style="width: 100px; float: left; text-align: right;">*Email:</span><span style="width: 390px; float: right; text-align: left;"><input type="text" name="commentEmail" id="commentEmail" size="30" /></span></div>';
			$form .= "\n\t\t".'<div style="clear: both;"><span style="width: 100px; float: left; text-align: right;">Comment:</span><span style="width: 390px; float: right; text-align: left;"><textarea cols="60" rows="10" name="commentBody" id="commentBody"></textarea></span></div>';
			$form .= "\n\t\t".'<div style="clear: both; padding-top: 10px; height: 30px;"><span style="width: 100px; float: left; text-align: right;">&nbsp;</span><span style="width: 390px; float: right;"><input type="reset" name="reset" value="Clear" /> <input type="submit" name="submit" value="Post Comment" /></span></div>';
			$form .= "\n\t\t".'<div style="clear: both; padding-top: 10px; height: 30px; font-size: 9px;">*Your email will be obfuscated to protect against spam harvesters.</div>';
			$form .= "\n\t".'</div>'."\n";
			$form .= '</form>'."\n";
			break;
		case 'upload' :
			break;
		default :
			break;
	}
	return $form;
}
/**
* Function: emailOB() - obfuscate an email address
*
* @access private
* @var string $email - email address to obfuscate
*/
function emailOB($email) {
	// store obfuscated email
	$obbed = '';
	//Clean whitespace
	$email = trim($email);
	//Get length
	$len = strlen($email);
	//Loop through the characters, converting them
	for ($i = 0; $i < $len; $i++) {
		$obbed .= "&#".ord($email{$i}).";";
	}
	//Return the obfuscated e-mail address
	return $obbed;
}
?>