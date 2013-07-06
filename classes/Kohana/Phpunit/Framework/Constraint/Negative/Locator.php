<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Phpunit_Framework_Constraint_Negative_Locator definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Phpunit_Framework_Constraint_Negative_Locator extends PHPUnit_Framework_Constraint {
	
	protected $locator;

	function __construct(array $locator)
	{
		$this->locator = $locator;
	}

	protected function matches($other)
	{
		try 
		{
			$other->not_present($this->locator, array());
			return TRUE;
		} 
		catch (Functest_Exception_Found $excption) 
		{
			return FALSE;
		}
	}

	public function failureDescription($other)
	{
		if ($other->is_root())
		{
			$node_string = 'HTML page';
		}
		else
		{
			$node_string = $other->tag_name();

			if ($id = $other->attribute('id'))
			{
				$node_string .= '#'.$id;
			}

			if ($class = $other->attribute('class'))
			{
				$node_string .= '.'.join('.', explode(' ', $class));
			}
		}
		return "$node_string ".$this->toString();
	}

	/**
	 * Returns a string representation of the constraint.
	 *
	 * @return string
	 */
	public function toString()
	{
		$string = "does not have '".Arr::get($this->locator, 0)."' selector '".Arr::get($this->locator, 1)."'";
		foreach (Arr::get($this->locator, 2, array()) as $filter => $value)
		{
			$string .= " and $filter => '$value'";
		}
		return $string;
	}
}