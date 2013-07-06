<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Functest_TestsTest 
 *
 * @group functest
 * @group functest.tests
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
			)
		);

		$this->assertEquals($expected, Functest_Tests::list_files($this->test_folder, array('functest' => $this->modpath)));
	}

	public function test_module_directories()
	{
		$expected = array(
			'functest' => $this->modpath
		);

		$this->assertEquals($expected, Functest_Tests::module_directories());

		$expected = array(
			'0' => APPPATH,
			'functest' => $this->modpath,
		);

		$this->environment()->set(array('functest.apptests' => TRUE));

		$this->assertEquals($expected, Functest_Tests::module_directories());
	}
}