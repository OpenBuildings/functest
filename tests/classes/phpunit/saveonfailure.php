<?php defined('SYSPATH') OR die('No direct script access.');

class PHPUnit_SaveOnFailure implements PHPUnit_Framework_TestListener {

	protected static $errors = FALSE;

	protected function save(PHPUnit_Framework_Test $test, $title, $page_content)
	{
		$this->initialize();
		$dir = Kohana::$config->load('functest.failures_dir');
		$url = $test->driver()->current_url();

		$javascript_errors = $test->driver()->javascript_errors();
		$javascript_errors_html = '';

		if ($javascript_errors)
		{
			$javascript_errors_html .= '<div style="margin-top:10px;"> Javascript Errors: <ul style="font-family:_monospace; font-size: 12px; background-color:white; padding: 10px;">';

			foreach ($javascript_errors as $error) 
			{
				$javascript_errors_html .= '<li style="margin-bottom:5px;"><div style="color:red;">'.$error['errorMessage'].'</div><div style="font-size:11px"> in '.$error['sourceName'].' line '.$error['lineNumber'].'</div></li>';
			}

			$javascript_errors_html .= '</ul></div>';
		}

		$error_message = <<<ERROR_MESSAGE
<div 
	style="position: fixed; background: lightgrey; border: 1px solid red; padding: 10px 30px 10px 20px; color: black; width: 800px; margin-left: -400px; top: 10px; left: 50%; border-radius: 4px; z-index: 10000; font-size: 18px; line-height: 25px; ">
	<div style="font-size:12px; padding-bottom:5px;">$url</div>
	$title
	<div 
		style="position:absolute; top: 5px; right: 5px; background: darkgray; color:white; width: 20px; height: 20px; line-height: 16px; text-align:center; font-weight:bold; cursor:pointer; border-radius: 4px;"
		onclick="javascript: this.parentNode.parentNode.removeChild(this.parentNode); return false;">
		&times;
	</div>

	$javascript_errors_html
</div>
ERROR_MESSAGE;

		$page_content = strtr($page_content, array(
			'href="/' => 'href="'.URL::base('http').'/',
			'href=\'/' => 'href=\''.URL::base('http').'/',
			'action="/' => 'action="'.URL::base('http').'/',
			'action=\'/' => 'action=\''.URL::base('http').'/',
			'src=\'/' => 'src=\''.URL::base('http').'/',
			'src="/' => 'src="'.URL::base('http').'/',
			'</body>' => $error_message.'</body>',
		));

		$test_name = get_class($test).'_'.$test->getName(FALSE);

		file_put_contents("$dir/$test_name.html", $page_content);
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

		if ( ! PHPUnit_SaveOnFailure::$errors)
		{
			foreach (scandir($dir) as $old_failure) 
			{
				if ($old_failure !== '.' AND $old_failure !== '..')
				{
					unlink($dir.$old_failure);
				}
			}
			PHPUnit_SaveOnFailure::$errors = TRUE;
		}
	}

	public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		if ($test instanceof FuncTest_TestCase AND $test->has_driver() AND $test->driver()->has_page())
		{
			$this->save($test, $e->getMessage(), $test->driver()->content());
		}
	}

	public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
	{
		if ($test instanceof FuncTest_TestCase AND $test->has_driver() AND $test->driver()->has_page())
		{
			$this->save($test, $e->getMessage(), $test->driver()->content());
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