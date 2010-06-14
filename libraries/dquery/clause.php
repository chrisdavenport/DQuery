<?php
/**
* @version		$Id: $
* @package		DQuery
* @copyright	Copyright (C) 2010 Davenport Technology Services. All rights reserved.
* @license		GNU/GPL version 2 or later.
*/

// Check to ensure this file is being called from within the Joomla Framework.
defined( 'JPATH_BASE' ) or die();

/**
* Base class for a database query clause.
*
* An abstract query class that must be extended for a given clause type.
* The main reason for this class is to enable type checking.
*
* @abstract
* @package		DQuery
*/

abstract class DQueryClause
	extends DQueryType
{
}