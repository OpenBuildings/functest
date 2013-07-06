<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_Fixture_CachableTest 
 *
 * @group functest
 * @group functest.fixture
 * @group functest.fixture.cachable
 * 
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
class Functest_Fixture_CachableTest extends Testcase_Functest_Internal {

	public $files;
	public $cache_file;
	public $cachable;

	public function setUp()
	{
		parent::setUp();

		$this->files = array(
			$this->modpath.$this->test_folder.'test_file1.txt',
			$this->modpath.$this->test_folder.'test_file2.txt'
		);

		$this->cache_file = $this->modpath.'tests'.DIRECTORY_SEPARATOR.'test_data'.DIRECTORY_SEPARATOR.'test_cache.txt';

		$this->cachable = Functest_Fixture_Cachable::factory()
			->files($this->files)
			->cache_file($this->cache_file);
	}

	public function test_latest_mtime()
	{
		touch($this->files[0], 10000);
		touch($this->files[1], 20000);

		$this->assertEquals(20000, Functest_Fixture_Cachable::latest_mtime($this->files));

		touch($this->files[0], 40000);

		$this->assertEquals(40000, Functest_Fixture_Cachable::latest_mtime($this->files));
	}

	public function test_factory()
	{
		$this->assertInstanceOf('Functest_Fixture_Cachable', Functest_Fixture_Cachable::factory());
	}

	public function test_is_cache_stale()
	{
		touch($this->files[0], 10000);
		touch($this->files[1], 20000);

		touch($this->cache_file, 30000);

		$this->assertFalse($this->cachable->is_cache_stale());

		touch($this->files[1], 40000);		

		$this->assertTrue($this->cachable->is_cache_stale());
	}
}