<?php
/**
 * @version     $Id: default.php,v 1.8 2008/10/31 06:17:57 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */
/*
 * default.php: when task not handled by controller (e.g., it's blank), display rows from table
 * --------------------------------------------------------------------------------------------
 * call model code to get data from DB
 * call function defined in this file to produce the HTML
 */

defined( '_JEXEC' ) or die( 'Restricted access' );      // no direct access

JToolBarHelper::title( JText::_( 'Joomla Mootools Gallery: Manage Images' ), 'generic.png' );
$document = & JFactory::getDocument();
$document->setTitle(JText::_('Joomla Mootools Gallery: Manage Images'));

//
// Get data from the model and list the rows
//
// $tableName  =& $this->get( 'tableName'  );       // calls getTableName () in the model
// print "default.php (for images): tableName = $tableName.<br />\n";

$rows       =& $this->get( 'Rows' );             // calls getRows() in the model
$pagination =& $this->get( 'Pagination' );       // calls getPagination() in the model
$pageids    =& $this->get( 'Pageids' );          // calls getPageids() in the model
$groupids   =& $this->get( 'Groupids' );         // calls getGroupids() in the model
$lists      =& $this->get( 'Lists' );            // calls getLists() in the model

listRows( $rows, $pagination, $groupids, $lists );

/**
 * outputs HTML to display the list of rows
 * @return void
 */
