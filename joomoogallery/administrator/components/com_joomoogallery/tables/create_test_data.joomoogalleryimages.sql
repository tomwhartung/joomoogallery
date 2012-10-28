
#
# SQL to create one or more rows in the jos_joomoogalleryimages table
#
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 1, "first/image/path", "title of first image", "description of first image", 1, "2008-06-06 01:02:03", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "third/image/path", "title of third image", "description of third image", 1, "2008-06-16 11:12:13", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/10", "title of image 10", "description of image 10", 10, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/11", "title of image 11", "description of image 11", 11, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/12", "title of image 12", "description of image 12", 12, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/14", "title of image 14", "description of image 14", 14, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/15", "title of image 15", "description of image 15", 15, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/16", "title of image 16", "description of image 16", 16, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/17", "title of image 17", "description of image 17", 17, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/18", "title of image 18", "description of image 18", 18, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/19", "title of image 19", "description of image 19", 19, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/20", "title of image 20", "description of image 20", 20, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/21", "title of image 21", "description of image 21", 21, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/22", "title of image 22", "description of image 22", 22, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/23", "title of image 23", "description of image 23", 23, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/24", "title of image 24", "description of image 24", 24, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/25", "title of image 25", "description of image 25", 25, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/26", "title of image 26", "description of image 26", 26, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/27", "title of image 27", "description of image 27", 27, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/28", "title of image 28", "description of image 28", 28, "2008-06-16 10:10:00", 1 );
INSERT INTO jos_joomoogalleryimages ( groupid, path, title, description, ordering, date_added, published )
	values ( 2, "/image/path/29", "title of image 29", "description of image 29", 29, "2008-06-16 10:10:00", 1 );



select * from jos_joomoogalleryimages;
select count(*) from jos_joomoogalleryimages;

alter table jos_joomoogalleryimages change column group_id groupid smallint(3) unsigned not null default '0';
ALTER TABLE jos_joomoogalleryimages MODIFY COLUMN description text NULL default '';

