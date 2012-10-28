<?php
/**
 * @version     $Id: joomoogalleryimages.php,v 1.8 2008/10/31 06:15:48 tomh Exp tomh $
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
 * Joomoogalleryimages Model for com_joomoogallery component
 */
class JoomoogalleryModelJoomoogalleryimages extends JoomoogalleryModelJoomoogallery
{
	/**
	 * ids of groups in the #__joomoogallerygroups table
	 * @access private
	 * @var array
	 */
	private $_groupids = array();

	/**
	 * constructor
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
		// print "Hello from JoomoogalleryModelJoomoogalleryimages::__construct()<br />\n";

		$this->_tableName = "#__joomoogalleryimages";
	}

	/**
	 * get array of ids of gallery component groups
	 * @access public
	 * @return integer number of gallery component
	 */
	public function getGroupids( )
	{
		// print "Hello from getGroupids()<br />\n";

		if ( count($this->_groupids) == 0 )
		{
			$query = 'SELECT id AS value, title AS text ' .
			    ' FROM #__joomoogallerygroups ' .
			    ' ORDER BY text;';
			$db =& $this->getDBO();
			$db->setQuery( $query );
			$this->_groupids = $db->loadObjectList();
		}

		return $this->_groupids;
	}

	/**
	 * create lists array containing ordering and filtering lists
	 * @access public
	 * @return array lists to use when outputing HTML to display the list of rows
	 */
	public function getLists( )
	{
		//	print "Hello from JoomoogalleryModelJoomoogalleryimages::getLists()<br />\n";
		//	print "getLists before: count(this->_lists) = " . count($this->_lists) . "<br />\n";

		if ( count($this->_lists) == 0 )
		{
			$this->_setupGroupidFiltering( );
			parent::getLists();
			//	print "getLists after: count(this->_lists) = " . count($this->_lists) . "<br />\n";
		}

		return $this->_lists;
	}
	/**
	 * sets rating column for current row
	 * @access public
	 * @return void
	 */
	public function setRating( $rating )
	{
		//	print "Hello from JoomoogalleryModelJoomoogallery::setRating()<br />\n";

		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if ( 0 < count($cids) )
		{
			foreach ( $cids as $cid )
			{
				$row->id = $cid;
				$row->rating = $rating;
				if ( !$row->store() )
				{
					print "setRating: error! error message = " . $row->getError . "<br />\n";
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
	 * builds order by clause for _listquery (implements ordering) - from p. 230 of Mastering book
	 * @access protected
	 * @return: order by clause for query
	 */
	protected function _buildQueryOrderBy()
	{
		// print "Hello from JoomoogalleryModelJoomoogalleryimages::_buildQueryOrderBy()<br />\n";
		//
		// array of fields that can be sorted:
		//
		$orderByColumns = array( 'id',
			'groupid',
			'title',
			'ordering',
			'path',
			'description',
			'comments',
			'rating',
			'columns',
			'published'
		);
		$orderByClause = $this->_getOrderByClause( $orderByColumns );

		// print "_buildQueryOrderBy: returning orderByClause = \"$orderByClause\"<br />\n";

		return $orderByClause;
	}
	/**
	 * builds where clause for _listquery (implements filtering) - from pp. 233-240 of Mastering book
	 * @access protected
	 * @return: where clause for query
	 */
	protected function _buildQueryWhere()
	{
		$app = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		// print "Hello from JoomoogalleryModelJoomoogalleryimages::_buildQueryWhere()<br />\n";
		//
		// get the filter for the search state ([un]published)
		// get the filter for the groupid value
		// get the filter for the comments value
		// get the filter for the rating value
		// set the where clause based on the filters
		//
		$whereClause = '';
		$whereConstraint = $this->_getSearchAndStateConstraints();

		$filter_groupid = $app->getUserStateFromRequest( $option.'filter_groupid', 'filter_groupid' );
		$filter_groupid = (int)$filter_groupid;
		if ( $filter_groupid )
		{
			$whereConstraint['groupid'] = ' groupid = ' . $filter_groupid;
		}

		$filter_comments = $app->getUserStateFromRequest( $option.'filter_comments', 'filter_comments' );
		$filter_comments = (int)$filter_comments;
		if ( $filter_comments == 0 || $filter_comments == 1 )
		{
			$whereConstraint['comments'] = ' comments = ' . $filter_comments;
		}

		$filter_rating = $app->getUserStateFromRequest( $option.'filter_rating', 'filter_rating' );
		$filter_rating = (int)$filter_rating;
		if ( $filter_rating == 0 || $filter_rating == 1 )
		{
			$whereConstraint['rating'] = ' rating = ' . $filter_rating;
		}

		$filter_pageid = $app->getUserStateFromRequest( $option.'filter_pageid', 'filter_pageid' );
		$filter_pageid = (int)$filter_pageid;
		if ( $filter_pageid )
		{
			//	$whereConstraint['pageid'] = ' pageid = ' . $filter_pageid;
			$whereConstraint['pageid'] = ' groupid in ' .
				'( select id from #__joomoogallerygroups where pageid = ' . $filter_pageid . ' )';
		}

		$constraintCount = 0;

		foreach ( $whereConstraint as $constraint )
		{
			if ( $constraintCount == 0 )
			{
				$whereClause = ' WHERE ';
				$constraintCount++;
			}
			else
			{
				$whereClause .= ' AND ';
			}
			$whereClause .= $constraint;
		}

		// print "_buildQueryWhere: returning whereClause = \"$whereClause\"<br />\n";
		return $whereClause;
	}

	/**
	 * Set up filtering on groupid
	 * @return array including groupid element
	 */
	private function _setupGroupidFiltering( )
	{
		$app = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		print "Hello from _setupGroupidFiltering<br />\n";
		//
		// add the first select option then append the groupids from the DB
		// build the form control (select list)
		//
		$options = array();
		$filter_groupid = $app->getUserStateFromRequest( $option.'filter_groupid', 'filter_groupid' );
		$js = "onchange=\"if (this.options[selectedIndex].value!=''){document.adminForm.submit();}\"";
		$options[] = JHTML::_('select.option', '0', '- '.JText::_('Select Gallery Group').' -' );
		$options = array_merge( $options, $this->_groupids );
		$this->_lists['groupid'] = JHTML::_('select.genericlist',
		                                     $options,
		                                     'filter_groupid',
		                                     'class="inputbox" size="1" '.$js,
		                                     'value', 'text', $filter_groupid);
		return $this->_lists;
	}
}
?>
