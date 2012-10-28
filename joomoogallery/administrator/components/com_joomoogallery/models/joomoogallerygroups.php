<?php
/**
 * @version     $Id: joomoogallerygroups.php,v 1.12 2008/10/31 06:15:48 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @link        http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Joomoogallerygroups Model for com_joomoogallery component
 */
class JoomoogalleryModelJoomoogallerygroups extends JoomoogalleryModelJoomoogallery
{
	/**
	 * constructor
	 * @access protected
	 */
	public function __construct( $config = array() )
	{
		parent::__construct( $config );
		//	print "Hello from JoomoogalleryModelJoomoogallerygroups::__construct()<br />\n";

		$this->_tableName = "#__joomoogallerygroups";
	}

	/**
	 * create lists array containing ordering and filtering lists
	 * @access public
	 * @return array lists to use when outputing HTML to display the list of rows
	 */
	public function getLists( )
	{
		//	print "Hello from JoomoogalleryModelJoomoogallerygroups::getLists()<br />\n";
		//	print "getLists before: count(this->_lists) = " . count($this->_lists) . "<br />\n";

		if ( count($this->_lists) == 0 )
		{
			//	$this->setupPageidFiltering( );
			parent::getLists();
			print "getLists after: count(this->_lists) = " . count($this->_lists) . "<br />\n";
		}

		return $this->_lists;
	}
	/**
	 * builds order by clause for _listquery (implements ordering) - from p. 230 of Mastering book
	 * @access protected
	 * @return: order by clause for query
	 */
	protected function _buildQueryOrderBy()
	{
		//	print "Hello from JoomoogalleryModelJoomoogallerygroups::_buildQueryOrderBy()<br />\n";
		//
		// array of fields that can be sorted:
		//
		$orderByColumns = array( 'id',
			'pageid',
			'title',
			'ordering',
			'comments',
			'description',
			'columns',
			'published'
		);
		$orderByClause = $this->_getOrderByClause( $orderByColumns );

		print "_buildQueryOrderBy: returning orderByClause = \"$orderByClause\"<br />\n";
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

		//	print "Hello from JoomoogalleryModelJoomoogallerygroups::_buildQueryWhere()<br />\n";
		//
		// get the filter for the search and state [un]published
		// get the filter for the pageid value
		// set the where clause based on the filters
		//
		$whereClause = '';
		$whereConstraint = $this->_getSearchAndStateConstraints();

		$filter_pageid = $app->getUserStateFromRequest( $option.'filter_pageid', 'filter_pageid' );
		$filter_pageid = (int)$filter_pageid;
		if ( $filter_pageid )
		{
			$whereConstraint['pageid'] = ' pageid = ' . $filter_pageid;
		}

		$filter_comments = $app->getUserStateFromRequest( $option.'filter_comments', 'filter_comments' );
		$filter_comments = (int)$filter_comments;
		if ( $filter_comments == 0 || $filter_comments == 1 )
		{
			$whereConstraint['comments'] = ' comments = ' . $filter_comments;
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
}
?>
