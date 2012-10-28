/**
 * @version     $Id: accordion.js,v 1.5 2008/10/31 18:30:20 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */
/*
 * accordion.js: enable gallery component to display one group at a time
 * =====================================================================
 * Uses mootools, inspired by accordion demo at http://demos111.mootools.net/
 */

/*
 * The addEvent method is part of mootools
 */
window.addEvent('domready', function(){
	/*
	 * Note that these bullet_* colors also match those used for headings and links
	 * ----------------------------------------------------------------------------
	 * So think twice before changing them.
	 * bullet_* color variables are defined in:
	 *    templates/tmpl_templateparameters/javascript/set_styles.js
	 * For more information see the comments in that file
	 */
	var bullet_blue   = '#0d0dbd';   // matches blue used in bullets
	var bullet_green  = '#0dbe0d';   // matches green used in bullets
	var bullet_red    = '#bd0d0d';   // matches red used in bullets
	var bullet_yellow = '#bdbe0d';   // matches yellow used in bullets

	var heading_blue   = bullet_blue;
	var heading_green  = bullet_green;
	var heading_red    = bullet_red;
	var heading_yellow = bullet_yellow;
	
	var link_blue   = bullet_blue;
	var link_green  = bullet_green;
	var link_red    = bullet_red;
	var link_yellow = bullet_yellow;

	/*
	 * Create our Accordion instance (derived from demo.js in mootools accordion demo)
	 * -------------------------------------------------------------------------------
	 * Need to remember to change the colors used below when changing template
	 * -----------------------------------------------------------------------
	 */
	var myAccordion = new Accordion($('accordion'), 'h4.toggler', 'div.element', {
		opacity: false,
		onActive: function(toggler, element)
		{
			toggler.setStyle( 'color', heading_red );
		},
		onBackground: function(toggler, element)
		{
			toggler.setStyle('color', link_yellow );
		}
	});
});

