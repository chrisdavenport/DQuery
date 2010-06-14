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
	 * Returns a SELECT statement.
	 *
	 * @param	DQuerySelect	Object.
	 * @return	string			An SQL statement.
	 */
	public function select( DQuerySelect $select )
	{
		$output = array( 'SELECT' );

		// Columns to be included in result set.
		$output[] = (string) $select->get( 'columns' );

		// FROM clause.
		$output[] = (string) $select->get( 'table' );

		// JOIN clause.
		$joins = $select->get( 'join' );
		foreach ($joins as $join) {
			$output[] = (string) $join;
		}

		// Optional WHERE clause.
		$where = $select->get( 'where' );
		if (!is_null( $where )) {
			$output[] = (string) $where;
		}

		// Optional GROUP BY clause.
		$groupby = $select->get( 'groupby' );
		if (!is_null( $groupby )) {
			$output[] = (string) $groupby;
		}

		// Optional ORDER BY clause.
		$orderby = $select->get( 'orderby' );
		if (!is_null( $orderby )) {
			$output[] = (string) $orderby;
		}

		return implode( ' ', $output );
	}

	/**
	 * Returns a JOIN clause.
	 *
	 * @param	DQueryJoin	Object.
	 * @return	string		An SQL clause statement.
	 */
	public function join( DQueryJoin $join )
	{
		$table = $join->get( 'table' );
		if ($table instanceof JTable) {
			$name = $table->getTableName();
		} else {
			$name = $join->get( 'name' );
		}
		$alias = $join->get( 'alias' );
		$output = "\n" . strtoupper( $join->get( 'type' ) ) . ' JOIN ';
		$output .= $alias ? $name.' AS '.$alias : $name;

		// ON conditions.
		$on = $join->get( 'on' );
		if (!is_null( $on )) {
			$output .= ' ON ' . (string) $on;
		}

		return $output;
	}

	/**
	 * Returns a list of columns.
	 *
	 * @param	DQueryClauseColumns	Object.
	 * @return	string				An SQL clause statement.
	 */
	public function clauseColumns( DQueryClause $clause )
	{
		$terms = $clause->get( 'terms' );

		// Default is to include all columns.
		if (empty( $terms )) {
			return '*';
		}

		// Construct array of columns specifications.
		$output = array();
		foreach ($terms as $term) {
			$output[] = (string) $term;
		}

		// Return concatenated list of columns specifications.
		return implode( ', ', $output );
	}

	/**
	 * Returns a single column.
	 *
	 * @param	DQueryClauseColumn	Object.
	 * @return	string				SQL column specifier.
	 */
	public function clauseColumn( DQueryClause $clause )
	{
		$output = $clause->get( 'name' );
		$alias = $clause->get( 'alias' );
		if ($alias) {
			$output .= ' AS ' . $alias;
		}

		return $output;
	}

	/**
	 * Returns a FROM clause.
	 *
	 * @param	DQueryClauseTable	Object.
	 * @return	string				An SQL clause statement.
	 */
	public function clauseTable( DQueryClause $clause )
	{
		$tables = $clause->get( 'table' );

		$output = array();
		foreach ($tables as $table) {
			$instance = $table->get( 'table' );
			if ($instance instanceof JTable) {
				$name = $instance->getTableName();
			} else {
				$name = $table->get( 'name' );
			}
			$alias = $table->get( 'alias' );
			$output[] = $alias ? $name.' AS '.$alias : $name;
		}

		$output = "\nFROM " . implode( ', ', $output );

		return $output;
	}

	/**
	 * Returns a WHERE clause.
	 *
	 * @param	DQueryClauseWhere	Object.
	 * @return	string				An SQL clause statement.
	 */
	public function clauseWhere( DQueryClause $clause )
	{
		$condition = $clause->get( 'condition' );

		return "\nWHERE " . (string) $condition;
	}

	/**
	 * Returns an ORDER BY clause.
	 *
	 * @param	DQueryClauseOrderBy	Object.
	 * @return	string				An SQL clause statement.
	 */
	public function clauseOrderBy( DQueryClause $clause )
	{
		$output = array();
		$terms = $clause->get( 'terms' );

		foreach ($terms as $name => $order) {
			$output[] = ($order == 'ASC') ? $name : $name.' '.$order;
		}

		return "\nORDER BY " . implode( ', ', $output );
	}

	/**
	 * Returns an GROUP BY clause.
	 *
	 * @param	DQueryClause	Object.
	 * @return	string			An SQL clause statement.
	 */
	public function clauseGroupBy( DQueryClause $clause )
	{
		$output = array();
		$terms = $clause->get( 'terms' );

		foreach ($terms as $term) {
			$output[] = $term;
		}

		return "\nGROUP BY " . implode( ', ', $output );
	}

	/**
	 * Returns a condition as a string.
	 *
	 * @param	DQueryCondition	Object.
	 * @return	string			A condition as a string.
	 */
	public function condition( DQueryCondition $clause )
	{
		$conditions = $clause->get( 'conditions' );
		$glue = $clause->get( 'glue' );

		// Recursively convert to string type.
		$output = array();
		foreach ($conditions as $condition ) {
			$output[] = (string) $condition;
		}

		switch (count( $conditions )) {
			case 0:
				$return = '';
				break;
			case 1:
				$return = $conditions[0];
				break;
			default:
				$return = '(' . implode( ' '.$glue.' ', $output ) . ')';
				break;
		}

		return $return;
	}

}