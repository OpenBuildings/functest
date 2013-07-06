<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_Environment definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Functest_Environment {

	protected $backup = array();

	public function restore()
	{
		$this->set($this->backup);
		$this->backup = array();
		return $this;
	}

	public static function factory(array $parameters = array())
	{
		return new Functest_Environment($parameters);
	}

	public function __construct(array $parameters = array())
	{
		if ($parameters)
		{
			$this->backup_and_set($parameters);
		}
	}

	public function backup_and_set(array $parameters)
	{
		return $this
			->backup(array_keys($parameters))
			->set($parameters);
	}

	public function backup(array $parameters)
	{
		foreach ($parameters as $name)
		{
			$method = 'get_'.Functest_Environment::name_type($name);

			$this->backup[$name] = Functest_Environment::$method($name);
		}
		return $this;
	}

	public function set(array $parameters)
	{
		foreach ($parameters as $name => $value) 
		{
			$method = 'set_'.Functest_Environment::name_type($name);
			Functest_Environment::$method($name, $value);
		}
		return $this;
	}

	public function name_type($name)
	{
		if (in_array($name, array('_GET', '_POST', '_SERVER', '_FILES', '_COOKIE', '_SESSION')))
		{
			return 'super_global';
		}
		elseif (strpos($name, '::$') !== FALSE)
		{
			return 'static_variable';
		}
		elseif (preg_match('/^[A-Z_-]+$/', $name) OR isset($_SERVER[$name]))
		{
			return 'server_variable';
		}
		else
		{
			return 'config_variable';
		}
	}

	public static function set_super_global($name, $value)
	{
		global $$name;

		$$name = $value;
	}

	public static function get_super_global($name)
	{
		global $$name;

		return $$name;
	}

	public static function set_config_variable($name, $value)
	{
		list($group, $name) = explode('.', $name, 2);

		Kohana::$config->load($group)->set($name, $value);
	}

	public static function get_config_variable($name)
	{
		return Kohana::$config->load($name);
	}

	public static function set_server_variable($name, $value)
	{
		$_SERVER[$name] = $value;
	}

	public static function get_server_variable($name)
	{
		return Arr::get($_SERVER, $name);
	}

	public static function set_static_variable($name, $value)
	{
		list($class, $name) = explode('::$', $name, 2);

		$class = new ReflectionClass($class);
		$property = $class->getProperty($name);
		$property->setAccessible(TRUE);
		$property->setValue(NULL, $value);
	}

	public static function get_static_variable($name)
	{
		list($class, $name) = explode('::$', $name, 2);

		$class = new ReflectionClass($class);
		$property = $class->getProperty($name);
		$property->setAccessible(TRUE);
		return $property->getValue();
	}


}