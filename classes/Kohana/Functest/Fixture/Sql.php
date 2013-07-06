<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_Resource_Sql definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Functest_Resource_Sql extends Functest_Resource_Cachable {

	public function process()
	{
		$cache_file = fopen($this->cache_file(), 'w');
		ftruncate($cache_file, 0);

		foreach ($this->files() as $file) 
		{
			fwrite($cache_file, file_get_contents($file)."\n\n");
		}

		fclose($cache_file);
	}
}