<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test node - an represents HTML node
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Node {

	protected $driver;
	protected $parent;
	protected $id;

	function __construct(FuncTest_Driver $driver, FuncTest_Node $parent = NULL, $id = '')
	{
		$this->driver = $driver;
		$this->parent = $parent;
		$this->id = $id;
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

	public function visible()
	{
		return $this->driver->visible($this->id);	
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

	/**
	 * ACTIONS
	 */

	public function click_on($selector, array $filters = NULL)
	{
		$this->find($selector, $filters)->click();
		return $this;
	}

	public function click_link($selector, array $filters = NULL)
	{
		$this->find(array('link', $selector, $filters))->click();
		return $this;
	}

	public function click_button($selector, array $filters = NULL)
	{
		$this->find(array('button', $selector, $filters))->click();
		return $this;
	}

	public function fill_in($selector, $with, array $filters = NULL)
	{
		$this->find(array('field', $selector, $filters))->set($with);
		return $this;
	}

	public function choose($selector, array $filters = NULL)
	{
		$this->find(array('field', $selector, $filters))->set(TRUE);
		return $this;
	}

	public function check($selector, array $filters = NULL)
	{
		$this->find(array('field', $selector, $filters))->set(TRUE);
		return $this;
	}

	public function uncheck($selector, array $filters = NULL)
	{
		$this->find(array('field', $selector, $filters))->set(FALSE);
		return $this;
	}

	public function attach_file($selector, $file, array $filters = NULL)
	{
		$this->find(array('field', $selector, $filters))->set($file);
		return $this;
	}

	public function select($selector, $option_filters, array $filters = NULL)
	{
		if ( ! is_array($option_filters))
		{
			$option_filters = array('value' => $option_filters);
		}
		$this->find(array('field', $selector, $filters))->find('option', $option_filters)->select_option();
		return $this;
	}

	public function unselect($selector, $option_filters, array $filters = NULL)
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
	 * FINDING
	 */
	
	public function find_field($selector, array $filters = NULL)
	{
		return $this->find(array('field', $selector, $filters));
	}

	public function find_link($selector, array $filters = NULL)
	{
		return $this->find(array('link', $selector, $filters));
	}

	public function find_button($selector, array $filters = NULL)
	{
		return $this->find(array('button', $selector, $filters));
	}

	public function find($selector, array $filters = NULL)
	{
		$locator = FuncTest::locator($selector, $filters);
		$node = $this->all($locator)->first();

		if ($node == NULL)
			throw new FuncTest_Exception_NotFound($locator, $this->driver);

		return $node;
	}

	public function end()
	{
		return $this->parent;
	}

	public function all($selector, array $filters = NULL)
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
