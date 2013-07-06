<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Func_Test Phantomjs driver. 
 *
 * @package    Functest
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Kohana_Functest_Driver_Phantomjs extends Functest_Driver {

	public $name = 'phantomjs';
	public $_next_query = array();

	protected $_user_agent;
	protected $_connection;

	public function connection()
	{
		if ( ! $this->_connection)
		{
			$this->_connection = new Functest_Driver_Phantomjs_Connection();
		}
		return $this->_connection;
	}

	public function clear()
	{
		$this->connection()->delete('cookie');
	}

	public function content($content = NULL)
	{
		return $this->connection()->get('source');
	}

	/**
	 * GETTERS
	 */
	
	public function tag_name($id)
	{
		return $this->connection()->get("element/$id/name");
	}

	public function attribute($id, $name)
	{
		return $this->connection()->get("element/$id/attribute/$name");	
	}

	public function html($id)
	{
		if ($id === NULL)
			return $this->content();

		return $this->connection()->get("element/$id/html");
	}

	public function text($id)
	{
		return $this->connection()->get("element/$id/text");	
	}

	public function value($id)
	{
		return $this->connection()->get("element/$id/value");	
	}

	public function is_visible($id)
	{
		return $this->connection()->get("element/$id/displayed");	
	}

	public function is_selected($id)
	{
		return $this->connection()->get("element/$id/selected");	
	}
	
	public function set($id, $value)
	{
		$tag_name = $this->tag_name($id);
		
		if ($tag_name == 'textarea')
		{
			$this->connection()->post("element/$id/value", array('value' => $value));	
		}
		elseif ($tag_name == 'input') 
		{
			$type = $this->attribute($id, 'type');
			if ($type == 'checkbox' OR $type == 'radio')
			{
				$this->connection()->post("element/$id/click", array());
			}
			elseif ($type == 'file')
			{
				$this->connection()->post("element/$id/upload", array('value' => $value));
			}
			else
			{
				$this->connection()->post("element/$id/value", array('value' => $value));	
			}
		}
		elseif ($tag_name == 'option')
		{
			$this->connection()->post("element/$id/selected", array('value' => $value));
		}
	}

	public function select_option($id, $value)
	{
		$this->connection()->post("element/$id/selected", array('value' => $value));
	}

	public function click($id)
	{
		$this->connection()->post("element/$id/click", array());
	}

	public function visit($uri, array $query = NULL)
	{
		$query = Arr::merge((array) $this->_next_query, (array) $query);

		$this->_next_query = NULL;
		$url = URL::site($uri, TRUE).URL::query($query, FALSE);

		$this->connection()->post('url', array('value' => $url));
	}

	public function current_path()
	{
		$url = parse_url($this->connection()->get('url'));
		return urldecode(join('?', array_filter(Arr::extract($url, array('path', 'query')))));
	}

	public function current_url()
	{
		return urldecode($this->connection()->get('url'));
	}

	public function all($xpath, $parent = NULL)
	{
		try
		{
			$elements = $this->connection()->post(($parent === NULL ? '' : 'element/'.$parent.'/').'elements', array('value' => '.'.$xpath));
		} 
		catch (Functest_Exception_Webdriver $exception) 
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

		return $elements;
	}

	public function next_query(array $query)
	{
		$this->_next_query = $query;
	}

	public function is_page_active()
	{
		return TRUE;
	}

	public function javascript_errors()
	{
		return $this->connection()->get('errors', array());
	}

	public function javascript_messages()
	{
		return $this->connection()->get('messages', array());
	}

	public function screenshot($file)
	{
		$this->connection()->post('screenshot', array('value' => $file));
	}

	public function user_agent()
	{
		if ( ! $this->_user_agent)
		{
			$this->_user_agent = Arr::get($this->connection()->get('settings'), 'userAgent');
		}

		return $this->_user_agent;
	}

	public function cookie($name, $value, $expires = 0)
	{
		$value = sha1($this->user_agent().$name.$value.Cookie::$salt).'~'.$value;

		return $this->connection()->post('cookie', array(
			'name' => $name, 
			'value' => $value, 
			'expires' => $expires ?: time() + Date::DAY,
			'path' => Cookie::$path, 
			'domain' => Cookie::$domain ?: parse_url(URL::base('http'), PHP_URL_HOST), 
			'secure' => Cookie::$secure, 
			'httponly' => Cookie::$httponly,
		));
	}
}
