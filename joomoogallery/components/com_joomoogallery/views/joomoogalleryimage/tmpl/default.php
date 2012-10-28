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
//
// Note for images we use a default of false for show_page_title
//
$show_page_title = JRequest::getBool( 'show_page_title', false );
JHTML::_('stylesheet', 'joomoogallery.css', 'components/' . $componentName . '/assets/');
?>

<a name="component_top" id="component_top"></a>
<?php if ( $show_page_title ) : ?>
 <div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
  <?php echo $this->params->get( 'page_title' ); ?>
 </div>
<?php endif; ?>

<?php
$imagesModel = $this->imagesModel;
$imageRow = $imagesModel->getRow();

print '<center>' . "\n";
print ' <div class="joomoogallery_image_page">' . "\n";

if ( $imageRow->rating )
{
	$articleXhtml = getRatingAndArticle( $imageRow );
	print $articleXhtml;
}
else
{
	$articleXhtml = getArticle( $imageRow );
	print $articleXhtml;
}

if ( $imageRow->comments )
{
	$result = getComments( $imageRow, $articleXhtml );
	$commentsXhtml = $result[0];
	print $commentsXhtml;
}

print ' </div>  <!-- close of div with class = joomoogallery_image_page -->' . "\n";
print '</center>' . "\n";

/**
 * get the xhtml for a joomoo gallery page
 * first check for the fixed rating placeholder, and go with that if it's present
 * else if necessary, add the placeholder replace it with the rating
 */
function getRatingAndArticle( $imageRow )
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_joomoobase'.DS.'assets'.DS.'constants.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_joomoorating'.DS.'assets'.DS.'constants.php' );

	$description = $imageRow->description;
	$fixedPlaceholder = '&' . JOOMOO_FIXED_RATING_REGEX . '&';
	$hasFixedPlaceholder = preg_match( $fixedPlaceholder, $description, $matches );

	//
	// if we have a leading or trailing fixed placeholder
	//    we take it out and tell getArticle where to put it back in
	//
	if ( $hasFixedPlaceholder )
	{
		$overridesString = $matches[1];
		$placeholderToReplace = $fixedPlaceholder;
		JPluginHelper::importPlugin('content', 'joomoofixedrating');
		$leadingPlaceholderRegex = '&^\s*' . JOOMOO_FIXED_RATING_REGEX . '&';
		$hasLeadingPlaceholder = preg_match( $leadingPlaceholderRegex, $description, $matches );
		$leadingPlaceholderText = $matches[0];
		$trailingPlaceholderRegex = '&' . JOOMOO_FIXED_RATING_REGEX . '\s*$&';
		$hasTrailingPlaceholder = preg_match( $trailingPlaceholderRegex, $description, $matches );
		$trailingPlaceholderText = $matches[0];
		if ( $hasLeadingPlaceholder )
		{
			$imageRow->description = preg_replace( $leadingPlaceholderRegex, '', $description );
			$articleXhtml = getArticle( $imageRow, $leadingPlaceholderText, JOOMOO_RATING_BELOW_IMAGE );
		}
		else if ( $hasTrailingPlaceholder )
		{
			$imageRow->description = preg_replace( $trailingPlaceholderRegex, '', $description );
			$articleXhtml = getArticle( $imageRow, $trailingPlaceholderText, JOOMOO_RATING_BELOW_DESCRIPTION );
		}
		else
		{
			$articleXhtml = getArticle( $imageRow );
		}
	}
	else
	{
		$overridesString = null;
		JPluginHelper::importPlugin('content', 'joomoorating');
		$ratingPlaceholder = '&' . JOOMOO_RATING_PLACEHOLDER . '&';
		$hasRatingPlaceholder = preg_match( $ratingPlaceholder, $description );
		$placeholderToReplace = $ratingPlaceholder;
		if ( $hasRatingPlaceholder )
		{
			$articleXhtml = getArticle( $imageRow );
		}
		else
		{
			$where_on_gallery_page = 'x';
			$plugin =& JPluginHelper::getPlugin( 'content', 'joomoorating' );
			$params = new JParameter( $plugin->params );
			$where_on_gallery_page = $params->get('where_on_gallery_page');
			$articleXhtml = getArticle( $imageRow, JOOMOO_RATING_PLACEHOLDER, $where_on_gallery_page );
		}
	}

	$result = getRating( $imageRow, $articleXhtml, $overridesString );
	$ratingXhtml = '<div class="joomoogallery_image_page_rating">' . "\n" .
		$result[0] . "\n" . '</div>' . "\n";
	$articleXhtml = preg_replace( $placeholderToReplace, $ratingXhtml, $articleXhtml );
