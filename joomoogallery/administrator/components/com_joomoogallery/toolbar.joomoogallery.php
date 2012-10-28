<?php
/**
 * @version     $Id: toolbar.joomoogallery.php,v 1.3 2008/10/31 06:13:22 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

/**
 * @package     Joomla
 * @subpackage  Joomoogallery
 */
switch( $task )
{
	case 'add':
	case 'edit':
		TOOLBAR_joomoogallery:: _NEW();
		break;

	default:
		TOOLBAR_joomoogallery::_DEFAULT();
		break;
}
?>
