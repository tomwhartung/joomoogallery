<?php
/**
 * @version     $Id: toolbar.joomoogallery.html.php,v 1.4 2008/10/31 06:13:22 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * @package     Joomla
 * @subpackage  Joomoogallery
 */
class TOOLBAR_joomoogallery
{
	/**
	 * Setup Joomoogallery toolbars
	 */
	function _NEW()
	{
		//	JToolBarHelper::media_manager();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	}

	function _DEFAULT()
	{
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'commentson', 'commentson.png', 'commentson.png', 'Enable<br />Comments' );
		JToolBarHelper::custom( 'commentsoff', 'commentsoff.png', 'commentsoff.png', 'Disable<br />Comments' );
		if ( JRequest::getVar('ctlr','','get','string') == 'images' )
		{
			JToolBarHelper::custom( 'ratingon', 'ratingon.png', 'ratingon.png', 'Enable<br />Rating' );
			JToolBarHelper::custom( 'ratingoff', 'ratingoff.png', 'ratingoff.png', 'Disable<br />Rating' );
		}
		JToolBarHelper::editList();
		JToolBarHelper::deleteList( "Are you sure you want to delete these rows?" );
	}
}
?>
