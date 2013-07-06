<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_Resource_Datafiles definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Functest_Fixture_Datafiles extends Functest_Fixture_Cachable {

	public static function factory()
	{
		return new Functest_Fixture_Datafiles();
	}

	public function process()
	{
		$old_filemtime = is_file($this->cache_file()) ? filemtime($this->cache_file()) : 0;

		$cache_file = fopen($this->cache_file(), 'w');
		ftruncate($cache_file, 0);
		
		try 
		{
			foreach ($this->files() as $file) 
			{
				require_once $file;
			}
		} 
		catch (Kohana_Exception $exception) 
		{
			fclose($cache_file);
			if ($old_filemtime)
			{
				touch($this->cache_file(), $old_filemtime);
			}
			
			throw $exception;
		}

		Functest_Fixture_Database::instance()->dump_data($this->cache_file());
		
		return $this;
	}

	public function reset()
	{
		Functest_Fixture_Database::instance()->truncate_all();
		Functest_Fixture_Database::instance()->load($this->cache_file());
		return $this;
	}

	public function update()
	{
		if ($this->is_cache_stale())
		{
			$this->process();
		}
		else
		{
			Functest_Fixture_Database::instance()->load($this->cache_file());
		}
		return $this;
	}
}