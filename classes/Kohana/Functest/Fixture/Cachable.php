<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_Resource_Cachable definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Functest_Fixture_Cachable {

	protected $_cache_file;
	protected $_files = array();

	public static function factory()
	{
		return new Functest_Fixture_Cachable();
	}

	public static function latest_mtime($files)
	{
		if ( ! $files)
			return NULL;
		
		$mtimes = array_map('filemtime', $files);
		rsort($mtimes);
		return reset($mtimes);
	}

	public function is_cache_stale()
	{
		return ( ! is_file($this->cache_file()) OR (filemtime($this->cache_file()) < Functest_Fixture_Cachable::latest_mtime($this->files())));
	}

	public function cache_file($cache_file = NULL)
	{
		if ($cache_file !== NULL)
		{
			$this->_cache_file = $cache_file;
			return $this;
		}
		return $this->_cache_file;
	}
	
	public function files(array $files = NULL)
	{
		if ($files !== NULL)
		{
			$files = array_filter($files, 'is_file');
			$this->_files = $files;
			return $this;
		}
		return $this->_files;
	}
}