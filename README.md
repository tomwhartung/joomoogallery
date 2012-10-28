joomoogallery
=============

JoomooGallery: joomla extension that displays lists of galleries, pages of gallery groups, and individual gallery images

 JoomooGallery
===============
This extension consists of a component that supports three types of views, i.e.,
menu options:

o  An option to display a list of gallery pages
o  An option to display a gallery page containing groups of images
o  An option to display a single image

Clicking on an image displayed on a gallery page causes the component to
display the single image on a page by itself.  Therefore site administrators
typically do not assign the option to display a single image to a menu item.

 Features
----------
The JoomooGallery extension contains PHP and Javascript code that provides a:

o  Backend component page to:
   o  List and add, change, and delete gallery groups
   o  List and add, change, and delete gallery images
   o  Site administrators can sort and filter both lists as is typical in
      the backend for other joomla components (e.g., content articles)
o  Backend component pages allowing site administrators to:
   o  Enable and disable joomoocomments for specific gallery groups and images
   o  Enable and disable joomooratings for specific gallery images

When creating a menu option for a gallery page in the backend, site
administrators can set options such as the following:

o  Image height, in pixels
o  Whether to display images in a single column or multiple columns
o  Whether to allow users to resize images with their mouse wheel
o  Whether to display image groups one at a time or all at once

See the section on Backend Parameters below for a description of each of the
options that site administrators can set in the backend for this extension.

 Database Tables and Columns
-----------------------------
This component uses two database tables, one for gallery groups and another
for gallery images.

Following are the columns in the jos_joomoogallerygroups table:

Field         Type                   Description
--------------------------------------------------------------------------------
id            int(11) unsigned       Standard joomla primary key
pageid        smallint(2) unsigned   Foreign key: jos_menu
title         varchar(255)           Gallery group title
description   text                   Gallery group description
comments      tinyint(1) unsigned    Joomoo comments flag (allow/disallow)
columns       smallint(2) unsigned   Number of columns of images in group
ordering      int(11) unsigned       Standard joomla ordering column
published     tinyint(1) unsigned    Standard joomla published flag

Following are the columns in the jos_joomoogalleryimages table:

Field         Type                   Description
--------------------------------------------------------------------------------
id            int(11) unsigned       Standard joomla primary key
groupid       smallint(3) unsigned   Foreign key: jos_joomoogallerygroups table
path          varchar(255)           Image directory path and file name
title         varchar(255)           Image title
description   text                   Image description
comments      tinyint(1) unsigned    Joomoo comments flag (allow/disallow)
rating        tinyint(1) unsigned    Joomoo rating flag (allow/disallow)
ordering      int(11) unsigned       Standard joomla ordering column
date_added    datetime               Date and time when image was added
published     tinyint(1) unsigned    Standard joomla published flag

 Menu Item (Backend) Parameters
--------------------------------
show_description_1
    Show description_1 after page heading and before help option?
    Options: Yes or No
description_1
    Description for after page heading and before help option
    Text field
show_description_2
    Show description_2 after help option and before images?
    Options: Yes or No
description_2
    Description for after help option and before images
    Text field
multi_column_height
    Initial height of images shown in more than one column
    Options: Range from 50px to 400px in increments of 25px
single_column_height
    Initial height of images shown in a single column
    Options: Range from 250px to 1000px in increments of 50px
single_column
    Display images in a single column?  Overrides number of columns specified
        for group.
    Options: Single column or Multiple columns
show_help
    Display link allowing user to see help text?
    Options: Hide or Show
allow_resizing
    Allow resizing of image via mouse wheel?
    Options: Disable or Enable
all_at_once
    Show all groups at once or one at a time?
    Options: One at a time or All at once
show_group_descriptions
    Show or hide group descriptions?
    Options: Hide or Show
show_image_titles
    Show or hide image titles?
    Options: Hide or Show
show_image_descriptions
    Show or hide image descriptions?
    Options: Hide or Show
image_title_location
    Where to put image titles?
    Options: Above Image or Below Image

