<?php defined('SYSPATH') OR die('No direct script access.');

use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;
use Openbuildings\PHPUnitSpiderling\Driver_Selenium_Connection;
use Openbuildings\PHPUnitSpiderling\Driver_Phantomjs_Connection;
use Openbuildings\EnvironmentBackup\Environment_Group_Config;

/**
 * Testcase_Functest definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Testcase_Functest extends Testcase_Spiderling {
	
	public function setUp()
	{
		if (in_array($this->driver_type(), array('kohana', 'simple')))
		{
			Functest_Tests::begin_transaction();
		}
	}

	public function driver_type()
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
			Functest_Tests::rollback_transaction();
		}
		else
		{
			Functest_Tests::load_fixtures();
		}
		
		parent::tearDown();
	}

	public function driver_phantomjs()
	{
		$driver = parent::driver_phantomjs();

		$config = Kohana::$config->load('functest.drivers.phantomjs');

		$connection = new Driver_Phantomjs_Connection($config['server']);
		$connection->start($config['pid'], $config['log']);

		return $driver
			->connection($connection)
			->base_url(URL::site(TRUE));
	}

	public function driver_selenium()
	{
		$driver = parent::driver_selenium();

		$config = Kohana::$config->load('functest.drivers.selenium');
		
		$connection = new Driver_Selenium_Connection($config['server']);
		$connection->start($config['desired']);

		return $driver
			->connection($connection)
			->base_url(URL::site(TRUE));
	}

	public function environment()
	{
		if ($this->_environment === NULL)
		{
			$this->_environment = parent::environment();
			$this->_environment->groups('config', new Environment_Group_Config());
		}
		return $this->_environment;
	}

}