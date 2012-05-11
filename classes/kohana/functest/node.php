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
	protected $selector;

	function __construct(FuncTest_Driver $driver, $parent = NULL, $selector = '')
	{
		$this->driver = $driver;
		$this->parent = $parent;
		$this->selector = $selector;
	}

	/**
	 * GETTERS
	 */
	
	public function dom()
	{
		return $this->driver->dom($this->selector);
	}

	public function selector()
	{
		return $this->selector;
	}

	public function html()
	{
		return $this->driver->html($this->selector);
	}

	public function __toString()
	{
		return $this->html();
	}

	public function tag_name()
	{
		return $this->driver->tag_name($this->selector);
	}

	public function attribute($name)
	{
		return $this->driver->attribute($this->selector, $name);
	}

	public function text()
	{
		return $this->driver->text($this->selector);
	}

	public function visible()
	{
		return $this->driver->visible($this->selector);	
	}

	public function value()
	{
		return $this->driver->value($this->selector);
	}


	/**
	 * SETTERS
	 */
	
	public function set($value)
	{
		$this->driver->set($this->selector, $value);
		return $this;
	}

	public function click($value)
	{
		$this->driver->click($this->selector);
		return $this;
	}

	public function select_option()
	{
		$this->driver->select_option($this->selector, TRUE);
		return $this;
	}

	public function unselect_option()
	{
		$this->driver->unselect_option($this->selector, TRUE);
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
		$this->find_link($selector, $filters)->click();
		return $this;
	}

	public function click_button($selector, array $filters = NULL)
	{
		$this->find_button($selector, $filters)->click();
		return $this;
	}

	public function fill_in($selector, $with, array $filters = NULL)
	{
		$this->driver()->find_field($selector, $filters)->set($with);
		return $this;
	}

	public function choose($selector, array $filters = NULL)
	{
		$this->find_field($selector, $filters)->set(TRUE);
		return $this;
	}

	public function check($selector, array $filters = NULL)
	{
		$this->find_field($selector, $filters)->set(TRUE);
		return $this;
	}

	public function uncheck($selector, array $filters = NULL)
	{
		$this->find_field($selector, $filters)->set(FALSE);
		return $this;
	}

	public function attach_file($locator, $file, array $filters = NULL)
	{
		$this->find_field($locator, $filters)->set($file);
		return $this;
	}

	public function select($selector, $option_filters, array $filters = NULL)
	{
		if ( ! is_array($option_filters))
		{
			$option_filters = array('value' => $option_filters);
		}
		$this->find_field($selector, $filters)->find('option', $option_filters)->select_option();
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
		return Arr::get($this->all($selector, $filters), 0);
	}

	public function end()
	{
		return $this->parent;
	}

	public function all($selector, array $filters = NULL)
	{
		$locator = FuncTest::locator($selector, $filters);

		$xpath = $this->selector.$locator->xpath();

		$elements = array();

		foreach($this->driver->all($xpath) as $i => $element)
		{
			$elements[] = FuncTest::node($this->driver, $this, "({$xpath})[".($i+1)."]");
		}

		return $locator->filter($elements);
	}

}
