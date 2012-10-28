<?php
/**
 * @version     $Id: controller.php,v 1.20 2008/10/31 06:31:54 tomh Exp tomh $
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
 * Joomoogallery Component Controller
 */
class JoomoogalleryController extends JController
{
	//
	// This component uses two models; inheritance structure is as follows:
	// JoomoobaseModelJoomoobaseDb - base class - in com_joomoobase/models/joomoobaseDb.php
	//    JoomoogalleryModelJoomoogallery - parent class - in administrator/com_joomoogallery/models/joomoogallery.php
	//       joomoogallerygroups: interacts with table of the same name in DB
	//       joomoogalleryimages: interacts with table of the same name in DB
	//
	/**
	 * path to code for base of model classes
	 * @access private
	 * @var string file path to base class of joomoo db model classes
	 */
	private $_baseModelPath;
	/**
	 * path to code for parent of model classes
	 * @access private
	 * @var string file path to parent of model classes
	 */
	private $_parentModelPath;
	/**
	 * path to code for groups model class
	 * @access private
	 * @var string file path to groups model class
	 */
	private $_groupsModelPath;
	/**
	 * path to code for images model class
	 * @access private
	 * @var string file path to images model class
	 */
	private $_imagesModelPath;
	/**
	 * model supporting access to groups table in DB
	 * @access private
	 * @var instance of JoomoogalleryModeljoomoogallerygroups
	 */
	private $_groupsModel = '';
	/**
	 * model supporting access to images table in DB
	 * @access private
	 * @var instance of JoomoogalleryModeljoomoogalleryimages
	 */
	private $_imagesModel = '';

	/**
	 * Constructor: set the model paths
	 * @access public
	 */
	public function __construct( $default = array() )
	{
		parent::__construct( $default );

		// print "Hello from JoomoogalleryController::__construct()<br />\n";

		require_once JPATH_COMPONENT.DS.'assets'.DS.'constants.php';

		$this->_baseModelPath = JPATH_SITE.DS.'components'.DS.'com_joomoobase'.DS.'models'.DS.'joomoobaseDb.php';
		$this->_parentModelPath = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_joomoogallery'.DS.'models'.DS.'joomoogallery.php';
		$this->_groupsModelPath = JPATH_COMPONENT.DS.'models'.DS.'joomoogallerygroups.php';
		$this->_imagesModelPath = JPATH_COMPONENT.DS.'models'.DS.'joomoogalleryimages.php';

		// $this_groupsModel_class = get_class( $this->_groupsModel );
		// print "Still in __construct(): this_groupsModel_class = \"$this_groupsModel_class\"<br />\n";
		// print "JoomoogalleryController::__construct(): models created OK?<br />\n";
	}

	/**
	 * Shows a list of gallery pages or a specific page (depending on view)
	 * @access public
	 */
	public function display()
	{
		// print "Hello from JoomoogalleryController::display() in file controller.php<br />\n";

		require_once $this->_baseModelPath;
		require_once $this->_parentModelPath;
		require_once $this->_groupsModelPath;
		require_once $this->_imagesModelPath;
		$this->_groupsModel = new JoomoogalleryModeljoomoogallerygroups();
		$this->_imagesModel = new JoomoogalleryModeljoomoogalleryimages();

		$viewName = JRequest::getVar('view');
		$view = $this->getView( $viewName, 'html' );
		$view->setModel( $this->_groupsModel, true );        // 'true' makes this the default model
		$view->setModel( $this->_imagesModel );

		$pageid = JRequest::getVar('Itemid');
		$this->_groupsModel->setPageid( $pageid );

		// print "display():: viewName = \"$viewName\"<br />\n";

		switch ( $viewName )
		{
			case 'joomoogallerylist':
				$this->showList( $view );
				break;
			case 'joomoogallerypage':
			default:
				$this->showPage( $view );
				break;
		}
	}
	/**
	 * Shows a list of gallery pages: loads views/joomoogallerylist/tmpl/default.php
	 * @access protected
	 */
	protected function showList( &$view )
	{
		// print "Hello from JoomoogalleryController::showList() in file controller.php<br />\n";

		$view->display();
	}
	/**
	 *
	 * Shows a gallery page: loads views/joomoogallerypage/tmpl/default.php
	 * @access protected
	 */
 	protected function showPage( &$view )
 	{
		$app = JFactory::getApplication();

		// print "Hello from JoomoogalleryController::showPage()) in file controller.php<br />\n";

		//
		// Add the javascript files
		// ------------------------
		// We must add the mootools here to ensure it's included before the accordion file (that uses it)
		//
		$componentName = $app->scope;
		$document =& JFactory::getDocument();

		$document->addScript($app->getCfg('live_site').'/media/system/js/mootools-core.js');
		$document->addScript($app->getCfg('live_site').'/media/system/js/mootools-more.js');
		$document->addScript($app->getCfg('live_site').'/components/' . $componentName . '/assets/accordion.js');

		$params = &$app->getParams();      // Get the parameters of the active menu item

		if ( $params->get('allow_resizing') )
		{
			$document->addScript($app->getCfg('live_site').'/components/' . $componentName . '/assets/mousewheel.js');
			$groupids = $this->_groupsModel->getGroupidsForPageid();
			$this->_imagesModel->setGroupids( $groupids );
			$javascript = $this->_imagesModel->getJavascript();
			$document->addScriptDeclaration( $javascript );
		}

		$view->display();
	}
}
