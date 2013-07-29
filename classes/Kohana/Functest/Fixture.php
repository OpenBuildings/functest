<?php defined('SYSPATH') OR die('No direct script access.');

use Openbuildings\DBFixtures\Fixture;

/**
 * Functest_Fixture_Database definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Functest_Fixture extends Fixture {

	public static $_instance;
	public static function instance()
	{
		if ( ! Functest_Fixture::$_instance)
		{
			Functest_Fixture::$_instance = new Functest_Fixture();
		}
		return Functest_Fixture::$_instance;
	}

	public function database_to_pdo($database)
	{
		$config = Kohana::$config->load('database.'.$database);

		if ( ! $config) 
			throw new Kohana_Exception('Database configuration for :database not found', array(':database' => $database));
		
		$connection = $config['connection'];
		$pdo = NULL;

		if ($config['type'] == 'PDO') 
		{
			$pdo = new PDO($connection['dsn'], $connection['username'], $connection['password']);
		}
		elseif ($config['type'] == 'MySQL')
		{
			$pdo = new PDO('mysql:host='.$connection['hostname'].';dbname='.$connection['database'], $connection['username'], $connection['password']);
		}

		if ($config['charset']) 
		{
			$pdo->exec('SET NAMES '.$pdo->quote($config['charset']));
		}

		return $pdo;
	}

	public function pdo(PDO $pdo = NULL)
	{
		if ($pdo === NULL AND $this->_pdo === NULL) 
		{
			$this->_pdo = $this->database_to_pdo(Kohana::$config->load('functest.database'));
		}

		return parent::pdo($pdo);
	}
}