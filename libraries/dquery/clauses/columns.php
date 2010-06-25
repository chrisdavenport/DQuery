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
* Columns query clause.
*
* @package		DQuery
*/

class DQueryClauseColumns
	extends DQueryClause
	implements iDQueryClause
{
	/**
	 * Terms array.
	 * Array elements must all be DQueryClauseColumn objects.
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $terms = array();

	/**
	 * Add a term or an array of terms to the query clause.
	 *
	 * Argument can be a string giving the name of a column, or
	 * an array of column names, or an array of column name/alias pairs.
	 *
	 * @param	string or array		Term or array of terms.
	 * @param	array				Array of arguments
	 * @return	DQueryClauseColumns Object for method chaining.
	 */
	public function addTerm( $terms, $args = array() )
	{
		$terms = (array) $terms;

		foreach ($terms as $name => $alias) {

			if (is_numeric( $name )) {
				$this->terms[] = new DQueryClauseColumn( $alias );
			} else {
				$this->terms[] = new DQueryClauseColumn( $name, $alias );
			}

		}

		return $this;
	}
}


/**
* Class representing a database column in a SELECT query.
*
* @package		DQuery
*/

class DQueryClauseColumn
	extends DQueryClause
{
	/**
	 * Column name.  Might be an SQL function.
	 *
	 * @var		string
	 * @access	protected
	 */
	protected $name = null;

	/**
	 * Optional column alias.
	 *
	 * @var		string
	 * @access	protected
	 */
	protected $alias = null;

	/**
	 * Optional JTable object.
	 *
	 * @var		JTable
	 * @access	protected
	 */
	protected $table = null;

	/**
	 * Class constructor.
	 *
	 * @param	string	Column name.  Might be an SQL function.
	 * @param	string	Optional column alias.
	 * @param	JTable	Optional JTable object associated with the column.
	 */
	public function __construct( $name, $alias = '', JTable $table = null )
	{
		$this->name = $name;
		$this->alias = $alias;
		$this->table = $table;
	}

	/**
	 * Check that column name corresponds to a table property.
	 *
	 * @return	boolean	True if column is valid; false otherwise.
	 */
	public function check()
	{
		return property_exists( $this->table, $this->name );
	}

}