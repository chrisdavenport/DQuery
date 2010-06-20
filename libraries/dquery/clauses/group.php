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
* Group By query clause.
*
* @package		DQuery
*/

class DQueryClauseGroup
	extends DQueryClause
	implements iDQueryClause
{
	/**
	 * Terms array.
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $terms = array();

	/**
	 * Add a term to the query clause.
	 *
	 * @param	string or array	Term or array of terms.
	 * @param	array			Array of arguments
	 */
	public function addTerm( $terms, $args = array() )
	{
		if (!is_array( $terms )) {
			$this->terms[] = $terms;
		} else {
			foreach ($terms as $term) {
				$this->terms[] = trim( $term );
			}
		}
	}

}