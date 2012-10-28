<?php
/**
 * @version     $Id: view.html.php,v 1.2 2008/10/31 06:17:10 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @link        http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

/**
 * Joomoogallerygroups View class for com_joomoogallery component
 */
class JoomoogalleryViewJoomoogallerygroups extends JView
{
	/**
	 * constructor
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Joomoogallerygroups view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		// print "Hello from JoomoogalleryViewJoomoogallerygroups::display()<br />\n";
		// print "tpl = \"" . $tpl . "\"<br />\n";
		// $tpl = 'html';   // loads tmpl/default_html.php (instead of tmpl/default.php)

		parent::display($tpl);
	}
}
?>
