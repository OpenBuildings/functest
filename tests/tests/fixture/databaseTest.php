<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_Fixture_DatabaseTest 
 *
 * @group functest
 * @group functest.fixture
 * @group functest.fixture.database
 * 
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
class Functest_Fixture_DatabaseTest extends Testcase_Functest {

	public $database;

	public function setUp()
	{
		parent::setUp();
		$this->environment()->backup_and_set(array(
			'functest.database' => 'test',
			'database.test' => array(
				'type' => 'mysql',
				'connection' => array(
					'database' => 'testdb',
					'username' => 'testuser',
					'password' => 'testpassword',
					'hostname' => 'testhost',
				)
			),
			'database.test2' => array(
				'type' => 'PDO',
				'connection' => array(
					'dsn'      => 'mysql:host=testhost2;dbname=testdb2',
					'username' => 'testuser2',
					'password' => 'testpassword2',
				)
			)
		));
	}

	public function test_construct()
	{
		$database = new Functest_Fixture_Database('test');

		$expected_params = array(
			':database' => 'testdb',
			':username' => 'testuser',
			':password' => 'testpassword',
			':hostname' => 'testhost',
		);

		$this->assertEquals($expected_params, $database->params());

		$database2 = new Functest_Fixture_Database('test2');

		$expected_params_pdo = array(
			':database' => 'testdb2',
			':username' => 'testuser2',
			':password' => 'testpassword2',
			':hostname' => 'testhost2',
		);

		$this->assertEquals($expected_params_pdo, $database2->params());
	}
}