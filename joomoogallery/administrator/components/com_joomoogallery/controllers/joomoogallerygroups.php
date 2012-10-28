<?php
/**
 * @version     $Id: joomoogallerygroups.php,v 1.10 2008/10/31 18:10:32 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * controller for managing Joomoogallerygroups table in DB
 */
class JoomoogalleryControllergroups extends JoomoogalleryController
{
	/**
	 * Constructor: set model name in parent class
	 */
	public function __construct( $default = array() )
	{
		parent::__construct( $default );
		$this->_modelName = 'Joomoogallerygroups';

		// print( "in JoomoogalleryControllergroups::__construct() calling print_r on default:<br />\n" );
		// print_r( $default );
		// print( "<br />\n" );
	}

	/**
	 * get model and view for component and call display() in view
	 * --> called by framework when task is not handled by another method in this class (i.e. when task is blank)
	 * @access public
	 * @return void
	 */
	public function display()
	{
		$modelName = $this->getModelName();
		$model =& $this->getModel( $modelName );         // instantiates model class
		// print "Hello from JoomoogalleryControllergroups::display() where modelName = " . $modelName . "<br />\n";
		// print "<br />model: " . print_r($model,true) . "<br />\n";

		$view  =& $this->getView( 'Joomoogallerygroups', 'html' );  // 'html': use view.html.php (not view.php)
		$view->setModel( $model, true );                            // true: this is the default model

		// print "<br />view: " . print_r($view,true) . "<br />\n";
		$view->display();
	}

	/**
	 * display the single-record edit form
	 * --> called by framework when task is 'edit' (or 'add', when that task is registered as such)
	 * @access public
	 * @return void
	 * @link http://docs.joomla.org/Tutorial:Developing_a_Model-View-Controller_Component_-_Part_4_-_Creating_an_Administrator_Interface
	 */
	public function edit()
	{
		// print "Hello from JoomoogalleryControllergroups::edit()<br />\n";
		// $this->printTask();
		// $this->printTaskArray();

		JRequest::setVar( 'view', 'joomoogallerygroups' );   // sets view directory to views/joomoogallerygroups/
		JRequest::setVar( 'layout', 'edit_row' );            // sets layout (view file) to (view directory)/tmpl/edit_row.php
	 	JRequest::setVar( 'hidemainmenu', 1 );               // turn off main menu while we are editing a single row
 
		parent::display();
	}
}
?>
