<?php
/**
 * @version     $Id: view.html.php,v 1.8 2008/10/31 06:37:28 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Joomoogallery component
 */
class JoomoogalleryViewJoomoogalleryPage extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// print "view.html.php: top of JoomoogalleryViewJoomoogallery::display method<br />\n";

		$params = &$app->getParams();      // Get the parameters of the active menu item
		$this->assignRef('params', $params);
		$groupsModel =& $this->getModel( 'joomoogalleryGroups' );
		$imagesModel =& $this->getModel( 'joomoogalleryImages' );
		$this->assignRef( 'groupsModel', $groupsModel );
		$this->assignRef( 'imagesModel', $imagesModel );

		parent::display($tpl);
	}
}