//	$articleXhtml .= 'where_on_gallery_page = ' . $where_on_gallery_page . '<br />';

	return $articleXhtml;
}
/**
 * get the xhtml for a joomoo gallery page
 * callers pass in placeHolder when we want to position the rating in a specific position on the page
 * in which case we have to add it inside this function because we want it to appear after the heading
 */
function getArticle( $imageRow, $placeHolder=null, $where_on_gallery_page='' )
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_joomoobase'.DS.'assets'.DS.'constants.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_joomoorating'.DS.'assets'.DS.'constants.php' );

	$articleXhtml = '';
	$title = $imageRow->title;
	$path = $imageRow->path;
	$description = $imageRow->description;

	$articleXhtml .= '  <h3 class="joomoogallery_title">' . $title . "</h3>\n";

	if ( $where_on_gallery_page == JOOMOO_RATING_ABOVE_IMAGE )
	{
		$articleXhtml .= $placeHolder . "\n";
	}

	$articleXhtml .= '  <div class="joomoogallery_the_image">' . "\n";
	$articleXhtml .= '   <center><img id="joomoogallery_the_image" src="' . $path . '" ' .
		'height="' . JOOMOO_GALLERY_IMAGE_HEIGHT . '" ' .
		'title="gallery image: ' . $title . '" alt="gallery image: ' . $title . '" /></center>' . "\n";
	$articleXhtml .= '  </div>   <!-- close of div with class = joomoogallery_the_image -->' . "\n";

	if ( $where_on_gallery_page == JOOMOO_RATING_BELOW_IMAGE )
	{
		$articleXhtml .= $placeHolder . "\n";
	}

	$articleXhtml .= '  <div class="joomoogallery_image_page_description">' . "\n";
	$articleXhtml .= '   <p class="joomoogallery">' . $description . "</p>\n";
	$articleXhtml .= '  </div>   <!-- close of div with class = joomoogallery_image_page_description -->' . "\n";

	if ( $where_on_gallery_page == JOOMOO_RATING_BELOW_DESCRIPTION )
	{
		$articleXhtml .= $placeHolder . "\n";
	}

	return $articleXhtml;
}
/**
 * trigger plugin event to get rating
 */
function getRating( $imageRow, $articleXhtml, $overridesString=null )
{
	$app = JFactory::getApplication();

	$id = $imageRow->id;
	$readmore_link = '/index.php?option=com_joomoogallery&view=joomoogalleryimage&id=' . $id;  // TODO: call JRoute(?)

	$article = new stdClass();
	$article->id = 0;
	$article->contentid = 0;
	$article->galleryimageid = $id;
	$article->readmore_link = $readmore_link;
	$article->text = $articleXhtml;
	$article->introtext = $articleXhtml;
	$article->fulltext = $articleXhtml;

	if ( $overridesString != null )
	{
		$article->overridesString = $overridesString;
	}

	$arguments = array( &$article );
	$result = $app->triggerEvent( 'onPrepareContent', $arguments );

	return $result;
}
/**
 * trigger plugin event to get comments
 */
function getComments( $imageRow, $articleXhtml )
{
	$app = JFactory::getApplication();

	require_once( JPATH_SITE.DS.'components'.DS.'com_joomoobase'.DS.'assets'.DS.'constants.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_joomoocomments'.DS.'assets'.DS.'constants.php' );
	JPluginHelper::importPlugin('content', 'joomoocomments');

	$articleXhtml = $articleXhtml . JOOMOO_COMMENTS_PLACEHOLDER;
	$id = $imageRow->id;
	$readmore_link = '/index.php?option=com_joomoogallery&view=joomoogalleryimage&id=' . $id;  // TODO: call JRoute(?)

	$article = new stdClass();
	$article->id = 0;
	$article->contentid = 0;
	$article->gallerygroupid = 0;
	$article->galleryimageid = $id;
	$article->readmore_link = $readmore_link;
	$article->text = $articleXhtml;
	$article->introtext = $articleXhtml;
	$article->fulltext = $articleXhtml;

	$arguments = array( &$article );
	$result = $app->triggerEvent( 'onBeforeDisplayContent', $arguments );

	return $result;
}
?>
