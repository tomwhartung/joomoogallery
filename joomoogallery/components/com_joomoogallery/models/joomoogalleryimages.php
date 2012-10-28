<?php
/**
 * @version     $Id: joomoogalleryimages.php,v 1.12 2008/10/31 06:34:05 tomh Exp tomh $
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
 * Images Model for com_joomoogallery component
 * Parent class is in administrator/components/com_joomoogallery/models/joomoogallery.php
 */
class JoomoogalleryModelJoomoogalleryimages extends JoomoogalleryModelJoomoogallery
{
	/**
	 * id of current group (within gallery component)
	 * @access private
	 * @var int value of groupid column in #__joomoogalleryimages table for this group
	 */
	private $_groupid = 0;
	/**
	 * array of groupids for the current gallery page
	 * @access private
	 * @var array groupids for page
	 */
	private $_groupids = array();
	/**
	 * array of image counts for the current gallery page (key is groupid)
	 * @access private
	 * @var array image counts for page
	 */
	private $_imageCount = array();
	/**
	 * string of javascript generated on the fly to support resizing of images via mousewheel
	 * @access private
	 * @var string generated javascript code
	 */
	private $_javascript = '';

	/**
	 * Overridden constructor
	 * @access protected
	 */
	public function __construct()
	{
		parent::__construct();

		// print "Hello from JoomoogalleryModelJoomoogalleryimages::__construct()<br />\n";

		$this->_tableName = "#__joomoogalleryimages";
	}

    /** 
     * Sets ID of current group
     * @access public
     * @return void
     */  
    public function setGroupid( $groupid=0 )
    {   
        $this->_groupid = $groupid;
    }   
    /** 
     * Gets ID of current group
     * @access public
     * @return integer ID of current group
     */  
    public function getGroupid ()
    {   
        return $this->_groupid;
    }   
    /** 
     * Sets array groupids to groups required for current page
     * @access public
     * @return void
     */  
    public function setGroupids( $groupids=array() )
    {   
        $this->_groupids = $groupids;
    }   
    /** 
     * Gets groupids needed for current page
     * @access public
     * @return array groupids for current page
     */  
    public function getGroupids ()
    {   
        return $this->_groupids;
    }   
	/**
	 * get total number of images for an array of ids of gallery component groups
	 * @access public
	 * @return integer total number of images for specified groups
	 */
	public function getTotalImageCount( $groupids=null )
	{
		// print "Hello from getTotalImageCount()<br />\n";

		if ( $groupids == null )
		{
			$groupids = $this->_groupids;
		}

		$totalImageCount = 0;

		foreach ( $groupids as $row )
		{
			$groupid = $row->id;
			$imageCount = $this->getImageCount( $groupid );
			$totalImageCount += $imageCount;
			//	print "getTotalImageCount groupid = " . $groupid . "; imageCount = " . $imageCount . "<br />\n";
			//
			// Reset row count and query to force a fresh count for each group
			//
 			$this->_rowCount = null;
			$this->_listQuery = '';
		}

		return $totalImageCount;
	}
	/**
	 * get number of images in a group
	 * @access public
	 * @return integer total number of images for specified groups
	 */
	public function getImageCount( $groupid )
	{
		// print "Hello from getImageCount()<br />\n";

		if ( ! isset($this->_imageCount[$groupid]) || $this->_imageCount[$groupid] == null )
		{
			$this->setGroupId( $groupid );
			$this->_imageCount[$groupid] = $this->getRowCount();
		}

		//	print "getImageCount: groupid = " . $groupid . "<br />\n";
		//	print "getImageCount: this->_imageCount[$groupid] = " . $this->_imageCount[$groupid] . "<br />\n";

		return $this->_imageCount[$groupid];
	}

