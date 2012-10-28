#
# SQL statements to help save typing when querying and making changes
#

#
# For modifications to tables after they were created
#
ALTER TABLE `jos_joomoogalleryimages` ADD COLUMN `likes` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `published`;

ALTER TABLE `jos_joomoogalleryimages` ADD COLUMN `comments` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `description`;
ALTER TABLE `jos_joomoogallerygroups` ADD COLUMN `comments` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `description`;

ALTER TABLE `jos_joomoogalleryimages` ADD COLUMN `rating` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `comments`;

#
# Getting the syntax for the select statement we need for filtering by page id when viewing a list of images
#
select distinct pageid from jos_joomoogallerygroups;
select id, pageid, title from jos_joomoogallerygroups where pageid = 64;
select id, groupid, title from jos_joomoogalleryimages where groupid in (select id from jos_joomoogallerygroups where pageid = 64) ;

