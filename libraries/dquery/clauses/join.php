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
* Join query clause.
*
* @package		DQuery
*/

class DQueryClauseJoin
	extends DQueryClause
	implements iDQueryClause
{
	/**
	 * Array of joins.
	 *
	 * @var		array of DQueryClauseJoinSimple objects.
	 * @access	protected
	 */
	protected $join = null;

	/**
	 * Add a term to the query clause.
	 *
	 * Argument can be a string giving the name of a table, or
	 * an array of table names, or an array of table name/alias pairs.
	 *
	 * @param	string or array	Term or array of terms.
	 */
	public function addTerm( $terms, $args = array() )
	{
		$terms = (array) $terms;

		foreach ($terms as $name => $alias) {

			if (is_numeric( $name )) {
				$this->join[] = new DQueryClauseJoinSimple( $alias, null, $args );
			} else {
				$this->join[] = new DQueryClauseJoinSimple( $name, $alias, $args );
			}

		}

		return $this;
	}

}

/**
* Simple join query clause.
*
* @package		DQuery
*/

class DQueryClauseJoinSimple
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
	 * Class constructor.
	 *
	 * @param	string	Table name.
	 * @param	string	Optional table alias.
	 * @param	array	Optional array of arguments.
	 */
	public function __construct( $name, $alias = '', $args = array() )
	{
		$this->name = $name;
		$this->alias = $alias;

		// Set the join type.
		if (isset( $args['type'] )) {
			$this->type = strtoupper( $args['type'] );
		}

		// Optionally, instantiate the corresponding JTable object.
		if (isset( $args['jtable'] ) && $args['jtable']) {

			// Instantiate a JTable object.
			if (!$this->table = JTable::getInstance( $name )) {
				echo 'Table not found: '.$name;
				return false;
			}

		}

	}

}