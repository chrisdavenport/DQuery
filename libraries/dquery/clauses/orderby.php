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
* Order By query clause.
*
* @package		DQuery
*/

class DQueryClauseOrderBy
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
			$this->terms[$terms] = 'ASC';
		} else {
			foreach ($terms as $name => $order) {
				$order = trim( strtoupper( $order ) );
				if ($order != 'ASC' && $order != 'DESC') {
					$order = 'ASC';
				}
				$this->terms[$name] = $order;
			}
		}
	}

}