<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test main class
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_Functest {

	static protected $drivers = array();

	public static function autoload()
	{
		$file = strtolower(str_replace('_', '/', $class));

		if ($file = Kohana::find_file('tests/classes', $file))
		{
			require_once $file;
		}
	}

	public static function locator($locator, array $filters = array())
	{
		return new Functest_Locator($locator, $filters);
	}

	public static function node(Functest_Driver $driver, Functest_Node $parent = NULL, $selector = '')
	{
		return new Functest_Node($driver, $parent, $selector);
	}

	public static function nodelist(Functest_Driver $driver, Functest_Locator $locator, Functest_Node $parent = NULL, $selector = '')
	{
		return new Functest_Nodelist($driver, $locator, $parent, $selector);
	}

	public static function driver($name)
	{
		if ( ! isset(Functest::$drivers[$name]))
		{
			$driver_class = 'Functest_Driver_'.ucfirst($name);

			Functest::$drivers[$name] = new $driver_class();
		}

		return Functest::$drivers[$name];
	}
}
