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
class Functest_TestsTest extends Testcase_Functest_Internal {

	public function test_list_files()
	{
		$expected = array(
			'functest' => array(
				$this->modpath.$this->test_folder.'test_file1.txt',
				$this->modpath.$this->test_folder.'test_file2.txt',
			),
			'test' => array(
					$this->modpath.'tests/testmodule/'.$this->test_folder.'test_file1.txt',
			)
		);
		$list_files = Functest_Tests::list_files($this->test_folder, array('functest' => $this->modpath));

		sort($list_files['functest']);

		$this->assertEquals($expected, $list_files);
	}

	public function test_module_directories()
	{
		$expected = array(
			'functest' => $this->modpath,
			'test' => $this->modpath.'tests/testmodule/',
		);

		$this->assertEquals($expected, Functest_Tests::module_directories());

		$expected = array(
			'0' => APPPATH,
			'functest' => $this->modpath,
			'test' => $this->modpath.'tests/testmodule/',
		);

		$this->environment()->set(array('functest.apptests' => TRUE));

		$this->assertEquals($expected, Functest_Tests::module_directories());
	}

	/**
	 * @covers Functest_Tests::autoload
	 */
	public function test_autoload()
	{
		$this->assertFalse(class_exists('Test_Dummy', FALSE));

		Functest_Tests::autoload('Test_Dummy');

		$this->assertTrue(class_exists('Test_Dummy', FALSE));
	}

	public function test_configure_environment()
	{
		$this->assertNotContains(array('Functest_Tests', 'autoload'), spl_autoload_functions());

		Functest_Tests::enable_environment();

		$this->assertContains(array('Functest_Tests', 'autoload'), spl_autoload_functions());

		Functest_Tests::disable_environment();

		$this->assertNotContains(array('Functest_Tests', 'autoload'), spl_autoload_functions());
	}

	public function test_fixture_files()
	{
		$fixtures_dir = 'tests'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR;

		$expected = array(
			$this->modpath.$fixtures_dir.'test_data.php',
			$this->modpath.$fixtures_dir.'test_data2.php',
			$this->modpath.'tests/testmodule/'.$fixtures_dir.'test_data.php',
		);

		$this->assertEquals($expected, Functest_Tests::fixture_files());
	}

	public function test_load_fixtures()
	{
		global $_functest_test_counter;
		$_functest_test_counter = 0;
		Kohana::cache(Functest_Tests::FIXTURE_CACHE, null, 0);
		Database::instance(Kohana::TESTING)->query(NULL, 'DELETE FROM table1');

		Functest_Tests::load_fixtures();

		$result = Database::instance(Kohana::TESTING)->query(Database::SELECT, 'SELECT * FROM table1');

		$expected = array(
			array(
				'id' => 1,
				'name' => 'test record',
			)
		);

		$this->assertEquals(1, $_functest_test_counter);
		
		$this->assertEquals($expected, $result->as_array());

		Database::instance(Kohana::TESTING)->query(NULL, 'DELETE FROM table1');

		Functest_Tests::load_fixtures();

		$result = Database::instance(Kohana::TESTING)->query(Database::SELECT, 'SELECT * FROM table1');

		$this->assertEquals($expected, $result->as_array());

		$this->assertEquals(1, $_functest_test_counter, 'Should load the sql from the cache. so the counter is not incremented');
	}

	public function test_transactions()
	{
		$database = $this->getMockBuilder('Database_MySQL')
			->disableOriginalConstructor()
			->getMock();

		$this->environment()->backup_and_set(array(
			'Database::$instances' => array(
				Kohana::TESTING => $database,
			)
		));

		$database->expects($this->once())
			->method('begin');

		$database->expects($this->once())
			->method('rollback');

		Functest_Tests::begin_transaction();
		Functest_Tests::rollback_transaction();
	}

	public function test_tests()
	{
		$tests = Functest_Tests::tests();
		$expected = array(
			$this->modpath.'tests/testmodule/tests/tests/DummyTest.php',
			$this->modpath.'tests/tests/FixtureTest.php',
			$this->modpath.'tests/tests/TestsTest.php',
		);
		
		$this->assertEquals($expected, $tests);
	}

	public function test_suite()
	{
		$suite = Functest_Tests::suite();
		$suite2 = Functest_Tests::suite();

		$this->assertInstanceOf(\PHPUnit\Framework\TestSuite::class, $suite);
		$this->assertSame($suite, $suite2);
	}
}
