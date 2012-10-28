<?php
/**
 * @version     $Id: edit_row.php,v 1.9 2008/10/31 18:18:44 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */
/*
 * edit_row.php: when task is 'add' or 'edit' display form allowing user to edit the row
 * -------------------------------------------------------------------------------------
 * call model code to get data from DB
 * call function defined in this file to produce the HTML
 */

defined( '_JEXEC' ) or die( 'Restricted access' );      // no direct access

JToolBarHelper::title( JText::_( 'Joomla Mootools Gallery: Update Group' ), 'generic.png' );
$document = & JFactory::getDocument();
$document->setTitle(JText::_('Joomla Mootools Gallery: Update Group'));

//
// Get row data from the model and display form allowing editing of the row
//
$row =& $this->get( 'Row' );            // calls getRow()
$pageids =& $this->get( 'Pageids' );    // calls getPageids()

// print "edit_row.php: row->id = \"" . $row->id . "\"<br />\n";

displayRow( $row, $pageids );

/**
 * outputs HTML allowing user to add a new or edit an existing row in the DB
 */
function displayRow( $row, $pageids )
{
	$option = JRequest::getCmd('option');

	// print "Hello from displayRow function<br />\n";

	$task = JRequest::getCmd('task');

	$lists = array();
	$lists['published'] = JHTML::_( 'select.booleanlist', 'published', 'class="inputBox"', $row->published );
	$lists['pageids']   = JHTML::_( 'select.genericlist', $pageids, 'pageid', null, 'value', 'text', $row->pageid );
	$lists['columns']   = JHTML::_( 'select.integerlist', 0,  19, 1, 'columns',  '', $row->columns );
	$lists['ordering']  = JHTML::_( 'select.integerlist', 0, 199, 1, 'ordering', '', $row->ordering );
	$lists['comments']  = JHTML::_( 'select.booleanlist', 'comments', 'class="inputBox"', $row->comments );

	$editor =& JFactory::getEditor();
	JHTML::_( 'behavior.calendar' );

	print '<form action="index.php" method="post" name="adminForm" id="adminForm">' . "\n";
	print ' <fieldset class="adminform">' . "\n";
	print '  <legend>Details</legend>' . "\n";
	print '  <table class="admintable">' . "\n";

	if ( $task == 'edit' )
	{
		print '   <tr>' . "\n";
		print '    <td width="100" align="right" class="key">Row ID:</td>' . "\n";
		print '    <td>' . $row->id . '</td>' . "\n";
		print '   </tr>' . "\n";
	}

	print '   <tr>' . "\n";
	print '    <td width="100" align="right" class="key">Parent Page ID:</td>' . "\n";
	print '    <td>' . "\n";
	echo $lists['pageids'];
	print '    </td>' . "\n";
	print '   </tr>' . "\n";

	print '   <tr>' . "\n";
	print '    <td width="100" align="right" class="key">Group Title:</td>' . "\n";
	print '    <td>' . "\n";
	print '     <input class="text_area" type="text" name="title" id="title" ';
	print         'size="50" maxlength="250" value="' . $row->title . '" />' . "\n";
	print '    </td>' . "\n";
	print '   </tr>' . "\n";

	print '   <tr>' . "\n";
	print '    <td width="100" align="right" class="key">Group Description:</td>' . "\n";
	print '    <td>' . "\n";
	// echo $editor->display( 'description', $row->description, '100%', '250','40', '5' );
	echo $editor->display( 'description', $row->description, '100%', '250','40', '5', 0 );  // trailing 0 -> omit buttons
	print '    </td>' . "\n";
	print '   </tr>' . "\n";

	print '   <tr>' . "\n";
	print '    <td width="100" align="right" class="key">Number of Columns:</td>' . "\n";
	print '    <td>' . "\n";
	echo $lists['columns'];
	print '    </td>' . "\n";
	print '   </tr>' . "\n";

	print '   <tr>' . "\n";
	print '    <td width="100" align="right" class="key">Ordering:</td>' . "\n";
	print '    <td>' . "\n";
	echo $lists['ordering'];
	print '    </td>' . "\n";
	print '   </tr>' . "\n";

	print '   <tr>' . "\n";
	print '    <td width="100" align="right" class="key">Enable Comments:</td>' . "\n";
	print '    <td>' . "\n";
	echo $lists['comments'];
	print '    </td>' . "\n";
	print '   </tr>' . "\n";

	print '   <tr>' . "\n";
	print '    <td width="100" align="right" class="key">Published:</td>' . "\n";
	print '    <td>' . "\n";
	echo $lists['published'];
	print '    </td>' . "\n";
	print '   </tr>' . "\n";
	print '  </table>' . "\n";

	print ' </fieldset>' . "\n";
	print ' <input type="hidden" name="id" value="' . $row->id . '" />' . "\n";
	print ' <input type="hidden" name="option" value="' . $option . '" />' . "\n";
	print ' <input type="hidden" name="task" value="' . $task . '" />' . "\n";
	print ' <input type="hidden" name="ctlr" value="groups" />' . "\n";

	print '</form>' . "\n";
	print '' . "\n";
}
?>
