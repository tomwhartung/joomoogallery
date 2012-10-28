/**
 * @version     $Id: mousewheel.js,v 1.3 2008/10/31 18:23:39 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 */
/*
 * mousewheel.js: enables use of mousewheel to change size of image(s)
 * =====================================================================
 * Uses mootools, inspired by Mousewheel Custom demo at http://demos111.mootools.net/
 */

/*
 * The addEvent method is part of mootools
 */
window.addEvent('domready', function(){

	/*
	 * Copied/derived from http://demos111.mootools.net/MousewheelCustom
	 * -----------------------------------------------------------------
	 */
	Element.Events.extend({
		'wheelup': {
			type: Element.Events.mousewheel.type,
			map: function(event){
				event = new Event(event);
				if (event.wheel >= 0) this.fireEvent('wheelup', event)
			}
		},
		'wheeldown': {
			type: Element.Events.mousewheel.type,
			map: function(event){
				event = new Event(event);
				if (event.wheel <= 0) this.fireEvent('wheeldown', event)
			}
		}
	});
});

