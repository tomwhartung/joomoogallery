#
# SQL to create one or more rows in the jos_joomoogallerygroups table
#
INSERT INTO jos_joomoogallerygroups ( pageid, title, description, columns, ordering, published )
	values ( 1, "first group title", "first group description", 2, 1, 1 );

INSERT INTO jos_joomoogallerygroups ( pageid, title, description, columns, ordering, published )
	values ( 2, "second group title", "second group description", 3, 2, 1 );

INSERT INTO jos_joomoogallerygroups ( pageid, title, description, columns, ordering, published )
	values ( 3, "third group title", "third group description", 4, 3, 1 );

INSERT INTO jos_joomoogallerygroups ( pageid, title, description, columns, ordering, published )
	values ( 4, "fourth group title", "fourth group description", 5, 3, 1 );

DESC jos_joomoogallerygroups;

SELECT count(*) FROM jos_joomoogallerygroups;
SELECT id, description FROM jos_joomoogallerygroups;
SELECT * FROM jos_joomoogallerygroups;

#
#  Other miscellaneous examples:
#
alter table jos_joomoogallerygroups change column page_id page_id smallint(3) unsigned not null default '0';
alter table jos_joomoogallerygroups change column page_id pageid smallint(3) unsigned not null default '0';
select pageid as value, title as text from jos_joomoogallerygroups order by value;
ALTER TABLE jos_joomoogallerygroups CHANGE COLUMN description description text NULL default '';

