<?php 

require_once __DIR__.'/../vendor/autoload.php';

Kohana::modules(array(
	'database' => MODPATH.'database',
	'module'   => __DIR__.'/..',
));

function test_autoload($class)
{
	$file = str_replace('_', '/', $class);

	if ($file = Kohana::find_file('tests/classes', $file))
	{
		require_once $file;
	}
}

spl_autoload_register('test_autoload');

Kohana::$config
	->load('database')
		->set(Kohana::TESTING, array(
			'type'       => 'MySQL',
			'connection' => array(
				'hostname'   => 'localhost',
				'database'   => 'test-functest',
				'username'   => 'root',
				'password'   => '',
				'persistent' => TRUE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
		));
