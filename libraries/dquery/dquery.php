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
 * Interface for query type classes.
 */
interface iDQueryType
{
	public function get( $property );	// Get property from object
	public function __toString();		// Convert object to string.
}

/**
 * Interface for query clause classes.
 */
interface iDQueryClause
{
	public function get( $property );					// Get property from object
	public function addTerm( $term, $args = array() );	// Add term to clause.
	public function __toString();						// Convert object to string.
}

/**
 * Interface for query condiition classes.
 */
interface iDQueryCondition
{
	public function get( $property );								// Get property from object
	public function add( $cond, $glue = null, $subs = array() );	// Add a condition
	public function qand( $cond, $subs = array() );					// AND
	public function qor( $cond, $subs = array() );					// OR
	public function qxor( $cond, $subs = array() );					// XOR
	public function qnot( $cond, $subs = array() );					// NOT

}

/**
 * Interface for query relation classes.
 */
interface iDQueryRelation
{
	public function get( $property );				// Get property from object
	public function addRelation( $left, $right, $relation = '=' );
	public function eq( $left, $right );			// Equals
	public function ne( $left, $right );			// Not equals
	public function lt( $left, $right );			// Less than
	public function le( $left, $right );			// Less than or equal to
	public function gt( $left, $right );			// Greater than
	public function ge( $left, $right );			// Greater than or equal to
	public function like( $left, $right );			// LIKE
	public function notlike( $left, $right) ;		// NOT LIKE
	public function isnull( $left );				// IS NULL
	public function isnotnull( $left );				// IS NOT NULL
}

/**
 * Interface for query adapter classes.
 */
interface iDQueryAdapter
{
	public function condition( DQueryCondition $condition );	// Condition clause
}

/*
 * Interface for select query adapter classes.
 */
interface iDQueryAdapterSelect
{
	public function select( DQuerySelect $select );				// Entry point for select statements
	public function join( DQueryJoin $join );					// Join clause
	public function clauseColumns( DQueryClause $clause );		// Columns clause
	public function clauseColumn( DQueryClause $clause );		// Column subclause
	public function clauseTable( DQueryClause $clause );		// FROM clause
	public function clauseWhere( DQueryClause $clause );		// WHERE clause
	public function clauseSort( DQueryClause $clause );			// ORDER BY clause
	public function clauseGroupby( DQueryClause $clause );		// GROUP BY clause
}

/**
* Factory class database query objects.
*
* @package		DQuery
*/

class DQuery
{
	/**
	 * Factory method which returns a reference to a freshly-minted query object.
	 *
	 * Options:-
	 * 	database	A JDatabase object to associate with the query.
	 * 	table		A JTable object to associate with the query.
	 *
	 * @param	string	Category (eg. 'types', 'clauses' ).
	 * @param	string	Query type (eg. 'select' ).
	 * @param	array	Array of options to be passed to the type constructor.
	 * @return	DQuery	A database query object.
	 * @access	public
	 */
	public static function query( $type, $options = array() )
	{
		$instance = self::getInstance( 'type', $type, $options );

		// Set the default database object to use.
		$database = isset( $options['database'] ) ? $options['database'] : JFactory::getDBO();
		$instance->setDatabase( $database );

		// Set the database table if specified.
		if (isset( $options['table'] )) {
			$instance->setTable( $options['table'] );
		}

		return $instance;
	}

	/**
	 * Factory method which returns a reference to a freshly-minted query object.
	 *
	 * Options:-
	 * 	database	A JDatabase object to associate with the query.
	 * 	table		A JTable object to associate with the query.
	 *
	 * @param	string	Category (eg. 'types', 'clauses' ).
	 * @param	string	Query type (eg. 'select' ).
	 * @param	array	Array of options to be passed to the type constructor.
	 * @return	DQuery	A database query object.
	 * @access	public
	 */
	public static function clause( $type, $options = array() )
	{
		return self::getInstance( 'clause', $type, $options );
	}

