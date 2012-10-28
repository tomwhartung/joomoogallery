<?php
/**
 * @version     $Id: save-router.php,v 1.1 2008/10/31 06:36:10 tomh Exp tomh $
 * @author      Tom Hartung <webmaster@tomhartung.com>
 * @package     Joomla
 * @subpackage  Joomoogallery
 * @copyright   Copyright (C) 2008 Tom Hartung. All rights reserved.
 * @since       1.5
 * @license     GNU/GPL, see LICENSE.php
 *
 * *** WE ARE NOT USING THIS (it is here for possible future reference) ***
 *
 */

/**
 * @param	array
 * @return	array
 */
function JoomoogalleryBuildRoute( &$query )
{
	$segments = array();

	if ( isset($query['view']) )
	{
		$segments[] = $query['view'];
		unset($query['view']);
	}

	return $segments;
}

/**
 * @param	array
 * @return	array
 */
function JoomoogalleryParseRoute( $segments )
{
	$vars = array();

	$view	= array_shift($segments);
	$vars['view'] = 'view';

	return $vars;
}


?>
