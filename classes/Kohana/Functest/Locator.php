<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Locator - converts varios locator formats into xpath
 *
 * @package    Func_Test
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_Functest_Locator {

	protected $xpath;
	protected $type;
	protected $selector;
	protected $filters;

	function __construct($selector, array $filters = array())
	{
		if ( ! is_array($selector))
		{
			$selector = array(
				Kohana::$config->load('functest.default_locator_type'),
				$selector,
				$filters
			);
		}
		// Manage nested selectors
		elseif (is_array($selector[1]))
		{
			$selector = $selector[1];
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

	public function is_filtered(Functest_Node $item, $index)
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

	public function filter_by_at(Functest_Node $item, $index, $value)
	{
		return $index == $value;
	}

	public function filter_by_value(Functest_Node $item, $index, $value)
	{
		return $item->value() == $value;
	}

	public function filter_by_text(Functest_Node $item, $index, $value)
	{
		$text = $item->text();
		
		return ($text AND $value AND mb_stripos($text, $value) !== FALSE);
	}

	public function filter_by_visible(Functest_Node $item, $index, $value)
	{
		return $item->is_visible() === $value;
	}

	public function filter_by_attributes(Functest_Node $item, $index, array $value)
	{
		foreach ($value as $attribute_name => $attribute_val) 
		{
			if ($item->attribute($attribute_name) != $attribute_val)
				return FALSE;
		}
		
		return TRUE;
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
		return Functest_Util::css2xpath($locator);
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
		$matchers['by option']      = "(self::select and ./option[(@value = \"\" or not(@value)) and contains(normalize-space(), \"$locator\")])";

		return "//*[($type) and (".join(' or ', $matchers).")]";
	}

	public function label_to_xpath($locator)
	{
		$type = "self::label";
			
		$matchers['by id']           = "@id = '$locator'";
		$matchers['by title']        = "contains(@title, '$locator')";
		$matchers['by content text'] = "contains(normalize-space(), '$locator')";
		$matchers['by img alt']      = "descendant::img[contains(@alt, '$locator')]";

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
