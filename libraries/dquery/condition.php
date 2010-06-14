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
* Class for a database query condition.
*
* Always use getInstance as a factory method to obtain a class of this type.
*
* @abstract
* @package		DQuery
*/

class DQueryCondition
	extends DQueryType
	implements iDQueryCondition
{
	/**
	 * Array of condition or relation objects.
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $conditions = array();

	/**
	 * Glue which holds members of the condition together.
	 *
	 * @var		string
	 * @access	protected
	 */
	protected $glue = null;

	/**
	 * Constructor.
	 */
	public function __construct( $cond = null, $glue = null )
	{
		if ($cond) {
			$this->add( $cond, $glue );
		}
	}

	/**
	 * Add a condition to the condition object.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @param	string	Glue to join the conditions together.
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function add( $cond, $glue = 'and' )
	{
		// Normalise the glue string.
		$glue = strtoupper( trim( $glue ) );

		// If we have an array of conditions then add them recursively.
		if (is_array( $cond )) {
			foreach ($cond as $term) {
				$this->add( $term, $glue );
			}
			return $this;
		}

		// If the conditions array has at most one entry, or if the glue is
		// compatible (ie. the same or null), then add the new condition
		// and set the glue to whatever we have been given (unless it's null).
		if (count( $this->conditions ) <= 1 || $this->glue == $glue || $this->glue == null) {
			$this->conditions[] = $cond;
			$this->glue = $glue ? $glue : $this->glue;
			return $this;
		}

		// Otherwise, the glue is incompatible, so we create a new condition object.
		$this->conditions = array( clone( $this ), $cond );
		$this->glue = $glue;
		return $this;
	}

	/**
	 * Add condition to condition object with a logical AND.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function qand( $cond )
	{
		$this->add( $cond, 'AND' );
		return $this;
	}

	/**
	 * Add condition to condition object with a logical OR.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function qor( $cond )
	{
		$this->add( $cond, 'OR' );
		return $this;
	}

	/**
	 * Add condition to condition object with a logical XOR.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function qxor( $cond )
	{
		$this->add( $cond, 'XOR' );
		return $this;
	}

	/**
	 * Add condition to condition object with a logical NOT.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function qnot( $cond )
	{
		$this->add( $cond, 'NOT' );
		return $this;
	}

}