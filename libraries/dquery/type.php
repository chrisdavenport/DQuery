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
* Base class for a database query.
*
* An abstract query class that must be extended for a given query type.
* Always use getInstance as a factory method to obtain a class of the required type.
*
* @abstract
* @package		DQuery
*/

abstract class DQueryType
	extends JObject
{
	/**
	 * The Joomla database object associated with this query.
	 *
	 * @var		JDatabase
	 * @access	protected
	 */
	protected $database = null;

	/**
	 * Options.
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $options = array();

	/**
	 * Database syntax to be used.
	 *
	 * @var		string
	 * @access	protected
	 */
	protected $adapter = 'mysql';

	/**
	 * Associates the query object with a Joomla database object.
	 *
	 * @param	JDatabase	Joomla database object.
	 * @param	array	Optional array of options.
	 * @return	DQuery	This object for method chaining.
	 * @access	publicclause->get( '
	 */
	public function setDatabase( JDatabase $database, $options = array() )
	{
		$this->database = $database;
		return $this;
	}

	/**
	 * Returns the Joomla database object associated with the query.
	 *
	 * @return	JDatabase	The Joomla database object
	 * @access	public
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * Sets the query syntax to be used.
	 *
	 * @param	string	Name of the syntax to use.
	 * @return	DQuery	This object for method chaining.
	 * @access	public
	 */
	public function setAdapter( $type = 'mysql' )
	{
		DQuery::adapter()->set( 'type', $type );
		return $this;
	}

	/**
	 * Returns the database syntax associated with the query.
	 *
	 * @return	string	Name of the syntax.
	 * @access	public
	 */
	public function getAdapter()
	{
		return DQuery::adapter()->get( 'type' );
	}

	/**
	 * Returns the query fragment as a string.
	 *
	 * @return	string	A database query fragment.
	 */
	public function __toString()
	{
		$adapter = DQuery::adapter()->getAdapter();
		$class = get_class( $this );
		$method = substr( $class, 6 );		// Remove "DQuery" prefix from class name

		$output = '';
		if (method_exists( $adapter, $method )) {
			$output = $adapter->$method( $this );
		}

		return $output;
	}

}