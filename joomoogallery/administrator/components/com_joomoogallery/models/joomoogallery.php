<?php
/**
 * @version     $Id: joomoogallery.php,v 1.11 2008/10/31 06:15:48 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Base class for models in the Joomoogallery component
 */
class JoomoogalleryModelJoomoogallery extends JoomoobaseModelJoomoobaseDb
{
	/**
	 * ids of pages that are gallery component
	 * @access private
	 * @var array
	 */
	private $_pageids = array();

	/**
	 * id of gallery component (from #__components table)
	 * @access private
	 * @var integer used to obtain list of gallery pages (menu items)
	 */
	private $_galleryId = 0;

	/**
	 * Overridden constructor
	 * @access public
	 */
	public function __construct( $config = array() )
	{
		//	print "Hello from JoomoogalleryModelJoomoogallery::__construct()<br />\n";

		parent::__construct( $config );
	}

	/**
	 * get array of ids of gallery component pages
	 * @access public
	 * @return array data from #__menu for joomoo gallery pages
	 */
	public function getPageids( )
	{
		//	print "Hello from JoomoogalleryModelJoomoogallery::getPageids()<br />\n";

		if ( count($this->_pageids) == 0 )
		{
			$query = 'SELECT id AS value, title AS text ' .
			    ' FROM #__menu ' .
			    ' WHERE component_id = ' . $this->getGalleryId() .
				 ' AND link like ' . "'%joomoogallerypage%'" .
			     ' AND published = 1 ' .
			    ' ORDER BY text;';
			$db =& $this->getDBO();
			$db->setQuery( $query );
			$this->_pageids = $db->loadObjectList();
		}

		//	print "JoomoobaseModelJoomoobase::this->_pageids: " . print_r($this->_pageids,true) . "<br />";
		return $this->_pageids;
	}

	/**
	 * create lists array containing ordering and filtering lists
	 * @access public
	 * @return array lists to use when outputing HTML to display the list of rows
	 */
	public function getLists( )
	{
		//	print "Hello from JoomoogalleryModelJoomoogallery::getLists()<br />\n";

		$this->_setupPageidFiltering( );
		$this->_setupCommentsFlagFiltering( );
		$this->_setupRatingFlagFiltering( );
		parent::getLists();

		return $this->_lists;
	}

	/**
	 * builds order by clause for _listquery (implements ordering)
	 * @access protected
	 * @return: order by clause for query
	 */
	protected function _getOrderByClause( $orderByColumns )
	{
		// print "Hello from JoomoogalleryModelJoomoogallery::_getOrderByClause()<br />\n";
		$option = JRequest::getCmd('option');
		$modelName = $this->getName();

		if ( $modelName == 'joomoogalleryimages' )
		{
			$default_filter_order = 'groupid';
		}
		else
		{
			$default_filter_order = 'pageid';
		}

		$orderByClause = parent::_getOrderByClause( $orderByColumns, $default_filter_order );

		//
		// Hmm this was not in the other models, not sure whether we need it.
		//    seems to work OK without it so "delete me you wuss"
		//
		//	if ( $filter_order != 'ordering' )
		//	{
		//		$orderByClause .= ', ordering ASC ';
		//	}

		// print "_getOrderByClause: returning orderByClause = \"$orderByClause\"<br />\n";
		return $orderByClause;
	}

	/**
	 * get id of gallery component from #__components (there can be only one)
	 * @access protected
	 * @return integer number of gallery component
	 */
	protected function getGalleryId( )
	{
		// print "Hello from JoomoogalleryModelJoomoogallery::getGalleryId()<br />\n";
		// print( "getGalleryId: this->_galleryId = $this->_galleryId<br />\n" );

		if ( $this->_galleryId == 0 )
		{
			$query = "SELECT extension_id FROM #__extensions WHERE name = 'com_joomoogallery';";
			$db =& $this->getDBO();
			$db->setQuery( $query );
			$this->_galleryId = $db->loadResult();
			// print( "getGalleryId: query = $query<br />\n" );
			// print( "getGalleryId: this->_galleryId = $this->_galleryId<br />\n" );
		}

		return $this->_galleryId;
	}

