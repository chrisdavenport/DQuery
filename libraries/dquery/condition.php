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
	extends DQueryObject
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
	 * Array of substitutions to be made on the condition string.
	 *
	 * @var		array
	 * @access	protected
	 */
	protected $subs = array();

	/**
	 * Constructor.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @param	string	Optional glue to join the conditions together.
	 * @param	array	Optional array of variable substitutions
	 */
	public function __construct( $cond = null, $glue = null, $subs = array() )
	{
		if ($cond) {
			$this->add( $cond, $glue, $subs );
		}
	}

	/**
	 * Add a condition to the condition object.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @param	string	Optional glue to join the conditions together.
	 * @param	array	Optional array of variable substitutions
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function add( $cond, $glue = 'and', $subs = array() )
	{
		// Normalise the glue string.
		$glue = strtoupper( trim( $glue ) );

		// If we have an array of conditions then add them recursively.
		if (is_array( $cond )) {
			foreach ($cond as $term) {
				$this->add( $term, $glue, $subs );
			}
			return $this;
		}

		// If the conditions array has at most one entry, or if the glue is
		// compatible (ie. the same or null), then add the new condition
		// and set the glue to whatever we have been given (unless it's null).
		if (count( $this->conditions ) <= 1 || $this->glue == $glue || $this->glue == null) {
			$this->conditions[] = $cond;
			$this->glue = $glue ? $glue : $this->glue;
			$this->subs = array_merge( $this->subs, $subs );
			return $this;
		}

		// Otherwise, the glue is incompatible, so we create a new condition object.
		$this->conditions = array( clone( $this ), $cond );
		$this->glue = $glue;
		$this->subs = $subs;
		return $this;
	}

	/**
	 * Add condition to condition object with a logical AND.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @param	array	Optional array of variable substitutions
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function qand( $cond, $subs = array() )
	{
		$this->add( $cond, 'AND', $subs );
		return $this;
	}

	/**
	 * Add condition to condition object with a logical OR.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @param	array	Optional array of variable substitutions
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function qor( $cond, $subs = array() )
	{
		$this->add( $cond, 'OR', $subs );
		return $this;
	}

	/**
	 * Add condition to condition object with a logical XOR.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @param	array	Optional array of variable substitutions
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function qxor( $cond, $subs = array() )
	{
		$this->add( $cond, 'XOR', $subs );
		return $this;
	}

	/**
	 * Add condition to condition object with a logical NOT.
	 *
	 * @param	mixed	String, DQueryCondition or DQueryRelation, or array of any of these.
	 * @param	array	Optional array of variable substitutions
	 * @return	DQueryCondition	This object for method chaining.
	 * @access	public
	 */
	public function qnot( $cond, $subs = array() )
	{
		$this->add( $cond, 'NOT', $subs );
		return $this;
	}

}