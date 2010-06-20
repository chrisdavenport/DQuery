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
* Class for a database join.
*
* Always use getInstance as a factory method to obtain a class of this type.
*
* @abstract
* @package		DQuery
*/

class DQueryJoin
	extends DQueryType
{
	/**
	 * Table name.
	 *
	 * @var		string
	 * @access	protected
	 */
	protected $name = null;

	/**
	 * Optional table alias.
	 *
	 * @var		string
	 * @access	protected
	 */
	protected $alias = null;

	/**
	 * Join type.
	 *
	 * @var		string
	 * @access	protected
	 */
	protected $type = 'left';

	/**
	 * JTable object.
	 *
	 * @var		JTable
	 * @access	protected
	 */
	protected $table = null;

	/**
	 * On condition object.
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $on = null;

	/**
	 * Set join type.
	 *
	 * @param	string		Join type.
	 * @return	DQueryJoin	This object for method chaining.
	 */
	public function type( $type )
	{
		$this->set( 'type', $type );
		return $this;
	}

	/**
	 * Set join table
	 *
	 * @param	string, array or JTable	Table name, table name/alias or object.
	 * @param	boolean					True if there should be a JTable object.
	 * @return	DQueryJoin				This object for method chaining.
	 */
	public function table( $table, $jtable = true )
	{
		if ($table instanceof JTable) {
			$this->set( 'table', $table );
			return $this;
		}

		if (is_array( $table )) {
			list( $table, $alias ) = each( $table );
			$this->set( 'alias', $alias );
		}
		$this->set( 'name', $table );

		if ($jtable) {
			// Instantiate a JTable object.
			if (!$join_table = JTable::getInstance( $table )) {
				echo 'Table not found: '.$table;
				return false;
			}
			$this->set( 'table', $join_table );
		}

		return $this;
	}

	/**
	 * Set table alias.
	 *
	 * @pararm	string	Alias.
	 */
	public function alias( $alias )
	{
		$this->alias = $alias;
		return $this;
	}

	/**
	 * Join ON conditions.
	 *
	 * @param	string or array	Term or array of terms.
	 * @param	array			Array of arguments
	 */
	public function on( $terms, $args = array() )
	{
		// Set the glue (default is AND).
		$glue = isset( $args['glue'] ) ? $args['glue'] : 'AND';

		// If we're being passed a condition object, then simply add it.
		if (is_null( $this->on ) && $terms instanceof DQueryCondition) {
			$this->on = $terms;
			return $this;
		}

		// Create a condition object if one doesn't already exist.
		if (!$this->on instanceof DQueryCondition) {
			$this->on = DQuery::condition();
		}

		// Add terms to condition object.
		$this->on->add( $terms, $glue );

		return $this;
	}

}