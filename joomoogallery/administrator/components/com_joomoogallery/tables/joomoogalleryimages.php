<?php
/**
 * @version     $Id: joomoogalleryimages.php,v 1.6 2008/10/31 06:12:03 tomh Exp tomh $
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
 * Joomla interface to #__joomoogalleryimages table
 */
class TableJoomoogalleryImages extends JTable
{
	/**
	 * @var int Primary Key
	 */
	public $id = null;
	/**
	 * @var int Foreign Key: link to #__joomoogallerygroups table
	 */
	public $groupid = null;
	/**
	 * @var string Directory and file name of image file
	 */
	public $path = null;
	/**
	 * @var string Title of image
	 */
	public $title = null;
	/**
	 * @var string Description of image
	 */
	public $description = null;
	/**
	 * @var int comments flag
	 */
	public $comments = null;
	/**
	 * @var int rating flag
	 */
	public $rating = null;
	/**
	 * @var int Sequence of image within group
	 */
	public $ordering = null;
	/**
	 * @var date Date image added
	 */
	public $date_added = null;
	/**
	 * @var int Published flag
	 */
	public $published = null;

	/**
	 * Constructor
	 */
	function __construct( &$db )
	{
		parent::__construct( '#__joomoogalleryimages', 'id', $db );

		// print "Hello from TableJoomoogalleryimages::__construct()<br />\n";
	}

	/**
	 * Validator: ensure required values get set
	 * @return boolean True if values are valid else False
	 */
	function check()
	{
		if ( ! $this->path )
		{
			$this->setError( JText::_('Please specify the directory path and file name of the image.') );
			return false;
		}

		if ( ! $this->title )
		{
			$this->setError( JText::_('Please specify a title for this image.') );
			return false;
		}

		// if ( ! $this->description )
		// {
		// 	$this->setError( JText::_('Please supply a description of the image.') );
		// 	return false;
		// }

		return true;
	}
}
