<?php
/**
 * @version     $Id: joomoogallerygroups.php,v 1.15 2008/10/31 06:34:05 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @link        http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Groups Model for com_joomoogallery component
 * Parent class is in administrator/components/com_joomoogallery/models/joomoogallery.php
 */
class JoomoogalleryModelJoomoogallerygroups extends JoomoogalleryModelJoomoogallery
{
	/**
	 * id of current page (within gallery component)
	 * @access private
	 * @var int value of component_id column in #__menu table for this page
	 */
	private $_pageid = 0;
	/**
	 * name of current page (within gallery component)
	 * @access private
	 * @var string value of name column in #__menu table for this page
	 */
	private $_pageName;
	/**
	 * data for pages (menu items) that are joomoo gallery pages
	 * @access private
	 * @var array data from #__menu about joomoo gallery pages
	 */
	private $_galleryPages = array();
	/**
	 * ids of groups that are part of a given gallery page
	 * @access private
	 * @var array groupids
	 */
	private $_groupids = array();

	/**
	 * Overridden constructor
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();

		// print "Hello from com_joomoogallery/models/joomoogallerygroups.php<br />\n";
		// print "JoomoogalleryModelJoomoogallerygroups::__construct()<br />\n";

		$this->_tableName = "#__joomoogallerygroups";
	}

	/**
	 * Sets ID of current page
	 * @access public
	 * @return void value of component_id column in #__menu
	 */
	public function setPageid( $pageid=0 )
	{
		$this->_pageid = $pageid;
	}
	/**
	 * Gets ID of current page
	 * @access public
	 * @return integer ID of current page
	 */
	public function getPageid ()
	{
		return $this->_pageid;
	}

	/**
	 * get array of rows from #__menu table for joomoogallery component pages
	 * @access public
	 * @return array data from #__menu table for joomoogallery pages
	 */
	public function getGalleryPages( )
	{
		//	print "Hello from JoomoogalleryModelJoomoogallerygroups::getGalleryPages()<br />\n";

		if ( count($this->_galleryPages) == 0 )
		{
			$query = 'SELECT id, link, title ' .
			    ' FROM #__menu ' .
			    ' WHERE component_id = ' . $this->getGalleryId() .
			      ' AND parent_id = ' . $this->_pageid .
			      ' AND published = 1' .
			    ' ORDER BY lft;';     // wtf as of 1.6 the ordering col is all "0"s??
			// $db =& $this->getDBO();
			// $db->setQuery( $query );
			// $this->_galleryPages = $db->loadObjectList();
			$this->_galleryPages = $this->_getList( $query );
		}

		//	print "getGalleryPages: query = $query<br />\n";

		return $this->_galleryPages;
	}

	/**
	 * get array of groupids for specified gallery page
	 * @access public
	 * @return array groupids belonging to pageid or $this->_pageid
	 */
	public function getGroupidsForPageid( $pageid = null )
	{
		// print "Hello from JoomoogalleryModelJoomoogallerygroups::getGroupidsForPageid()<br />\n";

		if ( $pageid == null )
		{
			$pageid = $this->_pageid;
		}

		$query = 'SELECT id FROM #__joomoogallerygroups ' .
		          ' WHERE pageid = ' . $pageid .
		           ' AND published = 1 ' .
		          ' ORDER BY ordering;';
		$this->_groupids = $this->_getList( $query );

		//	print( "getGroupidsForPageid: query = $query<br />\n" );

		return $this->_groupids;
	}

	/**
	 * builds where clause for _listquery (implements filtering)
	 * @access protected
	 * @return: where clause for query
	 */
	protected function _buildQueryWhere()
	{
		// print "Hello from JoomoogalleryModelJoomoogallerygroups::_buildQueryWhere()<br />\n";

		$whereClause = ' WHERE published = 1 AND pageid = ' . $this->_pageid;

		// print "_buildQueryWhere: returning whereClause = \"$whereClause\"<br />\n";
		return $whereClause;
	}
	/**
	 * builds order by clause for _listquery (implements ordering)
	 * @access protected
	 * @return: order by clause for query
	 */
	protected function _buildQueryOrderBy()
	{
		// print "Hello from JoomoogalleryModelJoomoogallerygroups::_buildQueryOrderBy()<br />\n";
		//
		// Note: At this time this function is a candidate for refactoring into the parent class
		//
		$orderByClause = ' ORDER BY ordering ASC';

		// print "_buildQueryOrderBy: returning orderByClause = \"$orderByClause\"<br />\n";
		return $orderByClause;
	}
}
?>
