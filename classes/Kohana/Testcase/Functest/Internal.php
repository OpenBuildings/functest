<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Testcase_Functest_Internal definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Testcase_Functest_Internal extends Testcase_Functest {

	public $modpath;
	public $test_folder;

	protected function setUp(): void
	{
		parent::setUp();
		$this->environment()->backup_and_set(array(
			'Request::$client_ip' => '127.0.0.1',
			'Request::$user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.162 Safari/535.19',
			'functest.modules' => array('functest', 'test'),
			'functest.apptests' => FALSE,
		));

		$this->modpath = Arr::get(Kohana::modules(), 'functest');
		$this->test_folder = 'tests'.DIRECTORY_SEPARATOR.'test_data'.DIRECTORY_SEPARATOR.'test_folder'.DIRECTORY_SEPARATOR;
	}
}
