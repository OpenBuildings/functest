<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test node - represents HTML node
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Node {

	protected $driver;
	protected $parent;
	protected $id = NULL;

	function __construct(FuncTest_Driver $driver, FuncTest_Node $parent = NULL, $id = NULL)
	{
		$this->driver = $driver;
		$this->parent = $parent;
		$this->id = $id;
	}

	public function current_test()
	{
		return $this->driver->current_test;
	}

	public function load_vars($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * GETTERS
	 */
	
	public function is_root()
	{
		return ! (bool) $this->id;
	}

	public function dom()
	{
		return $this->driver->dom($this->id);
	}

	public function id()
	{
		return $this->id;
	}

	public function html()
	{
		return $this->driver->html($this->id);
	}

	public function __toString()
	{
		return $this->html();
	}

	public function tag_name()
	{
		return $this->driver->tag_name($this->id);
	}

	public function attribute($name)
	{
		return $this->driver->attribute($this->id, $name);
	}

	public function text()
	{
		return $this->driver->text($this->id);
	}

	public function is_visible()
	{
		return $this->driver->is_visible($this->id);	
	}

	public function is_selected()
	{
		return $this->driver->is_selected($this->id);
	}

	public function is_checked()
	{
		return $this->driver->is_checked($this->id);
	}

	public function value()
	{
		return $this->driver->value($this->id);
	}


	/**
	 * SETTERS
	 */
	
	public function set($value)
	{
		$this->driver->set($this->id, $value);
		return $this;
	}
	
	public function append($value)
	{
		$this->driver->append($this->id, $value);
		return $this;
	}

	public function click()
	{
		$this->driver->click($this->id);
		return $this;
	}

	public function select_option()
	{
		$this->driver->select_option($this->id, TRUE);
		return $this;
	}

	public function unselect_option()
	{
		$this->driver->select_option($this->id, FALSE);
		return $this;
	}

	public function hover()
	{
		$this->driver->move_to($this->id());
		return $this;
	}


	/**
	 * ACTIONS
	 */

	public function hover_on($selector, array $filters = array())
	{
		$this->find($selector, $filters)->hover();
		return $this;
	}

	public function hover_link($selector, array $filters = array())
	{
		$this->find_link($selector, $filters)->hover();
		return $this;
	}

	public function click_on($selector, array $filters = array())
	{
		$this->find($selector, $filters)->click();
		return $this;
	}

	public function click_link($selector, array $filters = array())
	{
		$this->find_link($selector, $filters)->click();
		return $this;
	}

	public function click_button($selector, array $filters = array())
	{
		$this->find_button($selector, $filters)->click();
		return $this;
	}

	public function fill_in($selector, $with, array $filters = array())
	{
		$this->find_field($selector, $filters)->set($with);
		return $this;
	}

	public function choose($selector, array $filters = array())
	{
		$this->find_field($selector, $filters)->set(TRUE);
		return $this;
	}

	public function check($selector, array $filters = array())
	{
		$this->find_field($selector, $filters)->set(TRUE);
		return $this;
	}

	public function uncheck($selector, array $filters = array())
	{
		$this->find_field($selector, $filters)->set(FALSE);
		return $this;
	}

	public function attach_file($selector, $file, array $filters = array())
	{
		$this->find_field($selector, $filters)->set($file);
		return $this;
	}

	public function select($selector, $option_filters, array $filters = array())
	{
		if ( ! is_array($option_filters))
		{
			$option_filters = array('text' => $option_filters);
		}
		$this->find_field($selector, $filters)->find('option', $option_filters)->select_option();
		return $this;
	}

	public function unselect($selector, $option_filters, array $filters = array())
	{
		if ( ! is_array($option_filters))
		{
			$option_filters = array('value' => $option_filters);
		}

		$this->find_field($selector)->find('option', $option_filters)->unselect_option();
		return $this;
	}

	public function confirm($confirm)
	{
		$this->driver->confirm($confirm);
		return $this;
	}

	/**
	 * ASSERTIONS
	 */
	
	public function assertHasCss($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->hasCss($selector, $filters), $message);
		return $this;
	}

	public function assertHasNoCss($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->logicalNot($this->current_test()->hasCss($selector, $filters)), $message);
		return $this;
	}


	public function assertHasField($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->hasField($selector, $filters), $message);
		return $this;
	}

	public function assertHasNoField($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->logicalNot($this->current_test()->hasField($selector, $filters)), $message);
		return $this;
	}


	public function assertHasXPath($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->hasXpath($selector, $filters), $message);
		return $this;
	}

	public function assertHasNoXPath($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->logicalNot($this->current_test()->hasXpath($selector, $filters)), $message);
		return $this;
	}


	public function assertHasLink($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->hasLink($selector, $filters), $message);
		return $this;
	}

	public function assertHasNoLink($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->logicalNot($this->current_test()->hasLink($selector, $filters)), $message);
		return $this;
	}


	public function assertHasButton($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->hasButton($selector, $filters), $message);
		return $this;
	}

	public function assertHasNoButton($selector, $filters = array(), $message = NULL)
	{
		$this->current_test()->assertThat($this, $this->current_test()->logicalNot($this->current_test()->hasButton($selector, $filters)), $message);
		return $this;
	}


	/**
	 * FINDING
	 */
	
	public function find_field($selector, array $filters = array())
	{
		if (is_array($selector))
		{
			return $this->find($selector, $filters);
		}
		return $this->find(array('field', $selector, $filters));
	}

	public function find_link($selector, array $filters = array())
	{
		return $this->find(array('link', $selector, $filters));
	}

	public function find_button($selector, array $filters = array())
	{
		return $this->find(array('button', $selector, $filters));
	}

	public function find($selector, array $filters = array())
	{
		$locator = FuncTest::locator($selector, $filters);
		$retries = ceil(Kohana::$config->load('functest.default_wait_time') / 50);

		do
		{
			$node = $this->all($locator)->first();
			$retries -= 1;
			usleep(40000);
		}
		while ($retries > 0 AND ! $node);

		if ($node == NULL)
			throw new FuncTest_Exception_NotFound($locator, $this->driver);

		return $node;
	}

	public function end()
	{
		return $this->parent;
	}

	public function all($selector, array $filters = array())
	{
		if ($selector instanceof FuncTest_Locator)
		{
			$locator = $selector;
		}
		else
		{
			$locator = FuncTest::locator($selector, $filters);
		}

		return FuncTest::nodelist($this->driver, $locator, $this);
	}

}