	/**
	 * Generate javascript to support resizing of images with mousewheel
	 * @return string javascript for image resizing
	 */
	public function getJavascript( $groupids=null )
	{
		global $groupNum;         // sequential "serial number" for id of group div for mootools
		global $imageNum;         // sequential "serial number" for id of img tag for mootools

		$groupNum = 0;
		$imageNum = 0;
		$this->_javascript = '';

		// print "Hello from JoomoogalleryModelJoomoogalleryimages::getJavascript()<br />\n";

		if ( $groupids == null )
		{
			$groupids = $this->_groupids;
		}
		/*
		 * Generate a bit of javascript for each group and each image
		 * We do this at runtime because that's when we know how many images are on the page
		 * The addEvent method is part of mootools
		 * Original code from http://demos111.mootools.net/MousewheelCustom
		 */
		foreach ( $groupids as $row )
		{
			$groupid = $row->id;
			$this->_javascript .= $this->_javascriptForGroup( $groupid );
		}

		return $this->_javascript;
	}
	/**
	 * Generate javascript to support resizing of a single group's images
	 * We might be able to simplify this, see: http://demos111.mootools.net/Fx.Styles
	 * @return string javascript
	 */
	private function _javascriptForGroup( $groupid )
	{
		global $groupNum;         // sequential "serial number" for id of group div for mootools
		global $imageNum;         // sequential "serial number" for id of img tag for mootools

		// print "Hello from JoomoogalleryModelJoomoogalleryimages::_javascriptForGroup()<br />\n";

		//
		// each group appears in its own panel with a unique divIdAttr
		//
		$divIdAttr = 'div' . sprintf( "%02d", $groupNum );
		$groupNum++;
		$jsForGroup = "window.addEvent('domready', function() {";
		$jsForGroup .= "\$('" . $divIdAttr;
		$jsForGroup .= "').addEvents({
			        'wheeldown': function(e) {
			            e = new Event(e).stop();
			            this.width *= 1.1;
			            this.height *= 1.1;
			        },
			        'wheelup': function(e) {
			            e = new Event(e).stop();
			            if (this.width-30 >= 30) {
			                this.width /= 1.1;
			                this.height /= 1.1;
			            }
			        }
			    });
		    });\n";

		//
		// Loop to generate javascript for each image in the group
		//
		$imagesThisGroup = $this->getImageCount( $groupid );

		for ( $imageCount = 0; $imageCount < $imagesThisGroup; $imageCount++ )
		{
			$imgIdAttr = 'img' . sprintf( "%03d", $imageNum );
			$imageNum++;
			$jsForGroup .= "window.addEvent('domready', function() {";
			$jsForGroup .= "\$('" . $imgIdAttr;
			$jsForGroup .= "').addEvents({
			        'wheeldown': function(e) {
			            e = new Event(e).stop();
			            this.width *= 1.1;
			            this.height *= 1.1;
			        },
			        'wheelup': function(e) {
			            e = new Event(e).stop();
			            if (this.width-30 >= 30) {
			                this.width /= 1.1;
			                this.height /= 1.1;
			            }
			        }
			    });
		    });\n";
			// print "_javascriptForGroup:imageNum = $imageNum<br />\n";
			// print "_javascriptForGroup:imageCount = $imageCount<br />\n";
			// print "_javascriptForGroup:jsForGroup = $jsForGroup<br />\n";
		}

		return $jsForGroup;
	}

	/**
	 * builds where clause for _listquery (implements filtering)
	 * @access protected
	 * @return: where clause for query
	 */
	protected function _buildQueryWhere()
	{
		// print "Hello from JoomoogalleryModelJoomoogalleryimages::_buildQueryWhere()<br />\n";

		$whereClause = ' WHERE published = 1 and groupid = ' . $this->_groupid;

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
		// print "Hello from JoomoogalleryModelJoomoogalleryimages::_buildQueryOrderBy()<br />\n";

		//
		// Note: At this time this function is a candidate for refactoring into the parent class
		//
		$orderByClause = ' ORDER BY ordering ASC';

		// print "_buildQueryOrderBy: returning orderByClause = \"$orderByClause\"<br />\n";

		return $orderByClause;
	}
}
?>
