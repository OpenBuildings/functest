<?php defined('SYSPATH') OR die('No direct script access.');

class FuncTest_TestCase extends Unittest_TestCase {

	protected $driver_name;
	protected $driver;

	public function setUp()
	{
		parent::setUp();

		$this->driver_name = $this->driver_name ? $this->driver_name : Kohana::$config->load('functest.default_driver');

		FuncTest_Locator::$default_type = Kohana::$config->load('functest.default_locator_type');
	}

	public function tearDown()
	{
		if ($this->has_driver())
		{
			$this->driver()->clear();
		}
		
		parent::tearDown();
	}

	public function has_driver()
	{
		return $this->driver instanceof FuncTest_Driver;
	}

	public function driver()
	{
		if ( ! $this->has_driver())
		{
			$this->driver = FuncTest::driver($this->driver_name);
		}
		$this->driver->current_test = $this;

		return $this->driver;
	}

	public function page()
	{
		return $this->driver()->page();
	}

	public function visit($uri, array $query = array())
	{
		$this->driver()->visit($uri, $query);
		return $this;
	}

	public function content()
	{
		return $this->driver()->content();
	}

	public function current_path()
	{
		return $this->driver()->current_path();
	}

	public function current_url()
	{
		return $this->driver()->current_url();
	}

	public function hasCss($selector, array $filters = array())
	{
		return new PHPUnit_Framework_Constraint_Locator(array('css', $selector, $filters));
	}

	public function hasXpath($selector, array $filters = array())
	{
		return new PHPUnit_Framework_Constraint_Locator(array('xpath', $selector, $filters));
	}

	public function hasField($selector, array $filters = array())
	{
		return new PHPUnit_Framework_Constraint_Locator(array('field', $selector, $filters));
	}

	public function hasButton($selector, array $filters = array())
	{
		return new PHPUnit_Framework_Constraint_Locator(array('button', $selector, $filters));
	}

	public function hasLink($selector, array $filters = array())
	{
		return new PHPUnit_Framework_Constraint_Locator(array('link', $selector, $filters));
	}

	public function __call($method, $args)
	{
		return call_user_func_array(array($this->page(), $method), $args);
	}




} // End Kohana_Unittest_Jelly_TestCase