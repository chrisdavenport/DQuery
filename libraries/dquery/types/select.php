<?php
/**
* @version		$Id: $
* @package		DQuery
* @copyright	Copyright (C) 2010 Davenport Technology Services. All rights reserved.
* @license		GNU/GPL version 2 or later.
*/

// Check to ensure this file is being called from within the Joomla Framework.

defined( 'JPATH_BASE' ) or die();

jimport( 'dquery.type' );

/**
* DQuery type class for SELECT query.
*
* Always use DQuery::getInstance( 'select' ) as a factory method to obtain a class of this type.
*
* @abstract
* @package		DQuery
*/

class DQuerySelect
	extends DQueryType
	implements iDQueryType
{
	/**
	 * Columns clause.
	 *
	 * @var		DQueryClauseColumns
	 * @access	protected
	 */
	protected $columns = null;

	/**
	 * FROM clause.
	 *
	 * @var		DQueryClauseTable
	 * @access	protected
	 */
	protected $table = null;

	/**
	 * JOIN clause.
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $join = array();

	/**
	 * Where clause.
	 *
	 * @var		DQueryClauseWhere
	 * @access	protected
	 */
	protected $where = null;

	/**
	 * Order by clause.
	 *
	 * @var		DQueryClauseSort
	 * @access	protected
	 */
	protected $sort = null;

	/**
	 * Group by clause.
	 *
	 * @var		DQueryClauseGroup
	 * @access	protected
	 */
	protected $group = null;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		// Columns clause is required, so might as well create it now.
		$this->columns = DQuery::clause( 'columns' );

		// From clause is also compulsory.
		$this->table = DQuery::clause( 'table' );

	}

	/**
	 * Adds one or more column specifications to the SELECT query.
	 *
	 * @param	array or string	One or more columns.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function columns( $columns )
	{
		$this->columns->addTerm( $columns );
		return $this;
	}

	/**
	 * Adds a table name to the SELECT query.
	 *
	 * @param	mixed			JTable object or table name.
	 * @param	boolean			True if there should be a JTable object.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function table( $tables, $jtable = true )
	{
		$this->table->addTerm( $tables, array( 'jtable' => $jtable ) );
		return $this;
	}

	/**
	 * Adds a join to the SELECT query.
	 *
	 * @param	string			Join type or a join object.
	 * @param	mixed			JTable object or table name.
	 * @param	boolean			True if there should be a JTable object.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function join( $type, $table, $jtable = true )
	{
		// If we have been given a preconfigured join object, then simply add it.
		if ($table instanceof DQueryJoin) {
			$table->type( $type );
			$this->join[] = $table;
			return $this;
		}

		// Create a new join object and add it.
		$this->join[] = DQuery::join( $table )->type( $type );
		return $this;
	}

	/**
	 * Adds a LEFT JOIN to the SELECT query.
	 *
	 * @param	mixed			JTable object or table name.
	 * @param	boolean			True if there should be a JTable object.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function leftjoin( $table, $jtable = true )
	{
		$this->join( 'left', $table, $jtable );
		return $this;
	}

	/**
	 * Adds a RIGHT JOIN to the SELECT query.
	 *
	 * @param	mixed			JTable object or table name.
	 * @param	boolean			True if there should be a JTable object.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function rightjoin( $table, $jtable = true )
	{
		$this->join( 'right', $table, $jtable );
		return $this;
	}

	/**
	 * Adds an INNER JOIN to the SELECT query.
	 *
	 * @param	mixed			JTable object or table name.
	 * @param	boolean			True if there should be a JTable object.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function innerjoin( $table, $jtable = true )
	{
		$this->join( 'inner', $table, $jtable );
		return $this;
	}

	/**
	 * Adds an OUTER JOIN to the SELECT query.
	 *
	 * @param	mixed			JTable object or table name.
	 * @param	boolean			True if there should be a JTable object.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function outerjoin( $table, $jtable = true )
	{
		$this->join( 'outer', $table, $jtable );
		return $this;
	}

	/**
	 * Adds one or more term specifications to the WHERE clause.
	 *
	 * @param	array or string	One or more WHERE terms.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function where( $terms, $glue = 'AND' )
	{
		if (is_null( $this->where )) {
			$this->where = DQuery::clause( 'where' );
		}

		$this->where->addTerm( $terms, array( 'glue' => $glue ) );
		return $this;
	}

	/**
	 * Adds one or more term specifications to the ORDER BY clause.
	 *
	 * @param	array or string	One or more ORDER BY terms.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function sort( $terms )
	{
		if (is_null( $this->sort )) {
			$this->sort = DQuery::clause( 'sort' );
		}

		$this->sort->addTerm( $terms );
		return $this;
	}

	/**
	 * Adds one or more term specifications to the GROUP BY clause.
	 *
	 * @param	array or string	One or more GROUP BY terms.
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function group( $terms )
	{
		if (is_null( $this->group )) {
			$this->group = DQuery::clause( 'group' );
		}

		$this->group->addTerm( $terms );
		return $this;
	}

	/**
	 * Adds a pagination clause to the query.
	 *
	 * @param	integer			Page number being requested.
	 * @param	integer			Results per page (0 = use current).
	 * @return	DQuerySelect	This object for method chaining.
	 */
	public function page( $pageNumber = 0, $pageSize = 0 )
	{
		if (is_null( $this->page )) {
			$this->page = DQuery::clause( 'page' );
		}

		$this->page->addTerm( '', array( 'pageNumber' => $pageNumber, 'pageSize' => $pageSize ) );
		return $this;
	}

}