<?php
/**
 * @version     $Id: joomoogalleryimages.php,v 1.5 2008/10/31 18:10:14 tomh Exp tomh $
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
 * controller for managing Joomoogalleryimages table in DB
 */
class JoomoogalleryControllerimages extends JoomoogalleryController
{
	/**
	 * Constructor: set model name in parent class
	 */
	public function __construct( $default = array() )
	{
		// print( "Hello from JoomoogalleryControllerimages::__construct()<br />\n" );

		parent::__construct( $default );
		$this->_modelName = 'Joomoogalleryimages';
	}

	/**
	 * get model and view for component and call display() in view
	 * --> called by framework when task is not handled by another method in this class (i.e. when task is blank)
	 * @access public
	 * @return void
	 */
	public function display()
	{
		// print "Hello from JoomoogalleryControllerimages::display()<br />\n";
		// $this->printTask();

		$model =& $this->getModel( $this->getModelName() );         // instantiates model class
		$view  =& $this->getView( 'Joomoogalleryimages', 'html' );  // 'html': use view.html.php (not view.php)
		$view->setModel( $model, true );                            // true: this is the default model

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
		// print "Hello from JoomoogalleryControllerimages::edit()<br />\n";
		// $this->printTask();
		// $this->printTaskArray();

		JRequest::setVar( 'view', 'joomoogalleryimages' );   // sets view directory to views/joomoogalleryimages/
		JRequest::setVar( 'layout', 'edit_row' );            // sets layout (view file) to (view directory)/tmpl/edit_row.php
	 	JRequest::setVar( 'hidemainmenu', 1 );               // turn off main menu while we are editing a single row
 
		parent::display();
	}
	/**
	 * Sets rating flag in DB to appropriate value
	 * --> runs when user checks on one or more rows and clicks on Enable Rating or Disable Rating icon in toolbar
	 * @access public
	 * @return void
	 */
	public function ratingon( )
	{
		$option = JRequest::getCmd('option');
		print( "Hello world from JoomoogalleryController::ratingon()<br />\n" );

		if ( $this->getTask() == 'ratingoff' )
		{
			$rating = 0;
		}
		else
		{
			$rating = 1;
		}

		$model = $this->getModel( $this->getModelName() );
		$model->setRating( $rating );

		$link = 'index.php?option=' . $option . '&ctlr=' . $this->getCtlrReqVar();
		$this->setRedirect( $link );
	}

}
?>
