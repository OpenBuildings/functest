<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Selenium driver. 
 *
 * @package    FuncTest
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_FuncTest_Driver_Selenium extends FuncTest_Driver {

	public $name = 'selenium';
	public $_next_query = array();

	protected $_webdriver;

	public function clear()
	{
		$this->webdriver()->delete('cookie');
	}

	public function session_id()
	{
		return $this->webdriver()->session_id();
	}

	public function content($content = NULL)
	{
		return $this->webdriver()->get('source');
	}

	public function webdriver()
	{
		if ( ! $this->_webdriver)
		{
			$this->_webdriver = new FuncTest_Driver_Selenium_Webdriver();
		}
		return $this->_webdriver;
	}

	public function javascript_errors()
	{
		return $this->webdriver()->post('execute', array('script' => "return window.JSErrorCollector_errors ? window.JSErrorCollector_errors.pump() : [];", 'args' => array()));
	}
	
	// public function dom($id)
	// {
	// 	return $id ? $this->xpath()->find($xpath) : $this->dom;
	// }

	/**
	 * GETTERS
	 */
		public function tag_name($id)
	{
		return $this->webdriver()->get("element/$id/name");	
	}

	public function attribute($id, $name)
	{
		return $this->webdriver()->get("element/$id/attribute/$name");	
	}

	public function html($id)
	{
		if ( ! $id)
			return $this->content();

		return $this->webdriver()->get('execute', array(
			'script' => 'arguments[0].outerHTML',
			'args' => array(
				array('ELEMENT' => $id)
			)
		));	
	}

	public function text($id)
	{
		return $this->webdriver()->get("element/$id/text");	
	}

	public function value($id)
	{
		return $this->webdriver()->get("element/$id/value");	
	}

	public function is_visible($id)
	{
		return $this->webdriver()->get("element/$id/displayed");	
	}

	public function is_selected($id)
	{
		return $this->webdriver()->get("element/$id/selected");	
	}

	public function is_checked($id)
	{
		return $this->webdriver()->get("element/$id/selected");	
	}

	public function set($id, $value)
	{
		$tag_name = $this->tag_name($id);
		
		if ($tag_name == 'textarea')
		{
			$this->webdriver()->post("element/$id/clear", array());	
			$this->webdriver()->post("element/$id/value", array('value' => str_split($value)));	
		}
		elseif ($tag_name == 'input') 
		{
			$type = $this->attribute($id, 'type');
			if ($type == 'checkbox' OR $type == 'radio')
			{
				$this->webdriver()->post("element/$id/click", array());
			}
			else
			{
				if ($this->attribute($id, 'type') !== 'file')
				{
					$this->webdriver()->post("element/$id/clear", array());	
				}
				$this->webdriver()->post("element/$id/value", array('value' => str_split($value)));	
			}
		}
		elseif ($tag_name == 'option')
		{
			$this->webdriver()->post("element/$id/click", array());
		}
	}

	public function append($id, $value)
	{
		$this->webdriver()->post("element/$id/value", array('value' => str_split($value)));	
	}

	public function select_option($id, $value)
	{
		$this->webdriver()->post("element/$id/click", array());
	}

	public function confirm($confirm)
	{
		if ($confirm)
		{
			$this->webdriver()->post('accept_alert', array());
		}
		else
		{
			$this->webdriver()->post('dismiss_alert', array());	
		}
	}

	public function click($id)
	{
		$this->webdriver()->post("element/$id/click", array());
	}

	public function visit($uri, array $query = NULL)
	{
		$query = Arr::merge((array) $this->_next_query, (array) $query);

		$this->_next_query = NULL;
		$url = URL::site($uri, 'http').URL::query($query, FALSE);

		$this->webdriver()->post('url', array('url' => $url));
	}

	public function current_path()
	{
		$url = parse_url($this->webdriver()->get('url'));
		return urldecode(join('?', array_filter(Arr::extract($url, array('path', 'query')))));
	}

	public function current_url()
	{
		return urldecode($this->webdriver()->get('url'));
	}

	public function all($xpath, $parent = NULL)
	{
		try 
		{
			$elements = $this->webdriver()->post(($parent === NULL ? '' : 'element/'.$parent.'/').'elements', array('using' => 'xpath', 'value' => '.'.$xpath));
		} 
		catch (FuncTest_Exception_Webdriver $exception) 
		{
			if ($exception->error() == 'NoSuchElement')
			{
				return array();
			}
			else
			{
				throw $exception;
			}
		}

		return Arr::pluck($elements, 'ELEMENT');
	}

	public function next_query(array $query)
	{
		$this->_next_query = $query;
		return $this;
	}

	public function has_page()
	{
		return (bool) $this->webdriver()->session_id();
	}

	public function move_to($id = NULL, $x = NULL, $y = NULL)
	{
		$this->webdriver()->post('moveto', array_filter(array(
			'element' => $id,
			'xoffset' => $x,
			'yoffset' => $y
		), function($param)
		{
			return $param OR $param === 0;
		}));
		return $this;
	}
}
