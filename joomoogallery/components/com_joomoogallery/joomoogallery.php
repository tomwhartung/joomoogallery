<?php
/**
 * @version     $Id: joomoogallery.php,v 1.10 2008/10/31 19:25:26 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// JTable::addIncludePath( JPATH_COMPONENT.DS.'tables' );  // enables JTable to find subclasses in tables subdir.
// print 'JPATH_COMPONENT = ' . JPATH_COMPONENT . "<br />\n";

require_once (JPATH_COMPONENT.DS.'controller.php'); // Require controller code
$controller = new JoomoogalleryController( );       // Create the controller

//
// get the task - even though it is most likely blank - because that's what's expected
//    in this component the controller uses the view name to determine what to do
//    this is because what we do depends on which menu option has been selected
//
$task = JRequest::getCmd('task');

// $view = JRequest::getVar('view');
// print "joomoogallery.php: view = \"$view\"<br />\n";
// $Itemid = JRequest::getVar('Itemid');
// print "joomoogallery.php: Itemid = \"$Itemid\"<br />\n";

$controller->execute( $task );   // Perform the Request task

$controller->redirect();         // Redirect if set by the controller
?>
