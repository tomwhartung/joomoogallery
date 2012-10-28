# 
# version     $Id: install.joomoogallerygroups.sql,v 1.3 2008/11/03 21:40:41 tomh Exp tomh $
# author      Tom Hartung <webmaster@tomhartung.com>
# database    MySql
# copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
# license     TBD
#
#
# SQL to create jos_joomoogallerygroups table
#
DROP TABLE IF EXISTS `jos_joomoogallerygroups`;
CREATE TABLE IF NOT EXISTS `jos_joomoogallerygroups`
(
	`id` int(11) unsigned not null default null auto_increment,
	`pageid` smallint(2) unsigned not null default '0',
	`title` varchar(255) not null default '',
	`description` text null default '',
	`comments` tinyint(1) unsigned not null default '0',
	`columns` smallint(2) unsigned not null default '1',
	`ordering` int(11) unsigned not null default '0',
	`published` tinyint(1) unsigned not null default '0',
	PRIMARY KEY (`id`)
) CHARACTER SET `utf8` COLLATE `utf8_general_ci`;

