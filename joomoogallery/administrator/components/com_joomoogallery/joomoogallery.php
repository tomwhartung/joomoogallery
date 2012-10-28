<?php
/**
 * @version     $Id: joomoogallery.php,v 1.7 2008/10/30 06:18:46 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @link        http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );      // no direct access

// print "Hello from joomoogallery.php<br />\n";
//
// The model and controller classes each have a base class
// -------------------------------------------------------
// JoomoogalleryController - gallery controllers base class - in com_joomoogallery/controllers/joomoogallery.php
//    JoomoogalleryControllerGroups - in com_joomoogallery/controllers/groups.php
//    JoomoogalleryControllerImages - in com_joomoogallery/controllers/images.php
// JoomoobaseModelJoomoobaseDb - joomoo models base class - in com_joomoobase/models/joomoobaseDb.php
//    JoomoogalleryModelJoomoogallery - gallery models base class - in administrator/com_joomoogallery/models/joomoogallery.php
//       JoomoogalleryModelJoomooGalleryGroups - in administrator/com_joomoogallery/models/joomoogallerygroups.php
//       JoomoogalleryModelJoomooGalleryImages - in administrator/com_joomoogallery/models/joomoogalleryimages.php
//
require_once( JPATH_COMPONENT.DS.'controllers'.DS.'joomoogallery.php' );

$baseModelPath = JPATH_SITE.DS.'components'.DS.'com_joomoobase'.DS.'models'.DS.'joomoobaseDb.php';
require_once( $baseModelPath );
// print "Hello from joomoogallery.php where baseModelPath = \"" . $baseModelPath . "\"<br />\n";
// if ( ! class_exists('JoomoobaseModelJoomoobaseDb') )
// {
// 	print 'class_exists test failure for JoomoobaseModelJoomoobaseDb<br />' . "\n";
// }

require_once( JPATH_COMPONENT.DS.'models'.DS.'joomoogallery.php' );
JHTML::_('stylesheet', 'joomoogallery.css', 'administrator' .DS. 'components' .DS. 'com_joomoogallery' .DS. 'assets' .DS );
JTable::addIncludePath( JPATH_COMPONENT.DS.'tables' );  // enables JTable to find subclasses in tables subdir.

//
// Make sure we have a designated controller
//
$ctlr = JRequest::getWord('ctlr');

if ( $ctlr != 'images' )
{
	$ctlr = 'groups';    // default controller
}

$model = $ctlr;    // the model corresponds to controller

//
// This component supports two database tables:
// --------------------------------------------
//    ctlr = groups: click on Groups to manage the #__joomoogallerygroups table
//    ctlr = images: click on Images to manage the #__joomoogalleryimages table
// Each table has its own separate controller, model, and view
//
$linkGroups = 'index.php?option=com_joomoogallery&ctlr=groups';
$linkImages = 'index.php?option=com_joomoogallery&ctlr=images';
JSubMenuHelper::addEntry(JText::_('Groups'), $linkGroups, ($ctlr == 'groups') );
JSubMenuHelper::addEntry(JText::_('Images'), $linkImages, ($ctlr == 'images') );

//
// Require designated controller
// TODO: Improve error handling when file is not found
//
$controllerPath = JPATH_COMPONENT.DS.'controllers'.DS.'joomoogallery'.$ctlr.'.php';

if ( file_exists($controllerPath) )
{
	require_once $controllerPath;
}
else
{
	print "<p style='border: 3px solid red; font-size: 150%; font-weight: bold; padding: 10px;'>\n";
	print " admin.joomoogallery.php: missing controller: \"$ctlr\"<br />\n";
	print " controllerPath = \"$controllerPath\"<br />\n";
	print "</p>\n";
	$ctlr = '';   // instantiate base class controller so we at least get something
}

//
// Require model corresponding to controller
// Consider: Improving error handling when file or class is not found
//
$modelPath = JPATH_COMPONENT.DS.'models'.DS.'joomoogallery'.$model.'.php';

if ( file_exists($modelPath) )
{
	require_once $modelPath;
}
else
{
	print "<p style='border: 3px solid red; font-size: 150%; font-weight: bold; padding: 10px;'>\n";
	print " admin.joomoogallery.php: missing model: \"$model\"<br />\n";
	print " modelPath = \"$modelPath\"<br />\n";
	print "</p>\n";
}

$classname  = 'JoomoogalleryController'.$ctlr;
// print "joomoogallery.php: instantiating controller: \"" . $classname . "\"<br />\n";
$controller = new $classname();                      // Create the controller

$task = JRequest::getVar('task');
// print "joomoogallery.php: task: \"$task\"<br />\n";
$controller->execute( JRequest::getVar( 'task' ) );   // Perform the Request task
$controller->redirect();                              // Redirect if set by the controller

?>
