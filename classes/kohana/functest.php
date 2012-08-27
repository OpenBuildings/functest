<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test main class
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest {

	static protected $drivers = array();

	static public function locator($locator, array $filters = array())
	{
		return new FuncTest_Locator($locator, $filters);
	}

	static public function node(FuncTest_Driver $driver, FuncTest_Node $parent = NULL, $selector = '')
	{
		return new FuncTest_Node($driver, $parent, $selector);
	}

	static public function nodelist(FuncTest_Driver $driver, FuncTest_Locator $locator, FuncTest_Node $parent = NULL, $selector = '')
	{
		return new FuncTest_NodeList($driver, $locator, $parent, $selector);
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
