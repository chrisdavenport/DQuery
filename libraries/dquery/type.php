<?php
/**
* @version		$Id: $
* @package		DQuery
* @copyright	Copyright (C) 2010 Davenport Technology Services. All rights reserved.
* @license		GNU/GPL version 2 or later.
*/

// Check to ensure this file is being called from within the Joomla Framework.
defined( 'JPATH_BASE' ) or die();

jimport( 'dquery.object' );

/**
* Base class for a database query.
*
* An abstract query class that must be extended for a given query type.
* Always use getInstance as a factory method to obtain a class of the required type.
*
* @abstract
* @package		DQuery
*/

abstract class DQueryType
	extends DQueryObject
{
}