function listRows( $rows, $pagination, $groupids, $lists )
{
	$option = JRequest::getCmd('option');

	// print "listRows function for images: option: \"" . $option . "\"<br />\n";

	jimport( 'joomla.filter.output' );

	//
	// groupids is an array that actually also includes the group names
	// groupids is in the format needed by the drop down (in lists['groupid'])
	// Here we create a new array containing the groupid values as keys
	// Each (numeric) groupid references the appropriate group name
	//
	$groupNames = array();

	foreach( $groupids as $groupidAndName )
	{
		$groupid = $groupidAndName->value;
		$groupName = $groupidAndName->text;
		$groupNames[$groupid] = $groupName;
		// print "groupid = $groupid;<br />\n";
		// print "groupName = $groupName;<br />\n";
		// print "groupNames[$groupid] = $groupNames[$groupid];<br />\n";
	}

	$rowCount = count( $rows );
	$rowClassSuffix = 0;
	$maxCharsInDesc = 200;

	print '<form action="index.php" method="post" name="adminForm" id="adminForm">' . "\n";
	print ' <table>' . "\n";
	print '  <tr>' . "\n";
	print '   <td colspan="5" nowrap="nowrap" align="right">';
	echo  $lists['groupid'];       // requires groupids (function parameter) see setupGroupidFiltering
//	print '</td>' + "\n";          // closing tr tag causes a "0" to be printed?!?
	print "\n";
	print '  </tr>' . "\n";

	// print '  <tr>' . "\n";
	// print '  <td>lists:<br />' . print_r($lists['search'],true) . '</td>' . "\n";
	// print '  </tr>' . "\n";

	print '  <tr>' . "\n";
	print '   <td align="left" width="100%">';
	echo JText::_('filter');
	print '   <input type="text" name="filter_search" id="search" ';
	print      'value="' . $lists['search'] . '" ';
	print      'class="text_area" onchange="document.adminForm.submit();" />' . "\n";
	print '   <button onclick="this.form.submit();">';
	echo       JText::_('Go');
	print    '</button>' . "\n";
	print '   <button onclick="document.adminForm.filter_search.value=' . "''" . ';';
	print        'this.form.submit();">';
	echo       JText::_('Reset');
	print    '</button>' . "\n";
	print '   </td>';

	print '   <td nowrap="nowrap">';
	echo  $lists['pageid'];      // requires pageids (function parameter) see setupPageidFiltering
//  print '</td>' + "\n";        // closing tr tag causes a "0" to be printed?!?
    print "\n";

	print '   <td nowrap="nowrap">';
	echo  $lists['state'];
//	print '</td>' + "\n";          // closing tr tag causes a "0" to be printed?!?
	print "\n";

	print '   <td nowrap="nowrap">';
	echo  $lists['comments'];
//	print '</td>' + "\n";          // closing tr tag causes a "0" to be printed?!?
	print "\n";

	print '   <td nowrap="nowrap">';
	echo  $lists['rating'];
//	print '</td>' + "\n";          // closing tr tag causes a "0" to be printed?!?
	print "\n";

	print '  </tr>' . "\n";
	print ' </table>' . "\n";

	print ' <table class="adminlist">' . "\n";
	print '  <tr>' . "\n";
	print '   <th width="2%" style="text-align: right">';
	echo  JHTML::_('grid.sort', 'Id', 'id', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="2%" style="text-align: center">' . "\n";
	print '    <input type="checkbox" name="toggle" value="" ';
	print       'onclick="checkAll(' . count($rows) . ');" />' . "\n";
	print '   </th>' . "\n";

	print '   <th width="16%" style="text-align: left">';
	echo  JHTML::_('grid.sort', 'Image Title', 'title', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: center">';
	echo  JHTML::_('grid.sort', 'Published?', 'published', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: right">';
	echo  JHTML::_('grid.sort', 'Group Id', 'groupid', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="15%" style="text-align: left">';
	print 'Group Name';
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: center">';
	echo  JHTML::_('grid.sort', 'Ordering', 'ordering', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="20%" style="text-align: left">';
	echo  JHTML::_('grid.sort', 'File Path', 'path', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: center">';
	echo  JHTML::_('grid.sort', 'Comments Enabled?', 'comments', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: center">';
	echo  JHTML::_('grid.sort', 'Rating Enabled?', 'rating', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="30%" style="text-align: left">';
	echo  JHTML::_('grid.sort',
	               'Description (first ' . $maxCharsInDesc . ' characters)',
	               'description',
	               $lists['order_Dir'],
	               $lists['order']);
	print    '</th>' . "\n";

	print '  </tr>' . "\n";

	for ( $rowNum = 0; $rowNum < $rowCount; $rowNum++ )
	{
		$row =& $rows[$rowNum];
		$checked = JHTML::_( 'grid.id', $rowNum, $row->id );
		$published = JHTML::_( 'grid.published', $row, $rowNum );
		$titleLink = JRoute::_( 'index.php?option=' . $option . '&ctlr=images&task=edit&cid[]='. $row->id );
		$row->comments ? $comments = 'Y' : $comments = 'N';
		$row->rating ? $rating = 'Y' : $rating = 'N';
		$shortDescription = substr( $row->description, 0, $maxCharsInDesc );
		$ordering = '<input class="text_area" style="text-align: center" type="text" name="order[]" size="5" value="' .
		             $row->ordering . '" />';

		print '  <tr class="row' . $rowClassSuffix . '">' . "\n";
		print '   <td style="text-align: right">' . $row->id . "</td>\n";
		print '   <td style="text-align: center">' . $checked . "</td>\n";
		print '   <td style="text-align: left">' . "\n";
		print '    <a href="' . $titleLink . '">' . $row->title . "</a>\n";
		print '   </td>' . "\n";
		print '   <td style="text-align: center">' . $published . "</td>\n";
		print '   <td style="text-align: right">' . $row->groupid . "</td>\n";
		print '   <td style="text-align: left">' . $groupNames[$row->groupid] . "</td>\n";
		print '   <td style="text-align: right">' . $ordering . "</td>\n";
		print '   <td style="text-align: left">' . $row->path . "</td>\n";
		print '   <td style="text-align: center">' . $comments . "</td>\n";
		print '   <td style="text-align: center">' . $rating . "</td>\n";
		print '   <td style="text-align: left">' . $shortDescription . "</td>\n";
		print '  </tr>' . "\n";

		$rowClassSuffix = 1 - $rowClassSuffix;      // alternates between values of 0 and 1 (to no avail!)
	}

	if ( is_a($pagination, 'JPagination') )
	{
		print '  <tfoot>' . "\n";
		print '   <td colspan="11">' . $pagination->getListFooter() . "\n";
		print '   </td>' . "\n";
		print '  </tfoot>' . "\n";
	}
	else
	{
		$pagination_class_name = get_class($pagination);
		print '  <tfoot>' . "\n";
		print '   <td colspan="11">' . "\n";
		print '     Oops, WTF, pagination is a member of the "' . $pagination_class_name . '" class?!?';
		print '   </td>' . "\n";
		print '  </tfoot>' . "\n";
	}

	print ' </table>' . "\n";

	print ' <input type="hidden" name="option" value="' . $option . '" />' . "\n";
	print ' <input type="hidden" name="task" value="" />' . "\n";
	print ' <input type="hidden" name="ctlr" value="images" />' . "\n";
	print ' <input type="hidden" name="boxchecked" value="0" />' . "\n";

	if ( is_a($pagination, 'JPagination') )
	{
		print ' <input type="hidden" name="list_limit" value="' . $pagination->limit . '" />' . "\n";
	}

//	print ' <input type="hidden" name="filter_order" value="';
//	print    $lists['order'] . '" />' . "\n";
//	print ' <input type="hidden" name="filter_order_Dir" value="';
//	print    $lists['order_Dir'] . '" />' . "\n";

	print '</form>' . "\n";
	print '' . "\n";
}
?>
