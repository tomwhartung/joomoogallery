<?php
/**
 * @version     $Id: default.php,v 1.26 2008/10/31 06:37:47 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */
/*
 * default.php: display requested gallery page
 * -------------------------------------------
 * call model code to get data from DB
 * call functions defined in this file to produce the HTML
 */

defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$componentName = $app->scope;
$show_page_title = JRequest::getBool( 'show_page_title', true );
JHTML::_('stylesheet', 'joomoogallery.css', 'components/' . $componentName . '/assets/');
require_once( JPATH_SITE.DS.'components'.DS.'com_joomoobase'.DS.'assets'.DS.'constants.php' );
print '<div class="joomoogallery_page" id="joomoogallery_page"><center>' . "\n";
?>

<a name="component_top" id="component_top"></a>
<?php if ( $show_page_title ) : ?>
 <div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
  <?php echo $this->params->get( 'page_title' ); ?>
 </div>
<?php endif; ?>

<?php
$groupsModel = $this->groupsModel;
$imagesModel = $this->imagesModel;
$params      = $this->params;
global $groupNum;    // sequential "serial number" for id for each group div for mootools
global $imageNum;    // sequential "serial number" for id for each image for mootools
$groupNum = 0;
$imageNum = 0;

if ( $this->params->get( 'show_description_1' ) )
{
	print '<p class="joomoogallery">';
	echo $this->params->get( 'description_1' );
	print "</p>\n";
}

/*
 * If Help option is enabled, put text in its own pane so we display it only if user wants it
 */
if ( $params->get('show_help') )
{
	jimport('joomla.html.pane');
	$paneParams = array(
		'startOffset' => 1,
		'startTransition' => '',
	//	'onActive' => 'tabs.setStyle( \'color\', \'#bd0d0d\' )',
	//	'onBackground' => 'tabs.setStyle( \'color\', \'#bdbe0d\' )'
	);
	$pane =& JPane::getInstance( 'Sliders', $paneParams );
	echo $pane->startPane( 'HelpPane' );
	{
		echo $pane->startPanel( 'Click for Help', 'help' );
		printHelp( $this->params );
		echo $pane->endPanel();
	}
	echo $pane->endPane();
}

printGalleryPanel( $groupsModel, $imagesModel, $params );
print '</center></div>   <!-- close of div with id="joomoogallery_page" -->' . "\n";

/**
 * print help information for joomoo gallery
 */
function printHelp ( $params )
{
	print '<ul class="joomoogallery_list">' . "\n";

	if ( $params->get( 'allow_resizing' ) )
	{
		print ' <li>Use the mouse wheel to change the size of an image' . "\n";
		print ' </li>' . "\n";
		if ( ! $params->get( 'all_at_once' ) )
		{
			print ' <li>If the image is cropped after resizing, change groups then return to resize the window</li>' . "\n";
		}
	}

	print ' <li>Click on an image to see it by itself in a new window</li>' . "\n";
//	print ' <li><a href="/index.php/com_joomoogallery&view=joomoogallerylist">Use this link to return to the list of galleries</a>' . "\n";
	print ' </li>' . "\n";
	print '</ul>' . "\n";
}
/**
 * print a joomoo gallery page
 */
function printGalleryPanel( $groupsModel, $imagesModel, $params )
{
	global $groupNum;
	global $imageNum;

	$pageid   = $groupsModel->getPageid();
	$groupids = $groupsModel->getGroupidsForPageid( $pageid );

	print '<h3 class="joomoogallery_title">Gallery Images</h3>' . "\n";

	if ( $params->get( 'show_description_2' ) )
	{
		print '<p class="joomoogallery">';
		echo $params->get( 'description_2' );
		print "</p>\n";
	}

	if ( $params->get('all_at_once') )
	{
		print '<div id="noaccordion">' . "\n";
	}
	else
	{
		print '<div id="accordion">' . "\n";
	}

	if ( $params->get('single_column') )
	{
		$imageHeight = $params->get( 'single_column_height' );
	}
	else
	{
		$imageHeight = $params->get( 'multi_column_height' );
	}

	foreach( $groupids as $groupidRow )
	{
		$groupid = $groupidRow->id;
		$groupsModel->setId( $groupid );
		$groupRow = $groupsModel->getRow( );
		$imagesModel->setGroupId( $groupid );
		$imagesModel->clearRows( );
		$imageRows = $imagesModel->getRows();
		$groupTitle = $groupRow->title;
		$divIdAttr = 'div' . sprintf( "%02d", $groupNum );
		$groupNum++;
		if ( $params->get('all_at_once') )
		{
			print ' <h4>' . $groupTitle . '</h4>' . "\n";
			print ' <div class="noelement" id="' . $divIdAttr . '">' . "\n";
		}
		else
		{
			print ' <h4 class="toggler"><a name="group_title" id="group_title">' . $groupTitle . '</a></h4>' . "\n";
			print ' <div class="element" id="' . $divIdAttr . '">' . "\n";
		}
		printGroup( $groupRow, $imageRows, $params, $imageHeight );
		print ' </div>    <!-- close of div with class="element" -->' . "\n";
	}

	print '</div>   <!-- close of div with id="accordion" -->' . "\n";
}

/**
 * print a joomoo gallery group
 */
