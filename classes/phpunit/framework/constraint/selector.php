<?php defined('SYSPATH') OR die('No direct script access.');

class PHPUnit_Framework_Constraint_Selector extends PHPUnit_Framework_Constraint {

	protected $locator;

	function __construct(FuncTest_Driver $driver, $locator)
	{
		$this->drvier = $driver;
		$this->locator = $locator;
	}

	/**
	 * Evaluates the constraint for node $node. Returns TRUE if the
	 * node contains the locator, FALSE otherwise.
	 *
	 * @param mixed $other Value or object to evaluate.
	 * @return bool
	 */
	public function evaluate(FuncTest_Node $node)
	{
		return count($node->all($this->locator)) > 0;
	}
 
	/**
	 * Returns a string representation of the constraint.
	 *
	 * @return string
	 */
	public function toString()
	{
		return 'has selector';
	}
}