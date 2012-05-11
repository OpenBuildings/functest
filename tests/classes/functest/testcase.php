<?php defined('SYSPATH') OR die('No direct script access.');

class FuncTest_TestCase extends Unittest_TestCase {

	protected $driver;

	public function setUp()
	{
		parent::setUp();

		if ( ! $this->driver)
		{
			$this->driver = Kohana::$config->load('functest.default_driver');
		}

		FunctTest_Locator::$default_type = Kohana::$config->load('functest.default_locator_type');
	}

	public function driver($driver = NULL)
	{
		return FuncTest::driver($driver ? $driver : $this->driver);
	}

	public function page()
	{
		return $this->driver()->page();
	}

	public function assertHasCss($selector, $message = 'Should have the :type selector :selector')
	{
		return $this->assertThat($this->page(), $this->hasCss($selector), strtr($message, array(':type' => 'css', ':selector' => $selector)));
	}

	public function assertHasField($selector, $message = 'Should have the :type selector :selector')
	{
		return $this->assertThat($this->page(), $this->hasField($selector), strtr($message, array(':type' => 'css', ':selector' => $selector)));
	}

	public function assertHasXPath($selector, $message = 'Should have the :type selector :selector')
	{
		return $this->assertThat($this->page(), $this->hasXpath($selector), strtr($message, array(':type' => 'css', ':selector' => $selector)));
	}

	public function assertHasLink($selector, $message = 'Should have the :type selector :selector')
	{
		return $this->assertThat($this->page(), $this->hasLink($selector), strtr($message, array(':type' => 'css', ':selector' => $selector)));
	}

	public function assertHasButton($selector, $message = 'Should have the :type selector :selector')
	{
		return $this->assertThat($this->page(), $this->hasButton($selector), strtr($message, array(':type' => 'css', ':selector' => $selector)));
	}

	public function hasCss($selector)
	{
		return new PHPUnit_Framework_Constraint_Selector($this->driver, array('css', $selector));
	}

	public function hasXpath($selector)
	{
		return new PHPUnit_Framework_Constraint_Selector($this->driver, array('xpath', $selector));
	}

	public function hasField($selector)
	{
		return new PHPUnit_Framework_Constraint_Selector($this->driver, array('field', $selector));
	}

	public function hasButton($selector)
	{
		return new PHPUnit_Framework_Constraint_Selector($this->driver, array('button', $selector));
	}

	public function hasLink($selector)
	{
		return new PHPUnit_Framework_Constraint_Selector($this->driver, array('link', $selector));
	}


} // End Kohana_Unittest_Jelly_TestCase