<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_Fixture_Database definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Functest_Fixture_Database {

	public static $_instance;
	public static function instance($db_name = NULL)
	{
		if ( ! Functest_Fixture_Database::$_instance)
		{
			Functest_Fixture_Database::$_instance = new Functest_Fixture_Database($db_name);
		}
		return Functest_Fixture_Database::$_instance;
	}

	protected $_db;
	protected $_params = array();
	protected $_db_name;

	public function __construct($db_name = NULL)
	{
		$this->db_name($db_name ?: Kohana::$config->load('functest.database'));

		$this->db(Database::instance($this->db_name()));

		$config = Kohana::$config->load('database.'.$this->db_name().'.connection');

		if ( ! isset($config['database']))
		{
			$matches = array();
			
			if ( ! preg_match('/dbname=([^;]+)(;|$)/', $config['dsn'], $matches))
				throw new Kohana_Exception("Error connecting to database, database missing");

			$this->params(':database', $matches[1]);

			if ( ! preg_match('/host=([^;]+)(;|$)/', $config['dsn'], $matches))
				throw new Kohana_Exception("Error connecting to database, host missing");

			$this->params(':hostname', $matches[1]);
		}
		else
		{
			$this->params(':database', $config['database']);	
			$this->params(':hostname', $config['hostname']);
		}
		$this->params(':password', $config['password']);
		$this->params(':username', $config['username']);
	}

	public function dump_data($file)
	{
		$params = Arr::merge(array(':file' => $file), $this->params());
		
		return $this->command("mysqldump -u:username -p:password --skip-comments --skip-triggers --compact --no-create-info :database > :file ", $params);

		return $this;
	}

	public function truncate_all()
	{
		$tabls = $this->db()->list_tables();

		foreach ($tabls as $table) 
		{
			$this->db()->query(NULL, "TRUNCATE TABLE `$table`");
		}
	}

	public function dump_structure($file)
	{
		$params = Arr::merge(array(':file' => $file), $this->params());
		
		return $this->command("mysqldump -u:username -p:password -h:hostname --skip-comments --add-drop-table --no-data :database | sed 's/AUTO_INCREMENT=[0-9]*\b//' > :file ", $params);
	}

	public function load($file)
	{
		$handle = fopen($file, 'r');

		while (($sql_line = fgets($handle)) !== FALSE) 
		{
			$this->db()->query(Database::INSERT, $sql_line);	
		}
		
		fclose($handle);
	}

	public function command($command, array $params = array())
	{
		system(strtr($command, $params));

		return $this;
	}
	
	public function params($key = NULL, $value = NULL)
	{
		if ($key === NULL)
			return $this->_params;
	
		if (is_array($key))
		{
			$this->_params = $key;
		}
		else
		{
			if ($value === NULL)
				return Arr::get($this->_params, $key);
	
			$this->_params[$key] = $value;
		}
	
		return $this;
	}
	
	public function db($db = NULL)
	{
		if ($db !== NULL)
		{
			$this->_db = $db;
			return $this;
		}
		return $this->_db;
	}

	public function db_name($db_name = NULL)
	{
		if ($db_name !== NULL)
		{
			$this->_db_name = $db_name;
			return $this;
		}
		return $this->_db_name;
	}

}