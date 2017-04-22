<?php defined('SYSPATH') OR die('No direct script access.');

use Openbuildings\EnvironmentBackup\Environment;
use Openbuildings\PHPUnitSpiderling\TestCase as SpiderlingTestCase;
use Openbuildings\Spiderling\Driver_Phantomjs;
use Openbuildings\Spiderling\Driver_Phantomjs_Connection;
use Openbuildings\Spiderling\Driver_Selenium;
use Openbuildings\Spiderling\Driver_Selenium_Connection;
use Openbuildings\EnvironmentBackup\Environment_Group_Config;

/**
 * Testcase_Functest definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Testcase_Functest extends SpiderlingTestCase {
	
	public function setUp()
	{
		parent::setUp();

		if (in_array($this->driver_type(), array('kohana', 'simple')))
		{
			Functest_Tests::begin_transaction();
		}
	}

	public function driver_type(): string
	{
		return parent::driver_type() ?: Kohana::$config->load('functest.default_driver');
	}

	public function tearDown()
	{
		if ($this->is_driver_active())
		{
			$this->driver()->clear();
		}

		if ($this->is_environment_active())
		{
			$this->environment()->restore();
		}
		
		if (in_array($this->driver_type(), array('kohana', 'simple')))
		{
			try {
				Functest_Tests::rollback_transaction();
			} catch (\PDOException $e) {}
		}
		else
		{
			Functest_Tests::load_fixtures();
		}
		
		parent::tearDown();
	}

	public function driver_phantomjs(): Driver_Phantomjs
	{
		$driver = parent::driver_phantomjs();

		$config = Kohana::$config->load('functest.drivers.phantomjs');

		$connection = new Driver_Phantomjs_Connection($config['server']);
		$connection->start($config['pid'], $config['log']);

		return $driver
			->connection($connection)
			->base_url(URL::base(TRUE));
	}

	public function driver_selenium(): Driver_Selenium
	{
		$driver = parent::driver_selenium();

		$config = Kohana::$config->load('functest.drivers.selenium');
		
		$connection = new Driver_Selenium_Connection($config['server']);
		$connection->start($config['desired']);

		return $driver
			->connection($connection)
			->base_url(URL::base(TRUE));
	}

	public function environment(): Environment
	{
		if ($this->_environment === NULL)
		{
			$this->_environment = parent::environment();
			$this->_environment->groups('config', new Environment_Group_Config());
		}
		return $this->_environment;
	}

}
