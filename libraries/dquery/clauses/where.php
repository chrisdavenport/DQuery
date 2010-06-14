<?php
/**
* @version		$Id: $
* @package		DQuery
* @copyright	Copyright (C) 2010 Davenport Technology Services. All rights reserved.
* @license		GNU/GPL version 2 or later.
*/

// Check to ensure this file is being called from within the Joomla Framework.
defined( 'JPATH_BASE' ) or die();

jimport( 'dquery.clause' );

/**
* Where query clause.
*
* @package		DQuery
*/

class DQueryClauseWhere
	extends DQueryClause
	implements iDQueryClause
{
	/**
	 * Condition object.
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $condition = null;

	/**
	 * Add a term to the query clause.
	 *
	 * @param	string or array	Term or array of terms.
	 * @param	array			Array of arguments
	 */
	public function addTerm( $terms, $args = array() )
	{
		// Set the glue (default is AND).
		$glue = isset( $args['glue'] ) ? $args['glue'] : 'AND';

		// If we're being passed a condition object, then simply add it.
		if (is_null( $this->condition ) && $terms instanceof DQueryCondition) {
			$this->condition = $terms;
			return $this;
		}

		// Create a condition object if one doesn't already exist.
		if (!$this->condition instanceof DQueryCondition) {
			$this->condition = DQuery::condition();
		}

		// Add terms to condition object.
		$this->condition->add( $terms, $glue );

		return $this;
	}

}