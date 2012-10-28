<?php
/**
 * @version     $Id: joomoogallery.php,v 1.13 2008/10/31 18:06:42 tomh Exp tomh $
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
 * base class for joomoogallery component controllers
 */
class JoomoogalleryController extends JController
{
	/**
	 * short name of the this controller, ie, value of controller request variable
	 * @access protected
	 * @var string which controller subclass to use, 'images' or 'groups' (default)
	 */
	protected $_ctlrReqVar = '';
	/**
	 * name of the model class this controller uses
	 * @access protected
	 * @var string
	 */
	protected $_modelName = '';

	/**
	 * Constructor
	 */
	public function __construct( $default = array() )
	{
		// print( "Hello from JoomoogalleryController::__construct()<br />\n" );

		parent::__construct( $default );
		$ctlrReqVar = JRequest::getWord('ctlr');
		$this->_ctlrReqVar = $ctlrReqVar;

		$this->registerTask( 'add',   'edit' );   // when $task = 'add' framework calls edit() function in controller
		$this->registerTask( 'apply', 'save' );   // similarly, $task = 'apply' calls save()
		$this->registerTask( 'unpublish', 'publish' );
		$this->registerTask( 'commentsoff', 'commentson' );
		$this->registerTask( 'ratingoff', 'ratingon' );
	}

	/**
	 * Returns short name of this controller, ie. value of controller request variable
	 * @return string name of model for this component
	 */
	public function getCtlrReqVar()
	{
		// print "Hello from JoomoogalleryController::getCtlrReqVar()<br />\n";

		return $this->_ctlrReqVar;
	}
	/**
	 * Returns name of this controller's model
	 * @return string name of model for this component
	 */
	public function getModelName()
	{
		// print "Hello from JoomoogalleryController::getModelName()<br />\n";
		// print "returning this->_modelName = \"$this->_modelName\" <br />\n";

		return $this->_modelName;
	}

	/**
	 * save a row and redirect to main page
	 * --> called by framework when task is 'save'
	 * @access public
	 * @return void
	 */
	public function save()
	{
		$option = JRequest::getCmd('option');

		// print "Hello from JoomoogalleryController::save()<br />\n";

		$model = $this->getModel( $this->getModelName() );

		if ( $this->getCtlrReqVar() == 'images' )
		{
			$typeOfData = 'image';
		}
		else
		{
			$typeOfData = 'group';
		}

		if ( $model->store() )
		{
			$message = JText::_( 'Joomoo Gallery ' . $typeOfData . ' data saved OK!' );
		}
		else
		{
			$message  = JText::_( 'Error saving Joomoo Gallery ' . $typeOfData . ' data: ' );
			$message .= JText::_( $model->getError() );
		}

		$task = $this->getTask();
		$link = 'index.php?option=' . $option;
 
		if ( $task == 'apply' )
 		{
			$id = $model->getId();
			$link .= '&task=edit&cid[]=' . $id;
		}

		$link .= '&ctlr=' . $this->getCtlrReqVar();

		$this->setRedirect( $link, $message );
	}

	/**
	 * Sets published flag in DB to appropriate value
	 * --> runs when user clicks on (Un)Published icon in listing of rows
	 * --> also runs when user checks on one or more rows and clicks on Publish or Unpublish
	 * @access public
	 * @return void
	 */
	public function publish( )
	{
		$option = JRequest::getCmd('option');
		//	print( "Hello world from JoomoogalleryController::publish()<br />\n" );

		if ( $this->getTask() == 'unpublish' )
		{
			$published = 0;
		}
		else
		{
			$published = 1;
		}

		$model = $this->getModel( $this->getModelName() );
		$model->setPublished( $published );

		$link = 'index.php?option=' . $option . '&ctlr=' . $this->getCtlrReqVar();
		$this->setRedirect( $link );
	}

	/**
	 * Sets comments flag in DB to appropriate value
	 * --> runs when user checks on one or more rows and clicks on Enable Comments or Disable Comments icon in toolbar
	 * @access public
	 * @return void
	 */
	public function commentson( )
	{
		$option = JRequest::getCmd('option');
		//	print( "Hello world from JoomoogalleryController::commentson()<br />\n" );

		if ( $this->getTask() == 'commentsoff' )
		{
			$comments = 0;
		}
		else
		{
			$comments = 1;
		}

		$model = $this->getModel( $this->getModelName() );
		$model->setComments( $comments );

		$link = 'index.php?option=' . $option . '&ctlr=' . $this->getCtlrReqVar();
		$this->setRedirect( $link );
	}

	/**
	 * remove record(s) (and redirect to main page) - calls delete() method in model
	 * --> called by framework when task is 'remove'
	 * @access public
	 * @return void
	 */
	public function remove()
	{
		$option = JRequest::getCmd('option');

		// print( "Hello world from JoomoogalleryController::remove()<br />\n" );
		// $model = $this->getModel('');
		$model = $this->getModel( $this->getModelName() );

		if ( $model->delete() )
		{
			$message = JText::_( 'Row(s) deleted OK.' );
		}
		else
		{
			$message  = JText::_( 'Error: Unable to delete one or more rows: ' );
			$message .= JText::_( $model->getError() );
		}
 
		$link = 'index.php?option=' . $option . '&ctlr=' . $this->getCtlrReqVar();
		$this->setRedirect( $link, $message );
	}
	/**
	 * cancel editing a record
	 * @access public
	 * @return void
	 */
	public function cancel()
	{
		$option = JRequest::getCmd('option');

		$message = JText::_( 'Operation cancelled.' );

		// $link = 'index.php?option=' . $option . '&ctlr=images';
		$link = 'index.php?option=' . $option . '&ctlr=' . $this->getCtlrReqVar();
		$this->setRedirect( $link, $message );
	}

	//	//
	//	// Methods useful when learning and debugging:
	//	// -------------------------------------------
	//	//
	//	/**
	//	 * print current task (hopefully helpful when learning and debugging)
	//	 * @access protected
	//	 * @return void
	//	 */
	//	protected function printTask()
	//	{
	//		$task = $this->getTask();
	//		print "task = $task<br />\n";
	//	}
	//	/**
	//	 * print array of available tasks in controller (hopefully helpful when learning and debugging)
	//	 * an available task is a public or protected function in this class (except constructor and display)
	//	 * @access protected
	//	 * @return void
	//	 */
	//	protected function printTaskArray()
	//	{
	//		$taskArray = $this->getTasks();
	//		foreach ( $taskArray as $key => $thisTask )
	//		{
	//			print "taskArray[$key] = $thisTask<br />\n";
	//		}
	//	}
}
?>
