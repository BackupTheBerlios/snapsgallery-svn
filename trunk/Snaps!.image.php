<?php
/*
*	File:				Snaps!.image.php
*	Description:	Creates and returns thumbnails of images
*	Copyright:	©2004 The Sonic Group, LLC
*	Website:		http://labs.sonicdesign.us/projects/Snaps!/
*
*	This program is free software; you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation; either version 2 of the License, or
*	(at your option) any later version.
*/

/* The file */
if (!empty($_GET['filename'])) {
	$filename = $_GET['filename'];
}
if (!empty($_GET['size'])) {
	$size = $_GET['size'];
}

if (empty($mode)) {
	$mode = 'dynamic';
}
/* Set a maximum height and width */
$width = $size;
$height = $size;

if ($mode != 'cache') {
	header('Content-type: image/jpeg');
}

/* Get new dimensions and mimetype */
list($width_orig, $height_orig, $mimetype) = getimagesize($filename);

if ($width_orig < $width) {
	$width = $width_orig;
	$height = $height_orig;
} else if ($width && ($width_orig < $height_orig)) {
   $width = ($height / $height_orig) * $width_orig;
} else {
   $height = ($width / $width_orig) * $height_orig;
}

/* Create new image */
$image_p = imagecreatetruecolor($width, $height);

/* Determine the mimetype and use appropriate function */
switch($mimetype) {
	case '1' :
		if (imagetypes() & IMG_GIF) {
			$image = imagecreatefromgif($filename);
		} else {
			$image = imagecreate(210, 30);
			/* white background and blue text */
			$bg = imagecolorallocate($image, 255, 255, 255);
			$textcolor = imagecolorallocate($image, 0, 0, 180);
			/* write the string at the top left */
			imagestring($image, 5, 0, 0, "Filetype not supported!", $textcolor);
		}
		break;
	case '2' :
		if (imagetypes() & IMG_JPG) {
			$image = imagecreatefromjpeg($filename);
		} else {
			$image = imagecreate(210, 30);
			/* white background and blue text */
			$bg = imagecolorallocate($image, 255, 255, 255);
			$textcolor = imagecolorallocate($image, 0, 0, 180);
			/* write the string at the top left */
			imagestring($image, 5, 0, 0, "Filetype not supported!", $textcolor);
		}
		break;
	case '3' :
		if (imagetypes() & IMG_PNG) {
			$image = imagecreatefrompng($filename);
		} else {
			$image = imagecreate(210, 30);
			/* white background and blue text */
			$bg = imagecolorallocate($image, 255, 255, 255);
			$textcolor = imagecolorallocate($image, 0, 0, 180);
			/* write the string at the top left */
			imagestring($image, 5, 0, 0, "Filetype not supported!", $textcolor);
		}
		break;
	default :
		break;
}
/* Resample */
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

/*Output */
if ($mode == 'cache') {
	imagejpeg($image_p, $outPath.$outFilename, 100);
} else {
	imagejpeg($image_p, null, 100);
}

/*
	$filename = escapeshellarg($filename);
	if ($mode == 'cache') {
		passthru("{$config['imPath']}convert -quality 100 -antialias -thumbnail {$width}x{$height} {$filename} {$outPath}{$outFilename}");
	} else {
		passthru("{$config['imPath']}convert -quality 100 -antialias -thumbnail {$width}x{$height} {$filename}");
	}
*/
?>