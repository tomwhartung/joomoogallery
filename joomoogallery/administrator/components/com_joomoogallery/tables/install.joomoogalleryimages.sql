#
# version     $Id: install.joomoogalleryimages.sql,v 1.5 2008/11/03 21:40:41 tomh Exp tomh $
# author      Tom Hartung <webmaster@tomhartung.com>
# database    MySql
# copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
# license     TBD
# 
#
# SQL to create jos_joomoogalleryimages table
#
DROP TABLE IF EXISTS `jos_joomoogalleryimages`;
CREATE TABLE IF NOT EXISTS `jos_joomoogalleryimages`
(
	`id` int(11) unsigned not null default null auto_increment,
	`groupid` smallint(3) unsigned not null default '0',
	`path` varchar(255) not null default '',
	`title` varchar(255) not null default '',
	`description` text null default '',
	`comments` tinyint(1) unsigned not null default '0',
	`rating` tinyint(1) unsigned not null default '0',
	`ordering` int(11) unsigned not null default '0',
	`date_added` datetime not null default '0000-00-00 00:00:00',
	`published` tinyint(1) unsigned not null default '0',
	PRIMARY KEY (`id`),
	INDEX (`groupid`),
	CONSTRAINT image_to_group FOREIGN KEY (`groupid`) REFERENCES jos_joomoogallerygroup(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) CHARACTER SET `utf8` COLLATE `utf8_general_ci`;

