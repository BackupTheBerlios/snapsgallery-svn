DROP TABLE IF EXISTS `snaps_albums`;
CREATE TABLE `snaps_albums` (
  `albumID` int(11) NOT NULL auto_increment,
  `albumName` varchar(255) NOT NULL default '',
  `albumDesc` text NOT NULL,
  `albumCount` int(11) NOT NULL default '0',
  `albumCreated` int(11) NOT NULL default '0',
  `albumModified` int(11) NOT NULL default '0',
  PRIMARY KEY  (`albumID`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

DROP TABLE IF EXISTS `snaps_comments`;
CREATE TABLE `snaps_comments` (
  `commentID` int(11) NOT NULL auto_increment,
  `imageID` int(11) NOT NULL default '0',
  `commentName` varchar(255) NOT NULL default '',
  `commentEmail` varchar(255) NOT NULL default '',
  `commentBody` text NOT NULL,
  `commentCreated` int(11) NOT NULL default '0',
  PRIMARY KEY  (`commentID`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

DROP TABLE IF EXISTS `snaps_config`;
CREATE TABLE `snaps_config` (
  `var` varchar(255) NOT NULL default '',
  `val` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`var`)
) TYPE=MyISAM;

INSERT INTO `snaps_config` (`var`, `val`) VALUES ('version', '1.0');
INSERT INTO `snaps_config` (`var`, `val`) VALUES ('absPath', 'c:/htdocs/ourpadre/Snaps!/');
INSERT INTO `snaps_config` (`var`, `val`) VALUES ('albumsPath', 'albums/');
INSERT INTO `snaps_config` (`var`, `val`) VALUES ('cachePath', 'cache/');
INSERT INTO `snaps_config` (`var`, `val`) VALUES ('uploadsPath', 'uploads/');
INSERT INTO `snaps_config` (`var`, `val`) VALUES ('snapsURL', 'http://localhost/ourpadre/Snaps!/');
INSERT INTO `snaps_config` (`var`, `val`) VALUES ('albumsPP', '9');
INSERT INTO `snaps_config` (`var`, `val`) VALUES ('imagesPP', '9');

DROP TABLE IF EXISTS `snaps_images`;
CREATE TABLE `snaps_images` (
  `imageID` int(11) NOT NULL auto_increment,
  `albumID` int(11) NOT NULL default '0',
  `imageName` varchar(255) NOT NULL default '',
  `imageDesc` text NOT NULL,
  `imageFilename` varchar(255) NOT NULL default '',
  `imageSubName` varchar(255) NOT NULL default '',
  `imageSubEmail` varchar(255) NOT NULL default '',
  `imageCreated` int(11) NOT NULL default '0',
  `imageModified` int(11) NOT NULL default '0',
  `imageViews` int(11) NOT NULL default '0',
  PRIMARY KEY  (`imageID`)
) TYPE=MyISAM AUTO_INCREMENT=15 ;

DROP TABLE IF EXISTS `snaps_uploads`;
CREATE TABLE `snaps_uploads` (
  `upID` int(11) NOT NULL auto_increment,
  `upSubName` varchar(255) NOT NULL default '',
  `upSubEmail` varchar(255) NOT NULL default '',
  `upName` varchar(255) NOT NULL default '',
  `upDesc` text NOT NULL,
  `upFilename` varchar(255) NOT NULL default '',
  `upCreated` int(11) NOT NULL default '0',
  PRIMARY KEY  (`upID`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

DROP TABLE IF EXISTS `snaps_users`;
CREATE TABLE `snaps_users` (
  `userID` int(11) NOT NULL auto_increment,
  `userFname` varchar(255) NOT NULL default '',
  `userLname` varchar(255) NOT NULL default '',
  `userEmail` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default '',
  `userpass` varchar(32) NOT NULL default '',
  `userLastLogin` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userID`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

INSERT INTO `snaps_users` (`userID`, `userFname`, `userLname`, `userEmail`, `username`, `userpass`, `userLastLogin`) VALUES (1, 'Snaps!', 'Admin', 'snaps@sonicdesign.us', 'sadmin', 'c5edac1b8c1d58bad90a246d8f08f53b', 0);
