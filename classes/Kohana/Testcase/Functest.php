<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Testcase_Functest definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Testcase_Functest extends PHPUnit_Framework_TestCase {
	
	protected $_driver;
	protected $_driver_type;
	protected $_environment;
	
	public function setUp()
	{
		$driver = Arr::path($this->getAnnotations(), 'method.driver', array(Kohana::$config->load('functest.default_driver')));

		$this->_driver_type = $driver[0];

		if ($this->_driver_type === 'native')
		{
			Functest_Fixture_Database::instance()->db()->begin();
		}
	}

	public function tearDown()
	{
		if ($this->is_driver_active())
		{
			$this->driver()->clear();
		}

		if ($this->is_environment_active())
		{
			$this->environment()->restore();
		}
		
		if ($this->_driver_type === 'native')
		{
			Functest_Fixture_Database::instance()->db()->rollback();
		}
		else
		{
			Functest_Tests::datafiles()->reset();
		}
		
		parent::tearDown();
	}

	public function driver()
	{
		if ( ! $this->_driver)
		{
			$this->_driver = Functest::driver($this->_driver_type);
		}

		return $this->_driver;
	}

	public function driver_type()
	{
		return $this->_driver_type;
	}

	public function environment()
	{
		if ($this->_environment === NULL)
		{
			$this->_environment = Functest_Environment::factory();
		}
		return $this->_environment;
	}

	public function is_driver_active()
	{
		return (bool) $this->_driver;
	}

	public function is_environment_active()
	{
		return (bool) $this->_environment;
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

	public function __call($method, $args)
	{
		return call_user_func_array(array($this->page(), $method), $args);
	}
}