function printGroup( $groupRow, $imageRows, $params, $imageHeight )
{
	global $imageNum;
	
	if ( $params->get('single_column') )
	{
		$columns = 1;
	}
	else
	{
		$columns = $groupRow->columns;
	}

	$widthPercent = (int) (100 / $columns);  // want all cols to be the same width

	$show_group_descriptions = $params->get( 'show_group_descriptions' );
	$show_image_titles       = $params->get( 'show_image_titles' );
	$show_image_descriptions = $params->get( 'show_image_descriptions' );
	$image_title_location    = $params->get( 'image_title_location' );

	// print "show_group_descriptions = $show_group_descriptions<br />\n";
	// print "show_image_titles       = $show_image_titles<br />\n";
	// print "show_image_descriptions = $show_image_descriptions<br />\n";
	// print "image_title_location    = $image_title_location<br />\n";

	if ( $show_group_descriptions == 1 )
	{
		print '  <div class="joomoogallery_group_description">' . "\n";
		print '   <p class="joomoogallery">' . $groupRow->description . '</p>' . "\n";
		print '  </div>' . "\n";
	}

	$currentColumn = 0;
	$ratingPlaceholderRegex = '/' . JOOMOO_RATING_PLACEHOLDER . '/';
	$fixedPlaceholderRegex = '/' . JOOMOO_FIXED_RATING_REGEX . '/';

	print '  <table class="joomoogallery_group">' . "\n";
	print '   <tr>' . "\n";

	foreach( $imageRows as $imageRow )
	{
		$id = $imageRow->id;
		$title = $imageRow->title;
		$path = $imageRow->path;
		$imgIdAttr = 'img' . sprintf( "%03d", $imageNum++ );
		$description = preg_replace( $ratingPlaceholderRegex, '', $imageRow->description );
		$description = preg_replace( $fixedPlaceholderRegex, '', $description );
		$link = '/index.php?option=com_joomoogallery&view=joomoogalleryimage&id=' . $id;
		$route = JRoute::_( $link );

		if ( $columns < ++$currentColumn )
		{
			print '   </tr>' . "\n";
			print '   <tr>' . "\n";
			$currentColumn = 1;
		}
		print '    <td class="joomoogallery_image" width="' . $widthPercent . '%">' . "\n";
		if ( $show_image_titles == 1 &&
		     $image_title_location == 0 )
		{
			print '      <span class="joomoogallery_image_title">'  . $title . '</span><br />' . "\n";
		}

		//	print 'id = ' . $id . '<br />' . "\n";
		//	print 'route = ' . $route . '<br />' . "\n";
		print '     <a href="' . $route . '" target="_blank">' . "\n";
		print '      <img class="joomoogallery_image" id="' . $imgIdAttr . '" src="' . $path . '" ' .
		               'height="' . $imageHeight . '" ' .
		               'title="' . $description . '" alt="' . $description . '" /><br />' . "\n";
		print '     </a>' . "\n";

		if ( $show_image_titles == 1 &&
		     $image_title_location == 1 )
		{
			print '      <span class="joomoogallery_image_title">'  . $title . "</span>\n";
		}
		if ( $show_image_descriptions == 1 )
		{
			print '     <div class="joomoogallery_image_description">' . $description . "</div>\n";
		}
		print '    </td>' . "\n";
	}

	print '   </tr>' . "\n";
	if ( $groupRow->comments )
	{
		print '   <tr><td colspan="' . $columns . '"><center>' . "\n";
		printComments( $groupRow );
		print '   </center></td></tr>' . "\n";
	}
	print '  </table>' . "\n";
}
/**
 * print the comments entered for a joomoo gallery group
 */
function printComments( $groupRow )
{
	$app = JFactory::getApplication();
	static $commentFormNumber = 0;

	require_once( JPATH_SITE.DS.'components'.DS.'com_joomoocomments'.DS.'assets'.DS.'constants.php' );
	JPluginHelper::importPlugin('content', 'joomoocomments');

	$articleXhtml = $groupRow->description . JOOMOO_COMMENTS_PLACEHOLDER;
	$gallerygroupid = $groupRow->id;
	$document = & JFactory::getDocument();
	$readmore_link = $document->base;

	$article = new stdClass();
	$article->id = 0;
	$article->contentid = 0;
	$article->gallerygroupid = $gallerygroupid;
	$article->galleryimageid = 0;
	$article->readmore_link = $readmore_link;
	$article->text = $articleXhtml;
	$article->introtext = $articleXhtml;
	$article->fulltext = $articleXhtml;
	$article->formNumber = $commentFormNumber++;

	$arguments = array( &$article );
	$result = $app->triggerEvent( 'onBeforeDisplayContent', $arguments );
	$commentsXhtml = $result[0];
	print $commentsXhtml;
}

//	printLinkBack();
//	/**
//	 * print link back to list of galleries
//	 */
//	function printLinkBack()
//	{
//		// $link = '/index.php/option=com_joomoogallery&view=joomoogallerylist';
//		$link = '/index.php/galleries';
//		$route = JRoute::_( $link );
//	
//		print "<br />\n";
//		print 'link: <a href="' . $link . '">' . $link . '</a>' . "<br />\n";
//		print 'route: <a href="' . $route . '">' . $route . '</a>' . "<br />\n";
//	}
?>
