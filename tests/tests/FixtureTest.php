<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_TestsTest 
 *
 * @group tests
 * 
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
class Functest_FixtureTest extends Testcase_Functest_Internal {

	public function test_database_to_pdo()
	{
		$fixtures = new Functest_Fixture();

		$pdo = $fixtures->database_to_pdo(Kohana::TESTING);

		$this->assertInstanceOf('PDO', $pdo);

		$pdo = $fixtures->database_to_pdo('pdo_test');

		$this->assertInstanceOf('PDO', $pdo);

		$this->expectException(\Kohana_Exception::class);

		$pdo = $fixtures->database_to_pdo('pdo_not_exists');
	}
}
