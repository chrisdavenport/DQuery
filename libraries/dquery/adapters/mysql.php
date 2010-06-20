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
* MySQL adapter class.
*
* Each of the functions in this class is called from the __toString magic method
* of the corresponding class name.  For example, the select function will be called
* from DQuerySelect::__toString.
*
* @package		DQuery
*/

class DQueryAdapterMySQL
	implements iDQueryAdapter, iDQueryAdapterSelect
{
	/**
	 * Generic SQL adapter.
	 */
	protected $generic = null;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		// Get an instance of the generic SQL adapter.
		$this->generic = DQuery::adapter()->getAdapter( 'sql' );
	}

	/**
	 * Returns a SELECT statement.
	 *
	 * @param	DQuerySelect	Object.
	 * @return	string			An SQL statement.
	 */
	public function select( DQuerySelect $select )
	{
		return $this->generic->select( $select );
	}

	/**
	 * Returns a JOIN clause.
	 *
	 * @param	DQueryJoin	Object.
	 * @return	string		An SQL clause statement.
	 */
	public function join( DQueryJoin $join )
	{
		return $this->generic->join( $join );
	}

	/**
	 * Returns a list of columns.
	 *
	 * @param	DQueryClauseColumns	Object.
	 * @return	string				An SQL clause statement.
	 */
	public function clauseColumns( DQueryClause $clause )
	{
		return $this->generic->clauseColumns( $clause );
	}

	/**
	 * Returns a single column.
	 *
	 * @param	DQueryClauseColumn	Object.
	 * @return	string				SQL column specifier.
	 */
	public function clauseColumn( DQueryClause $clause )
	{
		return $this->generic->clauseColumn( $clause );
	}

	/**
	 * Returns a FROM clause.
	 *
	 * @param	DQueryClauseTable	Object.
	 * @return	string				An SQL clause statement.
	 */
	public function clauseTable( DQueryClause $clause )
	{
		return $this->generic->clauseTable( $clause );
	}

	/**
	 * Returns a WHERE clause.
	 *
	 * @param	DQueryClauseWhere	Object.
	 * @return	string				An SQL clause statement.
	 */
	public function clauseWhere( DQueryClause $clause )
	{
		return $this->generic->clauseWhere( $clause );
	}

	/**
	 * Returns an ORDER BY clause.
	 *
	 * @param	DQueryClauseSort	Object.
	 * @return	string				An SQL clause statement.
	 */
	public function clauseSort( DQueryClause $clause )
	{
		return $this->generic->clauseSort( $clause );
	}

	/**
	 * Returns an GROUP BY clause.
	 *
	 * @param	DQueryClause	Object.
	 * @return	string			An SQL clause statement.
	 */
	public function clauseGroupBy( DQueryClause $clause )
	{
		return $this->generic->clauseGroupBy( $clause );
	}

	/**
	 * Returns a condition as a string.
	 *
	 * @param	DQueryCondition	Object.
	 * @return	string			A condition as a string.
	 */
	public function condition( DQueryCondition $clause )
	{
		return $this->generic->condition( $clause );
	}

	/**
	 * Replace substition codes and perform quoting in the condition.
	 *
	 * @param	array	Array of substitions to be made.
	 * @param	string	String to be altered.
	 * @return	string	String after substitution.
	 */
	public function substitute( $needles, $haystack )
	{
		$source = $target = array();
		foreach ($needles as $key => $value) {
			$source[] = '{'.$key.'}';
			$target[] = is_numeric( $value ) ? $value : "'".$value."'";
		}

		$haystack = str_replace( $source, $target, $haystack );
		$haystack = preg_replace( '/\[(.*?)\]/', '`${1}`', $haystack );

		return $haystack;
	}

}