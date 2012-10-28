<?php
/**
 * @version     $Id: joomoogallerygroups.php,v 1.4 2008/10/31 06:12:03 tomh Exp tomh $
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
 * Joomla class interface to #__joomoogallerygroups table
 * Loaded automatically by framework because we follow the naming conventions.
 */
class TableJoomoogalleryGroups extends JTable
{
	/**
	 */
	/** @var int Primary Key */
	var $id = null;
	/** @var int possible link to the page on which this group appears */
	var $pageid = null;
	/** @var string Title of image */
	var $title = null;
	/** @var string Description of image */
	var $description = null;
	/** * @var int comments flag */
	public $comments = null;
	/** @var int number of columns of images within group */
	var $columns = null;
	/** @var int Sequence of group within page */
	var $ordering = null;
	/** @var int Published flag */
	var $published = null;

	/**
	 * Constructor
	 */
	function __construct( &$db )
	{
		parent::__construct( '#__joomoogallerygroups', 'id', $db );

		// print "Hello from TableJoomoogallerygroups::__construct()<br />\n";
	}

	/**
	 * Validator: ensure required values get set
	 * @return boolean True if values are valid else False
	 */
	function check()
	{
		if ( ! $this->title )
		{
			$this->setError( JText::_('Please specify a title for this group.') );
			return false;
		}

		// if ( ! $this->description )
		// {
		// 	$this->setError( JText::_('Please supply a description of this group.') );
		// 	return false;
		// }

		return true;
	}
}
