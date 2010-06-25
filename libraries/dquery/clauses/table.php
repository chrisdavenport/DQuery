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
* Table query clause.
*
* @package		DQuery
*/

class DQueryClauseTable
	extends DQueryClause
	implements iDQueryClause
{
	/**
	 * Array of tables.
	 *
	 * @var		array of DQueryClauseTableSimple objects.
	 * @access	protected
	 */
	protected $table = null;

	/**
	 * Add a term to the query clause.
	 *
	 * Argument can be a string giving the name of a table, or
	 * an array of table names, or an array of table name/alias pairs.
	 *
	 * @param	string or array		Term or array of terms.
	 * @param	array				Array of arguments
	 * @return	DQueryClauseTable	Object for method chaining.
	 */
	public function addTerm( $terms, $args = array() )
	{
		$terms = (array) $terms;

		foreach ($terms as $name => $alias) {

			if (is_numeric( $name )) {
				$this->table[] = new DQueryClauseTableSimple( $alias, null, $args );
			} else {
				$this->table[] = new DQueryClauseTableSimple( $name, $alias, $args );
			}

		}

		return $this;
	}

}

/**
* Simple table query clause.
*
* @package		DQuery
*/

class DQueryClauseTableSimple
	extends JObject
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
	 * JTable object.
	 *
	 * @var		JTable
	 * @access	protected
	 */
	protected $table = null;

	/**
	 * Class constructor.
	 *
	 * @param	string	Table name.
	 * @param	string	Optional table alias.
	 * @param	array	Optional array of arguments.
	 * @param	boolean	True if there should be a JTable object.
	 */
	public function __construct( $name, $alias = '', $args = array() )
	{
		$this->set( 'name', $name );
		$this->set( 'alias', $alias );

		if (isset( $args['jtable'] ) && $args['jtable']) {

			// Instantiate a JTable object.
			if (!$table = JTable::getInstance( $name )) {
				echo 'Table not found: '.$name;
				return false;
			}
			$this->set( 'table', $table );

		}

	}

}