	/**
	 * Factory method which returns a reference to a freshly-minted join object.
	 *
	 * @param	string		Join type (eg. 'left' ).
	 * @param	array		Array of options to be passed to the type constructor.
	 * @param	boolean		True if there should be a JTable object.
	 * @return	DQueryJoin	A database join object.
	 * @access	public
	 */
	public static function join( $table, $alias = '', $jtable = true )
	{
		return self::getInstance( '', 'join' )->table( $table, $jtable )->alias( $alias );
	}

	/**
	 * Factory method which returns a reference to a freshly-minted condition object.
	 *
	 * @param	mixed			String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @param	string			Optional glue to join the conditions together.
	 * @param	array			Optional array of variable substitutions
	 * @return	DQueryCondition	A condtion object.
	 * @access	public
	 */
	public static function condition( $cond = null, $glue = 'and', $subs = array() )
	{
		$instance = self::getInstance( '', 'condition' );

		if ($cond) {
			$instance->add( $cond, $glue, $subs );
		}

		return $instance;
	}

	/**
	 * Factory method which returns a reference to a freshly-minted relation object.
	 *
	 * @return	DQueryRelation	A relation object.
	 * @access	public
	 */
	public static function relation()
	{
		$instance = self::getInstance( 'extra', 'relation' );

		return $instance;
	}

	/**
	 * Factory method which returns a reference to the global adapter
	 * object, only creating it if it doesn't already exist.
	 *
	 * @return	DQueryAdapter	Global query adapter object.
	 * @access	public
	 */
	public static function adapter()
	{
		static $adapter = null;

		if (!isset( $adapter )) {
			$adapter = self::getInstance( '', 'adapter' );
		}

		return $adapter;
	}

	/**
	 * Factory method which returns a reference to a freshly-minted query object.
	 *
	 * Options:-
	 * 	database	A JDatabase object to associate with the query.
	 * 	table		A JTable object to associate with the query.
	 *
	 * @param	string	Category (eg. 'types', 'clauses' ).
	 * @param	string	Query object type (eg. 'select', 'where', etc. ).
	 * @param	mixed	Options to be passed to the object constructor.
	 * @return	object	A query object.
	 * @access	public
	 */
	public static function getInstance( $category, $type, $options = array() )
	{
		$type = preg_replace( '/[^A-Z0-9_\.-]/i', '', $type );

		// Construct class name.
		if ($category == '' || $category == 'type' ) {
			$adapter = 'DQuery'.ucfirst( $type );
		} else {
			$adapter = 'DQuery'.ucfirst( $category ).ucfirst( $type );
		}

		if ($category != '') {
			$category .= 's/';
		}

		// Load the class code if not already done.
		if (!class_exists( $adapter )) {

			jimport('joomla.filesystem.path');
			if ($path = JPath::find( self::addIncludePath(), $category.strtolower( $type ).'.php') ) {

				require_once( $path );

				if (!class_exists( $adapter )) {

					JError::raiseWarning( 0, 'Query component class ' . $adapter . ' not found in file.' );
					return false;
				}

			} else {

				JError::raiseWarning( 0, JText::_( 'Unable to load query component type: ').$category.'/'.$type );
				return false;

			}

		}

		// Instantiate the query object.
		$instance = new $adapter( $options );

		return $instance;
	}

	/**
	 * Add a directory where DQueryFactory should search for new extensions. You may
	 * either pass a string or an array of directories.
	 *
	 * @param	string	A path to search.
	 * @return	array	An array with directory elements.
	 * @access	public
	 */
	public static function addIncludePath( $path = null )
	{
		static $paths;

		if (!isset( $paths )) {
			$paths = array( dirname( __FILE__ ) );
		}

		// Just force path to array.
		settype( $path, 'array' );

		if (!empty( $path ) && !in_array( $path, $paths )) {

			// Loop through the path directories.
			foreach ($path as $dir) {

				// No surrounding spaces allowed!
				$dir = trim( $dir );

				// Add to the top of the search directories
				// so that custom paths are searched before core paths.
				array_unshift( $paths, $dir );
			}
		}
		return $paths;
	}

}