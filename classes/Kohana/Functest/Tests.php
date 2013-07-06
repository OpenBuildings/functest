<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_Tests definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Functest_Tests {

	public static $_suite = NULL;
	public static $_datafiles = NULL;

	public static function autoload($class)
	{
		$file = str_replace('_', '/', $class);

		if ($file = Kohana::find_file('tests/classes', $file))
		{
			require_once $file;
		}
	}

	/**
	 * Configures the environment for testing
	 *
	 * Does the following:
	 *
	 * * Loads the phpunit framework (for the web ui)
	 * * Restores exception phpunit error handlers (for cli)
	 * * registeres an autoloader to load test files
	 */
	public static function configure_environment()
	{
		// restore_exception_handler();
		// restore_error_handler();

		spl_autoload_register(array('Functest_Tests', 'autoload'));
	}

	public static function configure_database()
	{
		Functest_Fixture_Database::instance()->truncate_all();
		Functest_Tests::datafiles()->update();
	}

	public static function datafiles()
	{
		if (Functest_Tests::$_datafiles === NULL)
		{
			$fixture_files = Functest_Tests::list_files('tests'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'fixtures', Functest_Tests::module_directories());

			$fixture_files = call_user_func_array('Arr::merge', $fixture_files);
			
			$cache_file = Arr::get(Kohana::modules(), 'functest').'tests'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'fixtures.sql';

			Functest_Tests::$_datafiles = Functest_Fixture_Datafiles::factory()
				->cache_file($cache_file)
				->files($fixture_files);
		}
		
		return Functest_Tests::$_datafiles;
	}

	public static function list_files($dir, array $paths)
	{
		return array_map(function($path) use ($dir) { 
			$full_dir = $path.$dir;
			$files = array();

			if (is_dir($full_dir))
			{
				foreach (new DirectoryIterator($full_dir) as $file) 
				{
					if ( ! $file->isDot())
					{
						$files[] = $file->getPathname();
					}
				}
			}

			return $files;
		}, Functest_Tests::module_directories());
	}

	public static function module_directories()
	{
		$module_directories = Arr::extract(Kohana::modules(), Kohana::$config->load('functest.modules'));
		if (Kohana::$config->load('functest.apptests'))
		{
			array_unshift($module_directories, APPPATH);
		}
		return $module_directories;
	}

	public static function suite()
	{
		if ( ! Functest_Tests::$_suite)
		{
			Functest_Tests::$_suite = new PHPUnit_Framework_TestSuite;

			$modules_for_testing = Arr::extract(Kohana::modules(), Kohana::$config->load('functest.modules'));

			$files = Kohana::list_files('tests/tests', Functest_Tests::module_directories());
			$files = Arr::flatten($files);
			$files = array_filter($files, function($file){
				return substr($file, -8) === 'Test.php';
			});

			Functest_Tests::$_suite->addTestFiles($files);
		}

		return Functest_Tests::$_suite;
	}
}	