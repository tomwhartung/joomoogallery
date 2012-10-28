<?php
/**
 * @version     $Id: default.php,v 1.9 2008/10/31 06:18:31 tomh Exp tomh $
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
// print "Hello from tmpl/default.php.<br />\n";

JToolBarHelper::title( JText::_( 'Joomla Mootools Gallery: Manage Groups' ), 'generic.png' );
$document = & JFactory::getDocument();
$document->setTitle(JText::_('Joomla Mootools Gallery: Manage Groups'));

//
// Get data from the model and list the rows
//
// $tableName  =& $this->get( 'tableName'  );    // calls getTableName () in the model
$rows       =& $this->get( 'Rows' );             // calls getRows() in the model
$pageids    =& $this->get( 'Pageids' );          // calls getPageids() in the model
$pagination =& $this->get( 'Pagination' );       // calls getPagination() in the model
$lists      =& $this->get( 'lists' );            // calls getLists() in the model

//	$option = JRequest::getCmd('option');
//	print "calling the listRows function: option: \"" . $option . "\"<br />\n";

listRows( $rows, $pagination, $pageids, $lists );

/**
 * outputs HTML to display the list of rows
 * @return void
 */
function listRows( $rows, $pagination, $pageids, $lists )
{
	$option = JRequest::getCmd('option');

	print "listRows function: option: \"" . $option . "\"<br />\n";
	//
	// pageids is an array that actually also includes the page names
	// pageids is in the format needed by the drop down (in lists['pageid'])
	// Here we create a new array containing the pageid values as keys
	// Each (numeric) pageid references the appropriate page name
	//
	$pageNames = array();

	foreach( $pageids as $pageidAndName )
	{
		$pageid = $pageidAndName->value;
		$pageName = $pageidAndName->text;
		$pageNames[$pageid] = $pageName;
		// print "pageid = $pageid;<br />\n";
		// print "pageName = $pageName;<br />\n";
		// print "pageNames[$pageid] = $pageNames[$pageid];<br />\n";
	}

	jimport( 'joomla.filter.output' );

	$rowCount = count( $rows );
	$rowClassSuffix = 0;
	$maxCharsInDesc = 200;

	print '<form action="index.php" method="post" name="adminForm" id="adminForm">' . "\n";
	print ' <table>' . "\n";
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
//	print '</td>' + "\n";        // closing tr tag causes a "0" to be printed?!?
	print "\n";

	print '   <td nowrap="nowrap">';
	echo  $lists['state'];
//	print '</td>' + "\n";        // closing tr tag causes a "0" to be printed?!?
	print "\n";

	print '   <td nowrap="nowrap">';
	echo  $lists['comments'];
//	print '</td>' + "\n";          // closing tr tag causes a "0" to be printed?!?
	print "\n";

	print '  </tr>' . "\n";
	print ' </table>' . "\n";

	print ' <table class="adminlist">' . "\n";
	print '  <tr>' . "\n";
	print '   <th width="20px" style="text-align: right">';
	echo  JHTML::_('grid.sort', 'Id', 'id', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="20px" style="text-align: center">' . "\n";
	print '    <input type="checkbox" name="toggle" value="" ';
	print       'onclick="checkAll(' . count($rows) . ');" />' . "\n";
	print '   </th>' . "\n";

	print '   <th width="20%" style="text-align: left">';
	echo  JHTML::_('grid.sort', 'Group Title', 'title', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: center">';
	echo  JHTML::_('grid.sort', 'Published?', 'published', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: right">';
	echo  JHTML::_('grid.sort', 'Page Id', 'pageid', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="15%" style="text-align: left">';
	print 'Page Name';
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: center">';
	echo  JHTML::_('grid.sort', 'Ordering', 'ordering', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: center">';
	echo  JHTML::_('grid.sort', 'Columns', 'columns', $lists['order_Dir'], $lists['order']);
	print '</th>' . "\n";

	print '   <th width="5%" style="text-align: center">';
	echo  JHTML::_('grid.sort', 'Comments Enabled?', 'comments', $lists['order_Dir'], $lists['order']);
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
		$titleLink = JRoute::_( 'index.php?option=' . $option . '&ctlr=groups&task=edit&cid[]='. $row->id );
		if ( isset($pageNames[$row->pageid]) )
		{
			$pageName = $pageNames[$row->pageid];
		}
		else
		{
			$pageName = 'Unknown pageid: ' . $pageid;
		}
		$ordering = '<input class="text_area" style="text-align: center" type="text" name="order[]" size="5" value="' .
		             $row->ordering . '" />';
		$row->comments ? $comments = 'Y' : $comments = 'N';
		$shortDescription = substr( $row->description, 0, $maxCharsInDesc );

		print '  <tr class="row' . $rowClassSuffix . '">' . "\n";
		print '   <td style="text-align: right">' . $row->id . "</td>\n";
		print '   <td style="text-align: center">' . $checked . "</td>\n";
		print '   <td style="text-align: left">' . "\n";
		print '    <a href="' . $titleLink . '">' . $row->title . "</a>\n";
		print '   </td>' . "\n";
		print '   <td style="text-align: center">' . $published . "</td>\n";
		print '   <td style="text-align: right">'  . $row->pageid . "</td>\n";
		print '   <td style="text-align: left">'   . $pageName . "</td>\n";
		print '   <td style="text-align: right">'  . $ordering . "</td>\n";
		print '   <td style="text-align: center">' . $row->columns . "</td>\n";
		print '   <td style="text-align: center">' . $comments . "</td>\n";
		print '   <td style="text-align: left">'   . $shortDescription . "</td>\n";
		print '  </tr>' . "\n";

		$rowClassSuffix = 1 - $rowClassSuffix;      // alternates between values of 0 and 1 (to no avail!)
	}

	if ( is_a($pagination, 'JPagination') )
	{
		print '  <tfoot>' . "\n";
		print '   <td colspan="10">' . $pagination->getListFooter() . "\n";
		print '   </td>' . "\n";
		print '  </tfoot>' . "\n";
	}
	else
	{
		$pagination_class_name = get_class($pagination);
		print '  <tfoot>' . "\n";
		print '   <td colspan="10">' . "\n";
		print '     Oops, WTF, pagination is a member of the "' . $pagination_class_name . '" class?!?';
		print '   </td>' . "\n";
		print '  </tfoot>' . "\n";
	}

	print ' </table>' . "\n";

	print ' <input type="hidden" name="option" value="' . $option . '" />' . "\n";
	print ' <input type="hidden" name="task" value="" />' . "\n";
	print ' <input type="hidden" name="ctlr" value="groups" />' . "\n";
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
