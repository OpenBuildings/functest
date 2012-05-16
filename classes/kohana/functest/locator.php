<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Locator - converts varios locator formats into xpath
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Locator {

	static public $default_type = 'css';

	protected $xpath;
	protected $type;
	protected $selector;
	protected $filters;

	function __construct($selector, array $filters = array())
	{
		if ( ! is_array($selector))
		{
			$selector = array(
				FuncTest_Locator::$default_type,
				$selector,
				$filters
			);
		}
		$this->type = $selector[0];
		$this->selector = $selector[1];
		$this->filters = Arr::get($selector, 2, array());

		if ( ! method_exists($this, "{$this->type}_to_xpath"))
			throw new Kohana_Exception('Locator type ":type" does not exist', array(':type' => $this->type));

		foreach ($this->filters as $filter => $value) 
		{
			if ( ! method_exists($this, "filter_by_{$filter}"))
				throw new Kohana_Exception('Filter ":filter" does not exist', array(':filter' => $filter));
		}
	}

	public function is_filtered(FuncTest_Node $item, $index)
	{
		foreach ($this->filters as $filter => $value) 
		{
			if ( ! $this->{"filter_by_{$filter}"}($item, $index, $value))
			{
				return FALSE;
			}
		}
		return TRUE;
	}

	public function filter_by_at(FuncTest_Node $item, $index, $value)
	{
		return $index == $value;
	}

	public function filter_by_value(FuncTest_Node $item, $index, $value)
	{
		return $item->value() == $value;
	}

	public function filter_by_text(FuncTest_Node $item, $index, $value)
	{
		return mb_stripos($item->text(), $value) !== FALSE;
	}

	public function filter_by_visible(FuncTest_Node $item, $index, $value)
	{
		return $item->is_visible() === $value;
	}

	public function xpath()
	{
		if ( ! $this->xpath)
		{
			$this->xpath = $this->{"{$this->type}_to_xpath"}($this->selector);
		}
		return $this->xpath;
	}

	public function type()
	{
		return $this->type;
	}

	public function selector()
	{
		return $this->selector;
	}

	public function filters()
	{
		return $this->filters;
	}

	public function css_to_xpath($locator)
	{
		return FuncTest_Util::css2xpath($locator);
	}

	public function xpath_to_xpath($locator)
	{
		return $locator;
	}

	public function field_to_xpath($locator)
	{
		$type = "(self::input and (not(@type) or @type != 'submit')) or self::textarea or self::select";
			
		$matchers['by name']        = "@name = '$locator'";
		$matchers['by id']          = "@id = '$locator'";
		$matchers['by placeholder'] = "@placeholder = '$locator'";
		$matchers['by label for']   = "@id = //label[normalize-space() = '$locator']/@for";

		return "//*[($type) and (".join(' or ', $matchers).")]";
	}

	public function link_to_xpath($locator)
	{
		$matchers['by title']        = "contains(@title, '$locator')";
		$matchers['by id']           = "@id = '$locator'";
		$matchers['by content text'] = "contains(normalize-space(), '$locator')";
		$matchers['by img alt']      = "descendant::img[contains(@alt, '$locator')]";

		return "//a[".join(' or ', $matchers)."]";	
	}

	public function button_to_xpath($locator)
	{
		$type = "(self::input and @type = 'submit') or self::button";

		$matchers['by title']        = "contains(@title, '$locator')";
		$matchers['by id']           = "@id = '$locator'";
		$matchers['by content text'] = "contains(normalize-space(), '$locator')";
		$matchers['by img alt']      = "descendant::img[contains(@alt, '$locator')]";
		$matchers['by value']        = "contains(@value, '$locator')";
		$matchers['by name']         = "@name = '$locator'";

		return "//*[($type) and (".join(' or ', $matchers).")]";
	}

	public function __toString()
	{
		if ($this->filters)
		{
			$filters = array();
			foreach ($this->filters as $name => $value) 
			{
				$filters[] = "$name  => $value";
			}
			$filters = " filters: [".join(', ', $filters);
		}
		else
		{
			$filters = '';
		}
		return "Locator: ({$this->type}) {$this->selector}".$filters;
	}
}
