<?php 

require_once __DIR__.'/../vendor/autoload.php';

Kohana::modules(array(
	'database' => MODPATH.'database',
	'functest' => __DIR__.'/..',
	'test'     => __DIR__.'/../tests/testmodule',
));

Kohana::$config
	->load('database')
		->set(Kohana::TESTING, array(
			'type'       => 'PDO',
			'connection' => array(
				'dsn'        => 'mysql:host=localhost;dbname=test-functest',
				'username'   => 'root',
				'password'   => '',
				'persistent' => TRUE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
		))
		->set('pdo_test', array(
			'type'       => 'PDO',
			'connection' => array(
				'dsn'        => 'mysql:host=localhost;dbname=test-functest',
				'username'   => 'root',
				'password'   => '',
				'persistent' => TRUE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
		));

