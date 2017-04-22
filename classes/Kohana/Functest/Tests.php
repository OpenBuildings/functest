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
	CONST FIXTURE_CACHE = '_database_fixtures_cache';

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
	public static function enable_environment()
	{
		spl_autoload_register(array('Functest_Tests', 'autoload'));
	}

	public static function disable_environment()
	{
		spl_autoload_unregister(array('Functest_Tests', 'autoload'));
	}

	public static function fixture_files()
	{
		$search_directory = 'tests'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'fixtures';

		$fixture_files = Functest_Tests::list_files($search_directory, Functest_Tests::module_directories());

		if (count($fixture_files) > 1) 
		{
			$fixture_files = call_user_func_array('Arr::merge', $fixture_files);
		}

		$flattened_files = array_values(Arr::flatten($fixture_files));
		
		sort($flattened_files);

		return $flattened_files;
	}

	public static function begin_transaction()
	{
		Database::instance(Kohana::$config->load('functest.database'))->begin();
	}

	public static function rollback_transaction()
	{
		Database::instance(Kohana::$config->load('functest.database'))->rollback();
	}

	public static function load_fixtures()
	{
		$fixture = Functest_Fixture::instance();
		$import_sql = Kohana::cache(Functest_Tests::FIXTURE_CACHE);

		if ($import_sql)
		{
			$fixture->replace($import_sql);
		}
		else
		{
			$fixture->truncate_all();
			$fixture->execute_import_files(Functest_Tests::fixture_files());
			Kohana::cache(Functest_Tests::FIXTURE_CACHE, $fixture->dump(), Date::HOUR);
		}
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

	public static function tests()
	{
		$files = Kohana::list_files('tests/tests', Functest_Tests::module_directories());
		$files = Arr::flatten($files);

		return array_values(array_filter($files, function($file){
			return substr($file, -8) === 'Test.php';
		}));
	}

	public static function suite()
	{
		if ( ! Functest_Tests::$_suite)
		{
			Functest_Tests::$_suite = new \PHPUnit\Framework\TestSuite;

			Functest_Tests::$_suite->addTestFiles(Functest_Tests::tests());
		}

		return Functest_Tests::$_suite;
	}
}	
