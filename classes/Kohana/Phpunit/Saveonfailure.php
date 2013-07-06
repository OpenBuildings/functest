<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Phpunit_Saveonfailure definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
abstract class Kohana_Phpunit_Saveonfailure implements PHPUnit_Framework_TestListener {
	
	protected static $errors = FALSE;

	public static function to_absolute_attribute($attribute, $content)
	{
		return preg_replace('/('.$attribute.'=[\'"])\//', '$1'.URL::base('http').'/', $content);
	}

	protected function save(PHPUnit_Framework_Test $test, $title, $page_content)
	{
		$this->initialize();

		foreach (array('href', 'action', 'src') as $attribute) 
		{
			$page_content = Phpunit_Saveonfailure::to_absolute_attribute($attribute, $page_content);
		}

		$testview = View::factory('functest/testview', array(
			'url' => $test->driver()->current_url(), 
			'title' => $title, 
			'javascript_errors' => $test->driver()->javascript_errors(),
			'javascript_messages' => $test->driver()->javascript_messages(),
		))->render();

		$page_content = str_replace('</body>', $testview.'</body>', $page_content);

		$test_name = get_class($test).'_'.$test->getName(FALSE);

		file_put_contents(Kohana::$config->load('functest.failures_dir')."/$test_name.html", $page_content);
	}

	public function initialize()
	{
		$dir = Kohana::$config->load('functest.failures_dir');
		if ( ! file_exists($dir))
		{
			mkdir($dir, 0777, TRUE);
		}
		elseif ( ! is_writable($dir))
		{
			throw new Kohana_Exception('Cannot save failure image. Directory ":dir" not writable', array(':dir' => $dir));
		}

		if ( ! Phpunit_Saveonfailure::$errors)
		{
			foreach (scandir($dir) as $old_failure) 
			{
				if ($old_failure !== '.' AND $old_failure !== '..')
				{
					unlink($dir.$old_failure);
				}
			}
			Phpunit_Saveonfailure::$errors = TRUE;
		}
	}

	public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		if ($test instanceof Testcase_Functest AND $test->is_driver_active() AND $test->driver()->is_page_active())
		{
			$this->save($test, $e->getMessage(), $test->html());
		}
	}

	public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
	{
		if ($test instanceof Testcase_Functest AND $test->is_driver_active() AND $test->driver()->is_page_active())
		{
			$this->save($test, $e->getMessage(), $test->html());
		}
	}

	public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		
	}

	public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		
	}

	public function startTest(PHPUnit_Framework_Test $test)
	{

	}

	public function endTest(PHPUnit_Framework_Test $test, $time)
	{
		
	}

	public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
	{

	}

	public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		
	}
	
}