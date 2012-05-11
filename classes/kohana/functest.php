<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_FuncTest {

	static protected $drivers = array();

	static public function locator($locator, array $filters = NULL)
	{
		return new FuncTest_Locator($locator, $filters);
	}

	static public function node(FuncTest_Driver $driver, $parent = NULL, $selector = NULL)
	{
		return new FuncTest_Node($driver, $parent, $selector);
	}

	static public function driver($name)
	{
		if ( ! isset(FuncTest::$drivers[$name]))
		{
			$driver_class = 'FuncTest_Driver_'.ucfirst($name);

			FuncTest::$drivers[$name] = new $driver_class();
		}

		return FuncTest::$drivers[$name];
	}
}