	/**
	 * sets comments column for current row
	 * @access public
	 * @return void
	 */
	public function setComments( $comments )
	{
		//	print "Hello from JoomoogalleryModelJoomoogallery::setComments()<br />\n";
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable();

		if ( 0 < count($cids) )
		{
			foreach ( $cids as $cid )
			{
				$row->id = $cid;
				$row->comments = $comments;
				if ( !$row->store() )
				{
					print "setComments: error! error message = " . $row->getError . "<br />\n";
					$this->setError( $row->getError() );
					return false;
				}
			}
		}
		else
		{
			$this->setError( 'No row(s) specified for modification!' );
			return false;
		}

		return true;
	}

	/**
	 * Set up filtering on pageid
	 * @access private
	 * @return array including pageid element
	 */
	private function _setupPageidFiltering( )
	{
		//	print "Hello from _setupPageidFiltering<br />\n";
		$app = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		//
		// add the first select option then append the pageids from the DB
		// build the form control (select list)
		//
		$options = array();
		$filter_pageid = $app->getUserStateFromRequest( $option.'filter_pageid', 'filter_pageid' );
		// $js = 'onchange="document.adminForm.submit();"';  // from p. 235 of Mastering book
		$js = "onchange=\"if (this.options[selectedIndex].value!=''){document.adminForm.submit();}\"";
		$options[] = JHTML::_('select.option', '0', '- '.JText::_('Select Gallery Page').' -' );
		$options = array_merge( $options, $this->_pageids );
		$this->_lists['pageid'] = JHTML::_('select.genericlist',
		                                     $options,
		                                     'filter_pageid',
		                                     'class="inputbox" size="1" '.$js,
		                                     'value', 'text', $filter_pageid);
		return $this->_lists;
	}
	/**
	 * Set up filtering on comments flag
	 * @return array of lists
	 */
	private function _setupCommentsFlagFiltering( )
	{
		//	print "Hello from _setupCommentsFlagFiltering<br />\n";
		$app = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		//
		// build the form control (select list)
		//
		$filter_comments = $app->getUserStateFromRequest( $option.'filter_comments', 'filter_comments' );
		$js = "onchange=\"if (this.options[selectedIndex].value!=''){document.adminForm.submit();}\"";
		$options[] = JHTML::_('select.option', '-1', '- '.JText::_('Select Comment Flag').' -' );
		$options[] = JHTML::_('select.option', '0', ''.JText::_('Comments Disabled').'' );
		$options[] = JHTML::_('select.option', '1', ''.JText::_('Comments Enabled').'' );
		$this->_lists['comments'] = JHTML::_('select.genericlist',
		                                     $options,
		                                     'filter_comments',
		                                     'class="inputbox" size="1" '.$js,
		                                     'value', 'text', $filter_comments);
		return $this->_lists;
	}

	/**
	 * Set up filtering on rating flag
	 * @return array of lists
	 */
	private function _setupRatingFlagFiltering( )
	{
		// print "Hello from _setupRatingFlagFiltering<br />\n";
		$app = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		//
		// build the form control (select list)
		//
		$filter_rating = $app->getUserStateFromRequest( $option.'filter_rating', 'filter_rating' );
		$js = "onchange=\"if (this.options[selectedIndex].value!=''){document.adminForm.submit();}\"";
		$options[] = JHTML::_('select.option', '-1', '- '.JText::_('Select Rating Flag').' -' );
		$options[] = JHTML::_('select.option', '0', ''.JText::_('Rating Disabled').'' );
		$options[] = JHTML::_('select.option', '1', ''.JText::_('Rating Enabled').'' );
		$this->_lists['rating'] = JHTML::_('select.genericlist',
		                                     $options,
		                                     'filter_rating',
		                                     'class="inputbox" size="1" '.$js,
		                                     'value', 'text', $filter_rating);
		return $this->_lists;
	}
}
?>
