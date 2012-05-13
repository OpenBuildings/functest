<?php defined('SYSPATH') OR die('No direct script access.');

class FuncTest_TestCase extends Unittest_TestCase {

	static public function disambiguate($type, $selector, $filters, $message)
	{
		if ($filters === NULL)
		{
			$filters = array();
		}
		if (is_string($filters))
		{
			$filters = array();
			$message = $filters;
		}
		return array($selector, $filters, strtr($message, array(':type' => $type, ':selector' => $selector)));
	}

	protected $driver;

	public function setUp()
	{
		parent::setUp();
		$this->driver = FuncTest::driver($this->driver ? $this->driver : Kohana::$config->load('functest.default_driver'));
		FuncTest_Locator::$default_type = Kohana::$config->load('functest.default_locator_type');
	}

	public function tearDown()
	{
		if ($this->driver)
		{
			$this->driver->clear();
		}
		
		parent::tearDown();
	}

	public function driver($driver = NULL)
	{
		return $this->driver;
	}

	public function page()
	{
		return $this->driver()->page();
	}

	public function visit($uri, array $query = NULL)
	{
		return $this->driver()->visit($uri, $query);
	}

	public function content()
	{
		return $this->driver()->content();
	}

	public function current_path()
	{
		return $this->driver()->current_path();
	}

	public function current_url()
	{
		return $this->driver()->current_url();
	}


	public function assertHasCss($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->hasCss($selector, $filters), $message);
	}

	public function assertHasNoCss($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->logicalNot($this->hasCss($selector, $filters)), $message);
	}


	public function assertHasField($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->hasField($selector, $filters), $message);
	}

	public function assertHasNoField($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->logicalNot($this->hasField($selector, $filters)), $message);
	}


	public function assertHasXPath($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->hasXpath($selector, $filters), $message);
	}

	public function assertHasNoXPath($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->logicalNot($this->hasXpath($selector, $filters)), $message);
	}


	public function assertHasLink($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->hasLink($selector, $filters), $message);
	}

	public function assertHasNoLink($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->logicalNot($this->hasLink($selector, $filters)), $message);
	}


	public function assertHasButton($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->hasButton($selector, $filters), $message);
	}

	public function assertHasNoButton($selector, $filters = NULL, $message = NULL)
	{
		return $this->assertThat($this->page(), $this->logicalNot($this->hasButton($selector, $filters)), $message);
	}


	public function hasCss($selector, array $filters = NULL)
	{
		return new PHPUnit_Framework_Constraint_Locator(array('css', $selector, $filters));
	}

	public function hasXpath($selector, array $filters = NULL)
	{
		return new PHPUnit_Framework_Constraint_Locator(array('xpath', $selector, $filters));
	}

	public function hasField($selector, array $filters = NULL)
	{
		return new PHPUnit_Framework_Constraint_Locator(array('field', $selector, $filters));
	}

	public function hasButton($selector, array $filters = NULL)
	{
		return new PHPUnit_Framework_Constraint_Locator(array('button', $selector, $filters));
	}

	public function hasLink($selector, array $filters = NULL)
	{
		return new PHPUnit_Framework_Constraint_Locator(array('link', $selector, $filters));
	}


} // End Kohana_Unittest_Jelly_TestCase