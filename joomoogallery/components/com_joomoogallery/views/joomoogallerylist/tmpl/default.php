<?php
/**
 * @version     $Id: default.php,v 1.9 2008/10/31 06:38:16 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */
/*
 * default.php: display list of galleries
 * --------------------------------------
 * call model code to get data from DB
 * foreach loop in this file produces the HTML for the list
 */

defined('_JEXEC') or die('Restricted access');

JHTML::_('stylesheet', 'joomoogallery.css', 'components/com_joomoogallery/assets/');
$show_page_title = JRequest::getBool( 'show_page_title', true );

//	print "show_page_title = " . $show_page_title . "<br />";
//	print "page_title = " . $this->params->get( 'page_title' ) . "<br />";
//	print "Hello from file com_joomoogallery/views/joomoogallerylist/tmpl/default.php<br />\n";
?>

<a name="component_top" id="component_top"></a>
<?php if ( $show_page_title ) : ?>
 <div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
  <?php echo $this->params->get( 'page_title' ); ?>
 </div>
<?php endif; ?>

<?php if ( $this->params->get( 'show_description' ) ) : ?>
  <p class="joomoogallery">
  <?php echo $this->params->get( 'description' ); ?>
  </p>
<?php endif; ?>

<?php if ( $this->params->get( 'show_instructions' ) ) : ?>
  <p class="joomoogallery">Choose a gallery from the following list:
  </p>
<?php endif; ?>

<?php
$groupsModel = $this->groupsModel;
$imagesModel = $this->imagesModel;
$galleryPages = $this->get( 'GalleryPages' );    // calls getGalleryPages() in groupsModel

print '<ul class="joomoogallery_list">' . "\n";

foreach( $galleryPages as $galleryPage )
{
	//	print 'galleryPage:<br />:' . print_r($galleryPage,true) . "<br />\n";
	$pageid = $galleryPage->id;
	$link = $galleryPage->link . '&Itemid=' . $pageid;
	$route = JRoute::_( $link );

	$groupids     = $groupsModel->getGroupidsForPageid( $pageid );
	$imagesOnPage = $imagesModel->getTotalImageCount( $groupids );

	print ' <li class="joomoogallery_link">' . "\n";
	print '  <a href="' . $route . '">' . $galleryPage->title . '</a>' . "\n";

	if ( 0 < $imagesOnPage )
	{
		print '   (' . $imagesOnPage . ' images)' . "\n";
	}
	print ' </li>' . "\n";
	// print '  link: <a href="' . $link . '">' . $link . '</a><br />' . "\n";
	// print '  route: <a href="' . $route . '">' . $route . '</a><br />' . "\n";
}
print '</ul>' . "\n";

?>
